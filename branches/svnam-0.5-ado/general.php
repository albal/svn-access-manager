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
if (file_exists ( realpath ( "./config/config.inc.php" ) )) {
    require ("./config/config.inc.php");
}
elseif (file_exists ( realpath ( "../config/config.inc.php" ) )) {
    require ("../config/config.inc.php");
}
elseif (file_exists ( "/etc/svn-access-manager/config.inc.php" )) {
    require ("/etc/svn-access-manager/config.inc.php");
}
else {
    die ( "can't load config.inc.php. Check your installation!\n" );
}

$installBase = isset ( $CONF ['install_base'] ) ? $CONF ['install_base'] : "";

require ("$installBase/include/variables.inc.php");
// require ("./config/config.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
require_once ("$installBase/include/functions.inc.php");
include_once ("$installBase/include/output.inc.php");
function getGroupsForUser($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema ();
    $tGroups = array ();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups, " . $schema . "svn_users_groups " . " WHERE (svn_users_groups.user_id = '$tUserId') " . "   AND (svn_users_groups.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_users_groups.deleted = '00000000000000')";
    $result = db_query ( $query, $dbh );
    
    while ( $row = db_assoc ( $result ['result'] ) ) {
        
        $tGroups [] = $row;
    }
    
    return ($tGroups);

}
function getProjectResponsibleForUser($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema ();
    $tProjects = array ();
    $query = "SELECT svnmodule, reponame " . "  FROM " . $schema . "svnprojects, " . $schema . "svn_projects_responsible, " . $schema . "svnrepos " . " WHERE (svn_projects_responsible.user_id = '$tUserId') " . "   AND (svn_projects_responsible.deleted = '00000000000000') " . "   AND (svn_projects_responsible.project_id = svnprojects.id) " . "   AND (svnprojects.deleted = '00000000000000') " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svnrepos.deleted = '00000000000000') " . "ORDER BY svnmodule ASC";
    $result = db_query ( $query, $dbh );
    
    while ( $row = db_assoc ( $result ['result'] ) ) {
        
        $tProjects [] = $row;
    }
    
    return ($tProjects);

}
function getAccessRightsForUser($tUserId, $tGroups, $dbh) {

    global $CONF;
    
    if (isset ( $CONF ['repoPathSortOrder'] )) {
        $pathSort = $CONF ['repoPathSortOrder'];
    }
    else {
        $pathSort = "ASC";
    }
    
    $schema = db_determine_schema ();
    $tAccessRights = array ();
    $curdate = strftime ( "%Y%m%d" );
    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) ";
    if (count ( $tGroups ) > 0) {
        $query .= "     AND ((svn_access_rights.user_id = $tUserId) ";
        foreach ( $tGroups as $entry ) {
            $query .= "    OR (svn_access_rights.group_id = " . $entry ['group_id'] . ") ";
        }
        $query .= "       ) ";
    }
    else {
        $query .= "     AND (svn_access_rights.user_id = $tUserId) ";
    }
    $query .= "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svnprojects.svnmodule ASC, svn_access_rights.path $pathSort";
    
    $result = db_query ( $query, $dbh );
    
    while ( $row = db_assoc ( $result ['result'] ) ) {
        
        if (($row ['user_id'] != 0) and ($row ['group_id'] != 0)) {
            $row ['access_by'] = _ ( "user id + group id" );
        }
        elseif ($row ['group_id'] != 0) {
            $row ['access_by'] = _ ( "group id" );
        }
        elseif ($row ['user_id'] != 0) {
            $row ['access_by'] = _ ( "user id" );
        }
        else {
            $row ['access_by'] = " ";
        }
        $tAccessRights [] = $row;
    }
    
    return ($tAccessRights);

}
function getUserData($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema ();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = $tUserId)";
    $result = db_query ( $query, $dbh );
    $row = db_assoc ( $result ['result'] );
    
    return ($row);

}
function getGroupData($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema ();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = $tGroupId)";
    $result = db_query ( $query, $dbh );
    $row = db_assoc ( $result ['result'] );
    
    return ($row);

}

initialize_i18n ();

$SESSID_USERNAME = check_session ();
check_password_expired ();
$dbh = db_connect ();
$preferences = db_get_preferences ( $SESSID_USERNAME, $dbh );
$CONF ['page_size'] = $preferences ['page_size'];
$_SESSION ['svn_sessid'] ['helptopic'] = "general";

if ($_SERVER ['REQUEST_METHOD'] == "GET") {
    
    $schema = db_determine_schema ();
    
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (userid = '" . $SESSID_USERNAME . "') " . "ORDER BY userid ASC";
    $result = db_query ( $query, $dbh );
    if ($result ['rows'] == 1) {
        
        $row = db_assoc ( $result ['result'] );
        $tUserid = $row ['userid'];
        $tName = $row ['name'];
        $tGivenname = $row ['givenname'];
        $tEmail = $row ['emailaddress'];
        list ( $date, $time ) = splitdateTime ( $row ['password_modified'] );
        $tPwModified = $date . " " . $time;
        $tLocked = $row ['locked'] == 0 ? _ ( "no" ) : _ ( "yes" );
        $tSecurityQuestion = $row ['securityquestion'];
        $tAnswer = $row ['securityanswer'];
        $tPasswordExpires = $row ['passwordexpires'] == 1 ? _ ( "Yes" ) : _ ( "No" );
        $tCustom1 = $row ['custom1'];
        $tCustom2 = $row ['custom2'];
        $tCustom3 = $row ['custom3'];
        
        $tUserId = $row ['id'];
        $tGroups = getGroupsForUser ( $tUserId, $dbh );
        $tAccessRights = getAccessRightsForUser ( $tUserId, $tGroups, $dbh );
        $tProjects = getProjectResponsibleForUser ( $tUserId, $dbh );
        
        $_SESSION ['svn_sessid'] ['userid'] = $row ['id'];
    }
    else {
        
        $tUser = array ();
        $tMessage = _ ( "User " . $SESSID_USERNAME . " does not exist!" );
    }
    
    $template = "general.tpl";
    $header = "general";
    $subheader = "general";
    $menu = "general";
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect ( $dbh );
}

