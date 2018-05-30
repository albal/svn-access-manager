<?php

/**
 * main menu
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
require_once ("$installBase/include/db-functions-adodb.inc.php");
require_once ("$installBase/include/functions.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$_SESSION[SVNSESSID]['helptopic'] = "main";

$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tUsername = isset($_SESSION[SVNSESSID][USERNAME]) ? $_SESSION[SVNSESSID][USERNAME] : 'undefined';
    $tAdmin = isset($_SESSION[SVNSESSID][ADMIN]) ? $_SESSION[SVNSESSID][ADMIN] : 'n';
    $rightUserAdmin = db_check_acl($tUsername, 'User admin', $dbh);
    $rightGroupAdmin = db_check_acl($tUsername, 'Group admin', $dbh);
    $rightProjectAdmin = db_check_acl($tUsername, 'Project admin', $dbh);
    $rightRepositoryAdmin = db_check_acl($tUsername, 'Repository admin', $dbh);
    $rightAccessRightAdmin = db_check_acl($tUsername, 'Access rights admin', $dbh);
    $rightCreateFiles = db_check_acl($tUsername, 'Create files', $dbh);
    $rightReports = db_check_acl($tUsername, 'Reports', $dbh);
    $tGroupsAllowed = db_check_group_acl($tUsername, $dbh);
    $tUserMessages = db_getMessagesShort($dbh);
    $tStats = db_getStatistics($dbh);
    
    $template = "main.tpl";
    $header = "main";
    $subheader = "main";
    $menu = "main";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $tUsername = isset($_SESSION[SVNSESSID][USERNAME]) ? $_SESSION[SVNSESSID][USERNAME] : 'undefined';
    $tAdmin = isset($_SESSION[SVNSESSID][ADMIN]) ? $_SESSION[SVNSESSID][ADMIN] : 'n';
    $rightUserAdmin = db_check_acl($tUsername, 'User admin', $dbh);
    $rightGroupAdmin = db_check_acl($tUsername, 'Group admin', $dbh);
    $rightProjectAdmin = db_check_acl($tUsername, 'Project admin', $dbh);
    $rightRepositoryAdmin = db_check_acl($tUsername, 'Repository admin', $dbh);
    $rightAccessRightAdmin = db_check_acl($tUsername, 'Access rights admin', $dbh);
    $rightCreateFiles = db_check_acl($tUsername, 'Create files', $dbh);
    $rightReports = db_check_acl($tUsername, 'Reports', $dbh);
    $tGroupsAllowed = db_check_group_acl($tUsername, $dbh);
    $tUserMessages = db_getMessagesShort($dbh);
    $tStats = db_getStatistics($dbh);
    
    $template = "main.tpl";
    $header = "main";
    $subheader = "main";
    $menu = "main";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);

?>
