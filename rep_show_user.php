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
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

function getUsers($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tUsers = array();
    $query = " SELECT * " . "   FROM " . $schema . "svnusers " . "   WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tUsers[] = $row;
    }
    
    return $tUsers;

}

function getGroupsForUser($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tGroups = array();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups, " . $schema . "svn_users_groups " . " WHERE (svn_users_groups.user_id = '$tUserId') " . "   AND (svn_users_groups.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_users_groups.deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tGroups[] = $row;
    }
    
    return ($tGroups);

}

function getProjectResponsibleForUser($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tProjects = array();
    $query = "SELECT svnmodule, reponame " . "  FROM " . $schema . "svnprojects, " . $schema . "svn_projects_responsible, " . $schema . "svnrepos " . " WHERE (svn_projects_responsible.user_id = '$tUserId') " . "   AND (svn_projects_responsible.deleted = '00000000000000') " . "   AND (svn_projects_responsible.project_id = svnprojects.id) " . "   AND (svnprojects.deleted = '00000000000000') " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svnrepos.deleted = '00000000000000') " . "ORDER BY svnmodule ASC";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tProjects[] = $row;
    }
    
    return ($tProjects);

}

function getAccessRightsForUser($tUserId, $tGroups, $dbh) {

    global $CONF;
    
    if (isset($CONF['repoPathSortOrder'])) {
        $pathSort = $CONF['repoPathSortOrder'];
    }
    else {
        $pathSort = "ASC";
    }
    
    $schema = db_determine_schema();
    $tAccessRights = array();
    $curdate = strftime("%Y%m%d");
    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) ";
    if (count($tGroups) > 0) {
        $query .= "     AND ((svn_access_rights.user_id = $tUserId) ";
        foreach( $tGroups as $entry) {
            $query .= "    OR (svn_access_rights.group_id = " . $entry['group_id'] . ") ";
        }
        $query .= "       ) ";
    }
    else {
        $query .= "     AND (svn_access_rights.user_id = $tUserId) ";
    }
    $query .= "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svnprojects.svnmodule ASC, svn_access_rights.path $pathSort";
    
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        if (($row['user_id'] != 0) and ($row['group_id'] != 0)) {
            $row['access_by'] = _("user id + group id");
        }
        elseif ($row['group_id'] != 0) {
            $row['access_by'] = _("group id");
        }
        elseif ($row['user_id'] != 0) {
            $row['access_by'] = _("user id");
        }
        else {
            $row['access_by'] = " ";
        }
        $tAccessRights[] = $row;
    }
    
    return ($tAccessRights);

}

function getUserData($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = $tUserId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    
    return ($row);

}

function getGroupData($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = $tGroupId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    
    return ($row);

}

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Reports", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "repshowuser";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use rep_show_user without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $lang = check_language();
    $tUsers = getUsers(0, - 1, $dbh);
    
    $template = "rep_show_user.tpl";
    $header = REPORTS;
    $subheader = REPORTS;
    $menu = REPORTS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_show_x'])) || (isset($_POST['fSubmit_show']))) {
        $button = _("Create report");
    }
    else {
        $button = "undef";
    }
    
    if ($button == _("Create report")) {
        
        $tUserId = isset($_POST['fUser']) ? db_escape_string($_POST['fUser']) : "";
        $_SESSION[SVNSESSID]['user'] = $tUserId;
        
        if ($tUserId == "default") {
            
            $tMessage = _("No user selected!");
            $lang = check_language();
            $tUsers = getUsers(0, - 1, $dbh);
            $template = "rep_show_user.tpl";
            $header = REPORTS;
            $subheader = REPORTS;
            $menu = REPORTS;
            
            include ("$installBase/templates/framework.tpl");
            
            db_disconnect($dbh);
            
            exit();
        }
        else {
            
            $tUser = db_getUseridById($tUserId, $dbh);
            $tUserData = getUserData($tUserId, $dbh);
            $tUsername = $tUserData['userid'];
            $tAdministrator = $tUserData['admin'] == "y" ? _("Yes") : _("No");
            $tName = $tUserData['name'];
            $tGivenname = $tUserData['givenname'];
            $tEmailAddress = $tUserData['emailaddress'];
            $tLocked = $tUserData['locked'] == 0 ? _("No") : _("Yes");
            $tPasswordExpires = $tUserData['passwordexpires'] == 1 ? _("Yes") : _("No");
            $tAccessRight = $tUserData['user_mode'];
            $tPasswordModified = implode(" ", splitDateTime($tUserData['password_modified']));
            $lang = check_language();
            $tGroups = getGroupsForUser($tUserId, $dbh);
            $tAccessRights = getAccessRightsForUser($tUserId, $tGroups, $dbh);
            $tProjects = getProjectResponsibleForUser($tUserId, $dbh);
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    $template = "rep_show_user_result.tpl";
    $header = REPORTS;
    $subheader = REPORTS;
    $menu = REPORTS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

?>
