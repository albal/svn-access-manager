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
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Access rights admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "deleteaccessright";

if (($rightAllowed != "delete") && ($_SESSION[SVNSESSID]['admin'] != "p")) {
    
    db_log($SESSID_USERNAME, "tried to use deleteAccessRight without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if (isset($_GET['task'])) {
        
        $_SESSION[SVNSESSID]['task'] = db_escape_string(strtolower($_GET['task']));
    }
    else {
        
        $_SESSION[SVNSESSID]['task'] = "";
    }
    
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    $schema = db_determine_schema();
    
    $_SESSION[SVNSESSID][RIGHTID] = $tId;
    
    if ($_SESSION[SVNSESSID]['task'] == "delete") {
        
        $query = "SELECT * " . "  FROM " . $schema . "svn_access_rights " . " WHERE id = $tId";
        $result = db_query($query, $dbh);
        
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result[RESULT]);
            $projectid = $row['project_id'];
            $userid = $row[USER_ID];
            $groupid = $row[GROUP_ID];
            $tPathSelected = $row['path'];
            $validfrom = $row['valid_from'];
            $validuntil = $row['valid_until'];
            $tAccessRight = $row['access_right'];
            $lang = strtolower(check_language());
            
            if ($lang == "de") {
                
                $tValidFrom = substr($validfrom, 6, 2) . "." . substr($validfrom, 4, 2) . "." . substr($validfrom, 0, 4);
                $tValidUntil = substr($validuntil, 6, 2) . "." . substr($validuntil, 4, 2) . "." . substr($validuntil, 0, 4);
            }
            else {
                
                $tValidFrom = substr($validfrom, 4, 2) . "/" . substr($validfrom, 6, 2) . "/" . substr($validfrom, 0, 4);
                $tValidUntil = substr($validuntil, 4, 2) . "/" . substr($validuntil, 6, 2) . "/" . substr($validuntil, 0, 4);
            }
            
            $query = "SELECT * " . "  FROM " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = $projectid) " . "   AND (repo_id = svnrepos.id)";
            $result = db_query($query, $dbh);
            if ($result['rows'] == 1) {
                
                $row = db_assoc($result[RESULT]);
                $tProjectName = $row['svnmodule'];
                $tModulePath = $row['modulepath'];
                
                if ($userid != "0") {
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE id = $userid";
                    $result = db_query($query, $dbh);
                    
                    if ($result['rows'] == 1) {
                        
                        $row = db_assoc($result[RESULT]);
                        $name = $row['name'];
                        $givenname = $row['givenname'];
                        if ($givenname != "") {
                            $name = $givenname . " " . $name;
                        }
                        $tUsers = $name . " (" . $row['userid'] . ")";
                    }
                    else {
                        
                        $tMessage = _("Invalid user id $id requested!");
                    }
                }
                else {
                    
                    $tUsers = _("none");
                }
                
                if ($groupid != "0") {
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE id = $groupid";
                    $result = db_query($query, $dbh);
                    
                    if ($result['rows'] == 1) {
                        
                        $row = db_assoc($result[RESULT]);
                        $tGroups = $row['groupname'];
                    }
                    else {
                        
                        $tMessage = _("Invalid group id $groupid requested!");
                    }
                }
                else {
                    
                    $tGroups = _("none");
                }
            }
            else {
                
                $tMessage = _("Invalid project id $projectid requested!");
            }
        }
        else {
            
            $tMessage = _("Invalid access right id $tId requested!");
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "deleteAccessRight.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_ok_x'])) || (isset($_POST['fSubmit_ok']))) {
        $button = _("Delete");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    else {
        $button = "undef";
    }
    
    $schema = db_determine_schema();
    
    if ($button == _("Delete")) {
        
        $rightdata = db_getRightdata($_SESSION[SVNSESSID][RIGHTID], $dbh);
        if ($rightdata[USER_ID] != 0) {
            $username = db_getUseridById($rightdata[USER_ID], $dbh);
        }
        else {
            $username = "";
        }
        
        if ($rightdata[GROUP_ID] != 0) {
            $groupname = db_getGroupById($rightdata[GROUP_ID], $dbh);
        }
        
        $projectname = db_getProjectbyId($rightdata['project_id'], $dbh);
        $reponame = db_getRepoById($rightdata['repo_id'], $dbh);
        $path = $rightdata['path'];
        $accessright = $rightdata['access_right'];
        
        db_ta('BEGIN', $dbh);
        db_log($_SESSION[SVNSESSID]['username'], "deleted access right $accessright for repository $reponame, path $path, project $projectname", $dbh);
        $dbnow = db_now();
        $query = "UPDATE " . $schema . "svn_access_rights " . "   SET deleted = '$dbnow', " . "       deleted_user = '" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE id = " . $_SESSION[SVNSESSID][RIGHTID];
        $result = db_query($query, $dbh);
        
        if ($result['rows'] == 1) {
            
            db_ta('COMMIT', $dbh);
            db_disconnect($dbh);
            header("location: list_access_rights.php");
            exit();
        }
        else {
            
            db_ta('ROLLBACK', $dbh);
            
            $tMessage = sprintf(_("Error while updating right id %s for delete"), $_SESSION[SVNSESSID][RIGHTID]);
        }
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("location: list_access_rights.php");
        exit();
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    $header = ACCESS;
    $subheader = ACCESS;
    $menu = ACCESS;
    $template = "deleteAccessRight.tpl";
    
    include ("$installBase/templates/framework.tpl");
}
?>
