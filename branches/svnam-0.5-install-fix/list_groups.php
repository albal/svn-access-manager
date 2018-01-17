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
 * File: workOnGroupAccessRight.php
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
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Group admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "listgroups";
$groupAdmin = 0;
$tGroupsAllowed = array();

if ($rightAllowed == "none") {
    
    $tGroupsAllowed = db_check_group_acl($_SESSION[SVNSESSID]['username'], $dbh);
    if (count($tGroupsAllowed) == 0) {
        db_log($SESSID_USERNAME, "tried to use list_groups without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    else {
        $groupAdmin = 1;
    }
}
else {
    $groupAdmin = 2;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $_SESSION[SVNSESSID]['groupcounter'] = 0;
    $tGroups = db_getGroupsAllowed(0, - 1, $groupAdmin, $tGroupsAllowed, $dbh);
    $tCountRecords = db_getCountGroupsAllowed($groupAdmin, $tGroupsAllowed, $dbh);
    $tPrevDisabled = "disabled";
    
    if ($tCountRecords <= $CONF['page_size']) {
        
        $tNextDisabled = "disabled";
    }
    
    $template = "list_groups.tpl";
    $header = GROUPS;
    $subheader = GROUPS;
    $menu = GROUPS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_new_x'])) || (isset($_POST['fSubmit_new']))) {
        $button = _("New group");
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
    
    if (($button == "search") or ($tSearch != "")) {
        
        $tSearch = html_entity_decode($tSearch);
        $_SESSION[SVNSESSID]['search'] = $tSearch;
        $_SESSION[SVNSESSID]['searchtype'] = GROUPS;
        $tGroups = array();
        
        if ($tSearch == "") {
            
            $tErrorClass = "error";
            $tMessage = _("No search string given!");
        }
        else {
            
            $tArray = array();
            $schema = db_determine_schema();
            $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE ((groupname like '%$tSearch%') " . "    OR (description like '%$tSearch%')) " . "   AND (deleted = '00000000000000') " . "ORDER BY groupname ASC";
            $result = db_query($query, $dbh);
            while ( $row = db_assoc($result['result']) ) {
                
                $tArray[] = $row;
            }
            
            if (count($tArray) == 0) {
                
                $tErrorClass = "info";
                $tMessage = _("No group found!");
            }
            elseif (count($tArray) == 1) {
                
                $id = $tArray[0]['id'];
                $url = "workOnGroup.php?id=" . urlencode($id) . "&task=change";
                db_disconnect($dbh);
                header("Location: $url");
                exit();
            }
            else {
                
                db_disconnect($dbh);
                $_SESSION[SVNSESSID]['searchresult'] = $tArray;
                header("Location: searchresult.php");
                exit();
            }
        }
    }
    elseif ($button == _("New group")) {
        
        db_disconnect($dbh);
        header("Location: workOnGroup.php?task=new");
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
    
    $template = "list_groups.tpl";
    $header = GROUPS;
    $subheader = GROUPS;
    $menu = GROUPS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}
?>
