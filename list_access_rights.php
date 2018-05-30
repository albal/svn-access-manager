<?php

/**
 * list all acess rights
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
$rightAllowed = db_check_acl($SESSID_USERNAME, "Access rights admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "listaccessrights";

if ($rightAllowed == "none") {
    
    if ($_SESSION[SVNSESSID]['admin'] == "p") {
        
        $tSeeUserid = $SESSID_USERNAME;
    }
    else {
        
        db_log($SESSID_USERNAME, "tried to use list_access_rights without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
}
else {
    
    $tSeeUserid = - 1;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tAccessRights = db_getAccessRights($tSeeUserid, 0, - 1, $dbh);
    $tSearchUser = "";
    $tSearchGroup = "";
    $tSearchProject = "";
    $_SESSION[SVNSESSID]['rightcounter'] = 0;
    $tCountRecords = db_getCountAccessRights($tSeeUserid, $dbh);
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "list_access_rights.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    // fSearchBtn and fSearchBtn_x handled in else branch as button gets the same value
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_new_x'])) || (isset($_POST['fSubmit_new']))) {
        $button = _("New access right");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    elseif ((isset($_POST['fSubmit_delete'])) || (isset($_POST['fSubmit_delete_x']))) {
        $button = _("Delete selected");
    }
    elseif ((isset($_POST['fSubmit_clear'])) || (isset($_POST['fSubmit_clear_x']))) {
        $button = _("Clear filter");
    }
    else {
        $button = "getfilter";
    }
    
    $schema = db_determine_schema();
    
    $tSearch = isset($_POST['fSearch']) ? db_escape_string($_POST['fSearch']) : "";
    $tSearchProject = isset($_POST['fSearchProject']) ? db_escape_string($_POST['fSearchProject']) : "";
    $tSearchUser = isset($_POST['fSearchUser']) ? db_escape_string($_POST['fSearchUser']) : "";
    $tSearchGroup = isset($_POST['fSearchGroup']) ? db_escape_string($_POST['fSearchGroup']) : "";
    $tCntl = isset($_POST['fCntl']) ? db_escape_string($_POST['fCntl']) : "";
    
    if ($button == _("Back") && ($tCntl != "filter")) {
        
        db_disconnect($dbh);
        header("Location: main.php");
        exit();
    }
    elseif ($button == _("Clear filter")) {
        
        $tSerarchUser = "";
        $tSearchjProject = "";
        $tSearchGroup = "";
        $tAccessRights = db_getAccessRights($tSeeUserid, 0, - 1, $dbh);
    }
    elseif ($button == "getfilter") {
        
        $tAccessRights = db_getAccessRights($tSeeUserid, 0, - 1, $dbh);
    }
    elseif ($button == _("New access right") && ($tCntl != "filter")) {
        
        db_disconnect($dbh);
        header("Location: selectProject.php");
        exit();
    }
    elseif ($button == _("Delete selected") && ($tCntl != "filter")) {
        
        $max = $_SESSION[SVNSESSID]['max_mark'];
        $error = 0;
        
        db_ta('BEGIN', $dbh);
        
        for($i = 0; $i <= $max; $i ++) {
            
            $field = "fDelete" . $i;
            
            if (isset($_POST[$field])) {
                
                $id = $_SESSION[SVNSESSID]['mark'][$i];
                $right = db_getRightData($id, $dbh);
                $projectname = db_getProjectById($right['project_id'], $dbh);
                
                if ($right['user_id'] != 0) {
                    
                    $userid = db_getUseridById($right['user_id'], $dbh);
                }
                else {
                    
                    $userid = "";
                }
                
                if ($right['group_id'] != 0) {
                    
                    $groupname = db_getGroupById($right['group_id'], $dbh);
                }
                else {
                    
                    $groupname = "";
                }
                
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svn_access_rights " . "   SET deleted = '$dbnow', " . "       deleted_user = '" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE (id = $id)";
                $result = db_query($query, $dbh);
                if ($result['rows'] != 1) {
                    
                    $tMessage = sprintf(_("Can not delete access right with id %s"), $id);
                    $error = 1;
                }
                
                $logentry = sprintf("deleted access right %s in project %s, path %s", $right['access_right'], $projectname, $right['path']);
                db_log($_SESSION[SVNSESSID]['username'], $logentry, $dbh);
            }
        }
        
        if ($error == 0) {
            
            db_ta('COMMIT', $dbh);
            db_disconnect($dbh);
            header("location: list_access_rights.php");
            exit();
        }
        else {
            
            db_ta('ROLLBACK', $dbh);
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "list_access_rights.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
