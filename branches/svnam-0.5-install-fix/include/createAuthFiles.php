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
function createAuthUserFile($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    if ((isset($CONF[SEPARATEFILESPERREPO])) and ($CONF[SEPARATEFILESPERREPO] == "YES")) {
        
        $ret = createAuthUserFilePerRepo($dbh);
    }
    else {
        
        $retcode = 0;
        $tMessage = "";
        $dir = dirname($CONF[AUTHUSERFILE]);
        $entropy = rand_name();
        $os = determineOS();
        $slash = ($os == WINDOWS) ? "\\" : "/";
        $tempfile = $dir . $slash . "authtemp_" . $entropy;
        
        if ($CONF['createUserFile'] == "YES") {
            
            if (db_set_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
                
                if ($fileHandle = @fopen($tempfile, 'w')) {
                    
                    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (locked = '0') " . "ORDER BY userid";
                    $result = db_query($query, $dbh);
                    
                    while ( $row = db_assoc($result[RESULT]) ) {
                        
                        if (! @fwrite($fileHandle, $row[USERID] . ":" . $row['password'] . "\n")) {
                            
                            $retcode = 1;
                            $tMessage = _("Can't write to AuthUser file");
                            db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                        }
                    }
                    
                    @fclose($fileHandle);
                    
                    if ($retcode == 0) {
                        
                        if (($os == WINDOWS) and file_exists($CONF[AUTHUSERFILE])) {
                            unlink($CONF[AUTHUSERFILE]);
                        }
                        
                        if (@rename($tempfile, $CONF[AUTHUSERFILE])) {
                            
                            // if( @unlink( $tempfile ) ) {
                            
                            if (db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
                                
                                $tMessage = _("Auth user file successfully created!");
                            }
                            else {
                                
                                $retcode = 1;
                                $tMessage = _("Auth user file created but semaphore could not be released");
                                db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                            }
                            
                            // } else {
                            
                            // $retcode = 4;
                            // $tMessage = sprintf( _("Delete of %s failed!"), $tempfile );
                            // db_unset_semaphore( CREATEAUTHUSERFILE, 'sem', $dbh );
                            // }
                        }
                        else {
                            
                            $retcode = 3;
                            $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[AUTHUSERFILE]);
                            db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                        }
                    }
                }
                else {
                    
                    $retcode = 2;
                    $tMessage = sprintf(_("Cannot open file %s for writing!"), $tempfile);
                    db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                }
            }
            else {
                
                $retcode = 1;
                $tMessage = _("Can't set semaphore, another process is writing Auth User File, try again later");
            }
        }
        else {
            
            $retcode = 0;
            $tMessage = _("Create of auth user file not configured!");
        }
        
        $ret = array();
        $ret[ERROR] = $retcode;
        $ret[ERRORMSG] = $tMessage;
    }
    
    return $ret;

}

