<?php

/**
 * general information about the own account
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

/**
 * display information about own account
 */
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
require_once ("$installBase/include/functions.inc.php");
include_once ("$installBase/include/output.inc.php");

/**
 * get user's data
 *
 * @param integer $tUserId
 * @param resource $dbh
 * @return array
 */
function getUserData($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = $tUserId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result[RESULT]);
    
    return ($row);
    
}

/**
 * get group data
 *
 * @param integer $tGroupId
 * @param resource $dbh
 * @return array
 */
function getGroupData($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = $tGroupId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result[RESULT]);
    
    return ($row);
    
}

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];
$_SESSION[SVNSESSID]['helptopic'] = GENERAL;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $schema = db_determine_schema();
    $tUsernameError = '';
    $tGivennameError = '';
    $tNameError = '';
    $tEmailError = '';
    $tSecurityQuestionError = '';
    $tAnswerError = '';
    $tCustom1Error = '';
    $tCustom2Error = '';
    $tCustom3Error = '';
    
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (userid = '" . $SESSID_USERNAME . "') " . "ORDER BY userid ASC";
    $result = db_query($query, $dbh);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        $tUserid = $row[USERID];
        $tName = $row['name'];
        $tGivenname = $row['givenname'];
        $tEmail = $row['emailaddress'];
        list($date, $time ) = splitDateTimeI18n($row['password_modified']);
        $tPwModified = $date . " " . $time;
        $tLocked = $row['locked'] == 0 ? _("no") : _("yes");
        $tSecurityQuestion = $row['securityquestion'];
        $tAnswer = $row['securityanswer'];
        $tPasswordExpires = $row['passwordexpires'] == 1 ? _("Yes") : _("No");
        $tCustom1 = $row['custom1'];
        $tCustom2 = $row['custom2'];
        $tCustom3 = $row['custom3'];
        
        $tUserId = $row['id'];
        $tGroups = db_getGroupsForUser($tUserId, $dbh);
        $tAccessRights = db_getAccessRightsForUser($tUserId, $tGroups, $dbh);
        $tProjects = db_getProjectResponsibleForUser($tUserId, $dbh);
        
        $_SESSION[SVNSESSID][USERID] = $row['id'];
    }
    else {
        
        $tUser = array();
        $tMessage = _("User " . $SESSID_USERNAME . " does not exist!");
    }
    
    $template = "general.tpl";
    $header = GENERAL;
    $subheader = GENERAL;
    $menu = GENERAL;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
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
    
    $schema = db_determine_schema();
    $tUsernameError = 'ok';
    $tGivennameError = 'ok';
    $tNameError = 'ok';
    $tEmailError = 'ok';
    $tSecurityQuestionError = 'ok';
    $tAnswerError = 'ok';
    $tCustom1Error = 'ok';
    $tCustom2Error = 'ok';
    $tCustom3Error = 'ok';
    
    if ($button == _("Submit")) {
        
        $tGivenname = db_escape_string($_POST['fGivenname']);
        $tName = db_escape_string($_POST['fName']);
        $tEmail = db_escape_string($_POST['fEmail']);
        $tSecurityQuestion = db_escape_string($_POST['fSecurityQuestion']);
        $tAnswer = db_escape_string($_POST['fAnswer']);
        $tCustom1 = isset($_POST['fCustom1']) ? db_escape_string($_POST['fCustom1']) : "";
        $tCustom2 = isset($_POST['fCustom2']) ? db_escape_string($_POST['fCustom2']) : "";
        $tCustom3 = isset($_POST['fCustom3']) ? db_escape_string($_POST['fCustom3']) : "";
        $error = 0;
        
        if ($tName == "") {
            
            $error = 1;
            $tMessage = _("Please fill in your name!");
            $tMessageType = ALERT;
            $tNameError = ERROR;
        }
        elseif ($tEmail == "") {
            
            $error = 1;
            $tMessage = _("Please fill in your email address!");
            $tMessageType = ALERT;
            $tEmailError = ERROR;
        }
        elseif (! check_email($tEmail)) {
            
            $error = 1;
            $tMessage = sprintf(_("%s is not a valid email address!"), $tEmail);
            $tMessageType = ALERT;
            $tEmailError = ERROR;
        }
        elseif (($tAnswer != "") && ($tSecurityQuestion == "")) {
            
            $error = 1;
            $tMessage = _("Please fill in a security question too!");
            $tMessageType = ALERT;
            $tSecurityQuestionError = ERROR;
        }
        elseif (($tAnswer == "") && ($tSecurityQuestion != "")) {
            
            $error = 1;
            $tMessage = _("Please fill in an answer for the security question too!");
            $tMessageType = ALERT;
            $tAnswerError = ERROR;
        }
        
        if ($error == 0) {
            
            db_ta('BEGIN', $dbh);
            db_log($_SESSION[SVNSESSID]['username'], "user changed his data( $tName, $tGivenname, $tEmail)", $dbh);
            
            $query = "UPDATE " . $schema . "svnusers " . "   SET givenname = '$tGivenname', " . "       name = '$tName', " . "       emailaddress = '$tEmail', " . "       securityquestion = '$tSecurityQuestion', " . "       securityanswer = '$tAnswer', " . "       custom1 = '$tCustom1', " . "       custom2 = '$tCustom2', " . "       custom3 = '$tCustom3' " . " WHERE (id = " . $_SESSION[SVNSESSID][USERID] . ")";
            $result = db_query($query, $dbh);
            
            if ($result['rows'] > 0) {
                
                db_ta('COMMIT', $dbh);
                $tMessage = _("Changed data successfully");
                $tMessageType = "success";
            }
        }
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: main.php");
        exit();
    }
    
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (userid = '" . $SESSID_USERNAME . "') " . "ORDER BY userid ASC";
    $result = db_query($query, $dbh);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        $tUserid = $row[USERID];
        
        list($date, $time ) = splitdateTime($row['password_modified']);
        $tPwModified = $date . " " . $time;
        $tLocked = $row['locked'] == 0 ? _("no") : _("yes");
        $tPasswordExpires = $row['passwordexpires'] == 1 ? _("Yes") : _("No");
        
        if ($error == 0) {
            $tSecurityQuestion = $row['securityquestion'];
            $tAnswer = $row['securityanswer'];
            $tName = $row['name'];
            $tGivenname = $row['givenname'];
            $tEmail = $row['emailaddress'];
            $tCustom1 = $row['custom1'];
            $tCustom2 = $row['custom2'];
            $tCustom3 = $row['custom3'];
        }
        
        $tGroups = db_getGroupsForUser($_SESSION[SVNSESSID][USERID], $dbh);
        $tAccessRights = db_getAccessRightsForUser($_SESSION[SVNSESSID][USERID], $tGroups, $dbh);
        $tProjects = db_getProjectResponsibleForUser($_SESSION[SVNSESSID][USERID], $dbh);
    }
    else {
        
        $tUser = array();
        $tMessage = _("User " . $SESSID_USERNAME . " does not exist!");
        $tMessageType = ALERT;
    }
    
    $template = "general.tpl";
    $header = GENERAL;
    $subheader = GENERAL;
    $menu = GENERAL;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}
?>