if ($_SERVER ['REQUEST_METHOD'] == "POST") {
    
    if (isset ( $_POST ['fSubmit'] )) {
        $button = db_escape_string ( $_POST ['fSubmit'] );
    }
    elseif (isset ( $_POST ['fSubmit_ok_x'] )) {
        $button = _ ( "Submit" );
    }
    elseif (isset ( $_POST ['fSubmit_back_x'] )) {
        $button = _ ( "Back" );
    }
    elseif (isset ( $_POST ['fSubmit_ok'] )) {
        $button = _ ( "Submit" );
    }
    elseif (isset ( $_POST ['fSubmit_back'] )) {
        $button = _ ( "Back" );
    }
    else {
        $button = "undef";
    }
    
    $schema = db_determine_schema ();
    
    if ($button == _ ( "Submit" )) {
        
        $tGivenname = db_escape_string ( $_POST ['fGivenname'] );
        $tName = db_escape_string ( $_POST ['fName'] );
        $tEmail = db_escape_string ( $_POST ['fEmail'] );
        $tSecurityQuestion = db_escape_string ( $_POST ['fSecurityQuestion'] );
        $tAnswer = db_escape_string ( $_POST ['fAnswer'] );
        $tCustom1 = isset ( $_POST ['fCustom1'] ) ? db_escape_string ( $_POST ['fCustom1'] ) : "";
        $tCustom2 = isset ( $_POST ['fCustom2'] ) ? db_escape_string ( $_POST ['fCustom2'] ) : "";
        $tCustom3 = isset ( $_POST ['fCustom3'] ) ? db_escape_string ( $_POST ['fCustom3'] ) : "";
        $error = 0;
        
        if ($tName == "") {
            
            $error = 1;
            $tMessage = _ ( "Please fill in your name!" );
        }
        elseif ($tEmail == "") {
            
            $error = 1;
            $tMessage = _ ( "Please fill in your email address!" );
        }
        elseif (! check_email ( $tEmail )) {
            
            $error = 1;
            $tMessage = sprintf ( _ ( "%s is not a valid email address!" ), $tEmail );
        }
        elseif (($tAnswer != "") and ($tSecurityQuestion == "")) {
            
            $error = 1;
            $tMessage = _ ( "Please fill in a security question too!" );
        }
        elseif (($tAnswer == "") and ($tSecurityQuestion != "")) {
            
            $error = 1;
            $tMessage = _ ( "Please fill in an answer for the security question too!" );
        }
        
        if ($error == 0) {
            
            db_ta ( 'BEGIN', $dbh );
            db_log ( $_SESSION ['svn_sessid'] ['username'], "user changed his data( $tName, $tGivenname, $tEmail)", $dbh );
            
            $query = "UPDATE " . $schema . "svnusers " . "   SET givenname = '$tGivenname', " . "       name = '$tName', " . "       emailaddress = '$tEmail', " . "       securityquestion = '$tSecurityQuestion', " . "       securityanswer = '$tAnswer', " . "       custom1 = '$tCustom1', " . "       custom2 = '$tCustom2', " . "       custom3 = '$tCustom3' " . " WHERE (id = " . $_SESSION ['svn_sessid'] ['userid'] . ")";
            $result = db_query ( $query, $dbh );
            
            if ($result ['rows'] > 0) {
                
                db_ta ( 'COMMIT', $dbh );
                $tMessage = _ ( "Changed data successfully" );
            }
        }
    }
    elseif ($button == _ ( "Back" )) {
        
        db_disconnect ( $dbh );
        header ( "Location: main.php" );
        exit ();
    }
    
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (userid = '" . $SESSID_USERNAME . "') " . "ORDER BY userid ASC";
    $result = db_query ( $query, $dbh );
    if ($result ['rows'] == 1) {
        
        $row = db_assoc ( $result ['result'] );
        $tUserid = $row ['userid'];
        $tName = $row ['name'];
        $tGivenname = $row ['givenname'];
        $tEmail = $row ['emailaddress'];
        list ( $date, $time ) = splitdateTime ( $row ['password_modified'] );
        $tPwModified = $date . " " . $time;
        $tLocked = $row ['locked'] == 0 ? _ ( "no" ) : _ ( "yes" );
        $tSecurityQuestion = $row ['securityquestion'];
        $tAnswer = $row ['securityanswer'];
        $tPasswordExpires = $row ['passwordexpires'] == 1 ? _ ( "Yes" ) : _ ( "No" );
        $tCustom1 = $row ['custom1'];
        $tCustom2 = $row ['custom2'];
        $tCustom3 = $row ['custom3'];
        
        $tGroups = getGroupsForUser ( $_SESSION ['svn_sessid'] ['userid'], $dbh );
        $tAccessRights = getAccessRightsForUser ( $_SESSION ['svn_sessid'] ['userid'], $tGroups, $dbh );
        $tProjects = getProjectResponsibleForUser ( $_SESSION ['svn_sessid'] ['userid'], $dbh );
    }
    else {
        
        $tUser = array ();
        $tMessage = _ ( "User " . $SESSID_USERNAME . " does not exist!" );
    }
    
    $template = "general.tpl";
    $header = "general";
    $subheader = "general";
    $menu = "general";
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect ( $dbh );
}
?>