function createAuthUserFilePerRepo($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $retcode = 0;
    $tMessage = "";
    $dir = dirname($CONF[AUTHUSERFILE]);
    $entropy = rand_name();
    $os = determineOS();
    $slash = ($os == WINDOWS) ? "\\" : "/";
    $tempfile = $dir . $slash . "authtemp_" . $entropy;
    $curdate = strftime("%Y%m%d");
    
    if ($CONF['createUserFile'] == "YES") {
        
        if (db_set_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
            
            $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (deleted = '00000000000000')";
            $resultrepos = db_query($query, $dbh);
            while ( $row = db_assoc($resultrepos[RESULT]) ) {
                
                $repoid = $row['id'];
                $authuserfile = $row['auth_user_file'];
                $reponame = $row[REPONAME];
                if ($authuserfile == "") {
                    $authuserfile = dirname($CONF[AUTHUSERFILE]) . "/svn-passwd." . $reponame;
                }
                
                if ($fileHandle = @fopen($tempfile, 'w')) {
                    
                    $query = "SELECT DISTINCT svnusers.userid, svnusers.password " . "  FROM " . $schema . "svnusers, " . $schema . "svn_access_rights, " . $schema . "svnrepos, " . $schema . "svnprojects, " . $schema . "svn_users_groups" . " WHERE (svnprojects.repo_id=$repoid) " . "   AND (svn_access_rights.project_id = svnprojects.id) " . "   AND (svnrepos.deleted = '00000000000000') " . "   AND (svn_access_rights.deleted = '00000000000000') " . "   AND (svn_access_rights.valid_from <= '$curdate') " . "   AND (svn_access_rights.valid_until >= '$curdate') " . "   AND (svnprojects.deleted = '00000000000000') " . "   AND (svnusers.locked = '0') " . "   AND (" . "    (svnusers.id = svn_access_rights.user_id) OR ( " . "     (svn_users_groups.user_id = svnusers.id)" . "     AND (svn_users_groups.group_id = svn_access_rights.group_id)" . "     AND (svn_users_groups.deleted =  '00000000000000')" . "    ))" . "ORDER BY svnusers.userid";
                    
                    $result = db_query($query, $dbh);
                    
                    while ( $row = db_assoc($result[RESULT]) ) {
                        
                        if (! @fwrite($fileHandle, $row[USERID] . ":" . $row['password'] . "\n")) {
                            
                            $retcode = 1;
                            $tMessage = _("Can't write to AuthUser file");
                            db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                        }
                    }
                    
                    @fclose($fileHandle);
                    
                    if ($retcode == 0) {
                        
                        if (($os == WINDOWS) and file_exists($authuserfile)) {
                            unlink($authuserfile);
                        }
                        
                        if (! @rename($tempfile, $authuserfile)) {
                            $retcode = 3;
                            $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[AUTHUSERFILE]);
                            db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                        }
                    }
                    
                    // if( @unlink( $tempfile ) ) {
                    
                    // } else {
                    
                    // $retcode = 4;
                    // $tMessage = sprintf( _("Delete of %s failed!"), $tempfile );
                    // db_unset_semaphore( CREATEAUTHUSERFILE, 'sem', $dbh );
                    // }
                }
                else {
                    
                    $retcode = 2;
                    $tMessage = sprintf(_("Cannot open file %s for writing!"), $tempfile);
                    db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                }
            }
            
            if ($retcode == 0) {
                if (db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh)) {
                    
                    $tMessage = _("Auth user file successfully created!");
                }
                else {
                    
                    $retcode = 1;
                    $tMessage = _("Auth user file created but semaphore could not be released");
                    db_unset_semaphore(CREATEAUTHUSERFILE, 'sem', $dbh);
                }
            }
        }
        else {
            
            $retcode = 1;
            $tMessage = _("Can't set semaphore, another process is writing Auth User File, try again later");
        }
    }
    else {
        
        $retcode = 0;
        $tMessage = _("Create of auth user file not configured!");
    }
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;

}

