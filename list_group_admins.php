<?php

/*
 * SVN Access Manager - a subversion access rights management tool
 * Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
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
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Group admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "listgroupadmins";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use list_group_admins without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $_SESSION[SVNSESSID]['groupcounter'] = 0;
    $tGroups = db_getGroups(0, - 1, $dbh);
    $tCountRecords = db_getCountGroups($dbh);
    $tPrevDisabled = "disabled";
    
    if ($tCountRecords <= $CONF[PAGESIZE]) {
        
        $tNextDisabled = "disabled";
    }
    
    $template = "list_group_admins.tpl";
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
    
    $schema = db_determine_schema();
    
    $tSearch = isset($_POST['fSearch']) ? db_escape_string($_POST['fSearch']) : "";
    
    if (($button == "search") || ($tSearch != "")) {
        
        $tSearch = html_entity_decode($tSearch);
        $_SESSION[SVNSESSID]['search'] = $tSearch;
        $_SESSION[SVNSESSID]['searchtype'] = "groupadmin";
        $tGroups = array();
        
        if ($tSearch == "") {
            
            $tErrorClass = "error";
            $tMessage = _("No search string given!");
        }
        else {
            
            $tArray = array();
            $schema = db_determine_schema();
            $tParts = explode(" ", $tSearch);
            if (count($tParts) == 1) {
                
                $query = "SELECT * " . "  FROM " . $schema . "svn_groups_responsible," . $schema . "svnusers, " . $schema . "svngroups " . " WHERE (svn_groups_responsible.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000') " . "   AND (svn_groups_responsible.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND ((svnusers.name like '%$tSearch%') " . "    OR  (svnusers.givenname like '%$tSearch%') " . "    OR  (svnusers.userid like '%$tSearch%') " . "    OR  (svngroups.groupname like '%$tSearch%') " . "    OR  (svngroups.description like '%$tSearch%')) " . "ORDER BY svnusers.name ASC, svnusers.givenname ASC";
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svn_groups_responsible," . $schema . "svnusers, " . $schema . "svngroups " . " WHERE (svn_groups_responsible.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000') " . "   AND (svn_groups_responsible.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (";
                
                $i = 0;
                foreach( $tParts as $entry) {
                    
                    if ($i == 0) {
                        
                        $query = $query . "       (svnusers.name like '%$entry%') ";
                        $i ++;
                    }
                    else {
                        
                        $query = $query . "    OR (svnusers.name like '%$entry%') ";
                    }
                    
                    $query = $query . "    OR  (svnusers.givenname like '%$entry%') ";
                }
                
                $query = $query . "       ) " . "ORDER BY svnusers.name ASC, svnusers.givenname ASC";
            }

            $result = db_query($query, $dbh);
            while ( $row = db_assoc($result['result']) ) {
                
                $tArray[] = $row;
            }
            
            if (count($tArray) == 0) {
                
                $tErrorClass = "info";
                $tMessage = _("No group administrator found!");
                $tGroups = array();
            }
            elseif (count($tArray) == 1) {
                
                $id = $tArray[0]['id'];
                $url = "workOnGroupAccessRight.php?id=" . urlencode($id) . "&task=change";
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
        header("Location: selectGroup.php");
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
    
    $template = "list_group_admins.tpl";
    $header = GROUPS;
    $subheader = GROUPS;
    $menu = GROUPS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}
?>
