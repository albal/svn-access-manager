<?php

/**
 * list users
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
 * list view of all users
 */
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

/**
 * get users
 * 
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
function getUsers($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tUsers = array();
    $query = " SELECT * " . "   FROM " . $schema . "svnusers " . "   WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        if ((isset($CONF['use_ldap'])) && (strtoupper($CONF['use_ldap']) == "YES")) {
            $row['ldap'] = ldap_check_user_exists($row['userid']);
        }
        
        $tUsers[] = $row;
    }
    
    return $tUsers;
    
}

/**
 * get count of users
 * 
 * @param resource $dbh
 * @return integer|boolean
 */
function getCountUsers($dbh) {

    $schema = db_determine_schema();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "svnusers " . "   WHERE (deleted = '00000000000000') ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];
$rightAllowed = db_check_acl($SESSID_USERNAME, 'User admin', $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "listusers";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use list_users without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $_SESSION[SVNSESSID]['usercounter'] = 0;
    $tUsers = getUsers(0, - 1, $dbh);
    $tCountRecords = getCountUsers($dbh);
    
    $template = "list_users.tpl";
    $header = USERS;
    $subheader = USERS;
    $menu = USERS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_new_x'])) || (isset($_POST['fSubmit_new']))) {
        $button = _("New user");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    elseif ((isset($_POST['fSearchBtn'])) || (isset($_POST['fSearchBtn_x']))) {
        $button = _("search");
    }
    else {
        $button = "undef";
    }
    
    $tSearch = isset($_POST['fSearch']) ? db_escape_string($_POST['fSearch']) : "";
    
    if (($button == "search") || ($tSearch != "")) {
        
        $tSearch = html_entity_decode($tSearch);
        $_SESSION[SVNSESSID]['search'] = $tSearch;
        $_SESSION[SVNSESSID]['searchtype'] = USERS;
        $resulrt = db_get_list('users', $tSearch, $dbh);
        $tErrorClass = $result['errorclass'];
        $tMessage = $result['message'];
        $tUsers = $result[RESULT];
    }
    elseif ($button == _("New user")) {
        
        db_disconnect($dbh);
        header("Location: workOnUser.php?task=new");
        exit();
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: main.php");
        exit();
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    $template = "list_users.tpl";
    $header = USERS;
    $subheader = USERS;
    $menu = USERS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}
?>