function createAccessFile($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    if ((isset($CONF[SEPARATEFILESPERREPO])) and ($CONF[SEPARATEFILESPERREPO] == "YES")) {
        
        $ret = createAccessFilePerRepo($dbh);
    }
    else {
        
        $retcode = 0;
        $tMessage = "";
        $curdate = strftime("%Y%m%d");
        $oldpath = "";
        
        if ($CONF['createAccessFile'] == "YES") {
            
            if (db_set_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
                
                $dir = dirname($CONF[SVNACCESSFILE]);
                $entropy = rand_name();
                $os = determineOS();
                $slash = ($os == WINDOWS) ? "\\" : "/";
                $tempfile = $dir . $slash . "accesstemp_" . $entropy;
                
                if ($fileHandle = @fopen($tempfile, 'w')) {
                    
                    $groupwritten = 0;
                    
                    if ($retcode == 0) {
                        
                        // write groups to file
                        $query = "  SELECT svngroups.groupname, svnusers.userid " . "    FROM " . $schema . "svngroups, " . $schema . "svnusers, " . $schema . "svn_users_groups " . "   WHERE (svngroups.deleted = '00000000000000') " . "     AND (svn_users_groups.user_id = svnusers.id) " . "     AND (svn_users_groups.group_id = svngroups.id) " . "     AND (svnusers.deleted = '00000000000000') " . "     AND (svn_users_groups.deleted = '00000000000000') " . "ORDER BY svngroups.groupname ASC, svnusers.userid ASC";
                        $result = db_query($query, $dbh);
                        $oldgroup = "";
                        $users = "";
                        
                        while ( ($row = db_assoc($result[RESULT])) and ($retcode == 0) ) {
                            
                            if ($oldgroup != $row[GROUPNAME]) {
                                
                                if ($users != "") {
                                    
                                    if ($groupwritten == 0) {
                                        
                                        $groupwritten = 1;
                                        if (! @fwrite($fileHandle, "[groups]\n")) {
                                            
                                            $retcode = 1;
                                            $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                        }
                                    }
                                    
                                    if (! @fwrite($fileHandle, $oldgroup . " = " . $users . "\n")) {
                                        
                                        $retcode = 1;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    }
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
                                
                                $groupwritten = 1;
                                if (! @fwrite($fileHandle, "[groups]\n")) {
                                    
                                    $retcode = 1;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                }
                            }
                            
                            fwrite($fileHandle, $oldgroup . " = " . $users . "\n");
                        }
                    }
                    
                    if ($retcode == 0) {
                        
                        $first = 1;
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (superadmin = 1) " . "   AND (deleted = '00000000000000')";
                        $resultusr = db_query($query, $dbh);
                        while ( $rowusr = db_assoc($resultusr[RESULT]) ) {
                            
                            if ($first == 1) {
                                
                                $first = 0;
                                
                                // write superuser privileges for access to all repositories by http(s)
                                if (! @fwrite($fileHandle, "\n[/]\n")) {
                                    
                                    $retcode = 8;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                }
                            }
                            
                            if (! @fwrite($fileHandle, $rowusr[USERID] . " = r\n")) {
                                
                                $retcode = 5;
                                db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                            }
                            
                            if (isset($CONF[WRITEANONYMOUSACCESSRIGHTS]) and ($CONF[WRITEANONYMOUSACCESSRIGHTS] == 1)) {
                                
                                if (! @fwrite($fileHandle, "* = r\n")) {
                                    
                                    $retcode = 5;
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                }
                            }
                        }
                    }
                    
                    if ($retcode == 0) {
                        
                        // write access rights to file
                        if (isset($CONF[REPOPATHSORTORDER])) {
                            $pathSort = $CONF[REPOPATHSORTORDER];
                        }
                        else {
                            $pathSort = "ASC";
                        }
                        $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svn_access_rights.path " . $pathSort . ", access_right DESC";
                        // error_log( $query );
                        $result = db_query($query, $dbh);
                        
                        while ( ($row = db_assoc($result[RESULT])) and ($retcode == 0) ) {
                            
                            // Right 'none' handled in else branch
                            if ($row[ACCESS_RIGHT] == "read") {
                                
                                $right = "r";
                            }
                            elseif ($row[ACCESS_RIGHT] == "write") {
                                
                                $right = "rw";
                            }
                            else {
                                
                                $right = "";
                            }
                            
                            $checkpath = $row[REPO_ID] . $row['path'];
                            if ($checkpath != $oldpath) {
                                
                                $oldpath = $row[REPO_ID] . $row['path'];
                                $tPath = preg_replace('/\/$/', '', $row['path']);
                                if ($tPath == "") {
                                    $tPath = "/";
                                }
                                $repoName = $row[REPONAME] . ":";
                                if ($repoName == "/:") {
                                    $repoName = "";
                                }
                                if (! @fwrite($fileHandle, "\n[" . $repoName . $tPath . "]\n")) {
                                    
                                    $retcode = 4;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                }
                            }
                            
                            if (($row[USER_ID] != "0") and (! empty($row[USER_ID]))) {
                                
                                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = " . $row[USER_ID] . ")";
                                $resultusr = db_query($query, $dbh);
                                
                                if ($resultusr['rows'] == 1) {
                                    
                                    $rowusr = db_assoc($resultusr[RESULT]);
                                    if (! @fwrite($fileHandle, $rowusr[USERID] . " = " . $right . "\n")) {
                                        
                                        $retcode = 5;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    }
                                }
                            }
                            
                            if (($row[GROUP_ID] != "0") and (! empty($row[GROUP_ID]))) {
                                
                                $query = "  SELECT * " . "    FROM " . $schema . "svngroups " . "   WHERE (id = " . $row[GROUP_ID] . ")";
                                $resultgrp = db_query($query, $dbh);
                                
                                if ($resultgrp['rows'] == 1) {
                                    
                                    $rowgrp = db_assoc($resultgrp[RESULT]);
                                    if (! @fwrite($fileHandle, "@" . $rowgrp[GROUPNAME] . " = " . $right . "\n")) {
                                        
                                        $retcode = 6;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    }
                                }
                            }
                        }
                        
                        if (! @fwrite($fileHandle, "\n")) {
                            
                            $retcode = 7;
                            $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                        }
                        
                        @fclose($fileHandle);
                        
                        if (($os == WINDOWS) and file_exists($CONF[SVNACCESSFILE])) {
                            unlink($CONF[SVNACCESSFILE]);
                        }
                        
                        if (@rename($tempfile, $CONF[SVNACCESSFILE])) {
                            
                            // if( @unlink( $tempfile ) ) {
                            
                            if (db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
                                
                                $tMessage = _("Access file successfully created!");
                            }
                            else {
                                
                                $retcode = 1;
                                $tMessage = _("Access file successfully created but semaphore could nor be released");
                                db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                            }
                            
                            // } else {
                            
                            // $retcode = 4;
                            // $tMessage = sprintf( _("Delete of %s failed!"), $tempfile );
                            // db_unset_semaphore( CREATEACCESSFILE, 'sem', $dbh );
                            // }
                        }
                        else {
                            
                            $retcode = 3;
                            $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[SVNACCESSFILE]);
                            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                        }
                    }
                }
                else {
                    
                    $retcode = 1;
                    $tMessage = sprintf(_("Cannot open %s for wrtiting"), $tempfile);
                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                }
            }
            else {
                
                $retcode = 1;
                $tMessage = _("Can't set semaphore, another process is writing access file, try again later");
            }
        }
        else {
            
            $retcode = 0;
            $tMessage = _("Create of access file not configured!");
        }
        
        $ret = array();
        $ret[ERROR] = $retcode;
        $ret[ERRORMSG] = $tMessage;
    }
    
    return $ret;

}

