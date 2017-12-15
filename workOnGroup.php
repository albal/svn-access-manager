<?php

/*
 * SVN Access Manager - a subversion access rights management tool
 * Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
if (file_exists(realpath("./config/config.inc.php"))) {
    require ("./config/config.inc.php");
}
elseif (file_exists(realpath("../config/config.inc.php"))) {
    require ("../config/config.inc.php");
}
elseif (file_exists("/etc/svn-access-manager/config.inc.php")) {
    require ("/etc/svn-access-manager/config.inc.php");
}
else {
    die("can't load config.inc.php. Please check your installation!\n");
}

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/addMemberToGroup.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Group admin", $dbh);
$_SESSION['svn_sessid']['helptopic'] = "workongroup";

if ($rightAllowed == "none") {
    
    $tGroupsAllowed = db_check_group_acl($_SESSION['svn_sessid']['username'], $dbh);
    if (count($tGroupsAllowed) == 0) {
        db_log($SESSID_USERNAME, "tried to use workOnGroup without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
}

$schema = db_determine_schema();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tReadonly = "";
    $tTask = db_escape_string($_GET['task']);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if (($rightAllowed == "add") and (($tTask != "new") and ($tTask != "relist"))) {
        
        db_log($SESSID_USERNAME, "tried to use workOnGroup without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    if (($rightAllowed == "none") and ($tId != "") and (! array_key_exists($tId, $tGroupsAllowed))) {
        
        db_log($SESSID_USERNAME, "tried to use workOnGroup without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    if (strtolower($tTask) != "relist") {
        
        $_SESSION['svn_sessid']['task'] = strtolower($tTask);
    }
    
    if ($tTask == "relist") {
        
        $tDescription = $_SESSION['svn_sessid']['groupdescr'];
        $tGroup = $_SESSION['svn_sessid']['groupname'];
        $tMembers = $_SESSION['svn_sessid']['members'];
    }
    elseif ($_SESSION['svn_sessid']['task'] == "new") {
        
        $tDescription = "";
        $tGroup = "";
        $tMembers = array();
        $_SESSION['svn_sessid']['members'] = array();
        $_SESSION['svn_sessid']['groupid'] = $tId;
        $_SESSION['svn_sessid']['groupdescr'] = "";
        $_SESSION['svn_sessid']['groupname'] = "";
    }
    elseif ($_SESSION['svn_sessid']['task'] == "change") {
        
        $_SESSION['svn_sessid']['groupid'] = $tId;
        $tReadonly = "readonly";
        $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE id = $tId";
        $result = db_query($query, $dbh);
        
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result['result']);
            $tDescription = $row['description'];
            $tGroup = $row['groupname'];
            $tMembers = array();
            $query = "SELECT svnusers.userid, svnusers.name, svnusers.givenname " . "  FROM " . $schema . "svnusers, " . $schema . "svn_users_groups " . " WHERE (svn_users_groups.group_id = $tId) " . "   AND (svn_users_groups.deleted = '00000000000000') " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_users_groups.user_id = svnusers.id) " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
            $result = db_query($query, $dbh);
            
            while ( $row = db_assoc($result['result']) ) {
                
                $userid = $row['userid'];
                $name = $row['name'];
                $givenname = $row['givenname'];
                
                if ($givenname != "") {
                    
                    $name = $givenname . " " . $name;
                }
                
                $tMembers[$userid] = $name;
            }
            
            $_SESSION['svn_sessid']['members'] = $tMembers;
            $_SESSION['svn_sessid']['groupdescr'] = $tDescription;
            $_SESSION['svn_sessid']['groupname'] = $tGroup;
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION['svn_sessid']['task']);
    }
    
    $header = "groups";
    $subheader = "groups";
    $menu = "groups";
    $template = "workOnGroup.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $button = "";
    $buttonAdd = "";
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif (isset($_POST['fSubmit_ok_x'])) {
        $button = _("Submit");
    }
    elseif (isset($_POST['fSubmit_back_x'])) {
        $button = _("Back");
    }
    elseif (isset($_POST['fSubmit_ok'])) {
        $button = _("Submit");
    }
    elseif (isset($_POST['fSubmit_back'])) {
        $button = _("Back");
    }
    elseif (isset($_POST['fSubmit_add_x'])) {
        $button = _("Add member");
    }
    elseif (isset($_POST['fSubmit_add'])) {
        $button = _("Add member");
    }
    elseif (isset($_POST['fSubmit_remove_x'])) {
        $button = _("Remove member");
    }
    elseif (isset($_POST['fSubmit_remove'])) {
        $button = _("Remove member");
    }
    else {
        $button = "undef";
    }
    
    if (isset($_POST['fSubmitAdd'])) {
        $buttonAdd = db_escape_string($_POST['fSubmitAdd']);
    }
    elseif (isset($_POST['fSubmitAdd_ok_x'])) {
        $buttonAdd = _("Add");
    }
    elseif (isset($_POST['fSubmitAdd_ok'])) {
        $buttonAdd = _("Add");
    }
    elseif (isset($_POST['fSubmitAdd_back_x'])) {
        $buttonAdd = _("Cancel");
    }
    elseif (isset($_POST['fSubmitAdd_back'])) {
        $buttonAdd = _("Cancel");
    }
    else {
        $buttonAdd = "undef";
    }
    
    if (isset($_POST['fDescription'])) {
        
        $tDescription = db_escape_string($_POST['fDescription']);
    }
    
    if (isset($_POST['fGroup'])) {
        
        $tGroup = db_escape_string($_POST['fGroup']);
    }
    
    if (isset($_POST['members'])) {
        
        $tMembers = db_escape_string($_POST['members']);
    }
    else {
        
        $tMembers = array();
    }
    
    if ($button == _("Add member")) {
        
        $_SESSION['svn_sessid']['groupdescr'] = $tDescription;
        $_SESSION['svn_sessid']['groupname'] = $tGroup;
        
        addMemberToGroup($tGroup, $_SESSION['svn_sessid']['members'], $dbh);
        
        db_disconnect($dbh);
        exit();
    }
    elseif ($button == _("Remove member")) {
        
        if (count($tMembers) > 0) {
            
            $new = array();
            $old = $_SESSION['svn_sessid']['members'];
            
            foreach( $old as $userid => $name) {
                
                if (! in_array($userid, $tMembers)) {
                    
                    $new[$userid] = $name;
                }
            }
            
            $_SESSION['svn_sessid']['members'] = $new;
            $tMembers = $new;
        }
        else {
            
            $tMembers = $_SESSION['svn_sessid']['members'];
        }
    }
    elseif ($button == _("Submit")) {
        
        $error = 0;
        
        if ($tGroup == "") {
            
            $tMessage = _("Group name is missing. Please fill in!");
            $error = 1;
        }
        elseif ($tDescription == "") {
            
            $tMessage = _("Group description is missing. Please fill in!");
            $error = 1;
        }
        elseif (count($_SESSION['svn_sessid']['members']) == 0) {
            
            $tMessage = _("A group must have one member at least! Otherwise delete the whole group!");
            $error = 1;
        }
        else {
            
            if ($_SESSION['svn_sessid']['task'] == "new") {
                
                $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (groupname = '$tGroup') " . "   AND (deleted = '00000000000000')";
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (groupname = '$tGroup') " . "   AND (deleted = '00000000000000') " . "   AND (id != " . $_SESSION['svn_sessid']['groupid'] . ")";
            }
            
            $result = db_query($query, $dbh);
            
            if ($result['rows'] > 0) {
                
                $tMessage = sprintf(_("Group with name %s already exists!"), $tGroup);
                $error = 1;
            }
        }
        
        if ($error == 0) {
            
            if ($_SESSION['svn_sessid']['task'] == "new") {
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION['svn_sessid']['username'], "insert of group $tGroup ($tDescription)", $dbh);
                
                $error = 0;
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "svngroups (groupname, description, created, created_user) " . "     VALUES ('$tGroup', '$tDescription', '$dbnow', '" . $_SESSION['svn_sessid']['username'] . "')";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 0) {
                    
                    $tMessage = sprintf(_("Group %s not inserted due to database errors"), $tGroup);
                    $error = 1;
                }
                else {
                    
                    $groupid = db_get_last_insert_id('svngroups', 'id', $dbh);
                    
                    foreach( $_SESSION['svn_sessid']['members'] as $userid => $name) {
                        
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')";
                        $result = db_query($query, $dbh);
                        
                        if ($result['rows'] == 1) {
                            
                            $row = db_assoc($result['result']);
                            $id = $row['id'];
                            
                            db_log($_SESSION['svn_sessid']['username'], "added $userid to group  $tGroup", $dbh);
                            
                            $dbnow = db_now();
                            $query = "INSERT INTO " . $schema . "svn_users_groups (user_id, group_id, created, created_user) " . "     VALUES ($id, $groupid, '$dbnow', '" . $_SESSION['svn_sessid']['username'] . "')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Insert of user group relation failed for user_id %s and group_id %s"), $id, $groupid);
                                $error = 1;
                            }
                        }
                        else {
                            
                            $tMessage = sprintf(_("User %s not found!"), $userid);
                            $error = 1;
                        }
                    }
                }
                
                if ($error == 0) {
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    header("Location: list_groups.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
            elseif ($_SESSION['svn_sessid']['task'] == "change") {
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION['svn_sessid']['username'], "changed group $tGroup ($tDescription)", $dbh);
                
                $error = 0;
                $groupid = $_SESSION['svn_sessid']['groupid'];
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svngroups " . "   SET groupname = '$tGroup', " . "       description = '$tDescription', " . "       modified = '$dbnow', " . "       modified_user = '" . $_SESSION['svn_sessid']['username'] . "' " . " WHERE id = $groupid";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    $tUids = array();
                    
                    foreach( $_SESSION['svn_sessid']['members'] as $uid => $name) {
                        
                        $tUids[] = $uid;
                    }
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svn_users_groups " . " WHERE (group_id = $groupid) " . "   AND (deleted = '00000000000000')";
                    $result = db_query($query, $dbh);
                    
                    while ( ($row = db_assoc($result['result'])) and ($error == 0) ) {
                        
                        $userid = db_getUseridById($row['user_id'], $dbh);
                        
                        if (! in_array($userid, $tUids)) {
                            
                            $id = $row['id'];
                            $dbnow = db_now();
                            $query = "UPDATE " . $schema . "svn_users_groups " . "   SET deleted = '$dbnow', " . "       deleted_user = '" . $_SESSION['svn_sessid']['username'] . "' " . " WHERE id = " . $id;
                            $result_del = db_query($query, $dbh);
                            
                            if ($result_del['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Delete of svn_users_group record with id %s failed"), $id);
                                $error = 1;
                            }
                            
                            db_log($_SESSION['svn_sessid']['username'], "deleted user $userid from group $tGroup", $dbh);
                        }
                    }
                    
                    foreach( $_SESSION['svn_sessid']['members'] as $userid => $name) {
                        
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')";
                        $result = db_query($query, $dbh);
                        
                        if ($result['rows'] == 1) {
                            
                            $row = db_assoc($result['result']);
                            $id = $row['id'];
                            
                            $query = "SELECT * " . "  FROM " . $schema . "svn_users_groups " . " WHERE (user_id = $id) " . "   AND (group_id = $groupid) " . "   AND (deleted = '00000000000000')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] == 0) {
                                
                                $dbnow = db_now();
                                $query = "INSERT INTO " . $schema . "svn_users_groups (user_id, group_id, created, created_user) " . "     VALUES ($id, $groupid, '$dbnow', '" . $_SESSION['svn_sessid']['username'] . "')";
                                $result = db_query($query, $dbh);
                                
                                if ($result['rows'] == 1) {
                                }
                                else {
                                    
                                    $tMessage = sprintf(_("Insert of user/group relation (%s/%s) failed due to database error"), $id, $groupid);
                                    $error = 1;
                                }
                                
                                db_log($_SESSION['svn_sessid']['username'], "added $userid to group $tGroup", $dbh);
                            }
                        }
                        else {
                            
                            $tMessage = sprintf(_("User %s not found!"), $userid);
                            $error = 1;
                        }
                    }
                }
                else {
                    
                    $tMessage = sprintf(_("Update of group %s failed due to database errors"), $tGroup);
                    $error = 1;
                }
                
                if ($error == 0) {
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    header("Location: list_groups.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        
        $tMembers = $_SESSION['svn_sessid']['members'];
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: list_groups.php");
        exit();
    }
    elseif ($buttonAdd == _("Add")) {
        
        if (isset($_POST['membersadd'])) {
            
            $membersadd = db_escape_string($_POST['membersadd']);
        }
        else {
            
            $membersadd = array();
        }
        
        foreach( $membersadd as $userid) {
            
            $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000' )";
            $result = db_query($query, $dbh);
            
            if ($result['rows'] == 1) {
                
                $row = db_assoc($result['result']);
                $name = $row['name'];
                $givenname = $row['givenname'];
                
                if ($givenname != "") {
                    
                    $name = $givenname . " " . $name;
                }
            }
            
            $_SESSION['svn_sessid']['members'][$userid] = $name;
        }
        
        $group = $_SESSION['svn_sessid']['groupid'];
        $tDescription = $_SESSION['svn_sessid']['groupdescr'];
        $tGroup = $_SESSION['svn_sessid']['groupname'];
        
        db_disconnect($dbh);
        header("Location: workOnGroup.php?group=$group&task=relist");
        exit();
    }
    elseif ($buttonAdd = _("Cancel")) {
        
        $group = $_SESSION['svn_sessid']['groupid'];
        $task = $_SESSION['svn_sessid']['task'];
        
        db_disconnect($dbh);
        header("Location: workOnGroup.php?group=$group&task=relist");
        exit();
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    $header = "groups";
    $subheader = "groups";
    $menu = "groups";
    $template = "workOnGroup.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);

?>
