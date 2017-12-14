<?php

/*
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


if ( file_exists ( realpath ( "./config/config.inc.php" ) ) ) {
	require( "./config/config.inc.php" );
} elseif( file_exists ( realpath ( "../config/config.inc.php" ) ) ) {
	require( "../config/config.inc.php" );
} elseif( file_exists( "/etc/svn-access-manager/config.inc.php" ) ) {
	require( "/etc/svn-access-manager/config.inc.php" );
} else {
	die( "can't load config.inc.php. Please check your installation!\n" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");



function getLockedUsers( $start, $count, $dbh ) {

	$schema				= db_determine_schema();
	$tLockedUsers		= array();
	$query				= " SELECT * " .
						  "   FROM ".$schema."svnusers " .
						  "  WHERE (deleted = '00000000000000') " .
						  "    AND (locked != 0) " .
						  "ORDER BY userid ASC ";
#						  "   LIMIT $start, $count";
	$result				= db_query( $query, $dbh, $count, $start );
	   	
	while( $row = db_assoc( $result['result']) ) {
	   
		$tLockedUsers[] = $row;
	   		
	}

	return $tLockedUsers;
}

function getCountLockedUsers( $dbh ) {

	global $CONF;
	
	$schema				= db_determine_schema();
	$tUsers				= array();
	$query				= " SELECT COUNT(*) AS anz " .
						  "   FROM ".$schema."svnusers " .
						  "  WHERE (deleted = '00000000000000') " .
						  "    AND (locked != 0) " . 
						  "GROUP BY userid " .
						  "ORDER BY userid";
	$result				= db_query( $query, $dbh );
	   	
	if( $result['rows'] == 1 ) {
		
		$row			= db_assoc( $result['result'] );
		$count			= $row['anz'];
		
		return $count;
		
	} else {
		
		return false;
		
	}
}


initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh										= db_connect();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Reports", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "replockedusers";

if( $rightAllowed == "none" ) {
	
	db_log( $SESSID_USERNAME, "tried to use rep_locked_users without permission", $dbh );
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  
   	
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$_SESSION['svn_sessid']['logcounter']	= 0;
   	$tLockedUsers							= getLockedUsers( 0, -1, $dbh );
   	$tCountRecords							= getCountLockedUsers( $dbh );
   	$tPrevDisabled							= "disabled";
	
	if( $tCountRecords <= $CONF['page_size'] ) {
		
		$tNextDisabled 						= "disabled";
		
	}
	
   	$template								= "rep_locked_users.tpl";
   	$header									= "reports";
   	$subheader								= "reports";
   	$menu									= "reports";
   
   	include ("$installBase/templates/framework.tpl");
   	
   	db_disconnect( $dbh );
 
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	}
 	
   	$template		= "rep_locked_users.tpl";
   	$header			= "reports";
   	$subheader		= "reports";
   	$menu			= "reports";
   
   	include ("$installBase/templates/framework.tpl");
 
 	db_disconnect( $dbh );
}

?>