function createAccessFilePerRepo($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $retcode = 0;
    $tMessage = "";
    $curdate = strftime("%Y%m%d");
    $oldpath = "";
    
    if ($CONF['createAccessFile'] == "YES") {
        
        if (db_set_semaphore(CREATEACCESSFILE, 'sem', $dbh)) {
            
            $dir = dirname($CONF[SVNACCESSFILE]);
            $entropy = rand_name();
            $os = determineOS();
            $slash = ($os == WINDOWS) ? "\\" : "/";
            $tempfile = $dir . $slash . "accesstemp_" . $entropy;
            
            $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (deleted = '00000000000000')";
            $resultrepos = db_query($query, $dbh);
            while ( $row = db_assoc($resultrepos[RESULT]) ) {
                
                $repoid = $row['id'];
                $authuserfile = $row['auth_user_file'];
                $svnaccessfile = $row['svn_access_file'];
                $reponame = $row[REPONAME];
                if ($svnaccessfile == "") {
                    $svnaccessfile = dirname($CONF[SVNACCESSFILE]) . "/svn-access." . $reponame;
                }
                
                if ($fileHandle = @fopen($tempfile, 'w')) {
                    
                    $groupwritten = 0;
                    
                    if ($retcode == 0) {
                        
                        // write groups to file
                        $query = "  SELECT svngroups.groupname, svnusers.userid " . "    FROM " . $schema . "svngroups, " . $schema . "svnusers, " . $schema . "svn_users_groups, " . $schema . "svnprojects, " . $schema . "svn_access_rights, " . $schema . "svnrepos " . "   WHERE (svn_users_groups.user_id = svnusers.id) " . "     AND (svn_users_groups.group_id = svngroups.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "     AND (svnprojects.repo_id=$repoid) " . "     AND (svnprojects.id = svn_access_rights.project_id) " . "     AND (svn_access_rights.group_id=svngroups.id) " . "     AND (svn_access_rights.group_id != 0) " . "     AND (svn_users_groups.deleted='00000000000000') " . "     AND (svn_access_rights.deleted='00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svnprojects.deleted='00000000000000') " . "     AND (svngroups.deleted='00000000000000') " . "     AND (svnrepos.deleted='00000000000000') " . "     AND (svnusers.deleted='00000000000000') " . "ORDER BY svngroups.groupname ASC, svnusers.userid ASC";
                        $result = db_query($query, $dbh);
                        $oldgroup = "";
                        $users = "";
                        
                        while ( ($row = db_assoc($result[RESULT])) and ($retcode == 0) ) {
                            
                            if ($oldgroup != $row[GROUPNAME]) {
                                
                                if ($users != "") {
                                    
                                    $groupwritten = 1;
                                    
                                    if (! @fwrite($fileHandle, "[groups]\n")) {
                                        
                                        $retcode = 1;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        if ($groupwritten == 0) {
                                            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                        }
                                    }
                                    
                                    if (! @fwrite($fileHandle, $oldgroup . " = " . $users . "\n")) {
                                        
                                        $retcode = 1;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    }
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
                                
                                $groupwritten = 1;
                                
                                if (! @fwrite($fileHandle, "[groups]\n")) {
                                    
                                    $retcode = 1;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                }
                            }
                            
                            fwrite($fileHandle, $oldgroup . " = " . $users . "\n");
                        }
                    }
                    
                    if ($retcode == 0) {
                        
                        $first = 1;
                        $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (superadmin = 1) " . "   AND (deleted = '00000000000000')";
                        $resultusr = db_query($query, $dbh);
                        while ( $rowusr = db_assoc($resultusr[RESULT]) ) {
                            
                            if ($first == 1) {
                                
                                $first = 0;
                                
                                // write superuser privileges for access to all repositories by http(s)
                                if (! @fwrite($fileHandle, "\n[/]\n")) {
                                    
                                    $retcode = 8;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                }
                            }
                            
                            if (! @fwrite($fileHandle, $rowusr[USERID] . " = r\n")) {
                                
                                $retcode = 5;
                                $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                            }
                            
                            if (isset($CONF[WRITEANONYMOUSACCESSRIGHTS]) and ($CONF[WRITEANONYMOUSACCESSRIGHTS] == 1)) {
                                
                                if (! @fwrite($fileHandle, "* = r\n")) {
                                    
                                    $retcode = 5;
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                }
                            }
                        }
                    }
                    
                    if ($retcode == 0) {
                        
                        // write access rights to file
                        if (isset($CONF[REPOPATHSORTORDER])) {
                            $pathSort = $CONF[REPOPATHSORTORDER];
                        }
                        else {
                            $pathSort = "ASC";
                        }
                        $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "     AND (svnprojects.repo_id=$repoid) " . "     AND (svnprojects.deleted='00000000000000') " . "     AND (svnrepos.deleted='00000000000000') " . "ORDER BY svnrepos.reponame ASC, svn_access_rights.path " . $pathSort . ", access_right DESC";
                        $result = db_query($query, $dbh);
                        
                        while ( ($row = db_assoc($result[RESULT])) and ($retcode == 0) ) {
                            
                            // Right 'none' handled in else branch
                            if ($row[ACCESS_RIGHT] == "read") {
                                
                                $right = "r";
                            }
                            elseif ($row[ACCESS_RIGHT] == "write") {
                                
                                $right = "rw";
                            }
                            else {
                                
                                $right = "";
                            }
                            
                            $checkpath = $row[REPO_ID] . $row['path'];
                            if ($checkpath != $oldpath) {
                                
                                $oldpath = $row[REPO_ID] . $row['path'];
                                $tPath = preg_replace('/\/$/', '', $row['path']);
                                if ($tPath == "") {
                                    $tPath = "/";
                                }
                                if (! @fwrite($fileHandle, "\n[" . $row[REPONAME] . ":" . $tPath . "]\n")) {
                                    
                                    $retcode = 4;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                }
                            }
                            
                            if (($row[USER_ID] != "0") and (! empty($row[USER_ID]))) {
                                
                                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = " . $row[USER_ID] . ")";
                                $resultusr = db_query($query, $dbh);
                                
                                if ($resultusr['rows'] == 1) {
                                    
                                    $rowusr = db_assoc($resultusr[RESULT]);
                                    if (! @fwrite($fileHandle, $rowusr[USERID] . " = " . $right . "\n")) {
                                        
                                        $retcode = 5;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    }
                                }
                            }
                            
                            if (($row[GROUP_ID] != "0") and (! empty($row[GROUP_ID]))) {
                                
                                $query = "  SELECT * " . "    FROM " . $schema . "svngroups " . "   WHERE (id = " . $row[GROUP_ID] . ")";
                                $resultgrp = db_query($query, $dbh);
                                
                                if ($resultgrp['rows'] == 1) {
                                    
                                    $rowgrp = db_assoc($resultgrp[RESULT]);
                                    if (! @fwrite($fileHandle, "@" . $rowgrp[GROUPNAME] . " = " . $right . "\n")) {
                                        
                                        $retcode = 6;
                                        $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                        db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                                    }
                                }
                            }
                        }
                        
                        if (! @fwrite($fileHandle, "\n")) {
                            
                            $retcode = 7;
                            $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                        }
                        
                        @fclose($fileHandle);
                        
                        if (($os == WINDOWS) and file_exists($svnaccessfile)) {
                            unlink($svnaccessfile);
                        }
                        
                        if (! @rename($tempfile, $svnaccessfile)) {
                            $retcode = 3;
                            $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[SVNACCESSFILE]);
                            db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                        }
                    }
                    
                    // if( @unlink( $tempfile ) ) {
                    
                    // } else {
                    
                    // $retcode = 4;
                    // $tMessage = sprintf( _("Delete of %s failed!"), $tempfile );
                    // db_unset_semaphore( CREATEACCESSFILE, 'sem', $dbh );
                    // }
                }
                else {
                    
                    $retcode = 1;
                    $tMessage = sprintf(_("Cannot open %s for wrtiting"), $tempfile);
                    db_unset_semaphore(CREATEACCESSFILE, 'sem', $dbh);
                }
            } // end iteration over repos
            
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
            
            $retcode = 1;
            $tMessage = _("Can't set semaphore, another process is writing access file, try again later");
        }
    }
    else {
        
        $retcode = 0;
        $tMessage = _("Create of access file not configured!");
    }
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;

}

