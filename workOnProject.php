<?php

/**
 * Work on a project
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
include_once ("$installBase/addMemberToProject.php");
include_once ("$installBase/addGroupToProject.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Project admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "workonproject";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use workOnProject without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

$schema = db_determine_schema();

$query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (deleted = '00000000000000') " . "ORDER BY svnrepos.reponame";
$result = db_query($query, $dbh);
$tRepos = array();

while ( $row = db_assoc($result[RESULT]) ) {
    
    $id = $row['id'];
    $name = $row['reponame'];
    $tRepos[$id] = $name;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tMembers = array();
    $tReadonly = "";
    $tTask = db_escape_string($_GET[TASK]);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if (($rightAllowed == "add") && (($tTask != "new") && ($tTask != "relist"))) {
        
        db_log($SESSID_USERNAME, "tried to use workOnProject without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    if (strtolower($tTask) != "relist") {
        
        $_SESSION[SVNSESSID][TASK] = strtolower($tTask);
    }
    
    $tProjectError = '';
    $tModulePathError = '';
    $tResponsibleError = '';
    $tDescriptionError = '';
    $tMembersError = '';
    
    $_SESSION[SVNSESSID][PROJECTID] = $tId;
    
    if ($tTask == "relist") {
        
        $tProject = $_SESSION[SVNSESSID][PROJECT];
        $tModulepath = $_SESSION[SVNSESSID][MODULEPATH];
        $tRepo = $_SESSION[SVNSESSID]['repo'];
        $tDescription = $_SESSION[SVNSESSID][DESCRIPTION];
        $tMembers = $_SESSION[SVNSESSID][MEMBERS];
        $tGroups = $_SESSION[SVNSESSID][GROUPS];
    }
    elseif ($_SESSION[SVNSESSID][TASK] == "new") {
        
        $tProject = "";
        $tModulepath = "";
        $tRepo = "";
        $tDescription = "";
        $tMembers = array();
        $_SESSION[SVNSESSID][MEMBERS] = array();
        $_SESSION[SVNSESSID][GROUPS] = array();
        $_SESSION[SVNSESSID][PROJECT] = "";
        $_SESSION[SVNSESSID][MODULEPATH] = "";
        $_SESSION[SVNSESSID]['repo'] = "";
        $_SESSION[SVNSESSID][DESCRIPTION] = "";
    }
    elseif ($_SESSION[SVNSESSID][TASK] == "change") {
        
        $_SESSION[SVNSESSID][PROJECTID] = $tId;
        $tReadonly = "readonly";
        $query = "SELECT * " . "  FROM " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = $tId) " . "   AND (svnrepos.id = svnprojects.repo_id)";
        $result = db_query($query, $dbh);
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result[RESULT]);
            $tProject = $row['svnmodule'];
            $tModulepath = $row[MODULEPATH];
            $tRepo = $row['repo_id'];
            $tDescription = $row[DESCRIPTION];
            
            $query = "SELECT svnusers.userid, svnusers.name, svnusers.givenname " . "  FROM " . $schema . "svnusers, " . $schema . "svn_projects_responsible " . " WHERE (svn_projects_responsible.project_id = $tId) " . "   AND (svn_projects_responsible.deleted = '00000000000000') " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_projects_responsible.user_id = svnusers.id) " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
            $result = db_query($query, $dbh);
            
            while ( $row = db_assoc($result[RESULT]) ) {
                
                $userid = $row['userid'];
                $name = $row['name'];
                $givenname = $row['givenname'];
                
                if ($givenname != "") {
                    
                    $name = $givenname . " " . $name;
                }
                
                $tMembers[$userid] = $name;
            }
            
            $_SESSION[SVNSESSID][MEMBERS] = $tMembers;
            $_SESSION[SVNSESSID][PROJECT] = $tProject;
            $_SESSION[SVNSESSID][MODULEPATH] = $tModulepath;
            $_SESSION[SVNSESSID]['repo'] = $tRepo;
            $_SESSION[SVNSESSID][DESCRIPTION] = $tDescription;
            $_SESSION[SVNSESSID][GROUPS] = array();
        }
        else {
            
            $tMessage = _("Invalid projectid $id requested!");
            $tMessageType = DANGER;
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID][TASK]);
        $tMessageType = DANGER;
    }
    
    $header = PROJECTS;
    $subheader = PROJECTS;
    $menu = PROJECTS;
    $template = "workOnProject.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $button = "";
    $buttonadd = "";
    
    $tProjectError = 'ok';
    $tModulePathError = 'ok';
    $tResponsibleError = 'ok';
    $tDescriptionError = 'ok';
    $tMembersError = 'ok';
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_ok_x'])) || (isset($_POST['fSubmit_ok']))) {
        $button = _("Submit");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    elseif ((isset($_POST['fSubmit_add_x'])) || (isset($_POST['fSubmit_add']))) {
        $button = _("Add responsible");
    }
    elseif ((isset($_POST['fSubmit_remove_x'])) || (isset($_POST['fSubmit_remove']))) {
        $button = _("Remove responsible");
    }
    elseif ((isset($_POST['fSubmit_add_group'])) || (isset($_POST['fSubmit_add_group_x']))) {
        $button = _("Add group");
    }
    elseif ((isset($_POST['fSubmit_remove_group'])) || (isset($_POST['fSubmit_remove_group_x']))) {
        $button = _("Remove group");
    }
    else {
        $button = "undef";
    }
    
    if (isset($_POST['fSubmitAdd'])) {
        $buttonadd = db_escape_string($_POST['fSubmitAdd']);
    }
    elseif ((isset($_POST['fSubmitAdd_ok_x'])) || (isset($_POST['fSubmitAdd_ok']))) {
        $buttonadd = _("Add");
    }
    elseif ((isset($_POST['fSubmitAdd_back_x'])) || (isset($_POST['fSubmitAdd_back']))) {
        $buttonadd = _("Cancel");
    }
    else {
        $buttonadd = "undef";
    }
    
    if (isset($_POST['fSubmitAddGroup'])) {
        $buttonaddgroup = db_escape_string($_POST['fSubmitAdd']);
    }
    elseif ((isset($_POST['fSubmitAddGroup_ok_x'])) || (isset($_POST['fSubmitAddGroup_ok']))) {
        $buttonaddgroup = _("Add");
    }
    elseif ((isset($_POST['fSubmitAddGroup_back_x'])) || (isset($_POST['fSubmitAddGroup_back']))) {
        $buttonaddgroup = _("Cancel");
    }
    else {
        $buttonaddgroup = "undef";
    }
    
    if (isset($_POST['fProject'])) {
        
        $tProject = db_escape_string($_POST['fProject']);
    }
    else {
        
        $tProject = "";
    }
    
    if (isset($_POST['fModulepath'])) {
        
        $tModulepath = db_escape_string($_POST['fModulepath']);
    }
    else {
        
        $tModulepath = "";
    }
    
    if (isset($_POST['fRepo'])) {
        
        $tRepo = db_escape_string($_POST['fRepo']);
    }
    else {
        
        $tRepo = "";
    }
    
    if (isset($_POST['fDescription'])) {
        
        $tDescription = db_escape_string($_POST['fDescription']);
    }
    else {
        
        $tDescription = "";
    }
    
    if (isset($_POST[MEMBERS])) {
        
        $tMembers = db_escape_string($_POST[MEMBERS]);
    }
    else {
        
        $tMembers = array();
    }
    
    if (isset($_POST['groupsallowed'])) {
        
        $tGroups = db_escape_string($_POST['groupsallowed']);
    }
    else {
        
        $tGroups = array();
    }
    
    if ($button == _("Add responsible")) {
        
        $_SESSION[SVNSESSID][PROJECT] = $tProject;
        $_SESSION[SVNSESSID][MODULEPATH] = $tModulepath;
        $_SESSION[SVNSESSID]['repo'] = $tRepo;
        $_SESSION[SVNSESSID][DESCRIPTION] = $tDescription;
        $_SESSION[SVNSESSID][GROUPS] = $tGroups;
        
        addMemberToGroup($_SESSION[SVNSESSID][MEMBERS], $dbh);
        
        db_disconnect($dbh);
        exit();
    }
    elseif ($button == _("Remove responsible")) {
        
        if (count($tMembers) > 0) {
            
            $new = array();
            $old = $_SESSION[SVNSESSID][MEMBERS];
            
            foreach( $old as $userid => $name ) {
                
                if (! in_array($userid, $tMembers)) {
                    
                    $new[$userid] = $name;
                }
            }
            
            $_SESSION[SVNSESSID][MEMBERS] = $new;
            $tMembers = $new;
        }
        else {
            
            $tMembers = $_SESSION[SVNSESSID][MEMBERS];
        }
        
        $tProjectError = '';
        $tModulePathError = '';
        $tResponsibleError = '';
        $tDescriptionError = '';
        $tMembersError = '';
    }
    elseif ($button == _("Add group")) {
        
        $_SESSION[SVNSESSID][PROJECT] = $tProject;
        $_SESSION[SVNSESSID][MODULEPATH] = $tModulepath;
        $_SESSION[SVNSESSID]['repo'] = $tRepo;
        $_SESSION[SVNSESSID][DESCRIPTION] = $tDescription;
        
        addGroupToProject($_SESSION[SVNSESSID][GROUPS], $dbh);
        
        db_disconnect($dbh);
        exit();
    }
    elseif ($button == _("Remove group")) {
        
        if (count($tGroups) > 0) {
            $new = array();
            $old = $_SESSION[SVNSESSID][GROUPS];
            
            foreach( $old as $groupid => $name ) {
                
                if (! in_array($groupid, $tGroups)) {
                    
                    $new[$groupid] = $name;
                }
            }
            
            $_SESSION[SVNSESSID][GROUPS] = $new;
            $tGroups = $new;
        }
        else {
            
            $tGroups = $_SESSION[SVNSESSID][GROUPS];
        }
        
        $tProjectError = '';
        $tModulePathError = '';
        $tResponsibleError = '';
        $tDescriptionError = '';
        $tMembersError = '';
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: list_projects.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        if ($_SESSION[SVNSESSID][TASK] == "new") {
            
            $error = 0;
            
            if ($tProject == "") {
                
                $tMessage = _("Subversion project is missing, please fill in!");
                $tProjectError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif ($tDescription == "") {
                
                $tMessage = _("Project description is missing, please fill in!");
                $tDescriptionError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif ($tModulepath == "") {
                
                $tMessage = _("Subversion module path missing, please fill in!");
                $tModulePathError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif (empty($_SESSION[SVNSESSID][MEMBERS])) {
                
                $tMessage = _("Project responsible user missing, please fill in!");
                $tResponsibleError = ERROR;
                $tMessageType = DANGER;
                $tMembersError = ERROR;
                $error = 1;
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE (svnmodule = '$tProject') " . "   AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] > 0) {
                    
                    $tMessage = _("The project with the name $tProject exists already");
                    $tProjectError = ERROR;
                    $tMessageType = DANGER;
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION[SVNSESSID][USERNAME], "project $tProject ($tModulepath) added", $dbh);
                
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "svnprojects (svnmodule, modulepath, description, repo_id, created, created_user) " . "     VALUES ('$tProject', '$tModulepath', '$tDescription', '$tRepo', '$dbnow', '" . $_SESSION[SVNSESSID][USERNAME] . "')";
                
                $result = db_query($query, $dbh);
                if ($result['rows'] != 1) {
                    
                    $tMessaage = _("Error during database insert");
                    $tMessageType = DANGER;
                    $error = 1;
                }
                else {
                    
                    $projectid = db_get_last_insert_id('svnprojects', 'id', $dbh);
                    
                    foreach( $_SESSION[SVNSESSID][MEMBERS] as $userid => $name ) {
                        
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')";
                        $result = db_query($query, $dbh);
                        
                        if ($result['rows'] == 1) {
                            
                            db_log($_SESSION[SVNSESSID][USERNAME], "added project responsible $userid", $dbh);
                            
                            $row = db_assoc($result[RESULT]);
                            $id = $row['id'];
                            $dbnow = db_now();
                            $query = "INSERT INTO " . $schema . "svn_projects_responsible (user_id, project_id, created, created_user) " . "     VALUES ($id, $projectid, '$dbnow', '" . $_SESSION[SVNSESSID][USERNAME] . "')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Insert of user project relation failed for user_id %s and project_id %s"), $id, $projectid);
                                $tMessageType = DANGER;
                                $error = 1;
                            }
                        }
                        else {
                            
                            $tMessage = sprintf(_("User %s not found!"), $userid);
                            $tMessageType = DANGER;
                            $error = 1;
                        }
                    }
                }
                
                if ($error == 0) {
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                    $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;
                    header("Location: list_projects.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        elseif ($_SESSION[SVNSESSID][TASK] == "change") {
            
            $error = 0;
            $tReadonly = "readonly";
            $projectid = $_SESSION[SVNSESSID][PROJECTID];
            
            if ($tProject == "") {
                
                $tMessage = _("Subversion project name is missing, please fill in!");
                $tProjectError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif ($tModulepath == "") {
                
                $tMessage = _("Subversion module path missing, please fill in!");
                $tModulePathError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif ($tDescription == "") {
                
                $tMessage = _("Project description is missing, please fill in!");
                $tDescriptionError = ERROR;
                $tMessageType = DANGER;
                $error = 1;
            }
            elseif (empty($_SESSION[SVNSESSID][MEMBERS])) {
                
                $tMessage = _("Project responsible user missing, please fill in!");
                $tResponsibleError = ERROR;
                $tMessageType = DANGER;
                $tMembersError = ERROR;
                $error = 1;
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE (svnmodule = '$tProject') " . "   AND (deleted = '00000000000000') " . "   AND (id != " . $_SESSION[SVNSESSID][PROJECTID] . ")";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] > 0) {
                    
                    $tMessage = sprintf(_("The project with the name %s exists already"), $tProject);
                    $tProjectError = ERROR;
                    $tMessageType = DANGER;
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svnprojects " . "   SET svnmodule = '$tProject', " . "       modulepath = '$tModulepath', " . "       repo_id = $tRepo, " . "       description = '$tDescription', " . "       modified = '$dbnow', " . "       modified_user = '" . $_SESSION[SVNSESSID][USERNAME] . "' " . " WHERE (id = " . $_SESSION[SVNSESSID][PROJECTID] . ")";
                
                db_ta('BEGIN', $dbh);
                
                $project = db_getProjectById($_SESSION[SVNSESSID][PROJECTID], $dbh);
                $repo = db_getRepoById($tRepo, $dbh);
                
                db_log($_SESSION[SVNSESSID][USERNAME], "updated project $tProject ($tModulepath/$repo)", $dbh);
                
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    $tUids = array();
                    
                    foreach( $_SESSION[SVNSESSID][MEMBERS] as $uid => $name ) {
                        
                        $tUids[] = $uid;
                    }
                    
                    $tGroupIds = array();
                    
                    foreach( $_SESSION[SVNSESSID][GROUPS] as $groupid => $groupname ) {
                        
                        $tGroupIds[] = $groupid;
                    }
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (project_id = $projectid) " . "   AND (deleted = '00000000000000')";
                    $result = db_query($query, $dbh);
                    
                    while ( ($row = db_assoc($result[RESULT])) && ($error == 0) ) {
                        
                        $userid = db_getUseridById($row['user_id'], $dbh);
                        $uid = $row['user_id'];
                        $projectid = $row['project_id'];
                        
                        if (! in_array($userid, $tUids)) {
                            
                            db_log($_SESSION[SVNSESSID][USERNAME], "deleted $userid from $tProject as responsible", $dbh);
                            $id = $row['id'];
                            $dbnow = db_now();
                            $query = "UPDATE " . $schema . "svn_projects_responsible " . "SET deleted = '$dbnow', " . "    deleted_user = '" . $_SESSION[SVNSESSID][USERNAME] . "' " . " WHERE id = " . $id;
                            $result_del = db_query($query, $dbh);
                            
                            if ($result_del['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Delete of svn_projects_responsible record with id %s failed"), $id);
                                $tMessageType = DANGER;
                                $error = 1;
                            }
                        }
                    }
                    
                    foreach( $_SESSION[SVNSESSID][MEMBERS] as $userid => $name ) {
                        
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')";
                        $result = db_query($query, $dbh);
                        
                        if ($result['rows'] == 1) {
                            
                            $row = db_assoc($result[RESULT]);
                            $id = $row['id'];
                            
                            $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (user_id = $id) " . "   AND (project_id = $projectid) " . "   AND (deleted = '00000000000000')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] == 0) {
                                
                                db_log($_SESSION[SVNSESSID][USERNAME], " added project responsible $userid to project $tProject", $dbh);
                                $dbnow = db_now();
                                $query = "INSERT INTO " . $schema . "svn_projects_responsible (user_id, project_id, created, created_user) " . "     VALUES ($id, $projectid, '$dbnow', '" . $_SESSION[SVNSESSID][USERNAME] . "')";
                                $result = db_query($query, $dbh);
                                
                                if ($result['rows'] != 1) {
                                    $tMessage = sprintf(_("Insert of user/project relation (%s/%s) failed due to database error"), $id, $projectid);
                                    $error = 1;
                                }
                            }
                        }
                        else {
                            
                            $tMessage = sprintf(_("User %s not found!"), $userid);
                            $tMessageType = DANGER;
                            $error = 1;
                        }
                    }
                }
                else {
                    
                    $tMessage = _("Project not modified due to database error");
                    $tMessageType = DANGER;
                    $error = 1;
                }
                
                if ($error == 0) {
                    
                    $tMessage = _("Project successfully updated.");
                    $tMessageType = SUCCESS;
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                    $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;
                    header("Location: list_projects.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        else {
            
            $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID][TASK]);
            $tMessageType = DANGER;
        }
    }
    elseif ($buttonadd == _("Add")) {
        
        if (isset($_POST['membersadd'])) {
            
            $membersadd = db_escape_string($_POST['membersadd']);
        }
        else {
            
            $membersadd = array();
        }
        
        foreach( $membersadd as $userid ) {
            
            $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000' )";
            $result = db_query($query, $dbh);
            
            if ($result['rows'] == 1) {
                
                $row = db_assoc($result[RESULT]);
                $name = $row['name'];
                $givenname = $row['givenname'];
                
                if ($givenname != "") {
                    
                    $name = $givenname . " " . $name;
                }
            }
            
            $_SESSION[SVNSESSID][MEMBERS][$userid] = $name;
        }
        
        $project = $_SESSION[SVNSESSID][PROJECTID];
        $tProject = $_SESSION[SVNSESSID][PROJECT];
        $tModulepath = $_SESSION[SVNSESSID][MODULEPATH];
        $tRepo = $_SESSION[SVNSESSID]['repo'];
        $tDescription = $_SESSION[SVNSESSID][DESCRIPTION];
        $tMembers = $_SESSION[SVNSESSID][MEMBERS];
        $tProjectError = '';
        $tModulePathError = '';
        $tResponsibleError = '';
        $tDescriptionError = '';
        
        db_disconnect($dbh);
        header("Location: workOnProject.php?id=$project&task=relist");
        exit();
    }
    elseif ($buttonadd == _("Cancel")) {
        
        $project = $_SESSION[SVNSESSID][PROJECTID];
        $task = $_SESSION[SVNSESSID][TASK];
        
        db_disconnect($dbh);
        header("Location: workOnProject.php?id=$project&task=relist");
        exit();
    }
    else {
        
        $tMessage = sprintf(_("Invalid button (%s/%s), anyone tampered arround with?"), $button, $buttonadd);
        $tMessageType = DANGER;
    }
    
    $header = PROJECTS;
    $subheader = PROJECTS;
    $menu = PROJECTS;
    $template = "workOnProject.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
