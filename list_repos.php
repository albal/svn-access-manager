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
if (file_exists ( realpath ( "./config/config.inc.php" ) )) {
    require ("./config/config.inc.php");
}
elseif (file_exists ( realpath ( "../config/config.inc.php" ) )) {
    require ("../config/config.inc.php");
}
elseif (file_exists ( "/etc/svn-access-manager/config.inc.php" )) {
    require ("/etc/svn-access-manager/config.inc.php");
}
else {
    die ( "can't load config.inc.php. Please check your installation!\n" );
}

$installBase = isset ( $CONF ['install_base'] ) ? $CONF ['install_base'] : "";

require ("$installBase/include/variables.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");
function getRepos($start, $count, $dbh) {

    $schema = db_determine_schema ();
    $tRepos = array ();
    $query = "SELECT   * " . "    FROM " . $schema . "svnrepos " . "   WHERE (deleted = '00000000000000') " . "ORDER BY reponame ASC ";
    $result = db_query ( $query, $dbh, $count, $start );
    
    while ( $row = db_assoc ( $result ['result'] ) ) {
        
        $tRepos [] = $row;
    }
    
    return $tRepos;

}
function getCountRepos($dbh) {

    $schema = db_determine_schema ();
    $tRepos = array ();
    $query = "SELECT   COUNT(*) AS anz " . "    FROM " . $schema . "svnrepos " . "   WHERE (deleted = '00000000000000') ";
    $result = db_query ( $query, $dbh );
    
    if ($result ['rows'] == 1) {
        
        $row = db_assoc ( $result ['result'] );
        $count = $row ['anz'];
        
        return $count;
    }
    else {
        
        return false;
    }

}

initialize_i18n ();

$SESSID_USERNAME = check_session ();
check_password_expired ();
$dbh = db_connect ();
$preferences = db_get_preferences ( $SESSID_USERNAME, $dbh );
$CONF ['page_size'] = $preferences ['page_size'];
$rightAllowed = db_check_acl ( $SESSID_USERNAME, 'Repository admin', $dbh );
$_SESSION ['svn_sessid'] ['helptopic'] = "listrepos";

if ($rightAllowed == "none") {
    
    db_log ( $SESSID_USERNAME, "tried to use list_repos without permission", $dbh );
    db_disconnect ( $dbh );
    header ( "Location: nopermission.php" );
    exit ();
}

if ($_SERVER ['REQUEST_METHOD'] == "GET") {
    
    $_SESSION ['svn_sessid'] ['repocounter'] = 0;
    $tRepos = getRepos ( 0, - 1, $dbh );
    $tCountRecords = getCountRepos ( $dbh );
    $tPrevDisabled = "disabled";
    
    if ($tCountRecords <= $CONF ['page_size']) {
        
        $tNextDisabled = "disabled";
    }
    
    $template = "list_repos.tpl";
    $header = "repos";
    $subheader = "repos";
    $menu = "repos";
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect ( $dbh );
}

if ($_SERVER ['REQUEST_METHOD'] == "POST") {
    
    if (isset ( $_POST ['fSubmit'] )) {
        $button = db_escape_string ( $_POST ['fSubmit'] );
    }
    elseif (isset ( $_POST ['fSubmit_new_x'] )) {
        $button = _ ( "New repository" );
    }
    elseif (isset ( $_POST ['fSubmit_back_x'] )) {
        $button = _ ( "Back" );
    }
    elseif (isset ( $_POST ['fSubmit_new'] )) {
        $button = _ ( "New repository" );
    }
    elseif (isset ( $_POST ['fSubmit_back'] )) {
        $button = _ ( "Back" );
    }
    elseif (isset ( $_POST ['fSearchBtn'] )) {
        $button = _ ( "search" );
    }
    elseif (isset ( $_POST ['fSearchBtn_x'] )) {
        $button = _ ( "search" );
    }
    else {
        $button = "undef";
    }
    
    $tSearch = isset ( $_POST ['fSearch'] ) ? db_escape_string ( $_POST ['fSearch'] ) : "";
    
    if (($button == "search") or ($tSearch != "")) {
        
        $tSearch = html_entity_decode ( $tSearch );
        $_SESSION ['svn_sessid'] ['search'] = $tSearch;
        $_SESSION ['svn_sessid'] ['searchtype'] = "repos";
        $tRepos = array ();
        
        if ($tSearch == "") {
            
            $tErrorClass = "error";
            $tMessage = _ ( "No search string given!" );
        }
        else {
            
            $tArray = array ();
            $schema = db_determine_schema ();
            $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE ((repouser like '%$tSearch%') " . "    OR (reponame like '%$tSearch%')) " . "   AND (deleted = '00000000000000') " . "ORDER BY reponame ASC";
            $result = db_query ( $query, $dbh );
            while ( $row = db_assoc ( $result ['result'] ) ) {
                
                $tArray [] = $row;
            }
            
            if (count ( $tArray ) == 0) {
                
                $tErrorClass = "info";
                $tMessage = _ ( "No user found!" );
            }
            elseif (count ( $tArray ) == 1) {
                
                $id = $tArray [0] ['id'];
                $url = "workOnRepo.php?id=" . urlencode ( $id ) . "&task=change";
                db_disconnect ( $dbh );
                header ( "location: $url" );
                exit ();
            }
            else {
                
                db_disconnect ( $dbh );
                $_SESSION ['svn_sessid'] ['searchresult'] = $tArray;
                header ( "location: searchresult.php" );
                exit ();
            }
        }
    }
    elseif ($button == _ ( "New repository" )) {
        
        db_disconnect ( $dbh );
        header ( "Location: workOnRepo.php?task=new" );
        exit ();
    }
    elseif ($button == _ ( "Back" )) {
        
        db_disconnect ( $dbh );
        header ( "Location: main.php" );
        exit ();
    }
    else {
        
        $tMessage = sprintf ( _ ( "Invalid button %s, anyone tampered arround with?" ), $button );
    }
    
    $template = "list_repos.tpl";
    $header = "repos";
    $subheader = "repos";
    $menu = "repos";
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect ( $dbh );
}
?>
