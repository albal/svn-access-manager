<?php

/**
 * Work on a messages
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
 * $LastChangedDate: 2018-04-25 11:39:05 +0200 (Wed, 25 Apr 2018) $
 * $LastChangedBy: kriegeth $
 *
 * $Id: workOnProject.php 1088 2018-04-25 09:39:05Z kriegeth $
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
$rightAllowed = db_check_acl($SESSID_USERNAME, "User admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "workonmessage";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use workOnMessage without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

$schema = db_determine_schema();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tValidFromError = '';
    $tValidUntilError = '';
    $tUserMessageError = '';
    
    $tTask = db_escape_string($_GET['task']);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if (($rightAllowed == "add") && ($tTask != "new")) {
        
        db_log($SESSID_USERNAME, "tried to use workOnMessage without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    $_SESSION[SVNSESSID]['task'] = strtolower($tTask);
    $_SESSION[SVNSESSID][MESSAGEID] = $tId;
    
    if ($_SESSION[SVNSESSID]['task'] == "new") {
        $tValidFrom = '';
        $tValidUntil = '';
        $tUserMessage = '';
    }
    elseif ($_SESSION[SVNSESSID]['task'] == "change") {
        
        $query = "SELECT * " . "  FROM " . $schema . "messages " . " WHERE id = $tId";
        $result = db_query($query, $dbh);
        if ($result['rows'] == 1) {
            $row = db_assoc($result[RESULT]);
            $tMessageId = $row['id'];
            $tValidFrom = $row['validfrom'];
            $tValidUntil = $row['validuntil'];
            $tUserMessage = $row['message'];
            $tValidFrom = splitDateForBootstrap($tValidFrom);
            $tValidUntil = splitDateForBootstrap($tValidUntil);
            
            $_SESSION[SVNSESSID]['validfrom'] = $tValidFrom;
            $_SESSION[SVNSESSID]['validuntil'] = $tValidUntil;
            $_SESSION[SVNSESSID]['message'] = $tUserMessage;
            $_SESSION[SVNSESSID][MESSAGEID] = $tMessageId;
        }
        else {
            $tMessage = sprintf(_("Invalid messageid %s requested!"), $tId);
            $tMessageType = DANGER;
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
        $tMessageType = DANGER;
    }
    
    $header = USERS;
    $subheader = USERS;
    $menu = USERS;
    $template = "workOnMessage.tpl";
    
    include ("$installBase/templates/framework.tpl");
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
    
    if ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: list_messages.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        $error = 0;
        $tValidFromError = 'ok';
        $tValidUntilError = 'ok';
        $tUserMessageError = 'ok';
        $schema = db_determine_schema();
        $tValidFrom = isset($_POST['fValidFrom']) ? db_escape_string($_POST['fValidFrom']) : "";
        $tValidUntil = isset($_POST['fValidUntil']) ? db_escape_string($_POST['fValidUntil']) : "";
        $tUserMessage = strip_tags((isset($_POST['fUserMessage']) ? db_escape_string($_POST['fUserMessage']) : ""), '<br>');
        
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
        
        if (empty($tUserMessage)) {
            $tMessage = _("Please fill in a message!");
            $tMessageType = DANGER;
            $tUserMessageError = ERROR;
            $error = 1;
        }
        
        if ($_SESSION[SVNSESSID]['task'] == "new") {
            
            if ($error == 0) {
                
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "messages (validfrom, validuntil, message, created, created_user) VALUES('$validFrom', '$validUntil', '$tUserMessage', '$dbnow', '" . $_SESSION[SVNSESSID][USERNAME] . "');";
                db_ta('BEGIN', $dbh);
                db_log($_SESSION[SVNSESSID][USERNAME], "added message", $dbh);
                $result = db_query($query, $dbh);
                if ($result['rows'] == 1) {
                    
                    $tMessage = _("Message successfully saved");
                    $tMessageType = SUCCESS;
                    $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                    $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    header("Location: list_messages.php");
                    exit();
                }
                else {
                    
                    $error = 1;
                    $tMessage = _("Error during database insert of message data");
                    $tMessageType = 'error';
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        elseif ($_SESSION[SVNSESSID]['task'] == "change") {
            
            if ($error == 0) {
                
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "messages SET validfrom = '$validFrom', validuntil = '$validUntil', message = '$tUserMessage', modified = '$dbnow', modified_user = '" . $_SESSION[SVNSESSID][USERNAME] . "' WHERE ( id = " . $_SESSION[SVNSESSID][MESSAGEID] . ");";
                db_ta('BEGIN', $dbh);
                db_log($_SESSION[SVNSESSID][USERNAME], "updated message with id " . $_SESSION[SVNSESSID][MESSAGEID], $dbh);
                
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    db_ta('COMMIT', $dbh);
                    
                    $tMessage = _("Message successfully modified");
                    $tMessageType = SUCCESS;
                    $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                    $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;
                    db_disconnect($dbh);
                    header("Location: list_messages.php");
                    exit();
                }
                else {
                    
                    $tMessage = sprintf(_("Message not modified due to database error (%s)"), $result['rows']);
                    $tMessageType = DANGER;
                    $error = 1;
                    db_ta('ROLLBACK', $dbh);
                }
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
    
    $header = USERS;
    $subheader = USERS;
    $menu = USERS;
    $template = "workOnMessage.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>