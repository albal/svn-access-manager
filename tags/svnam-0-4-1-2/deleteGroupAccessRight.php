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

require ("./include/variables.inc.php");
require ("./config/config.inc.php");
require ("./include/functions.inc.php");
require ("./include/output.inc.php");
require ("./include/db-functions.inc.php");

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['user_sort_fields']					= $preferences['user_sort_fields'];
$CONF['user_sort_order']					= $preferences['user_sort_order'];
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "deletegroup";

if( $rightAllowed != "delete" ) {
		
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$tTask									= escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId								= escape_string( $_GET['id'] );
		
	} else {

		$tId								= "";

	}
	
	$_SESSION['svn_sessid']['task']			= strtolower( $tTask );
	$_SESSION['svn_sessid']['groupid']		= $tId;
	
	if( $_SESSION['svn_sessid']['task'] == "delete" ) {
		
		$query								= "SELECT * " .
											  "  FROM svn_groups_responsible, svngroups " .
											  " WHERE (svn_groups_responsible.id = $tId) " .
											  "   AND (svngroups.id = svn_groups_responsible.group_id)";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_array( $result['result'] );
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
	
   	include ("./templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= escape_string( $_POST['fSubmit'] );
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
	
	if( $button == _("Delete") ) {
		
		$query								= "  UPDATE svn_groups_responsible " .
											   "    SET deleted = now(), " .
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
	
   	include ("./templates/framework.tpl");
}

db_disconnect( $dbh );
?>
