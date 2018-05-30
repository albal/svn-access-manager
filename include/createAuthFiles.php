<?php

/**
 * create configuration files for Subversion access and viewvc access.
 *
  * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
 * @license GPL v2
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
 *            $LastChangedDate$
 *            $LastChangedBy$
 *           
 *            $Id$
 *           
 *           
 *         
 *
 */

/**
 * write a line to a open file
 *
 * @param resource $fileHandle
 * @param string $content
 * @param resource $dbh
 * @param string $semaphore
 * @param string $filename
 * @return integer[]|string[]
 */
function writeToFile($fileHandle, $content, $dbh = '', $semaphore = '', $filename = '') {

    $retcode = 0;
    $tMessage = '';
    
    if (! @fwrite($fileHandle, $content)) {
        
        $retcode = 1;
        $tMessage = sprintf(_("Cannot write to %s"), $filename);
        error_log($tMessage);
        if ($semaphore != '') {
            db_unset_semaphore($semaphore, 'sem', $dbh);
        }
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write all users to file
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @return string[]|integer[]
 */
function writeAllUsers($dbh, $schema, $fileHandle) {

    $retcode = 0;
    $tMessage = "";
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (locked = '0') " . "ORDER BY userid";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        list($retcode, $tMessage ) = writeToFile($fileHandle, $row[USERID] . ":" . $row['password'] . "\n", $dbh, CREATEAUTHUSERFILE, 'AuthUser file');
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write users per repository
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param integer $repoid
 * @return string[]|integer[]
 */
function writeUserPerRepo($dbh, $schema, $fileHandle, $repoid) {

    $retcode = 0;
    $tMessage = "";
    $curdate = strftime("%Y%m%d");
    $query = "SELECT DISTINCT svnusers.userid, svnusers.password " . "  FROM " . $schema . "svnusers, " . $schema . "svn_access_rights, " . $schema . "svnrepos, " . $schema . "svnprojects, " . $schema . "svn_users_groups" . " WHERE (svnprojects.repo_id = $repoid) " . "   AND (svn_access_rights.project_id = svnprojects.id) " . "   AND (svnrepos.deleted = '00000000000000') " . "   AND (svn_access_rights.deleted = '00000000000000') " . "   AND (svn_access_rights.valid_from <= '$curdate') " . "   AND (svn_access_rights.valid_until >= '$curdate') " . "   AND (svnprojects.deleted = '00000000000000') " . "   AND (svnusers.locked = '0') " . "   AND (" . "    (svnusers.id = svn_access_rights.user_id) OR ( " . "     (svn_users_groups.user_id = svnusers.id)" . "     AND (svn_users_groups.group_id = svn_access_rights.group_id)" . "     AND (svn_users_groups.deleted =  '00000000000000')" . "    ))" . "ORDER BY svnusers.userid";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        list($retcode, $tMessage ) = writeToFile($fileHandle, $row[USERID] . ":" . $row['password'] . "\n", $dbh, CREATEAUTHUSERFILE, 'AuthUser file');
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * add administrator access
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @return string[]|integer[]
 */
function addAdmins($dbh, $schema, $fileHandle) {

    $retcode = 0;
    $tMessage = "";
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (superadmin = 1) " . "   AND (deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        list($retcode, $tMessage ) = writeToFile($fileHandle, $row[USERID] . ":" . $row['password'] . "\n", $dbh, CREATEAUTHUSERFILE, 'Authuser file');
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * get name and path of auth user file
 *
 * @param string $authuserfile
 * @param string $reponame
 * @return string
 */
function getAuthUserFile($authuserfile, $reponame) {

    global $CONF;
    
    if ($authuserfile == "") {
        $authuserfile = dirname($CONF[AUTHUSERFILE]) . "/svn-passwd." . $reponame;
    }
    
    return ($authuserfile);
    
}

/**
 * create auth user file
 *
 * @param resource $dbh
 * @return integer[]|string[]
 */
function createAuthUserFile($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $retcode = 0;
    $tMessage = "";
    $dir = dirname($CONF[AUTHUSERFILE]);
    $entropy = rand_name();
    $os = determineOS();
    $slash = getSlash($os);
    $tempfile = $dir . $slash . "authtemp_" . $entropy;
    
    if (! db_set_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
        
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = _("Can't set semaphore, another process is writing Auth User File, try again later");
        
        return $ret;
    }
    
    if ($fileHandle = @fopen($tempfile, 'w')) {
        
        list($retcode, $tMessage ) = writeAllUsers($dbh, $schema, $fileHandle);
        
        @fclose($fileHandle);
        
        if ($retcode == 0) {
            
            unlinkFile($os, $CONF[AUTHUSERFILE]);
            
            if (@rename($tempfile, $CONF[AUTHUSERFILE])) {
                
                if (db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
                    
                    $ret = array();
                    $ret[ERROR] = $retcode;
                    $ret[ERRORMSG] = _("Auth user file successfully created!");
                    
                    return $ret;
                }
                else {
                    
                    $retcode = 1;
                    $tMessage = _("Auth user file created but semaphore could not be released");
                }
            }
            else {
                
                $retcode = 3;
                $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[AUTHUSERFILE]);
            }
        }
    }
    else {
        
        $retcode = 2;
        $tMessage = sprintf(_("Cannot open file %s for writing!"), $tempfile);
    }
    
    db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * copy a file
 *
 * @param string $tempfile
 * @param string $authuserfile
 * @return integer[]|string[]
 */
function copyFile($tempfile, $authuserfile) {

    if (@rename($tempfile, $authuserfile)) {
        
        $retcode = 0;
        $tMessage = '';
    }
    else {
        
        $retcode = 3;
        $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $authuserfile);
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * create auth user file on per repo basis
 *
 * @param resource $dbh
 * @return integer[]|string[]
 */
function createAuthUserFilePerRepo($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $retcode = 0;
    $tMessage = "";
    $dir = dirname($CONF[AUTHUSERFILE]);
    $entropy = rand_name();
    $os = determineOS();
    $slash = getSlash($os);
    $tempfile = $dir . $slash . "authtemp_" . $entropy;
    
    if (! db_set_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
        
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = _("Can't set semaphore, another process is writing Auth User File, try again later");
        
        return $ret;
    }
    
    $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (deleted = '00000000000000')";
    $resultrepos = db_query($query, $dbh);
    while ( $row = db_assoc($resultrepos[RESULT]) ) {
        
        $repoid = $row['id'];
        $authuserfile = $row['auth_user_file'];
        $reponame = $row[REPONAME];
        $authuserfile = getAuthUserFile($authuserfile, $reponame);
        
        if ($fileHandle = @fopen($tempfile, 'w')) {
            
            /**
             * always add admin users to per repo password files
             */
            list($retcode, $tMessage ) = addAdmins($dbh, $schema, $fileHandle);
            list($retcode, $tMessage ) = writeUserPerRepo($dbh, $schema, $fileHandle, $repoid);
            
            @fclose($fileHandle);
            
            if ($retcode == 0) {
                
                unlinkFile($os, $authuserfile);
                list($retcode, $tMessage ) = copyFile($tempfile, $authuserfile);
            }
        }
        else {
            
            $retcode = 2;
            $tMessage = sprintf(_("Cannot open file %s for writing!"), $tempfile);
        }
    }
    
    if ($retcode == 0) {
        if (db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
            
            $tMessage = _("Auth user file successfully created!");
            $ret = array();
            $ret[ERROR] = $retcode;
            $ret[ERRORMSG] = $tMessage;
            
            return $ret;
        }
        else {
            
            $retcode = 1;
            $tMessage = _("Auth user file created but semaphore could not be released");
        }
    }
    
    db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * concatenate user
 *
 * @param string $users
 * @param string $user
 * @return string
 */
function concatUsers($users, $user) {

    if ($users == "") {
        
        $users = $user;
    }
    else {
        
        $users = $users . ", " . $user;
    }
    
    return ($users);
    
}

/**
 * write groups to file
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param string $tempfile
 * @return string[]|integer[]
 */
function writeGroups($dbh, $schema, $fileHandle, $tempfile) {

    $retcode = 0;
    $tMessage = "";
    $query = "  SELECT svngroups.groupname, svnusers.userid " . "    FROM " . $schema . "svngroups, " . $schema . "svnusers, " . $schema . "svn_users_groups " . "   WHERE (svngroups.deleted = '00000000000000') " . "     AND (svn_users_groups.user_id = svnusers.id) " . "     AND (svn_users_groups.group_id = svngroups.id) " . "     AND (svnusers.deleted = '00000000000000') " . "     AND (svn_users_groups.deleted = '00000000000000') " . "ORDER BY svngroups.groupname ASC, svnusers.userid ASC";
    $result = db_query($query, $dbh);
    $oldgroup = "";
    $users = "";
    $groupwritten = 0;
    
    while ( ($row = db_assoc($result[RESULT])) && ($retcode == 0) ) {
        
        if ($oldgroup != $row[GROUPNAME]) {
            
            if ($users != "") {
                
                if ($groupwritten == 0) {
                    
                    $groupwritten = 1;
                    list($retcode, $tMessage ) = writeToFile($fileHandle, "[groups]\n", $dbh, CREATEACCESSFILE, $tempfile);
                }
                
                list($retcode, $tMessage ) = writeToFile($fileHandle, $oldgroup . " = " . $users . "\n", $dbh, CREATEACCESSFILE, $tempfile);
            }
            
            $users = $row[USERID];
            $oldgroup = $row[GROUPNAME];
        }
        else {
            $users = concatUsers($users, $row[USERID]);
        }
    }
    
    if ($users != "") {
        
        if ($groupwritten == 0) {
            
            $groupwritten = 1;
            list($retcode, $tMessage ) = writeToFile($fileHandle, "[groups]\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
        
        fwrite($fileHandle, $oldgroup . " = " . $users . "\n");
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write admin to access file
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param resource $tempfile
 * @return string[]|integer[]
 */
function writeAdminToAccessFile($dbh, $schema, $fileHandle, $tempfile) {

    global $CONF;
    
    $retcode = 0;
    $tMessage = "";
    $first = 1;
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (superadmin = 1) " . "   AND (deleted = '00000000000000')";
    $resultusr = db_query($query, $dbh);
    while ( $rowusr = db_assoc($resultusr[RESULT]) ) {
        
        if ($first == 1) {
            
            $first = 0;
            
            // write superuser privileges for access to all repositories by http(s)
            list($retcode, $tMessage ) = writeToFile($fileHandle, "\n[/]\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
        
        list($retcode, $tMessage ) = writeToFile($fileHandle, $rowusr[USERID] . " = r\n", $dbh, CREATEACCESSFILE, $tempfile);
        
        if (isset($CONF[WRITEANONYMOUSACCESSRIGHTS]) && ($CONF[WRITEANONYMOUSACCESSRIGHTS] == 1)) {
            
            list($retcode, $tMessage ) = writeToFile($fileHandle, "* = r\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * check svn repository path
 *
 * @param string $tPath
 * @return string
 */
function checkPath($tPath) {

    if ($tPath == "") {
        $tPath = "/";
    }
    return ($tPath);
    
}

/**
 * check repository name
 *
 * @param string $repoName
 * @return string
 */
function checkRepoName($repoName) {

    if ($repoName == "/:") {
        $repoName = "";
    }
    
    return ($repoName);
    
}

/**
 * write groups to file
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param array $row
 * @param string $right
 * @param string $tempfile
 * @return string[]|integer[]
 */
function writeGroupToFile($dbh, $schema, $fileHandle, $row, $right, $tempfile) {

    $retcode = 0;
    $tMessage = '';
    
    if (($row[GROUP_ID] != "0") && (! empty($row[GROUP_ID]))) {
        
        $query = "  SELECT * " . "    FROM " . $schema . "svngroups " . "   WHERE (id = " . $row[GROUP_ID] . ")";
        $resultgrp = db_query($query, $dbh);
        
        if ($resultgrp['rows'] == 1) {
            
            $rowgrp = db_assoc($resultgrp[RESULT]);
            list($retcode, $tMessage ) = writeToFile($fileHandle, "@" . $rowgrp[GROUPNAME] . " = " . $right . "\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write users to file
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param array $row
 * @param string $right
 * @param string $tempfile
 * @return string[]|integer[]
 */
function writeUserToFile($dbh, $schema, $fileHandle, $row, $right, $tempfile) {

    $retcode = 0;
    $tMessage = '';
    
    if (($row[USER_ID] != "0") && (! empty($row[USER_ID]))) {
        
        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = " . $row[USER_ID] . ")";
        $resultusr = db_query($query, $dbh);
        
        if ($resultusr['rows'] == 1) {
            
            $rowusr = db_assoc($resultusr[RESULT]);
            list($retcode, $tMessage ) = writeToFile($fileHandle, $rowusr[USERID] . " = " . $right . "\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write access rights to file
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param string $tempfile
 * @return string[]|integer[]
 */
function writeAccessRightsToFile($dbh, $schema, $fileHandle, $tempfile) {

    global $CONF;
    
    $retcode = 0;
    $tMessage = "";
    $oldpath = "";
    $curdate = strftime("%Y%m%d");
    $pathSort = getRepoSortPath();
    
    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svn_access_rights.path " . $pathSort . ", access_right DESC";
    $result = db_query($query, $dbh);
    
    while ( ($row = db_assoc($result[RESULT])) && ($retcode == 0) ) {
        
        $right = translateRight($row[ACCESS_RIGHT]);
        
        $checkpath = $row[REPO_ID] . $row['path'];
        if ($checkpath != $oldpath) {
            
            $oldpath = $row[REPO_ID] . $row['path'];
            $tPath = checkPath(preg_replace('/\/$/', '', $row['path']));
            $repoName = checkRepoName($row[REPONAME] . ":");
            list($retcode, $tMessage ) = writeToFile($fileHandle, "\n[" . $repoName . $tPath . "]\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
        
        list($retcode, $tMessage ) = writeUserToFile($dbh, $schema, $fileHandle, $row, $right, $tempfile);
        list($retcode, $tMessage ) = writeGroupToFile($dbh, $schema, $fileHandle, $row, $right, $tempfile);
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * create svn access file
 *
 * @param resource $dbh
 * @return integer[]|string[]
 */
function createAccessFile($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $retcode = 0;
    $tMessage = "";
    
    if (! db_set_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
        
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = _("Can't set semaphore, another process is writing access file, try again later");
        return $ret;
    }
    
    $dir = dirname($CONF[SVNACCESSFILE]);
    $entropy = rand_name();
    $os = determineOS();
    $slash = getSlash($os);
    $tempfile = $dir . $slash . "accesstemp_" . $entropy;
    $fileHandle = @fopen($tempfile, 'w');
    if (! $fileHandle) {
        
        $retcode = 1;
        $tMessage = sprintf(_("Cannot open %s for wrtiting"), $tempfile);
        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = sprintf(_("Cannot open %s for wrtiting"), $tempfile);
        return $ret;
    }
    
    /**
     * write groups to file
     */
    list($retcode, $tMessage ) = writeGroups($dbh, $schema, $fileHandle, $tempfile);
    
    if ($retcode == 0) {
        
        list($retcode, $tMessage ) = writeAdminToAccessFile($dbh, $schema, $fileHandle, $tempfile);
    }
    
    if ($retcode == 0) {
        
        /**
         * write access rights to file
         */
        list($retcode, $tMessage ) = writeAccessRightsToFile($dbh, $schema, $fileHandle, $tempfile);
        
        if (! @fwrite($fileHandle, "\n")) {
            
            $retcode = 7;
            $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
        }
        
        @fclose($fileHandle);
        
        unlinkFile($os, $CONF[SVNACCESSFILE]);
        
        if (@rename($tempfile, $CONF[SVNACCESSFILE])) {
            
            if (db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
                
                $tMessage = _("Access file successfully created!");
            }
            else {
                
                $retcode = 1;
                $tMessage = _("Access file successfully created but semaphore could nor be released");
                db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
            }
        }
        else {
            
            $retcode = 3;
            $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[SVNACCESSFILE]);
            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
        }
    }
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * write groups on per repository base
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param string $tempfile
 * @param integer $repoid
 * @return string[]|integer[]
 */
function writeGroupsPerRepo($dbh, $schema, $fileHandle, $tempfile, $repoid) {

    $retcode = 0;
    $tMessage = "";
    $curdate = strftime("%Y%m%d");
    $query = "  SELECT svngroups.groupname, svnusers.userid " . "    FROM " . $schema . "svngroups, " . $schema . "svnusers, " . $schema . "svn_users_groups, " . $schema . "svnprojects, " . $schema . "svn_access_rights, " . $schema . "svnrepos " . "   WHERE (svn_users_groups.user_id = svnusers.id) " . "     AND (svn_users_groups.group_id = svngroups.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "     AND (svnprojects.repo_id=$repoid) " . "     AND (svnprojects.id = svn_access_rights.project_id) " . "     AND (svn_access_rights.group_id=svngroups.id) " . "     AND (svn_access_rights.group_id != 0) " . "     AND (svn_users_groups.deleted='00000000000000') " . "     AND (svn_access_rights.deleted='00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svnprojects.deleted='00000000000000') " . "     AND (svngroups.deleted='00000000000000') " . "     AND (svnrepos.deleted='00000000000000') " . "     AND (svnusers.deleted='00000000000000') " . "ORDER BY svngroups.groupname ASC, svnusers.userid ASC";
    $result = db_query($query, $dbh);
    $oldgroup = "";
    $users = "";
    $groupwritten = 0;
    
    while ( ($row = db_assoc($result[RESULT])) && ($retcode == 0) ) {
        
        if ($oldgroup != $row[GROUPNAME]) {
            
            if ($users != "") {
                
                $groupwritten = 1;
                list($retcode, $tMessage ) = writeToFile($fileHandle, "[groups]\n", '', '', $tempfile);
                list($retcode, $tMessage ) = writeToFile($fileHandle, $oldgroup . " = " . $users . "\n", $dbh, CREATEACCESSFILE, $tempfile);
            }
            
            $users = $row[USERID];
            $oldgroup = $row[GROUPNAME];
        }
        else {
            
            if ($users == "") {
                
                $users = $row[USERID];
            }
            else {
                
                $users = $users . ", " . $row[USERID];
            }
        }
    }
    
    if ($users != "") {
        
        if ($groupwritten == 0) {
            
            list($retcode, $tMessage ) = writeToFile($fileHandle, "[groups]\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
        
        fwrite($fileHandle, $oldgroup . " = " . $users . "\n");
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write access rights on per repository basis
 *
 * @param resource $dbh
 * @param string $schema
 * @param resource $fileHandle
 * @param string $tempfile
 * @param integer $repoid
 * @return string[]|integer[]
 */
function writeAccessRightsPerRepoToFile($dbh, $schema, $fileHandle, $tempfile, $repoid) {

    $retcode = 0;
    $tMessage = "";
    $oldpath = "";
    $curdate = strftime("%Y%m%d");
    $pathSort = getRepoSortPath();
    
    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "     AND (svnprojects.repo_id=$repoid) " . "     AND (svnprojects.deleted='00000000000000') " . "     AND (svnrepos.deleted='00000000000000') " . "ORDER BY svnrepos.reponame ASC, svn_access_rights.path " . $pathSort . ", access_right DESC";
    $result = db_query($query, $dbh);
    
    while ( ($row = db_assoc($result[RESULT])) && ($retcode == 0) ) {
        
        $right = translateRight($row[ACCESS_RIGHT]);
        
        $checkpath = $row[REPO_ID] . $row['path'];
        if ($checkpath != $oldpath) {
            
            $oldpath = $row[REPO_ID] . $row['path'];
            $tPath = preg_replace('/\/$/', '', $row['path']);
            if ($tPath == "") {
                $tPath = "/";
            }
            list($retcode, $tMessage ) = writeToFile($fileHandle, "\n[" . $row[REPONAME] . ":" . $tPath . "]\n", $dbh, CREATEACCESSFILE, $tempfile);
        }
        
        list($retcode, $tMessage ) = writeUserToFile($dbh, $schema, $fileHandle, $row, $right, $tempfile);
        list($retcode, $tMessage ) = writeGroupToFile($dbh, $schema, $fileHandle, $row, $right, $tempfile);
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * creste acces file on per repo basis
 *
 * @param resource $dbh
 * @return integer[]|string
 */
function createAccessFilePerRepo($dbh) {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    $schema = db_determine_schema();
    
    $retcode = 0;
    $tMessage = "";
    
    if (! db_set_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
        
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = _("Can't set semaphore, another process is writing access file, try again later");
        
        return $ret;
    }
    
    $dir = dirname($CONF[SVNACCESSFILE]);
    $entropy = rand_name();
    $os = determineOS();
    $slash = getSlash($os);
    $tempfile = $dir . $slash . "accesstemp_" . $entropy;
    
    $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (deleted = '00000000000000')";
    $resultrepos = db_query($query, $dbh);
    while ( ($row = db_assoc($resultrepos[RESULT])) && ($retcode == 0) ) {
        
        $repoid = $row['id'];
        $reponame = $row[REPONAME];
        $svnaccessfile = getSvnAccessFile($row['svn_access_file'], $reponame);
        
        if ($fileHandle = @fopen($tempfile, 'w')) {
            
            /**
             * write groups to file
             */
            list($retcode, $tMessage ) = writeGroupsPerRepo($dbh, $schema, $fileHandle, $tempfile, $repoid);
            
            /**
             * write admin to access file
             */
            list($retcode, $tMessage ) = writeAdminToAccessFile($dbh, $schema, $fileHandle, $tempfile);
            
            /**
             * write access rights to file
             */
            list($retcode, $tMessage ) = writeAccessRightsPerRepoToFile($dbh, $schema, $fileHandle, $tempfile, $repoid);
            list($retcode, $tMessage ) = writeToFile($fileHandle, "\n", $dbh, CREATEACCESSFILE, $tempfile);
            
            @fclose($fileHandle);
            
            unlinkFile($os, $svnaccessfile);
            
            if (! @rename($tempfile, $svnaccessfile)) {
                $retcode = 3;
                $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[SVNACCESSFILE]);
                db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
            }
        }
        else {
            
            $retcode = 1;
            $tMessage = sprintf(_("Cannot open %s for wrtiting"), $tempfile);
            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
        }
    }
    /**
     * end iteration over repos
     */
    
    if (db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
        
        $tMessage = _("Access file successfully created!");
    }
    else {
        
        $retcode = 1;
        $tMessage = _("Access file successfully created but semaphore could not be released");
        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
    }
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * get group members
 *
 * @param integer $groupid
 * @param resource $dbh
 * @return array[]
 */
function getGroupMembers($groupid, $dbh) {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    $schema = db_determine_schema();
    
    $members = array();
    $query = "  SELECT userid " . "    FROM " . $schema . "svnusers, " . $schema . "svngroups, " . $schema . "svn_users_groups " . "   WHERE (svngroups.id = $groupid) " . "     AND (svngroups.id = svn_users_groups.group_id) " . "     AND (svnusers.id = svn_users_groups.user_id) " . "     AND (svn_users_groups.deleted='00000000000000') " . "     AND (svnusers.deleted='00000000000000') " . "ORDER BY userid ASC";
    $result = db_query($query, $dbh);
    while ( $row = db_assoc($result[RESULT]) ) {
        $members[] = $row[USERID];
    }
    
    return $members;
    
}

/**
 * delete user from array
 *
 * @param array $members
 * @param string $userid
 * @return array[]
 */
function deleteUser($members, $userid) {

    $new = array();
    
    for($i = 0; $i < count($members); $i ++) {
        
        if ($members[$i] != $userid) {
            
            $new[] = $members[$i];
        }
    }
    
    return $new;
    
}

/**
 * get users for upper directory
 *
 * @param string $checkpath
 * @param string $repopathes
 * @return array[]
 */
function getUpperDirUsers($checkpath, $repopathes) {

    $parts = explode('/', $checkpath);
    $count = count($parts);
    $data = array();
    
    if ($count >= 2) {
        
        array_pop($parts);
        
        $path = implode('/', $parts);
        
        if (array_key_exists($path, $repopathes)) {
            
            $data = $repopathes[$path];
        }
        else {
            
            $data = getUpperDirUsers($path, $repopathes);
        }
    }
    
    return $data;
    
}

/**
 * write Apache auth cdonfiguration
 *
 * @param resource $dbh
 * @param resource $fileHandle
 * @param string $tempfile
 * @param string $modulepath
 * @param string $currentgroup
 * @return array[]
 */
function writeApacheAuthConfig($dbh, $fileHandle, $tempfile, $modulepath, $currentgroup) {

    global $CONF;
    
    $retcode = 0;
    $tMessage = '';
    
    list($retcode, $tMessage ) = writeToFile($fileHandle, "<Location $modulepath>\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "     AuthType Basic\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "     AuthName \"Viewvc Access Control\"\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "     AuthUserFile " . $CONF[AUTHUSERFILE] . "\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "     AuthGroupFile " . $CONF[VIEWVCGROUPS] . "\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "     Require group $currentgroup\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "</Location>\n\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write Apache viewvc location match entries
 *
 * @param resource $dbh
 * @param resource $fileHandle
 * @param string $tempfile
 * @return array[]
 */
function writeApacheViewvcLocationMatch($dbh, $fileHandle, $tempfile) {

    global $CONF;
    
    $retcode = 0;
    $tMessage = '';
    
    list($retcode, $tMessage ) = writeToFile($fileHandle, "<LocationMatch (^" . $CONF[VIEWVCLOCATION] . "\$|^" . $CONF[VIEWVCLOCATION] . "/\$)>\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "      AuthType Basic\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "      AuthName \"Viewvc Access Control\"\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "      AuthUserFile /etc/svn/svn-passwd\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "      Require valid-user\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    list($retcode, $tMessage ) = writeToFile($fileHandle, "</LocationMatch>\n", $dbh, CREATEVIEWVCCONF, $tempfile);
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write viewvc groups
 *
 * @param resource $dbh
 * @param resource $fileHandle
 * @param string $groups
 * @param string $tempgroups
 * @return string[]|integer[]
 */
function writeViewvcGroups($dbh, $fileHandle, $groups, $tempgroups) {

    $retcode = 0;
    $tMessage = '';
    
    foreach( $groups as $group => $members ) {
        
        if (count($members) != 0) {
            
            list($retcode, $tMessage ) = writeToFile($fileHandle, $group . ":", $dbh, CREATEVIEWVCCONF, $tempgroups);
            if ($retcode != 0) {
                
                return (array(
                        $retcode,
                        $tMessage
                ));
            }
            
            if (is_array($members) && ! empty($members)) {
                
                for($i = 0; $i < count($members); $i ++) {
                    
                    list($retcode, $tMessage ) = writeToFile($fileHandle, $members[$i] . " ", $dbh, CREATEVIEWVCCONF, $tempgroups);
                }
            }
            
            list($retcode, $tMessage ) = writeToFile($fileHandle, "\n", $dbh, CREATEVIEWVCCONF, $tempgroups);
        }
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * write viewvc access users
 *
 * @param array $row
 * @param array $groups
 * @param array $repopathes
 * @param string $currentgroup
 * @param string $checkpath
 * @param resource $dbh
 * @param string $schema
 * @return array[]
 */
function writeViewvcAccessUsers($row, $groups, $repopathes, $currentgroup, $checkpath, $dbh, $schema) {

    if (($row[USER_ID] != "0") && (! empty($row[USER_ID]))) {
        
        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = " . $row[USER_ID] . ")";
        $resultusr = db_query($query, $dbh);
        
        if ($resultusr['rows'] == 1) {
            
            // add user to apache access group
            $rowusr = db_assoc($resultusr[RESULT]);
            
            if (! in_array($rowusr[USERID], $groups[$currentgroup])) {
                
                if ($row[ACCESS_RIGHT] != "none") {
                    $groups[$currentgroup][] = $rowusr[USERID];
                    $repopathes[$checkpath][] = $rowusr[USERID];
                }
                else {
                    $groups[$currentgroup] = deleteUser($groups[$currentgroup], $rowusr[USERID]);
                    $repopathes[$checkpath] = deleteUser($repopathes[$checkpath], $rowusr[USERID]);
                }
            }
        }
    }
    
    return (array(
            'groups' => $groups,
            'repopathes' => $repopathes
    ));
    
}

/**
 * update groups in array
 *
 * @param string $right
 * @param array $groups
 * @param string $currentgroup
 * @param string $member
 * @return array
 */
function updateGroups($right, $groups, $currentgroup, $member) {

    if ($right != "none") {
        $groups[$currentgroup][] = $member;
    }
    else {
        $groups[$currentgroup] = deleteUser($groups[$currentgroup], $member);
    }
    
    return ($groups);
    
}

/**
 * update repository pathes
 *
 * @param string $right
 * @param array $repopathes
 * @param string $checkpath
 * @param string $member
 * @return array
 */
function updateRepoPathes($right, $repopathes, $checkpath, $member) {

    if ($right != "none") {
        $repopathes[$checkpath][] = $member;
    }
    else {
        $repopathes[$checkpath] = deleteUser($repopathes[$checkpath], $member);
    }
    
    return ($repopathes);
    
}

/**
 * write viewvc acces groups
 *
 * @param array $row
 * @param array $groups
 * @param array $repopathes
 * @param string $currentgroup
 * @param string $checkpath
 * @param resource $dbh
 * @param string $schema
 * @return array[]
 */
function writeViewvcAccessGroups($row, $groups, $repopathes, $currentgroup, $checkpath, $dbh, $schema) {

    if (($row[GROUP_ID] != "0") && (! empty($row[GROUP_ID]))) {
        
        $query = "  SELECT * " . "    FROM " . $schema . "svngroups " . "   WHERE (id = " . $row[GROUP_ID] . ")";
        $resultgrp = db_query($query, $dbh);
        
        if ($resultgrp['rows'] == 1) {
            
            // get group members
            $rowgrp = db_assoc($resultgrp[RESULT]);
            $groupid = $rowgrp['id'];
            $members = getGroupMembers($groupid, $dbh);
            
            foreach( $members as $member ) {
                
                if (! in_array($member, $groups[$currentgroup])) {
                    
                    $groups = updateGroups($row[ACCESS_RIGHT], $groups, $currentgroup, $member);
                    $repopathes = updateRepoPathes($row[ACCESS_RIGHT], $repopathes, $checkpath, $member);
                }
            }
        }
    }
    
    return (array(
            'groups' => $groups,
            'repopathes' => $repopathes
    ));
    
}

/**
 * coipy viewvc group config file
 *
 * @param string $tempgroups
 * @param string $file
 * @param string $semaphore
 * @param resource $dbh
 * @return integer[]|string[]
 */
function copyViewvcGroupFile($tempgroups, $file, $semaphore = '', $dbh = '') {

    $tMessage = '';
    $retcode = 0;
    $os = determineOS();
    
    unlinkFile($os, $file);
    
    if (! @rename($tempgroups, $file)) {
        
        $retcode = 3;
        $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempgroups, $file);
        if ($semaphore != '') {
            db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
        }
        error_log($tMessage);
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * copy viewvc config file
 *
 * @param string $tempfile
 * @param string $file
 * @param string $semaphore
 * @param string $dbh
 * @return integer[]|string[]
 */
function copyViewvcConfFile($tempfile, $file, $semaphore = '', $dbh = '') {

    $tMessage = '';
    $retcode = 0;
    $os = determineOS();
    
    unlinkFile($os, $file);
    
    if (! @rename($tempfile, $file)) {
        
        $retcode = 3;
        $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $file);
        if ($semaphore != '') {
            db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
        }
        error_log($tMessage);
    }
    
    return (array(
            $retcode,
            $tMessage
    ));
    
}

/**
 * create viewvc configuration
 *
 * @param resource $dbh
 * @return integer[]|string[]
 */
function createViewvcConfig($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $retcode = 0;
    $tMessage = "";
    $curdate = strftime("%Y%m%d");
    $oldpath = "";
    $modulepath = "";
    $currentgroup = "g" . rand_name();
    $groups[$currentgroup] = "";
    $repopathes = array();
    $os = determineOS();
    $slash = getSlash($os);
    
    if (! db_set_semaphore(CREATEVIEWVCCONF, 'sem', $dbh)) {
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = _("Can't set semaphore, another process is writing access file, try again later");
        
        return $ret;
    }
    
    $dir = dirname($CONF[VIEWVCCONF]);
    $entropy = rand_name();
    $tempfile = $dir . $slash . "viewvc_conf_temp_" . $entropy;
    $tempgroups = $dir . $slash . "viewvc_groups_temp_" . $entropy;
    
    if ((! $fileHandle = @fopen($tempfile, 'w')) || (! $groupHandle = @fopen($tempgroups, 'w'))) {
        
        $ret = array();
        $ret[ERROR] = 1;
        $ret[ERRORMSG] = sprintf(_("Can't write to %s or to %s"), $tempfile, $tempgroups);
        
        return $ret;
    }
    
    $dir = dirname($CONF[VIEWVCGROUPS]);
    $entropy = rand_name();
    
    $pathSort = getRepoSortPath();
    $query = "SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svn_access_rights.path " . $pathSort . ", svn_access_rights.access_right DESC";
    $result = db_query($query, $dbh);
    
    while ( ($row = db_assoc($result[RESULT])) && ($retcode == 0) ) {
        
        $checkpath = $row[REPO_ID] . $row['path'];
        
        if ($checkpath != $oldpath) {
            
            $currentgroup = "g" . rand_name();
            while ( array_key_exists($currentgroup, $groups) ) {
                $currentgroup = "g" . rand_name();
            }
            
            if (! array_key_exists($checkpath, $repopathes)) {
                
                $data = getUpperDirUsers($checkpath, $repopathes);
                $repopathes[$checkpath] = $data;
            }
            else {
                
                $data = $repopathes[$checkpath];
            }
            
            $groups[$currentgroup] = $data;
            $oldpath = $row[REPO_ID] . $row['path'];
            $modulepath = $CONF[VIEWVCLOCATION] . "/" . $row[REPONAME] . $row['path'];
            
            list($retcode, $tMessage ) = writeApacheAuthConfig($dbh, $fileHandle, $tempfile, $modulepath, $currentgroup);
        }
        
        $ret = writeViewvcAccessUsers($row, $groups, $repopathes, $currentgroup, $checkpath, $dbh, $schema);
        $groups = $ret['groups'];
        $repopathes = $ret['repopathes'];
        
        $ret = writeViewvcAccessGroups($row, $groups, $repopathes, $currentgroup, $checkpath, $dbh, $schema);
        $groups = $ret['groups'];
        $repopathes = $ret['repopathes'];
    }
    
    list($retcode, $tMessage ) = writeViewvcGroups($dbh, $groupHandle, $groups, $tempgroups);
    
    @fclose($groupHandle);
    
    list($retcode, $tMessage ) = writeApacheViewvcLocationMatch($dbh, $fileHandle, $tempfile);
    
    @fclose($fileHandle);
    
    if ($retcode == 0) {
        list($retcode, $tMessage ) = copyViewvcGroupFile($tempgroups, $CONF[VIEWVCGROUPS], 'CREATEVIEWVCCONF', $dbh);
        list($retcode, $tMessage ) = copyViewvcConfFile($tempfile, $CONF[VIEWVCCONF], 'CREATEVIEWVCCONF', $dbh);
    }
    
    if ($retcode == 0) {
        
        if (db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh)) {
            
            $tMessage = _("Viewvc access configuration successfully created!");
        }
        else {
            
            $retcode = 1;
            $tMessage = _("Viewvc access configuration successfully created but semaphore could nor be released");
            db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
        }
    }
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}
?>
