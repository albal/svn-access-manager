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
$rightAllowed = db_check_acl($SESSID_USERNAME, "Repository admin", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "workonrepo";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use workOnRepo without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tReadonly = "";
    $tTask = db_escape_string($_GET['task']);
    if (isset($_GET['id'])) {
        
        $tId = db_escape_string($_GET['id']);
    }
    else {
        
        $tId = "";
    }
    
    if (($rightAllowed == "add") && ($tTask != "new")) {
        
        db_log($SESSID_USERNAME, "tried to use workOnRepo without permission", $dbh);
        db_disconnect($dbh);
        header("Location: nopermission.php");
        exit();
    }
    
    $_SESSION[SVNSESSID]['task'] = strtolower($tTask);
    $_SESSION[SVNSESSID][REPOID] = $tId;
    
    $schema = db_determine_schema();
    
    if ($_SESSION[SVNSESSID]['task'] == "new") {
        
        $tReponame = "";
        $tRepopath = "";
        $tRepouser = "";
        $tRepopassword = "";
        $tSeparate = "";
        $tAuthUserFile = "";
        $tSvnAccessFile = "";
        $tCreateRepo = "";
    }
    elseif ($_SESSION[SVNSESSID]['task'] == "change") {
        
        $tReadonly = "readonly";
        $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE id = $tId";
        $result = db_query($query, $dbh);
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result['result']);
            $tReponame = $row['reponame'];
            $tRepopath = $row['repopath'];
            $tRepouser = $row['repouser'];
            $tRepopassword = $row['repopassword'];
            $tSeparate = $row['different_auth_files'];
            $tAuthUserFile = $row['auth_user_file'];
            $tSvnAccessFile = $row['svn_access_file'];
            $tCreateRepo = "";
        }
        else {
            
            $tMessage = _("Invalid userid $id requested!");
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
    }
    
    $header = REPOS;
    $subheader = REPOS;
    $menu = REPOS;
    $template = "workOnRepo.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $tReponame = db_escape_string($_POST['fReponame']);
    $tRepopath = db_escape_string($_POST['fRepopath']);
    if (get_magic_quotes_gpc() == 1) {
        $tRepopath = no_magic_quotes($tRepopath);
    }
    $tRepopath = preg_replace('/\\\/', '/', $tRepopath);
    $tRepouser = db_escape_string($_POST['fRepouser']);
    $tRepopassword = db_escape_string($_POST['fRepopassword']);
    $tAuthUserFile = isset($_POST['fAuthUserFile']) ? db_escape_string($_POST['fAuthUserFile']) : "";
    $tSvnAccessFile = isset($_POST['fSvnAccessFile']) ? db_escape_string($_POST['fSvnAccessFile']) : "";
    $tCreateRepo = isset($_POST['fCreateRepo']) ? db_escape_string($_POST['fCreateRepo']) : "";
    $os = determineOs();
    
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
    
    $schema = db_determine_schema();
    
    if ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: list_repos.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        if ($_SESSION[SVNSESSID]['task'] == "new") {
            
            $error = 0;
            
            if ($tReponame == "") {
                
                $tMessage = _("Repository name is missing, please fill in!");
                $error = 1;
            }
            elseif ($tRepopath == "") {
                
                $tMessage = _("Repository path missing, please fill in!");
                $error = 1;
            }
            elseif ((! preg_match('/^file:\//', $tRepopath)) && (! preg_match('/^http:\//', $tRepopath)) && (! preg_match('/^https:\//', $tRepopath))) {
                
                $tMessage = _("Repository path must start with file://, http:// or https://!");
                $error = 1;
            }
            elseif (preg_match('/^file:\//', $tRepopath)) {
                
                $tmp = preg_replace('/^file:\/\//', '', $tRepopath);
                
                if (substr($tmp, 0, 1) != "/") {
                    
                    if ($os == WINDOWS) {
                        $example = "file:///c:/svn/testrepo";
                    }
                    else {
                        $example = "file:///svn/testrepo";
                    }
                    $tMessage = sprintf(_("A repository path must start with '/' after file:// like %s"), $example);
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (reponame = '$tReponame') " . "   AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                
                if ($result['rows'] > 0) {
                    
                    $tMessage = sprintf(_("The repository with the name %s exists already"), $tReponame);
                    $error = 1;
                }
            }
            
            if ($error == 0) {
                
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "svnrepos (reponame, repopath, repouser, repopassword, auth_user_file, svn_access_file, created, created_user) " . "     VALUES ('$tReponame', '$tRepopath', '$tRepouser', '$tRepopassword', '$tAuthUserFile', '$tSvnAccessFile', '$dbnow', '" . $_SESSION[SVNSESSID][USERNAME] . "')";
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION[SVNSESSID][USERNAME], "addes repository $tReponame ($tRepopath)", $dbh);
                
                $result = db_query($query, $dbh);
                if ($result['rows'] != 1) {
                    
                    db_ta('ROLLBACK', $dbh);
                    
                    $tMessage = _("Error during database insert");
                }
                else {
                    
                    db_ta('COMMIT', $dbh);
                    
                    $tMessage = _("Repository successfully inserted");
                    $warn = 0;
                    
                    if ($tCreateRepo == "1") {
                        
                        if (! isset($CONF[SVNADMIN_COMMAND]) || ($CONF[SVNADMIN_COMMAND] == "")) {
                            
                            $tMessage = _("Repository successfully inserted into database but not created in the filesystem because no svnadmin command given in config.inc.php!");
                            $warn = 1;
                        }
                        else {
                            
                            if (preg_match('/^file:\//', $tRepopath)) {
                                
                                $os = determineOs();
                                
                                if ($os == WINDOWS) {
                                    
                                    $tRepopath = no_magic_quotes($tRepopath);
                                    $svncmd = no_magic_quotes($CONF[SVNADMIN_COMMAND]);
                                }
                                else {
                                    
                                    $svncmd = $CONF[SVNADMIN_COMMAND];
                                }
                                
                                $repopath = preg_replace('/^file:\/\//', '', $tRepopath);
                                
                                if ($os == WINDOWS) {
                                    
                                    $repopath = preg_replace('/^\//', '', $repopath);
                                    $repopath = preg_replace('/\\\/', '/', $repopath);
                                }
                                
                                $compatibility = isset($CONF['repo_compatibility']) ? $CONF['repo_compatibility'] : "--pre-1.4-compatible";
                                $tCreateRepository = $svncmd . " " . $compatibility . " create " . $repopath;
                                
                                if ($os == WINDOWS) {
                                    
                                    exec($tCreateRepository, $output, $returncode);
                                }
                                else {
                                    
                                    exec(escapeshellcmd($tCreateRepository), $output, $returncode);
                                }
                                
                                sleep(2);
                                
                                if ($returncode != 0) {
                                    
                                    $tMessage = _("Repository successfully inserted into database but creation of repository in the filesystem failed. Do this manually!");
                                    $warn = 1;
                                }
                                else {
                                    
                                    $tMessage = _("Repository successfully inserted into database and created in filesystem");
                                    $warn = 1;
                                }
                            }
                            else {
                                
                                $tMessage = _("Repository sucessfully inserted into database but not created in filesystem because it's not locally hosted!");
                                $warn = 1;
                            }
                        }
                    }
                }
                
                if ($warn == 0) {
                    db_disconnect($dbh);
                    header("Location: list_repos.php");
                    exit();
                }
            }
        }
        elseif ($_SESSION[SVNSESSID]['task'] == "change") {
            
            $error = 0;
            $tReadonly = "readonly";
            
            if ($tReponame == "") {
                
                $tMessage = _("Repository name is missing, please fill in!");
                $error = 1;
            }
            elseif ($tRepopath == "") {
                
                $tMessage = _("Repository path missing, please fill in!");
                $error = 1;
            }
            elseif ((! preg_match('/^file:\//', $tRepopath)) && (! preg_match('/^http:\//', $tRepopath)) && (! preg_match('/^https:\//', $tRepopath))) {
                
                $tMessage = _("Repository path must start with file://, http:// or https://!");
                $error = 1;
            }
            elseif (preg_match('/^file:\//', $tRepopath)) {
                
                $tmp = preg_replace('/^file:\/\//', '', $tRepopath);
                if (substr($tmp, 0, 1) != "/") {
                    
                    if ($os == WINDOWS) {
                        $example = "file:///c:/svn/testrepo";
                    }
                    else {
                        $example = "file:///svn/testrepo";
                    }
                    $tMessage = sprintf(_("A repository path must start with '/' after file:// like %s"), $example);
                    $error = 1;
                }
            }
            else {
                
                if ($error == 0) {
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (reponame = '$tReponame') " . "   AND (deleted = '00000000000000') " . "   AND (id != " . $_SESSION[SVNSESSID][REPOID] . ")";
                    $result = db_query($query, $dbh);
                    
                    if ($result['rows'] > 0) {
                        
                        $tMessage = _("The repository with the name $tReponame exists already");
                        $error = 1;
                    }
                }
            }
            
            if ($error == 0) {
                
                $reponame = db_getRepoById($_SESSION[SVNSESSID][REPOID], $dbh);
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "svnrepos " . "   SET reponame = '$tReponame', " . "       repopath = '$tRepopath', " . "       repouser = '$tRepouser', " . "       repopassword = '$tRepopassword', " . "       auth_user_file='$tAuthUserFile', " . "       svn_access_file='$tSvnAccessFile', " . "       modified = '$dbnow', " . "       modified_user = '" . $_SESSION[SVNSESSID][USERNAME] . "' " . " WHERE (id = " . $_SESSION[SVNSESSID][REPOID] . ")";
                
                db_ta('BEGIN', $dbh);
                db_log($_SESSION[SVNSESSID][USERNAME], "updated repository $reponame", $dbh);
                
                $result = db_query($query, $dbh);
                
                if ($result['rows'] == 1) {
                    
                    db_ta('COMMIT', $dbh);
                    db_disconnect($dbh);
                    header("Location: list_repos.php");
                    exit();
                    
                    $tMessage = _("Repository successfully modified");
                }
                else {
                    
                    db_ta('ROLLBACK', $dbh);
                    
                    $tMessage = _("Repository not modified due to database error");
                }
            }
        }
        else {
            
            $tMessage = sprintf(_("Invalid task %s, anyone tampered arround with?"), $_SESSION[SVNSESSID]['task']);
        }
    }
    else {
        
        $tMessage = _("Invalid button $button, anyone tampered arround with?");
    }
    
    $header = REPOS;
    $subheader = REPOS;
    $menu = REPOS;
    $template = "workOnRepo.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
