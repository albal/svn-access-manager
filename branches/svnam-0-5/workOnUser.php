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

function getRights($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $lang = check_language();
    $tRightsAvailable = array();
    $query = "SELECT id, right_name, allowed_action, description_$lang AS description " . "  FROM " . $schema . "rights " . " WHERE (deleted = '00000000000000') " . " ORDER BY id ASC";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tRightsAvailable[] = $row;
    }
    
    return $tRightsAvailable;

}

function getRightsGranted($user_id, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $tRightsGranted = array();
    $query = "SELECT right_id, allowed " . "  FROM " . $schema . "users_rights " . " WHERE (user_id = $user_id) " . "   AND (deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tRightsGranted[$row['right_id']] = $row['allowed'];
    }
    
    return $tRightsGranted;

}

function getLdapUser() {


}

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "User admin", $dbh);
$isGlobalAdmin = db_check_global_admin($SESSID_USERNAME, $dbh);
$SESSID_USERID = db_getIdByUserid($SESSID_USERNAME, $dbh);
$tRightsGrantedToCurUser = getRightsGranted($SESSID_USERID, $dbh);
$_SESSION['svn_sessid']['helptopic'] = "workonuser";
if ($rightAllowed == "add") {
    $tDisabled = "disabled";
}
else {
    $tDisabled = "";
}
if (($rightAllowed != "delete")) {
    $tDisabledAdmin = "disabled";
}
else {
    $tDisabledAdmin = "";
}

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use workOnUser without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tReadonly = "";
    $tTask = db_escape_string($_GET['task']);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if ((($rightAllowed == "add") and ($tTask != "new"))) {
        
        db_log($SESSID_USERNAME, "tried to use workOnUser without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    $_SESSION['svn_sessid']['task'] = strtolower($tTask);
    $_SESSION['svn_sessid']['userid'] = $tId;
    $tRightsAvailable = getRights($dbh);
    
    $schema = db_determine_schema();
    
    if ($_SESSION['svn_sessid']['task'] == "new") {
        
        $tUserid = "";
        $tName = "";
        $tGivenname = "";
        $tEmail = "";
        $tCustom1 = "''";
        $tCustom2 = "''";
        $tCustom3 = "''";
        if (isset($CONF['expire_password'])) {
            $tPasswordExpires = $CONF['expire_password'];
        }
        else {
            $tPasswordExpires = 1;
        }
        $tLocked = 0;
        $tAdministrator = "n";
        if (isset($CONF['userDefaultAccess'])) {
            $tUserRight = $CONF['userDefaultAccess'];
        }
        else {
            $tUserRight = "read";
        }
        $tRightsGranted = array();
        if ((isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES")) {
            $tUsers = get_ldap_users();
        }
        
        $_SESSION['svn_sessid']['rightsgranted'] = array();
        $_SESSION['svn_sessid']['passwordexpires'] = "1";
        $_SESSION['svn_sessid']['locked'] = "0";
        $_SESSION['svn_sessid']['adminster'] = "n";
    }
    elseif ($_SESSION['svn_sessid']['task'] == "change") {
        
        $tReadonly = "readonly";
        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE id = $tId";
        $result = db_query($query, $dbh);
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result['result']);
            $tUserid = $row['userid'];
            $tName = $row['name'];
            $tGivenname = $row['givenname'];
            $tEmail = $row['emailaddress'];
            $tCustom1 = (empty($row['custom1']) ? "''" : $row['custom1']);
            $tCustom2 = (empty($row['custom2']) ? "''" : $row['custom2']);
            $tCustom3 = (empty($row['custom3']) ? "''" : $row['custom3']);
            $tPasswordExpires = $row['passwordexpires'];
            $tLocked = $row['locked'];
            $tAdministrator = $row['admin'];
            $tUserRight = $row['user_mode'];
            $tRightsGranted = getRightsGranted($row['id'], $dbh);
            $_SESSION['svn_sessid']['rightsgranted'] = $tRightsGranted;
            $_SESSION['svn_sessid']['passwordexpires'] = $tPasswordExpires;
            $_SESSION['svn_sessid']['locked'] = $tLocked;
            $_SESSION['svn_sessid']['adminster'] = $tAdministrator;
        }
        else {
            
            $tMessage = sprintf(_("Invalid userid %s requested!"), $id);
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION['svn_sessid']['task']);
    }
    
    $header = "users";
    $subheader = "users";
    $menu = "users";
    $template = "workOnUser.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $tUserid = isset($_POST['fUserid']) ? db_escape_string($_POST['fUserid']) : "";
    $tUserid = explode(":", $tUserid);
    $tUserid = $tUserid[0];
    $tName = isset($_POST['fName']) ? db_escape_string($_POST['fName']) : "";
    $tGivenname = isset($_POST['fGivenname']) ? db_escape_string($_POST['fGivenname']) : "";
    $tPassword = isset($_POST['fPassword']) ? db_escape_string($_POST['fPassword']) : "";
    $tPassword2 = isset($_POST['fPassword2']) ? db_escape_string($_POST['fPassword2']) : "";
    $tEmail = isset($_POST['fEmail']) ? db_escape_string($_POST['fEmail']) : "";
    $tCustom1 = isset($_POST['fCustom1']) ? db_escape_string($_POST['fCustom1']) : "";
    $tCustom2 = isset($_POST['fCustom2']) ? db_escape_string($_POST['fCustom2']) : "";
    $tCustom3 = isset($_POST['fCustom3']) ? db_escape_string($_POST['fCustom3']) : "";
    $tPasswordExpires = isset($_POST['fPasswordExpires']) ? db_escape_string($_POST['fPasswordExpires']) : $_SESSION['svn_sessid']['passwordexpires'];
    $tLocked = isset($_POST['fLocked']) ? db_escape_string($_POST['fLocked']) : $_SESSION['svn_sessid']['locked'];
    $tAdministrator = isset($_POST['fAdministrator']) ? db_escape_string($_POST['fAdministrator']) : $_SESSION['svn_sessid']['adminster'];
    $tUserRight = isset($_POST['fUserRight']) ? db_escape_string($_POST['fUserRight']) : "";
    $tRightsAvailable = getRights($dbh);
    $tRightsGranted = array();
    
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
    else {
        $button = "undef";
    }
    
    $schema = db_determine_schema();
    
    if ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: list_users.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        if ($_SESSION['svn_sessid']['task'] == "new") {
            
            $error = 0;
            
            if ($tUserid == "") {
                
                $tMessage = _("Userid is missing, please fill in!");
                $error = 1;
            }
            elseif ($tUserid == "default") {
                
                $tMessage = _("Please select an user!");
                $error = 1;
            }
            elseif ($tName == "") {
                
                $tMessage = _("Name missing, please fill in!");
                $error = 1;
            }
            elseif ((! isset($CONF['use_ldap'])) or ((isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES"))) {
                
                if (($tPassword == "") and ($tPassword2 == "")) {
                    
                    $tMessage = _("A new user needs a password!");
                    $error = 1;
                }
            }
            elseif ((! isset($CONF['use_ldap'])) or ((isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES"))) {
                
                if (($tPassword != "") or ($tPassword2 != "")) {
                    
                    if ($tPassword != $tPassword2) {
                        
                        $tMessage = _("Passwords do not match!");
                        $error = 1;
                    }
                    else {
                        
                        $retval = checkPasswordPolicy($tPassword, $tAdministrator);
                        if ($retval == 0) {
                            
                            $tMessage = _("Password does not match the password policy!");
                            $error = 1;
                        }
                    }
                }
            }
            
            if ($tEmail == "") {
                
                $tMessage = _("Email address is missing, please fill in!");
                $error = 1;
            }
            elseif (! check_email($tEmail)) {
                
                $tMessage = sprintf(_("%s is not a valid email address!"), $tEmail);
                $error = 1;
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$tUserid') " . "   AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] > 0) {
                    
                    $tMessage = sprintf(_("The user with the userid %s exists already"), $tUserid);
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                foreach( $tRightsAvailable as $right) {
                    
                    $right_id = $right['id'];
                    $tOldRight = isset($_SESSION['svn_sessid']['rightsgranted'][$right_id]) ? $_SESSION['svn_sessid']['rightsgranted'][$right_id] : "";
                    $field = "fId" . $right_id;
                    $value = isset($_POST[$field]) ? db_escape_string($_POST[$field]) : $tOldRight;
                    $tCurRight = $tRightsGrantedToCurUser[$right_id];
                    $tRightName = db_getRightName($right_id, $dbh);
                    $tRightsGranted[$right_id] = $value;
                    
                    if (strtolower($value) == "delete") {
                        
                        if ($tCurRight != "delete") {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "edit") {
                        
                        if (($tCurRight != "delete") and ($tCurRight != "edit")) {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "add") {
                        
                        if (($tCurRight != "delete") and ($tCurRight != "edit") and ($tCurRight != "add")) {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "read") {
                        
                        if (($tCurRight != "delete") and ($tCurRight != "edit") and ($tCurRight != "add") and ($tCurRight != "read")) {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "none") {
                    }
                    else {
                        
                        $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                        $error = 1;
                    }
                }
            }
            
            if ($error == 0) {
                
                $tPassword = ($tPassword == "") ? generatePassword("y") : $tPassword;
                $pwcrypt = db_escape_string(pacrypt($tPassword), $dbh);
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "svnusers (userid, name, givenname, password, passwordexpires, locked, emailaddress, custom1, custom2, custom3, admin, created, created_user, password_modified, user_mode) " . "     VALUES ('$tUserid', '$tName', '$tGivenname', '$pwcrypt', '$tPasswordExpires', '$tLocked', '$tEmail', '$tCustom1', '$tCustom2','$tCustom3','$tAdministrator','$dbnow', '" . $_SESSION['svn_sessid']['username'] . "', '20000101000000', '$tUserRight')";
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION['svn_sessid']['username'], "added user $tUserid, $tName, $tGivenname", $dbh);
                
                $result = db_query($query, $dbh);
                if ($result['rows'] == 1) {
                    
                    $lastid = db_get_last_insert_id('svnusers', 'id', $dbh);
                    
                    foreach( $tRightsAvailable as $right) {
                        
                        $right_id = $right['id'];
                        $field = "fId" . $right_id;
                        $value = isset($_POST[$field]) ? db_escape_string($_POST[$field]) : "";
                        
                        if ($value != "") {
                            $query = "SELECT * " . "  FROM " . $schema . "users_rights " . " WHERE (right_id = $right_id) " . "   AND (user_id = $lastid) " . "   AND (deleted = '00000000000000')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] > 0) {
                                
                                $dbnow = db_now();
                                $query = "UPDATE " . $schema . "users_rights " . "   SET modified = '$dbnow', " . "       modified_user = '" . $_SESSION['svn_sessid']['username'] . "'," . "       allowed = '$value' " . " WHERE (user_id = $lastid) " . "   AND (right_id = $right_id)";
                            }
                            else {
                                
                                $dbnow = db_now();
                                $query = "INSERT INTO " . $schema . "users_rights (right_id, user_id, allowed, created, created_user) " . "     VALUES ($right_id, $lastid, '$value', '$dbnow', '" . $_SESSION['svn_sessid']['username'] . "')";
                            }
                            
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] == 0) {
                                
                                $tMessage = _("Error during database write of user rights");
                                $error = 1;
                            }
                        }
                    }
                    
                    $tRightsGranted = getRightsGranted($lastid, $dbh);
                }
                else {
                    
                    $error = 1;
                    $tMessaage = _("Error during database insert of user data");
                }
                
                if ($error != 0) {
                    
                    db_ta('ROLLBACK', $dbh);
                }
                else {
                    
                    db_ta('COMMIT', $dbh);
                    
                    $tMessage = _("User successfully inserted");
                }
            }
        }
        elseif ($_SESSION['svn_sessid']['task'] == "change") {
            
            $error = 0;
            $tReadonly = "readonly";
            
            if ($tUserid == "") {
                
                $tMessage = _("Userid is missing, please fill in!");
                $error = 1;
            }
            elseif ($tName == "") {
                
                $tMessage = _("Name missing, please fill in!");
                $error = 1;
            }
            elseif ((! isset($CONF['use_ldap'])) or ((isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES"))) {
                
                if (($tPassword != "") or ($tPassword2 != "")) {
                    
                    if ($tPassword != $tPassword2) {
                        
                        $tMessage = _("Passwords do not match!");
                        $error = 1;
                    }
                    else {
                        
                        $retval = checkPasswordPolicy($tPassword);
                        if ($retval == 0) {
                            
                            $tMessage = _("Password does not match the password policy!");
                            $error = 1;
                        }
                    }
                }
            }
            
            if ($tEmail == "") {
                
                $tMessage = _("Emailaddress is missing, please fill in!");
                $error = 1;
            }
            elseif (! check_email($tEmail)) {
                
                $tMessage = sprintf(_("%s is not a valid email address!"), $tEmail);
                $error = 1;
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . "  WHERE (userid = '$tUserid') " . "    AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 0) {
                    
                    $tMessage = sprintf(_("The user %s does not exist"), $tUserid);
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                foreach( $tRightsAvailable as $right) {
                    
                    $right_id = $right['id'];
                    $tOldRight = isset($_SESSION['svn_sessid']['rightsgranted'][$right_id]) ? $_SESSION['svn_sessid']['rightsgranted'][$right_id] : "";
                    $field = "fId" . $right_id;
                    $value = isset($_POST[$field]) ? db_escape_string($_POST[$field]) : $tOldRight;
                    $tCurRight = $tRightsGrantedToCurUser[$right_id];
                    $tRightName = db_getRightName($right_id, $dbh);
                    $tRightsGranted[$right_id] = $value;
                    
                    if (strtolower($value) == "delete") {
                        
                        if ($tCurRight != "delete") {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "edit") {
                        
                        if (($tCurRight != "delete") and ($tCurRight != "edit")) {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "add") {
                        
                        if (($tCurRight != "delete") and ($tCurRight != "edit") and ($tCurRight != "add")) {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "read") {
                        
                        if (($tCurRight != "delete") and ($tCurRight != "edit") and ($tCurRight != "add") and ($tCurRight != "read")) {
                            
                            $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                            $error = 1;
                        }
                    }
                    elseif (strtolower($value) == "none") {
                    }
                    else {
                        
                        $tMessage = sprintf(_("You are not allowed to grant the right '%s' for '%s' because you have insufficient privileges: '%s'"), $value, $tRightName, $tCurRight);
                        $error = 1;
                    }
                }
            }
            
            if ($error == 0) {
                
                $pwcrypt = db_escape_string(pacrypt($tPassword), $dbh);
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svnusers " . "   SET name 			= '$tName', " . "       givenname 		= '$tGivenname', " . "       emailaddress 	= '$tEmail', " . "       custom1         = '$tCustom1', " . "       custom2         = '$tCustom2', " . "       custom3         = '$tCustom3', " . "       passwordexpires 	= '$tPasswordExpires', " . "       locked 			= '$tLocked', " . "       admin 			= '$tAdministrator', " . "       user_mode 	    = '$tUserRight', " . "       modified 		= '$dbnow', " . "       modified_user 	= '" . $_SESSION['svn_sessid']['username'] . "'";
                
                if ($tPassword != "") {
                    
                    $query .= ", password = '$pwcrypt'";
                }
                
                $query .= " WHERE (id = " . $_SESSION['svn_sessid']['userid'] . ")";
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION['svn_sessid']['username'], "updated user $tUserid", $dbh);
                
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    foreach( $tRightsAvailable as $right) {
                        
                        $right_id = $right['id'];
                        $field = "fId" . $right_id;
                        $value = isset($_POST[$field]) ? db_escape_string($_POST[$field]) : "";
                        
                        if ($value != "") {
                            $query = "SELECT * " . "  FROM " . $schema . "users_rights " . " WHERE (right_id = $right_id) " . "   AND (user_id = " . $_SESSION['svn_sessid']['userid'] . ") " . "   AND (deleted = '00000000000000')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] > 0) {
                                
                                $dbnow = db_now();
                                $query = "UPDATE " . $schema . "users_rights " . "   SET modified = '$dbnow', " . "       modified_user = '" . $_SESSION['svn_sessid']['username'] . "'," . "       allowed = '$value' " . " WHERE (user_id = " . $_SESSION['svn_sessid']['userid'] . ") " . "   AND (right_id = $right_id)";
                            }
                            else {
                                
                                $dbnow = db_now();
                                $query = "INSERT INTO " . $schema . "users_rights (right_id, user_id, allowed, created, created_user) " . "     VALUES ($right_id, " . $_SESSION['svn_sessid']['userid'] . ", '$value', '$dbnow', '" . $_SESSION['svn_sessid']['username'] . "')";
                            }
                            
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] == 0) {
                                
                                $tMessage = _("Error during database write of user rights");
                                $error = 1;
                            }
                        }
                    }
                    
                    $tRightsGranted = getRightsGranted($_SESSION['svn_sessid']['userid'], $dbh);
                }
                else {
                    
                    $tMessage = _("User not modified due to database error");
                    $error = 1;
                }
                
                if ($error == 0) {
                    
                    db_ta('COMMIT', $dbh);
                    
                    $tMessage = _("User successfully modified");
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        else {
            
            $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION['svn_sessid']['task']);
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    if ((isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES")) {
        $tUsers = get_ldap_users();
    }
    
    $header = "users";
    $subheader = "users";
    $menu = "users";
    $template = "workOnUser.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
