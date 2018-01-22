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

$dbh = db_connect();
$SESSID_USERNAME = check_session();
$_SESSION[SVNSESSID]['helptopic'] = "password";
$schema = db_determine_schema();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $header = "password";
    $subheader = "password";
    $menu = "password";
    $template = "password.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    $fUser = $SESSID_USERNAME;
    $fPassword_current = db_escape_string($_POST['fPassword_current']);
    $fPassword = db_escape_string($_POST['fPassword']);
    $fPassword2 = db_escape_string($_POST['fPassword2']);
    
    $result = db_query("SELECT * FROM svnusers WHERE userid = '$fUser'", $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        $checked_password = addslashes(pacrypt($fPassword_current, $row['password']));
        $result = db_query("SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE userid = '$fUser' " . "   AND password = '$checked_password'", $dbh);
        
        if ($result['rows'] != 1) {
            
            $error = 1;
            $pPassword_password_current_text = _("Current password not entered!");
        }
        else {
            
            $row = db_assoc($result['result']);
            $isAdmin = $row['admin'];
        }
    }
    else {
        
        $error = 1;
        $pPassword_email_text = _("User doesn't exist!");
    }
    
    if (empty($fPassword) or ($fPassword != $fPassword2)) {
        
        $error = 1;
        $pPassword_password_text = _("New passwords do not match!");
    }
    elseif ($fPassword == $fPassword_current) {
        
        $error = 1;
        $pPassword_password_text = _("New password can not be the same as the current password!");
    }
    
    if ($error == 0) {
        
        if (checkPasswordPolicy($fPassword, $isAdmin) == 0) {
            
            $tMessage = _("Password not strong enough!");
            $error = 1;
        }
    }
    
    if ($error != 1) {
        
        db_ta("BEGIN", $dbh);
        
        $password = db_escape_string(pacrypt($fPassword), $dbh);
        $moddate = getDateJhjjmmtt();
        $dbnow = db_now();
        $result = db_query("UPDATE " . $schema . "svnusers " . "   SET password = '$password', " . "       password_modified = '$dbnow' " . " WHERE userid = '$fUser'", $dbh);
        
        if ($result['rows'] == 1) {
            
            db_log($_SESSION[SVNSESSID]['username'], "password changed", $dbh);
            
            $tMessage = _("Password changed successfully");
            
            db_ta("COMMIT", $dbh);
            
            $_SESSION[SVNSESSID]['password_expired'] = 0;
        }
        else {
            
            $tMessage = _("Password change failed due to database error!");
            db_ta("ROLLBACK", $dbh);
        }
    }
    
    $header = "password";
    $subheader = "password";
    $menu = "password";
    $template = "password.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
