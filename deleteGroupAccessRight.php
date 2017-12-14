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

/*

File:  deleteGroupAccessRight.php
$LastChangedDate$
$LastChangedBy$

$Id$

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
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "deletegroupaccessright";

if( $rightAllowed != "delete" ) {
		
	db_log( $SESSID_USERNAME, "tried to use deleteGroupAccessRight without permission", $dbh );
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$tTask									= db_escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId								= db_escape_string( $_GET['id'] );
		
	} else {

		$tId								= "";

	}
	
	$_SESSION['svn_sessid']['task']			= strtolower( $tTask );
	$_SESSION['svn_sessid']['groupid']		= $tId;
	
	$schema									= db_determine_schema();
	
	if( $_SESSION['svn_sessid']['task'] == "delete" ) {
		
		$query								= "SELECT * " .
											  "  FROM ".$schema."svn_groups_responsible, ".$schema."svngroups " .
											  " WHERE (svn_groups_responsible.id = $tId) " .
											  "   AND (svngroups.id = svn_groups_responsible.group_id)";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_assoc( $result['result'] );
			$tGroup							= $row["groupname"];
			$tDescription					= $row["description"];
			$tRights						= $row['allowed'];
			
		} else {
		
			$tMessage						= _( "Invalid groupid $id requested!" );	
			
		}
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
		
	}
	
	$header									= "groups";
	$subheader								= "groups";
	$menu									= "groups";
	$template								= "deleteGroupAccessRight.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button									= _("Delete");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button									= _("Delete");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} else {
		$button									= "undef";
	}
	
	$schema									= db_determine_schema();
	
	if( $button == _("Delete") ) {
		
		$dbnow								= db_now();
		$query								= "  UPDATE ".$schema."svn_groups_responsible " .
											   "    SET deleted = '$dbnow', " .
											   "        deleted_user = '".$_SESSION['svn_sessid']['username'].
											   "' WHERE id = ".$_SESSION['svn_sessid']['groupid'];
		
		db_ta( 'BEGIN', $dbh );
		db_log( $_SESSION['svn_sessid']['username'], "deleted group responsible user", $dbh );
		
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] >= 0 ) {

			db_ta( 'COMMIT', $dbh );
			$tMessage						= _("Group successfully deleted" );
		
			db_disconnect( $dbh );
		
			header( "Location: list_group_admins.php" );
			exit;
			
		} else {
			
			db_ta( 'ROLLBACK', $dbh );
			$tMessage						= _( "Group responsible not deleted due to database error" );
			
		}
		
	} elseif( $button == _("Back") ) {
		
		db_disconnect( $dbh );
		header( "Location: list_groups.php" );
		exit;
		
	} else {
	
		$tMessage							= _( "Invalid button $button, anyone tampered arround with?" );
			
	}
	
	$header									= "groups";
	$subheader								= "groups";
	$menu									= "groups";
	$template								= "deleteGroupAccessRight.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

db_disconnect( $dbh );
?>
