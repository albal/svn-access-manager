<?php

/**
 * Set an access right
 *
 * @author Thomas Krieger
 * @copyright 2018 Thomas Krieger. All rights reserved.
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
 * @filesource
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
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Access rights admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "setaccessright";

if (($rightAllowed != "edit") && ($rightAllowed != "delete") && ($_SESSION[SVNSESSID]['admin'] != "p")) {
    
    db_log($SESSID_USERNAME, "tried to use setAccessRight without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

$schema = db_determine_schema();

$tUsers = array();
$query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
$result = db_query($query, $dbh);
while ( $row = db_assoc($result[RESULT]) ) {
    
    $id = $row[USERID];
    $name = $row['name'];
    $givenname = $row['givenname'];
    
    if ($givenname != "") {
        
        $name = $givenname . " " . $name;
    }
    
    $tUsers[$id] = $name;
}

$tGroups = array();
$query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (deleted = '00000000000000') " . "ORDER BY svngroups.groupname ASC";
$result = db_query($query, $dbh);

while ( $row = db_assoc($result[RESULT]) ) {
    
    $id = $row['id'];
    $groupname = $row['groupname'];
    $tGroups[$id] = $groupname;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if (isset($_GET['task'])) {
        
        $_SESSION[SVNSESSID]['task'] = db_escape_string(strtolower($_GET['task']));
    }
    else {
        
        $_SESSION[SVNSESSID]['task'] = "";
    }
    
    if ($_SESSION[SVNSESSID]['task'] == CHANGE) {
        
        $tReadonly = "disabled";
    }
    else {
        
        $tReadonly = "";
    }
    
    $tProjectName = $_SESSION[SVNSESSID]['svnmodule'];
    $tRepoName = $_SESSION[SVNSESSID]['reponame'];
    $tRepoPath = $_SESSION[SVNSESSID]['repopath'];
    $tRepoUser = $_SESSION[SVNSESSID]['repouser'];
    $tRepoPassword = $_SESSION[SVNSESSID]['repopassword'];
    $tModulePath = $_SESSION[SVNSESSID]['modulepath'];
    $tPathSelected = $tModulePath . $_SESSION[SVNSESSID]['pathselected'];
    $tPathSelected = str_replace('//', '/', $tPathSelected);
    $tNone = CHECKED;
    $tRecursive = CHECKED;
    $tValidFromError = '';
    $tValidUntilError = '';
    $tAccessRightError = '';
    $tUsersError = '';
    $tGroupsError = '';
    
    $lang = check_language();
    
    if (isset($_SESSION[SVNSESSID]['validfrom'])) {
        
        $tValidFrom = splitDateForBootstrap($_SESSION[SVNSESSID]['validfrom']);
    }
    else {
        
        $tValidFrom = "";
    }
    
    if (isset($_SESSION[SVNSESSID]['validuntil'])) {
        
        $tValidUntil = splitDateForBootstrap($_SESSION[SVNSESSID]['validuntil']);
    }
    else {
        
        $tValidUntil = "";
    }
    
    if ($tValidFrom == "0000-00-00") {
        
        $tValidFrom = "";
    }
    
    if ($tValidUntil == "9999-99-99") {
        
        $tValidUntil = "";
    }
    
    if (isset($_SESSION[SVNSESSID]['accessright'])) {
        
        $tAccessRight = $_SESSION[SVNSESSID]['accessright'];
        
        if ($tAccessRight == "none") {
            
            $tNone = CHECKED;
            $tRead = "";
            $tWrite = "";
        }
        elseif ($tAccessRight == "read") {
            
            $tNone = "";
            $tRead = CHECKED;
            $tWrite = "";
        }
        elseif ($tAccessRight == WRITE) {
            
            $tNone = "";
            $tRead = "";
            $tWrite = CHECKED;
        }
    }
    else {
        
        $tAccessRight = "";
    }
    
    if (isset($_SESSION[SVNSESSID][USERID])) {
        
        $tUid = $_SESSION[SVNSESSID][USERID];
    }
    else {
        
        $tUid = "";
    }
    
    if (isset($_SESSION[SVNSESSID][GROUPID])) {
        
        $tGid = $_SESSION[SVNSESSID][GROUPID];
    }
    else {
        
        $tGid = "";
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "setAccessRight.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $tProjectName = $_SESSION[SVNSESSID]['svnmodule'];
    $tProjectid = $_SESSION[SVNSESSID]['projectid'];
    $tRepoName = $_SESSION[SVNSESSID]['reponame'];
    $tRepoPath = $_SESSION[SVNSESSID]['repopath'];
    $tRepoUser = $_SESSION[SVNSESSID]['repouser'];
    $tRepoPassword = $_SESSION[SVNSESSID]['repopassword'];
    $tModulePath = $_SESSION[SVNSESSID]['modulepath'];
    $tPathSelected = $tModulePath . $_SESSION[SVNSESSID]['pathselected'];
    $tPathSelected = str_replace('//', '/', $tPathSelected);
    $tAccessRight = isset($_POST['fAccessRight']) ? db_escape_string($_POST['fAccessRight']) : "";
    $tRecursive = isset($_POST['fRecursive']) ? db_escape_string($_POST['fRecursive']) : "";
    $tValidFrom = isset($_POST['fValidFrom']) ? db_escape_string($_POST['fValidFrom']) : "";
    $tValidUntil = isset($_POST['fValidUntil']) ? db_escape_string($_POST['fValidUntil']) : "";
    $tUsers = isset($_POST['fUsers']) ? db_escape_string($_POST['fUsers']) : array();
    $tGroups = isset($_POST['fGroups']) ? db_escape_string($_POST['fGroups']) : array();
    $tValidFromError = 'ok';
    $tValidUntilError = 'ok';
    $tAccessRightError = 'ok';
    $tUsersError = 'ok';
    $tGroupsError = 'ok';
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_ok_x'])) || (isset($_POST['fSubmit_ok']))) {
        $button = _("Submit");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    else {
        $button = "undef";
    }
    
    $lang = check_language();
    
    if ($tAccessRight == "none") {
        
        $tNone = CHECKED;
        $tRead = "";
        $tWrite = "";
    }
    elseif ($tAccessRight == "read") {
        
        $tNone = "";
        $tRead = CHECKED;
        $tWrite = "";
    }
    elseif ($tAccessRight == WRITE) {
        
        $tNone = "";
        $tRead = "";
        $tWrite = CHECKED;
    }
    
    if ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("location: list_access_rights.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        $error = 0;
        $lang = strtolower(check_language());
        
        if ($tValidFrom != "") {
            
            $day = substr($tValidFrom, 8, 2);
            $month = substr($tValidFrom, 5, 2);
            $year = substr($tValidFrom, 0, 4);
            
            if (! check_date($day, $month, $year)) {
                
                $tMessage = sprintf(_("Not a valid date: %s (valid from)"), $tValidFrom);
                $tMessageType = DANGER;
                $tValidFromError = ERROR;
                $error = 1;
            }
            else {
                
                $validFrom = sprintf("%04s%02s%02s", $year, $month, $day);
            }
        }
        else {
            
            $validFrom = "00000000";
        }
        
        if ($tValidUntil != "") {
            
            $day = substr($tValidUntil, 8, 2);
            $month = substr($tValidUntil, 5, 2);
            $year = substr($tValidUntil, 0, 4);
            
            if (! check_date($day, $month, $year)) {
                
                $tMessage = sprintf(_("Not a valid date: %s (valid until)"), $tValidUntil);
                $tMessageType = DANGER;
                $tValidUntilError = ERROR;
                $error = 1;
            }
            else {
                
                $validUntil = sprintf("%04s%02s%02s", $year, $month, $day);
            }
        }
        else {
            
            $validUntil = "99999999";
        }
        
        if (substr($tPathSelected, 0, 1) != "/") {
            
            $tPathSelected = "/" . $tPathSelected;
        }
        
        foreach( $tUsers as $userid ) {
            
            if ($error == 0) {
                
                $mode = db_getUserRightByUserid($userid, $dbh);
                if (($tAccessRight == WRITE) && ($mode != WRITE)) {
                    
                    $tMessage = _("User is not allowed to have write access, global right is read only");
                    $tMessageType = DANGER;
                    $tAccessRightError = ERROR;
                    $error = 1;
                }
            }
        }
        
        foreach( $tGroups as $groupid ) {
            
            if ($error == 0) {
                
                $mode = db_getGroupRightByGroupid($groupid, $dbh);
                if (($tAccessRight == WRITE) && ($mode != WRITE)) {
                    
                    $groupName = db_getGroupById($groupid, $dbh);
                    $tMessage = sprintf(_("Group %s contains an user with no global write permission!"), $groupName);
                    $tMessageType = 'warning';
                    $tAccessRightError = 'warn';
                    $error = 1;
                }
            }
        }
        
        if (($error == 0) && ($_SESSION[SVNSESSID]['task'] == CHANGE)) {
            
            if ($_SESSION[SVNSESSID][USERID] != 0) {
                
                $mode = db_getUserRightByUserid($_SESSION[SVNSESSID][USERID], $dbh);
                if (($tAccessRight == WRITE) && ($mode != WRITE)) {
                    
                    $tMessage = _("User is not allowed to have write access, global right is read only");
                    $error = 1;
                }
            }
            elseif ($_SESSION[SVNSESSID][GROUPID] != 0) {
                
                $mode = db_getGroupRightByGroupid($_SESSION[SVNSESSID][GROUPID], $dbh);
                if (($tAccessRight == WRITE) && ($mode != WRITE)) {
                    $groupName = db_getGroupById($_SESSION[SVNSESSID][GROUPID], $dbh);
                    $tMessage = sprintf(_("Group %s contains an user with no global write permission!"), $groupName);
                    $tMessageType = 'warning';
                    $tAccessRightError = 'warn';
                    $error = 1;
                }
            }
            else {
                
                $mode = "undefined";
            }
        }
        
        if (($_SESSION[SVNSESSID]['task'] == "new") && (count($tUsers) == 0) && (count($tGroups) == 0)) {
            
            $tMessage = _("No user or no group selected!");
            $tMessageType = DANGER;
            $tUsersError = ERROR;
            $tGroupsError = ERROR;
            $error = 1;
        }
        
        $curdate = strftime("%Y%m%d");
        
        if ($error == 0) {
            
            if ($_SESSION[SVNSESSID]['task'] == CHANGE) {
                
                db_ta('BEGIN', $dbh);
                
                $tId = $_SESSION[SVNSESSID]['rightid'];
                $olddata = db_getRightData($tId, $dbh);
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svn_access_rights " . "   SET modified = '$dbnow', " . "       modified_user = '" . $_SESSION[SVNSESSID]['username'] . "', " . "       valid_from = '$validFrom', " . "       valid_until = '$validUntil', " . "       access_right = '$tAccessRight' " . " WHERE (id = $tId)";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    $user = db_getUseridById($olddata['user_id'], $dbh);
                    $repo = db_getRepoById($olddata['repo_id'], $dbh);
                    $path = $olddata['path'];
                    $oldright = $olddata['access_right'];
                    
                    db_log($_SESSION[SVNSESSID]['username'], "updated access right from $oldright to $tAccessRight for $user in $repo for $path", $dbh);
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    
                    header("location: list_access_rights.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                    $tMessage = _("Error while writing access right modification");
                    $tMessageType = DANGER;
                }
            }
            else {
                
                if ($error == 0) {
                    
                    db_ta('BEGIN', $dbh);
                    
                    foreach( $tUsers as $userid ) {
                        
                        $id = db_getIdByUserid($userid, $dbh);
                        $mode = db_getUserRightByUserid($userid, $dbh);
                        $query = "SELECT * " . "  FROM " . $schema . "svn_access_rights " . " WHERE (user_id = '$id') " . "   AND (path = '$tPathSelected') " . "   AND (deleted = '00000000000000') " . "   AND (project_id = '$tProjectid') ";
                        $result = db_query($query, $dbh);
                        
                        while ( ($row = db_assoc($result[RESULT])) && ($error == 0) ) {
                            
                            $rightid = $row['id'];
                            $tPathSelected = $row['path'];
                            $dbnow = db_now();
                            $query = "UPDATE " . $schema . "svn_access_rights " . "   SET deleted = '$dbnow', " . "       deleted_user = '" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE (id = $rightid)";
                            $resultupd = db_query($query, $dbh);
                            if ($resultupd['rows'] != 1) {
                                
                                $tMessage = _("Error while deleting access right");
                                $tMessageType = DANGER;
                                $error = 1;
                            }
                            
                            db_log($_SESSION[SVNSESSID]['username'], "deleted access right for $userid for $tPathSelected", $dbh);
                        }
                        
                        $dbnow = db_now();
                        $query = "INSERT INTO " . $schema . "svn_access_rights " . "            (project_id, user_id, path, valid_from, valid_until, access_right, created, created_user) " . "     VALUES ('$tProjectid', '$id', '$tPathSelected', '$validFrom', '$validUntil', '$tAccessRight', '$dbnow', '" . $_SESSION[SVNSESSID]['username'] . "')";
                        $result = db_query($query, $dbh);
                        if ($result['rows'] != 1) {
                            
                            $tMessage = sprintf(_("Error while inserting access right for user %s"), $userid);
                            $tMessageType = DANGER;
                            $error = 1;
                        }
                        
                        db_log($_SESSION[SVNSESSID]['username'], "added access right $tAccessRight for " . $userid . " to $tPathSelected", $dbh);
                    }
                    
                    if ($error == 0) {
                        
                        foreach( $tGroups as $groupid ) {
                            
                            $query = "SELECT * " . "  FROM " . $schema . "svn_access_rights " . " WHERE (group_id = '$groupid') " . "   AND (path = '$tPathSelected') " . "   AND (deleted = '00000000000000') " . "   AND (project_id = '$tProjectid') ";
                            $result = db_query($query, $dbh);
                            
                            while ( ($row = db_assoc($result[RESULT])) && ($error == 0) ) {
                                
                                $rightid = $row['id'];
                                $dbnow = db_now();
                                $query = "UPDATE " . $schema . "svn_access_rights " . "   SET deleted = '$dbnow', " . "       deleted_user = '" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE (id = $rightid)";
                                $resultupd = db_query($query, $dbh);
                                if ($resultupd['rows'] != 1) {
                                    
                                    $tMessage = _("Error while deleting access right");
                                    $tMessageType = DANGER;
                                    $error = 1;
                                }
                                
                                db_log($_SESSION[SVNSESSID]['username'], "deleted access right for $userid for $tPathSelected", $dbh);
                            }
                            
                            $dbnow = db_now();
                            $query = "INSERT INTO " . $schema . "svn_access_rights " . "            (project_id, group_id, path, valid_from, valid_until, access_right, created, created_user) " . "     VALUES ('$tProjectid', '$groupid', '$tPathSelected', '$validFrom', '$validUntil', '$tAccessRight', '$dbnow', '" . $_SESSION[SVNSESSID]['username'] . "')";
                            $result = db_query($query, $dbh);
                            if ($result['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Error while inserting access right for group %s"), $groupid);
                                $tMessageType = DANGER;
                                $error = 1;
                            }
                            
                            db_log($_SESSION[SVNSESSID]['username'], "added access right $tAccessRight for $groupid to $tPathSelected", $dbh);
                        }
                    }
                    
                    if ($error == 0) {
                        
                        db_ta('COMMIT', $dbh);
                    }
                    else {
                        
                        db_ta('ROLLBACK', $dbh);
                    }
                }
            }
        }
        
        if ($error == 0) {
            
            db_disconnect($dbh);
            header("location: list_access_rights.php");
            exit();
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
        $tMessageType = DANGER;
    }
    
    $tUsers = array();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $id = $row[USERID];
        $name = $row['name'];
        $givenname = $row['givenname'];
        
        if ($givenname != "") {
            
            $name = $givenname . " " . $name;
        }
        
        $tUsers[$id] = $name;
    }
    
    $tGroups = array();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $id = $row['id'];
        $groupname = $row['groupname'];
        $tGroups[$id] = $groupname;
    }
    
    if (isset($_SESSION[SVNSESSID][USERID])) {
        
        $tUid = $_SESSION[SVNSESSID][USERID];
    }
    else {
        
        $tUid = "";
    }
    
    if (isset($_SESSION[SVNSESSID][GROUPID])) {
        
        $tGid = $_SESSION[SVNSESSID][GROUPID];
    }
    else {
        
        $tGid = "";
    }
    
    if ($_SESSION[SVNSESSID]['task'] == CHANGE) {
        
        $tReadonly = "disabled";
    }
    else {
        
        $tReadonly = "";
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "setAccessRight.tpl";
    
    include ("$installBase/templates/framework.tpl");
}
?>