function getGroupMembers($groupid, $dbh) {

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

function deleteUser($members, $userid) {

    $new = array();
    
    for($i = 0; $i < count($members); $i ++) {
        
        if ($members[$i] != $userid) {
            
            $new[] = $members[$i];
        }
    }
    
    return $new;

}

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

function createViewvcConfig($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $retcode = 0;
    $tMessage = "";
    $curdate = strftime("%Y%m%d");
    $oldpath = "";
    $oldgroup = "";
    $modulepath = "";
    $currentgroup = "g" . rand_name();
    $groups[$currentgroup] = "";
    $repopathes = array();
    
    if ($CONF['createViewvcConf'] == "YES") {
        
        if (db_set_semaphore(CREATEVIEWVCCONF, 'sem', $dbh)) {
            
            $dir = dirname($CONF[VIEWVCCONF]);
            $entropy = rand_name();
            $os = determineOS();
            $slash = ($os == WINDOWS) ? "\\" : "/";
            $tempfile = $dir . $slash . "viewvc_conf_temp_" . $entropy;
            
            if ($fileHandle = @fopen($tempfile, 'w')) {
                
                $dir = dirname($CONF[VIEWVCGROUPS]);
                $entropy = rand_name();
                $os = determineOS();
                $slash = ($os == WINDOWS) ? "\\" : "/";
                $tempgroups = $dir . $slash . "viewvc_groups_temp_" . $entropy;
                
                if ($groupHandle = @fopen($tempgroups, 'w')) {
                    
                    if (isset($CONF[REPOPATHSORTORDER])) {
                        $pathSort = $CONF[REPOPATHSORTORDER];
                    }
                    else {
                        $pathSort = "ASC";
                    }
                    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svn_access_rights.path " . $pathSort . ", svn_access_rights.access_right DESC";
                    
                    $result = db_query($query, $dbh);
                    
                    while ( ($row = db_assoc($result[RESULT])) and ($retcode == 0) ) {
                        
                        $checkpath = $row[REPO_ID] . $row['path'];
                        
                        if ($checkpath != $oldpath) {
                            
                            $oldgroup = $currentgroup;
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
                            
                            if (! @fwrite($fileHandle, "<Location $modulepath>\n")) {
                                $retcode = 9;
                                $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                            }
                            
                            if ($retcode == 0) {
                                if (! @fwrite($fileHandle, "     AuthType Basic\n")) {
                                    $retcode = 9;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                }
                            }
                            
                            if ($retcode == 0) {
                                if (! @fwrite($fileHandle, "     AuthName \"Viewvc Access Control\"\n")) {
                                    $retcode = 9;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                }
                            }
                            
                            if ($retcode == 0) {
                                if (! @fwrite($fileHandle, "     AuthUserFile " . $CONF[AUTHUSERFILE] . "\n")) {
                                    $retcode = 9;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                }
                            }
                            
                            if ($retcode == 0) {
                                if (! @fwrite($fileHandle, "     AuthGroupFile " . $CONF[VIEWVCGROUPS] . "\n")) {
                                    $retcode = 9;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                }
                            }
                            
                            if ($retcode == 0) {
                                if (! @fwrite($fileHandle, "     Require group $currentgroup\n")) {
                                    $retcode = 9;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                }
                            }
                            
                            if ($retcode == 0) {
                                if (! @fwrite($fileHandle, "</Location>\n\n")) {
                                    $retcode = 9;
                                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                }
                            }
                        }
                        
                        if ($row[ACCESS_RIGHT] != "none") {
                            
                            if (($row[USER_ID] != "0") and (! empty($row[USER_ID]))) {
                                
                                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = " . $row[USER_ID] . ")";
                                $resultusr = db_query($query, $dbh);
                                
                                if ($resultusr['rows'] == 1) {
                                    
                                    // add user to apache access group
                                    $rowusr = db_assoc($resultusr[RESULT]);
                                    
                                    if (! in_array($rowusr[USERID], $groups[$currentgroup])) {
                                        
                                        $groups[$currentgroup][] = $rowusr[USERID];
                                        $repopathes[$checkpath][] = $rowusr[USERID];
                                    }
                                }
                            }
                            
                            if (($row[GROUP_ID] != "0") and (! empty($row[GROUP_ID]))) {
                                
                                $query = "  SELECT * " . "    FROM " . $schema . "svngroups " . "   WHERE (id = " . $row[GROUP_ID] . ")";
                                $resultgrp = db_query($query, $dbh);
                                
                                if ($resultgrp['rows'] == 1) {
                                    
                                    // get group members
                                    $rowgrp = db_assoc($resultgrp[RESULT]);
                                    $groupid = $rowgrp['id'];
                                    $members = getGroupMembers($groupid, $dbh);
                                    
                                    foreach( $members as $member) {
                                        
                                        if (! in_array($member, $groups[$currentgroup])) {
                                            
                                            $groups[$currentgroup][] = $member;
                                            $repopathes[$checkpath][] = $member;
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            
                            if (($row[USER_ID] != "0") and (! empty($row[USER_ID]))) {
                                
                                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = " . $row[USER_ID] . ")";
                                $resultusr = db_query($query, $dbh);
                                
                                if ($resultusr['rows'] == 1) {
                                    
                                    // delete user from apache access group
                                    $rowusr = db_assoc($resultusr[RESULT]);
                                    
                                    if (in_array($rowusr[USERID], $groups[$currentgroup])) {
                                        
                                        $groups[$currentgroup] = deleteUser($groups[$currentgroup], $rowusr[USERID]);
                                        $repopathes[$checkpath] = deleteUser($repopathes[$checkpath], $rowusr[USERID]);
                                    }
                                }
                            }
                            
                            if (($row[GROUP_ID] != "0") and (! empty($row[GROUP_ID]))) {
                                
                                $query = "  SELECT * " . "    FROM " . $schema . "svngroups " . "   WHERE (id = " . $row[GROUP_ID] . ")";
                                $resultgrp = db_query($query, $dbh);
                                
                                if ($resultgrp['rows'] == 1) {
                                    
                                    // get group members
                                    $rowgrp = db_assoc($resultgrp[RESULT]);
                                    $groupid = $rowgrp['id'];
                                    $members = getGroupMembers($groupid, $dbh);
                                    
                                    foreach( $members as $member) {
                                        
                                        if (in_array($member, $groups[$currentgroup])) {
                                            
                                            $groups[$currentgroup] = deleteUser($groups[$currentgroup], $member);
                                            $repopathes[$checkpath] = deleteUser($repopathes[$checkpath], $member);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    foreach( $groups as $group => $members) {
                        
                        if (count($members) != 0) {
                            
                            if (! fwrite($groupHandle, $group . ":")) {
                                
                                $retcode = 10;
                                $tMessage = sprintf(_("Cannot write to %s"), $tempgroups);
                                db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                            }
                            else {
                                
                                if (is_array($members) and ! empty($members)) {
                                    for($i = 0; $i < count($members); $i ++) {
                                        if (! fwrite($groupHandle, $members[$i] . " ")) {
                                            $retcode = 10;
                                            $tMessage = sprintf(_("Cannot write to %s"), $tempgroups);
                                            db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                                        }
                                    }
                                }
                            }
                            
                            if (! fwrite($groupHandle, "\n")) {
                                
                                $retcode = 10;
                                $tMessage = sprintf(_("Cannot write to %s"), $tempgroups);
                                db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                            }
                        }
                    }
                    
                    @fclose($groupHandle);
                }
                
                if (! @fwrite($fileHandle, "<LocationMatch (^" . $CONF[VIEWVCLOCATION] . "\$|^" . $CONF[VIEWVCLOCATION] . "/\$)>\n")) {
                    $retcode = 9;
                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
                
                if (! @fwrite($fileHandle, "      AuthType Basic\n")) {
                    $retcode = 9;
                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
                
                if (! @fwrite($fileHandle, "      AuthName \"Viewvc Access Control\"\n")) {
                    $retcode = 9;
                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
                
                if (! @fwrite($fileHandle, "      AuthUserFile /etc/svn/svn-passwd\n")) {
                    $retcode = 9;
                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
                
                if (! @fwrite($fileHandle, "      Require valid-user\n")) {
                    $retcode = 9;
                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
                
                if (! @fwrite($fileHandle, "</LocationMatch>\n")) {
                    $retcode = 9;
                    $tMessage = sprintf(_("Cannot write to %s"), $tempfile);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
                
                @fclose($fileHandle);
            }
            
            if ($retcode == 0) {
                
                if (($os == WINDOWS) and file_exists($CONF[VIEWVCGROUPS])) {
                    unlink($CONF[VIEWVCGROUPS]);
                }
                
                if (@rename($tempgroups, $CONF[VIEWVCGROUPS])) {
                    
                    // if( @unlink( $tempgroups ) ) {
                    
                    if (($os == WINDOWS) and file_exists($CONF[VIEWVCCONF])) {
                        unlink($CONF[VIEWVCCONF]);
                    }
                    
                    if (@rename($tempfile, $CONF[VIEWVCCONF])) {
                        
                        // if( @unlink( $tempfile ) ) {
                        
                        if (db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh)) {
                            
                            $tMessage = _("Viewvc access configuration successfully created!");
                        }
                        else {
                            
                            $retcode = 1;
                            $tMessage = _("Viewvc access configuration successfully created but semaphore could nor be released");
                            db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                        }
                        
                        // } else {
                        
                        // $retcode = 4;
                        // $tMessage = sprintf( _("Delete of %s failed!"), $tempfile );
                        // db_unset_semaphore( 'ccreateviewvcconf', 'sem', $dbh );
                        // }
                    }
                    else {
                        
                        $retcode = 3;
                        $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempgroups, $CONF[VIEWVCGROUPS]);
                        db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                    }
                    
                    // } else {
                    
                    // $retcode = 4;
                    // $tMessage = sprintf( _("Delete of %s failed!"), $tempgroups );
                    // db_unset_semaphore( 'ccreateviewvcconf', 'sem', $dbh );
                    // }
                }
                else {
                    
                    $retcode = 3;
                    $tMessage = sprintf(_("Copy from %s to %s failed!"), $tempfile, $CONF[VIEWVCGROUPS]);
                    db_unset_semaphore(CREATEVIEWVCCONF, 'sem', $dbh);
                }
            }
        }
        else {
            
            $retcode = 1;
            $tMessage = _("Can't set semaphore, another process is writing access file, try again later");
        }
    }
    else {
        
        $retcode = 0;
        $tMessage = _("Create of access file not configured!");
    }
    
    $ret = array();
    $ret[ERROR] = $retcode;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;

}
?>
