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

 
require ("./include/variables.inc.php");
require ("./config/config.inc.php");
require_once ("./include/functions.inc.php");
require_once ("./include/db-functions-adodb.inc.php");
include_once ("./include/output.inc.php");



function getUsers( $start, $count, $dbh ) {

	global $CONF;
	
	$schema				= db_determine_schema();
	$tUsers				= array();
	$query				= " SELECT * " .
						  "   FROM ".$schema."svnusers " .
						  "   WHERE (deleted = '00000000000000') " .
						  "ORDER BY ".$CONF['user_sort_fields']." ".$CONF['user_sort_order'];
#						  "   LIMIT $start, $count";
	$result				= db_query( $query, $dbh, $count, $start );
	   	
	while( $row = db_assoc( $result['result']) ) {
	   
		$tUsers[] 		= $row;
	   		
	}

	return $tUsers;
}

function getCountUsers( $dbh ) {

	$schema				= db_determine_schema();
	$tUsers				= array();
	$query				= " SELECT COUNT(*) AS anz " .
						  "   FROM ".$schema."svnusers " .
						  "   WHERE (deleted = '00000000000000') ";
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
$CONF['user_sort_fields']					= $preferences['user_sort_fields'];
$CONF['user_sort_order']					= $preferences['user_sort_order'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, 'User admin', $dbh );
$_SESSION['svn_sessid']['helptopic']		= "list_users";

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		
   	
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$_SESSION['svn_sessid']['usercounter']	= 0;
   	$tUsers									= getUsers( 0, $CONF['page_size'], $dbh );
   	$tCountRecords							= getCountUsers( $dbh );
   	$tPrevDisabled							= "disabled";
	
	if( $tCountRecords <= $CONF['page_size'] ) {
		
		$tNextDisabled 						= "disabled";
		
	}
	
   	$template								= "list_users.tpl";
   	$header									= "users";
   	$subheader								= "users";
   	$menu									= "users";
   
   	include ("./templates/framework.tpl");
   	
   	db_disconnect( $dbh );
 
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   

 	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_f_x'] ) ) {
		$button									= _("<<");
	} elseif( isset( $_POST['fSubmit_p_x'] ) ) {
		$button									= _("<");
	} elseif( isset( $_POST['fSubmit_n_x'] ) ) {
		$button									= _(">");			
	} elseif( isset( $_POST['fSubmit_l_x'] ) ) {
		$button									= _(">>");
	} elseif( isset( $_POST['fSubmit_new_x'] ) ) {
		$button									= _("New user");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_new'] ) ) {
		$button									= _("New user");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} else {
		$button									= "undef";
	}
 	
 	if( $button == _("New user") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: workOnUser.php?task=new" );
 		exit;
 		
 	} elseif( $button == _("Back") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: main.php" );
 		exit;
 		
 	} elseif( $button == _("<<") ) {
		
		$_SESSION['svn_sessid']['usercounter']		= 0;
		$tUsers										= getUsers( 0, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountUsers( $dbh );
		$tPrevDisabled								= "disabled";
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _("<") ) {
		
		$_SESSION['svn_sessid']['usercounter']--;
		if( $_SESSION['svn_sessid']['usercounter'] < 0 ) {
			
			$_SESSION['svn_sessid']['usercounter']	= 0;
			$tPrevDisabled							= "disabled";
			
		} elseif( $_SESSION['svn_sessid']['usercounter'] == 0 ) {
			
			$tPrevDisabled							= "disabled";
		}
		
		$start										= $_SESSION['svn_sessid']['usercounter'] * $CONF['page_size'];
		$tUsers										= getUsers( $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountUsers( $dbh );
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _(">") ) {
		
		$_SESSION['svn_sessid']['usercounter']++;
		$start										= $_SESSION['svn_sessid']['usercounter'] * $CONF['page_size'];
		$tUsers										= getUsers( $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountUsers( $dbh );
		$tRemainingRecords							= $tCountRecords - $start - $CONF['page_size'];
		
		if( $tRemainingRecords <= 0 ) {
			
			$tNextDisabled							= "disabled";
			
		}
		
	} elseif( $button == _(">>") ) {
		
		$count										= getCountUsers( $dbh );
		$rest   									= $count % $CONF['page_size'];
		if( $rest != 0 ) {
			
			$start									= $count - $rest + 1;
			$_SESSION['svn_sessid']['usercounter'] 	= floor($count / $CONF['page_size'] );
			
		} else {
			
			$start									= $count - $CONF['page_size'] - 1;
			$_SESSION['svn_sessid']['usercounter'] 	= floor($count / $CONF['page_size'] ) - 1;
			
		}
		
		$tUsers										= getUsers( $start, $CONF['page_size'], $dbh );
		$tNextDisabled								= "disabled";
				
	} else {
		
		$tMessage									= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
 	
   	$template		= "list_users.tpl";
   	$header			= "users";
   	$subheader		= "users";
   	$menu			= "users";
   
   	include ("./templates/framework.tpl");
 
 	db_disconnect( $dbh );
}
?>
