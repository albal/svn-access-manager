<?php

/**
 * Work on group access rights
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
 *           
 *            SVN Access Manager - a subversion access rights management tool
 *            Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *           
 *            This program is free software; you can redistribute it and/or modify
 *            it under the terms of the GNU General Public License as published by
 *            the Free Software Foundation; either version 2 of the License, or
 *            (at your option) any later version.
 *           
 *            This program is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU General Public License for more details.
 *           
 *            You should have received a copy of the GNU General Public License
 *            along with this program; if not, write to the Free Software
 *            Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *           
 *
 */

/*
 *
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Group admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "workongroupaccessright";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use workOnGroupAccessRight without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

$schema = db_determine_schema();

$tUsers = array();
$query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
$result = db_query($query, $dbh);
while ( $row = db_assoc($result[RESULT]) ) {
    
    $userid = $row[USERID];
    $name = $row['name'];
    $givenname = $row['givenname'];
    
    if ($givenname != "") {
        
        $name = $givenname . " " . $name;
    }
    
    $tUsers[$userid] = $name;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tUser = "";
    $tRight = "";
    $tReadonly = "";
    $tUserError = '';
    $tRightError = '';
    $tTask = db_escape_string($_GET['task']);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if (($rightAllowed == "add") && ($tTask != "new")) {
        
        db_log($SESSID_USERNAME, "tried to use workOnGroupAccessRight without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    $_SESSION[SVNSESSID]['task'] = strtolower($tTask);
    
    if ($_SESSION[SVNSESSID]['task'] == "new") {
        
        $tRight = "";
        $tUser = "";
        
        $query = "SELECT groupname " . "  FROM " . $schema . "svngroups " . " WHERE id=" . $_SESSION[SVNSESSID][GROUPID];
        $result = db_query($query, $dbh);
        if ($result['rows'] > 0) {
            $row = db_assoc($result[RESULT]);
            $tGroupName = $row[GROUPNAME];
        }
        else {
            $tGroupName = "undefined";
        }
        
        $_SESSION[SVNSESSID][USERID] = $tUser;
        $_SESSION[SVNSESSID][RIGHT] = $tRight;
        $_SESSION[SVNSESSID][GROUPNAME] = $tGroupName;
    }
    elseif ($_SESSION[SVNSESSID]['task'] == "change") {
        
        $query = "SELECT groupname " . "  FROM " . $schema . "svngroups, " . $schema . "svn_groups_responsible " . " WHERE (svn_groups_responsible.id=$tId) " . "   AND (svngroups.id = svn_groups_responsible.group_id)";
        $result = db_query($query, $dbh);
        if ($result['rows'] > 0) {
            
            $row = db_assoc($result[RESULT]);
            $tGroupName = $row[GROUPNAME];
        }
        else {
            
            $tGroupName = "undefined";
        }
        
        $_SESSION[SVNSESSID][GROUPID] = $tId;
        $tReadonly = "disabled";
        $query = "SELECT svngroups.groupname, svnusers.userid, svn_groups_responsible.allowed " . "  FROM " . $schema . "svnusers, " . $schema . "svn_groups_responsible, " . $schema . "svngroups " . " WHERE (svngroups.id = svn_groups_responsible.group_id) " . "   AND (svn_groups_responsible.id=$tId) " . "   AND (svnusers.id = svn_groups_responsible.user_id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000')";
        $result = db_query($query, $dbh);
        if ($result['rows'] > 0) {
            
            $row = db_assoc($result[RESULT]);
            $tRight = $row[ALLOWED];
            $tUser = $row[USERID];
        }
        else {
            
            $tUser = "";
            $tRight = "";
        }
        
        $_SESSION[SVNSESSID][USERID] = $tUser;
        $_SESSION[SVNSESSID][RIGHT] = $tRight;
        $_SESSION[SVNSESSID][GROUPNAME] = $tGroupName;
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
        $tMessageType = DANGER;
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "workOnGroupAccessRight.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    elseif ((isset($_POST['fSubmit_ok_x'])) || (isset($_POST['fSubmit_ok']))) {
        $button = _("Set access rights");
    }
    else {
        $button = "";
    }
    
    $tUserError = 'ok';
    $tRightError = 'ok';
    $error = 0;
    
    if ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("location: list_group_admins.php");
        exit();
    }
    elseif ($button == _("Set access rights")) {
        
        $tUser = isset($_POST['fUser']) ? db_escape_string($_POST['fUser']) : "";
        $tRight = isset($_POST['fRight']) ? db_escape_string($_POST['fRight']) : "";
        
        if ($_SESSION[SVNSESSID]['task'] == "new") {
            
            if ($tUser == "") {
                
                $tMessage = _("Please select user!");
                $tUserError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif ($tRight == "") {
                
                $tMessage = _("Please select right!");
                $tRightError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            else {
                
                $tGroupResponsibleId = - 1;
                $userid = db_getIdByUserid($tUser, $dbh);
                $groupid = $_SESSION[SVNSESSID][GROUPID];
                $groupname = db_getGroupById($groupid, $dbh);
                $query = "SELECT * " . "  FROM " . $schema . "svn_groups_responsible " . " WHERE (group_id=$groupid) " . "   AND (user_id=$userid) " . "   AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                if ($result['rows'] == 0) {
                    
                    $dbnow = db_now();
                    $query = "INSERT INTO " . $schema . "svn_groups_responsible (user_id, group_id, allowed, created, created_user) " . "     VALUES ('$userid', '$groupid', '$tRight', '$dbnow', '" . $_SESSION[SVNSESSID]['username'] . "')";
                    db_ta('BEGIN', $dbh);
                    db_log($_SESSION[SVNSESSID]['username'], "added $tUser as responsible for group $groupname with right $tRight", $dbh);
                    
                    $result = db_query($query, $dbh);
                    if ($result['rows'] != 1) {
                        
                        db_ta('ROLLBACK', $dbh);
                        
                        $tMessage = _("Error during database insert");
                        $tMessageType = DANGER;
                    }
                    else {
                        
                        $tGroupResponsibleId = db_get_last_insert_id('svn_groups_responsibles', 'id', $dbh);
                        db_ta('COMMIT', $dbh);
                        
                        $tMessage = _("Group responsible user successfully inserted");
                        $tMessageType = SUCCESS;
                        $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                        $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;
                        db_disconnect($dbh);
                        header("Location: list_group_admins.php");
                        exit();
                    }
                }
                else {
                    
                    $tMessage = sprintf(_("Group responsible user for group %s (%s/%s) already exists!"), $groupname, $groupid, $userid);
                    $tMessageType = DANGER;
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                $tReadonly = "";
                $query = "SELECT svngroups.groupname, svnusers.userid, svn_groups_responsible.allowed " . "  FROM " . $schema . "svnusers, " . $schema . "svn_groups_responsible, " . $schema . "svngroups " . " WHERE (svngroups.id = svn_groups_responsible.group_id) " . "   AND (svn_groups_responsible.id=" . $tGroupResponsibleId . ") " . "   AND (svnusers.id = svn_groups_responsible.user_id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                if ($result['rows'] > 0) {
                    
                    $row = db_assoc($result[RESULT]);
                    $tRight = $row[ALLOWED];
                    $tUser = $row[USERID];
                    $tGroupName = $row[GROUPNAME];
                }
                else {
                    
                    $tUser = "";
                    $tRight = "";
                    $tGroupName = "undefined";
                }
            }
            else {
                $tGroupName = $_SESSION[SVNSESSID][GROUPNAME];
            }
        }
        elseif ($_SESSION[SVNSESSID]['task'] == "change") {
            
            $tUser = $_SESSION[SVNSESSID][USERID];
            if ($tUser == "") {
                
                $tMessage = _("Please select user!");
                $tUserError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif ($tRight == "") {
                
                $tMessage = _("Please select right!");
                $tRightError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            else {
                
                $groupid = $_SESSION[SVNSESSID][GROUPID];
                $groupname = db_getGroupById($groupid, $dbh);
                $query = "SELECT * " . "  FROM " . $schema . "svn_groups_responsible " . " WHERE (id=" . $_SESSION[SVNSESSID][GROUPID] . ")";
                $result = db_query($query, $dbh);
                if ($result['rows'] > 0) {
                    
                    $dbnow = db_now();
                    $query = "UPDATE " . $schema . "svn_groups_responsible " . "   SET allowed='$tRight', " . "       modified='$dbnow', " . "       modified_user='" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE (id=" . $_SESSION[SVNSESSID][GROUPID] . ")";
                    db_ta('BEGIN', $dbh);
                    db_log($_SESSION[SVNSESSID]['username'], "changed $tUser as responsible for group $groupname to right $tRight", $dbh);
                    
                    $result = db_query($query, $dbh);
                    if ($result['rows'] != 1) {
                        
                        db_ta('ROLLBACK', $dbh);
                        
                        $tMessaage = _("Error during database insert");
                        $tMessageType = DANGER;
                    }
                    else {
                        
                        db_ta('COMMIT', $dbh);
                        
                        $tMessage = _("Group responsible user successfully changed");
                        $tMessageType = SUCCESS;
                        $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                        $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;
                        db_disconnect($dbh);
                        header("Location: list_group_dmins.php");
                        exit();
                    }
                }
                else {
                    
                    $tMessage = sprintf(_("Group responsible user for group %s (%s) does not exist!"), $groupname, $groupid);
                    $tMessageType = DANGER;
                    $error = 1;
                }
            }
            
            $tReadonly = "disabled";
            $query = "SELECT svngroups.groupname, svnusers.userid, svn_groups_responsible.allowed " . "  FROM " . $schema . "svnusers, " . $schema . "svn_groups_responsible, " . $schema . "svngroups " . " WHERE (svngroups.id = svn_groups_responsible.group_id) " . "   AND (svn_groups_responsible.id=" . $_SESSION[SVNSESSID][GROUPID] . ") " . "   AND (svnusers.id = svn_groups_responsible.user_id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000')";
            $result = db_query($query, $dbh);
            if ($result['rows'] > 0) {
                
                $row = db_assoc($result[RESULT]);
                $tRight = $row[ALLOWED];
                $tUser = $row[USERID];
                $tGroupName = $row[GROUPNAME];
            }
            else {
                
                $tUser = "";
                $tRight = "";
                $tGroupName = "undefined";
            }
        }
        else {
            
            $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
            $tMessageType = DANGER;
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
        $tMessageType = DANGER;
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "workOnGroupAccessRight.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
