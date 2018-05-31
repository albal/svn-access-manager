<?php

/**
 * Report user
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
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];
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
    $tUsers = db_getUsers(0, - 1, $dbh);
    $tUserError = '';
    
    $template = "rep_show_user.tpl";
    $header = REPORTS;
    $subheader = REPORTS;
    $menu = REPORTS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    $tUserError = '';
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_show_x'])) || (isset($_POST['fSubmit_show']))) {
        $button = _("Create report");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    else {
        $button = "undef";
    }
    
    if ($button == _("Create report")) {
        
        $tUserId = isset($_POST['fUser']) ? db_escape_string($_POST['fUser']) : "";
        $_SESSION[SVNSESSID]['user'] = $tUserId;
        
        if (($tUserId == "default") || empty($tUserId)) {
            
            $tMessage = _("No user selected!");
            $tMessageType = DANGER;
            $tUserError = 'error';
            $lang = check_language();
            $tUsers = db_getUsers(0, - 1, $dbh);
            $template = "rep_show_user.tpl";
            $header = REPORTS;
            $subheader = REPORTS;
            $menu = REPORTS;
            
            include ("$installBase/templates/framework.tpl");
            
            db_disconnect($dbh);
            
            exit();
        }
        else {
            
            $tUserError = '';
            $tUser = db_getUseridById($tUserId, $dbh);
            $tUserData = db_getUserData($tUserId, $dbh);
            $tUsername = $tUserData['userid'];
            $tAdministrator = $tUserData['admin'] == "y" ? _("Yes") : _("No");
            $tName = $tUserData['name'];
            $tGivenname = $tUserData['givenname'];
            $tEmailAddress = $tUserData['emailaddress'];
            $tLocked = $tUserData['locked'] == 0 ? _("No") : _("Yes");
            $tPasswordExpires = $tUserData['passwordexpires'] == 1 ? _("Yes") : _("No");
            $tAccessRight = $tUserData['user_mode'];
            $tPasswordModified = implode(" ", splitDateTimeI18n($tUserData['password_modified']));
            $lang = check_language();
            $tGroups = db_getGroupsForUser($tUserId, $dbh);
            $tAccessRights = db_getAccessRightsForUser($tUserId, $tGroups, $dbh);
            $tProjects = db_getProjectResponsibleForUser($tUserId, $dbh);
        }
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: main.php");
        exit();
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
        $tMessageType = DANGER;
        $tUserError = '';
    }
    
    $template = "rep_show_user_result.tpl";
    $header = REPORTS;
    $subheader = REPORTS;
    $menu = REPORTS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

?>
