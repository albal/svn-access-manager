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
	die( "can't load config.inc.php. Check your installation!\n'" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
#require ("./config/config.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "User admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "deleteuser";
$schema										= db_determine_schema();

if( $rightAllowed != "delete" ) {
	
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
	$_SESSION['svn_sessid']['userid']		= $tId;
	
	if( $_SESSION['svn_sessid']['task'] == "delete" ) {
		
		$query								= "SELECT * " .
											  "  FROM ".$schema."svnusers " .
											  " WHERE id = $tId";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_assoc( $result['result'] );
			$tUserid						= $row['userid'];
			$tName							= $row['name'];
			$tGivenname						= $row['givenname'];
			$tEmail							= $row['emailaddress'];
			$tPasswordExpires				= $row['passwordexpires'];
			$tLocked						= $row['locked'];
			$tAdministrator					= $row['admin'];
			
		} else {
		
			$tMessage						= _( "Invalid userid $id requested!" );	
			
		}
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
		
	}
	
	$header									= "users";
	$subheader								= "users";
	$menu									= "users";
	$template								= "deleteUser.tpl";
	
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
	
	if( $button == _("Delete") ) {
		
		$userid								= db_getUseridById( $_SESSION['svn_sessid']['userid'], $dbh );
		$error								= 0;
		$dbnow								= db_now();
		$query								= "UPDATE ".$schema."svnusers " .
											  "   SET deleted = '$dbnow', " .
											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE id = ".$_SESSION['svn_sessid']['userid'].
											  "   AND (deleted = '00000000000000')";
		
		db_ta( 'BEGIN', $dbh );
		db_log( $_SESSION['svn_sessid']['username'], "deleted user $userid", $dbh );
		
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
		
				$dbnow						= db_now();
				$query						= "UPDATE ".$schema."svn_access_rights " .
											  "   SET deleted = '$dbnow', " .
											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE (user_id = ".$_SESSION['svn_sessid']['userid'].") " .
											  "   AND (deleted = '00000000000000')";
				$result						= db_query( $query, $dbh );
				
				if( $result['rows'] < 0 ) {
					
					$error					= 1;
				}
		}
		
		if( $error == 0 ) {
		
				$dbnow						= db_now();
				$query						= "UPDATE ".$schema."svn_projects_responsible " .
											  "   SET deleted = '$dbnow', " .
											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE user_id = ".$_SESSION['svn_sessid']['userid'].
											  "   AND (deleted = '00000000000000')";
				$result						= db_query( $query, $dbh );
				
				if( $result['rows'] < 0 ) {
					
					$error					= 1;
				}
		}
		
		if( $error == 0 ) {
		
				$dbnow						= db_now();
				$query						= "UPDATE ".$schema."svn_users_groups " .
											  "   SET deleted = '$dbnow', " .
											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE user_id = ".$_SESSION['svn_sessid']['userid'].
											  "   AND (deleted = '00000000000000')";
				$result						= db_query( $query, $dbh );
				
				if( $result['rows'] < 0 ) {
					
					$error					= 1;
				}
		}
		
		if( $error == 0 ) {
			
			db_ta( 'COMMIT', $dbh );
			$tMessage						= _("User successfully deleted" );
			
			db_disconnect( $dbh );
			
			header( "Location: list_users.php" );
			exit;
			
		} else {
			
			db_ta( 'ROLLBACK', $dbh );
			$tMessage						= _( "User not deleted due to database errors" );
			
		}
		
	} elseif( $button == _("Back") ) {
		
		db_disconnect( $dbh );
		header( "Location: list_users.php" );
		exit;
		
	} else {
	
		$tMessage							= _( "Invalid button $button, anyone tampered arround with?" );
			
	}
	
	$header									= "users";
	$subheader								= "users";
	$menu									= "users";
	$template								= "deleteUser.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

db_disconnect( $dbh );
?>
