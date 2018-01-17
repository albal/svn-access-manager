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
if (file_exists(realpath("./config/config.inc.php"))) {
    require ("./config/config.inc.php");
}
elseif (file_exists(realpath("../config/config.inc.php"))) {
    require ("../config/config.inc.php");
}
elseif (file_exists("/etc/svn-access-manager/config.inc.php")) {
    require ("/etc/svn-access-manager/config.inc.php");
}
else {
    die("can't load config.inc.php. Please check your installation!\n");
}

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
$CONF['page_size'] = $preferences['page_size'];
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

while ( $row = db_assoc($result['result']) ) {
    
    $id = $row['id'];
    $name = $row['reponame'];
    $tRepos[$id] = $name;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tMembers = array();
    $tReadonly = "";
    $tTask = db_escape_string($_GET['task']);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if (($rightAllowed == "add") and (($tTask != "new") and ($tTask != "relist"))) {
        
        db_log($SESSID_USERNAME, "tried to use workOnProject without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    if (strtolower($tTask) != "relist") {
        
        $_SESSION[SVNSESSID]['task'] = strtolower($tTask);
    }
    
    $_SESSION[SVNSESSID]['projectid'] = $tId;
    
    if ($tTask == "relist") {
        
        $tProject = $_SESSION[SVNSESSID]['project'];
        $tModulepath = $_SESSION[SVNSESSID]['modulepath'];
        $tRepo = $_SESSION[SVNSESSID]['repo'];
        $tMembers = $_SESSION[SVNSESSID]['members'];
        $tGroups = $_SESSION[SVNSESSID]['groups'];
    }
    elseif ($_SESSION[SVNSESSID]['task'] == "new") {
        
        $tProject = "";
        $tModulepath = "";
        $tRepo = "";
        $tMembers = array();
        $_SESSION[SVNSESSID]['members'] = array();
        $_SESSION[SVNSESSID]['groups'] = array();
        $_SESSION[SVNSESSID]['project'] = "";
        $_SESSION[SVNSESSID]['modulepath'] = "";
        $_SESSION[SVNSESSID]['repo'] = "";
    }
    elseif ($_SESSION[SVNSESSID]['task'] == "change") {
        
        $_SESSION[SVNSESSID]['projectid'] = $tId;
        $tReadonly = "readonly";
        $query = "SELECT * " . "  FROM " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = $tId) " . "   AND (svnrepos.id = svnprojects.repo_id)";
        $result = db_query($query, $dbh);
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result['result']);
            $tProject = $row['svnmodule'];
            $tModulepath = $row['modulepath'];
            $tRepo = $row['repo_id'];
            
            $query = "SELECT svnusers.userid, svnusers.name, svnusers.givenname " . "  FROM " . $schema . "svnusers, " . $schema . "svn_projects_responsible " . " WHERE (svn_projects_responsible.project_id = $tId) " . "   AND (svn_projects_responsible.deleted = '00000000000000') " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_projects_responsible.user_id = svnusers.id) " . "ORDER BY " . $CONF['user_sort_fields'] . " " . $CONF['user_sort_order'];
            $result = db_query($query, $dbh);
            
            while ( $row = db_assoc($result['result']) ) {
                
                $userid = $row['userid'];
                $name = $row['name'];
                $givenname = $row['givenname'];
                
                if ($givenname != "") {
                    
                    $name = $givenname . " " . $name;
                }
                
                $tMembers[$userid] = $name;
            }
            
            $_SESSION[SVNSESSID]['members'] = $tMembers;
            $_SESSION[SVNSESSID]['project'] = $tProject;
            $_SESSION[SVNSESSID]['modulepath'] = $tModulepath;
            $_SESSION[SVNSESSID]['repo'] = $tRepo;
            $_SESSION[SVNSESSID]['groups'] = array();
        }
        else {
            
            $tMessage = _("Invalid projectid $id requested!");
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
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
    
    if (isset($_POST['members'])) {
        
        $tMembers = db_escape_string($_POST['members']);
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
        
        $_SESSION[SVNSESSID]['project'] = $tProject;
        $_SESSION[SVNSESSID]['modulepath'] = $tModulepath;
        $_SESSION[SVNSESSID]['repo'] = $tRepo;
        $_SESSION[SVNSESSID]['groups'] = $tGroups;
        
        addMemberToGroup($tGroups, $_SESSION[SVNSESSID]['members'], $dbh);
        
        db_disconnect($dbh);
        exit();
    }
    elseif ($button == _("Remove responsible")) {
        
        if (count($tMembers) > 0) {
            
            $new = array();
            $old = $_SESSION[SVNSESSID]['members'];
            
            foreach( $old as $userid => $name) {
                
                if (! in_array($userid, $tMembers)) {
                    
                    $new[$userid] = $name;
                }
            }
            
            $_SESSION[SVNSESSID]['members'] = $new;
            $tMembers = $new;
        }
        else {
            
            $tMembers = $_SESSION[SVNSESSID]['members'];
        }
    }
    elseif ($button == _("Add group")) {
        
        $_SESSION[SVNSESSID]['project'] = $tProject;
        $_SESSION[SVNSESSID]['modulepath'] = $tModulepath;
        $_SESSION[SVNSESSID]['repo'] = $tRepo;
        
        addGroupToProject($tGroups, $_SESSION[SVNSESSID]['groups'], $dbh);
        
        db_disconnect($dbh);
        exit();
    }
    elseif ($button == _("Remove group")) {
        
        if (count($tGroups) > 0) {
            $new = array();
            $old = $_SESSION[SVNSESSID]['groups'];
            
            foreach( $old as $groupid => $name) {
                
                if (! in_array($groupid, $tGroups)) {
                    
                    $new[$groupid] = $name;
                }
            }
            
            $_SESSION[SVNSESSID]['groups'] = $new;
            $tGroups = $new;
        }
        else {
            
            $tGroups = $_SESSION[SVNSESSID]['groups'];
        }
    }
    elseif ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: list_projects.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        if ($_SESSION[SVNSESSID]['task'] == "new") {
            
            $error = 0;
            
            if ($tProject == "") {
                
                $tMessage = _("Subversion project is missing, please fill in!");
                $error = 1;
            }
            elseif ($tModulepath == "") {
                
                $tMessage = _("Subversion module path missing, please fill in!");
                $error = 1;
            }
            elseif (empty($_SESSION[SVNSESSID]['members'])) {
                
                $tMessage = _("Project responsible user missing, please fill in!");
                $error = 1;
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE (svnmodule = '$tProject') " . "   AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] > 0) {
                    
                    $tMessage = _("The project with the name $tProject exists already");
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION[SVNSESSID]['username'], "project $tProject ($tModulepath) added", $dbh);
                
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "svnprojects (svnmodule, modulepath, repo_id, created, created_user) " . "     VALUES ('$tProject', '$tModulepath', '$tRepo', '$dbnow', '" . $_SESSION[SVNSESSID]['username'] . "')";
                
                $result = db_query($query, $dbh);
                if ($result['rows'] != 1) {
                    
                    $tMessaage = _("Error during database insert");
                    $error = 1;
                }
                else {
                    
                    $projectid = db_get_last_insert_id('svnprojects', 'id', $dbh);
                    
                    foreach( $_SESSION[SVNSESSID]['members'] as $userid => $name) {
                        
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')";
                        $result = db_query($query, $dbh);
                        
                        if ($result['rows'] == 1) {
                            
                            db_log($_SESSION[SVNSESSID]['username'], "added project responsible $userid", $dbh);
                            
                            $row = db_assoc($result['result']);
                            $id = $row['id'];
                            $dbnow = db_now();
                            $query = "INSERT INTO " . $schema . "svn_projects_responsible (user_id, project_id, created, created_user) " . "     VALUES ($id, $projectid, '$dbnow', '" . $_SESSION[SVNSESSID]['username'] . "')";
                            $result = db_query($query, $dbh);
                            
                            if ($result['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Insert of user project relation failed for user_id %s and project_id %s"), $id, $projectid);
                                $error = 1;
                            }
                        }
                        else {
                            
                            $tMessage = sprintf(_("User %s not found!"), $userid);
                            $error = 1;
                        }
                    }
                }
                
                if ($error == 0) {
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    header("Location: list_projects.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        elseif ($_SESSION[SVNSESSID]['task'] == "change") {
            
            $error = 0;
            $tReadonly = "readonly";
            $projectid = $_SESSION[SVNSESSID]['projectid'];
            
            if ($tProject == "") {
                
                $tMessage = _("Subversion project name is missing, please fill in!");
                $error = 1;
            }
            elseif ($tModulepath == "") {
                
                $tMessage = _("Subversion module path missing, please fill in!");
                $error = 1;
            }
            elseif (empty($_SESSION[SVNSESSID]['members'])) {
                
                $tMessage = _("Project responsible user missing, please fill in!");
                $error = 1;
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE (svnmodule = '$tProject') " . "   AND (deleted = '00000000000000') " . "   AND (id != " . $_SESSION[SVNSESSID]['projectid'] . ")";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] > 0) {
                    
                    $tMessage = _("The project with the name $tProject exists already");
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svnprojects " . "   SET svnmodule = '$tProject', " . "       modulepath = '$tModulepath', " . "       repo_id = $tRepo, " . "       modified = '$dbnow', " . "       modified_user = '" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE (id = " . $_SESSION[SVNSESSID]['projectid'] . ")";
                
                db_ta('BEGIN', $dbh);
                
                $project = db_getProjectById($tProject, $dbh);
                $repo = db_getRepoById($tRepo, $dbh);
                
                db_log($_SESSION[SVNSESSID]['username'], "updated project $tProject ($tModulepath/$repo)", $dbh);
                
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    $tUids = array();
                    
                    foreach( $_SESSION[SVNSESSID]['members'] as $uid => $name) {
                        
                        $tUids[] = $uid;
                    }
                    
                    $tGroupIds = array();
                    
                    foreach( $_SESSION[SVNSESSID]['groups'] as $groupid => $groupname) {
                        
                        $tGroupIds[] = $groupid;
                    }
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (project_id = $projectid) " . "   AND (deleted = '00000000000000')";
                    $result = db_query($query, $dbh);
                    
                    while ( ($row = db_assoc($result['result'])) and ($error == 0) ) {
                        
                        $userid = db_getUseridById($row['user_id'], $dbh);
                        $uid = $row['user_id'];
                        $projectid = $row['project_id'];
                        
                        if (! in_array($userid, $tUids)) {
                            
                            db_log($_SESSION[SVNSESSID]['username'], "deleted $userid from $tProject as responsible", $dbh);
                            $id = $row['id'];
                            $dbnow = db_now();
                            $query = "UPDATE " . $schema . "svn_projects_responsible " . "SET deleted = '$dbnow', " . "    deleted_user = '" . $_SESSION[SVNSESSID]['username'] . "' " . " WHERE id = " . $id;
                            $result_del = db_query($query, $dbh);
                            
                            if ($result_del['rows'] != 1) {
                                
                                $tMessage = sprintf(_("Delete of svn_projects_responsible record with id %s failed"), $id);
                                $error = 1;
                            }
                        }
                    }
                    
                    foreach( $_SESSION[SVNSESSID]['members'] as $userid => $name) {
                        
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')";
                        $result = db_query($query, $dbh);
                        
                        if ($result['rows'] == 1) {
                            
                            $row = db_assoc($result['result']);
                            $id = $row['id'];
                            
                            $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (user_id = $id) " . "   AND (project_id = $projectid) " . "   AND (deleted = '00000000000000')";
                            $result = db_query($query, $dbh);
                            
                            // print_r($id);
                            // print_r($groupid);
                            // print_r($userid);
                            // print_r($result);
                            
                            if ($result['rows'] == 0) {
                                
                                db_log($_SESSION[SVNSESSID]['username'], " added project responsible $userid to project $tProject", $dbh);
                                $dbnow = db_now();
                                $query = "INSERT INTO " . $schema . "svn_projects_responsible (user_id, project_id, created, created_user) " . "     VALUES ($id, $projectid, '$dbnow', '" . $_SESSION[SVNSESSID]['username'] . "')";
                                $result = db_query($query, $dbh);
                                
                                if ($result['rows'] != 1) {
                                    $tMessage = sprintf(_("Insert of user/project relation (%s/%s) failed due to database error"), $id, $projectid);
                                    $error = 1;
                                }
                            }
                        }
                        else {
                            
                            $tMessage = sprintf(_("User %s not found!"), $userid);
                            $error = 1;
                        }
                    }
                }
                else {
                    
                    $tMessage = _("Project not modified due to database error");
                    $error = 1;
                }
                
                if ($error == 0) {
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    header("Location: list_projects.php");
                    exit();
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                }
            }
        }
        else {
            
            $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
        }
    }
    elseif ($buttonadd == _("Add")) {
        
        if (isset($_POST['membersadd'])) {
            
            $membersadd = db_escape_string($_POST['membersadd']);
        }
        else {
            
            $membersadd = array();
        }
        
        foreach( $membersadd as $userid) {
            
            $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000' )";
            $result = db_query($query, $dbh);
            
            if ($result['rows'] == 1) {
                
                $row = db_assoc($result['result']);
                $name = $row['name'];
                $givenname = $row['givenname'];
                
                if ($givenname != "") {
                    
                    $name = $givenname . " " . $name;
                }
            }
            
            $_SESSION[SVNSESSID]['members'][$userid] = $name;
        }
        
        $project = $_SESSION[SVNSESSID]['projectid'];
        $tProject = $_SESSION[SVNSESSID]['project'];
        $tModulepath = $_SESSION[SVNSESSID]['modulepath'];
        $tRepo = $_SESSION[SVNSESSID]['repo'];
        $tMembers = $_SESSION[SVNSESSID]['members'];
        
        db_disconnect($dbh);
        header("Location: workOnProject.php?id=$project&task=relist");
        exit();
    }
    elseif ($buttonadd == _("Cancel")) {
        
        $project = $_SESSION[SVNSESSID]['projectid'];
        $task = $_SESSION[SVNSESSID]['task'];
        
        db_disconnect($dbh);
        header("Location: workOnProject.php?id=$project&task=relist");
        exit();
    }
    else {
        
        $tMessage = sprintf(_("Invalid button (%s/%s), anyone tampered arround with?"), $button, $buttonadd);
    }
    
    $header = PROJECTS;
    $subheader = PROJECTS;
    $menu = PROJECTS;
    $template = "workOnProject.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
