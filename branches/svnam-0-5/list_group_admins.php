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

File:  list_group_admins.php
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
	die( "can't load config.inc.php. Check your installation!\n'" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
#require ("./config/config.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");



function getGroups( $start, $count, $dbh ) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
    
	$tGroups			= array();
	$query				= "SELECT  svnusers.userid, svnusers.name, svnusers.givenname, svngroups.groupname, svngroups.description, svn_groups_responsible.allowed, svn_groups_responsible.id AS id " .
						  "   FROM ".$schema."svn_groups_responsible, ".$schema."svngroups, ".$schema."svnusers " .
						  "   WHERE (svnusers.deleted = '00000000000000') " .
						  "     AND (svngroups.deleted = '00000000000000') " .
						  "     AND (svn_groups_responsible.deleted = '00000000000000') " .
						  "     AND (svnusers.id = svn_groups_responsible.user_id) " .
						  "     AND (svngroups.id = svn_groups_responsible.group_id) ";
#						  "   LIMIT $start, $count";
	$result				= db_query( $query, $dbh, $count, $start );
	   	
	while( $row = db_assoc( $result['result']) ) {
	   
		$tGroups[] 		= $row;
	   		
	}

	return $tGroups;
}

function getCountGroups( $dbh ) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
    
	$tGroups			= array();
	$query				= "SELECT  COUNT(*) AS anz " .
						  "   FROM ".$schema."svngroups " .
						  "   WHERE (deleted = '00000000000000') ";
	$query				= "SELECT  COUNT(*) AS anz " .
						  "   FROM ".$schema."svn_groups_responsible, ".$schema."svngroups, ".$schema."svnusers " .
						  "   WHERE (svnusers.deleted = '00000000000000') " .
						  "     AND (svngroups.deleted = '00000000000000') " .
						  "     AND (svn_groups_responsible.deleted = '00000000000000') " .
						  "     AND (svnusers.id = svn_groups_responsible.user_id) " .
						  "     AND (svngroups.id = svn_groups_responsible.group_id)";
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
$CONF['user_sort_fields']					= $preferences['user_sort_fields'];
$CONF['user_sort_order']					= $preferences['user_sort_order'];
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "list_groups";

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$_SESSION['svn_sessid']['groupcounter']		= 0;
   	$tGroups									= getGroups( 0, $CONF['page_size'], $dbh );
   	$tCountRecords								= getCountGroups( $dbh );
   	$tPrevDisabled								= "disabled";
   	
   	if( $tCountRecords <= $CONF['page_size'] ) {
		
		$tNextDisabled 							= "disabled";
		
	}
	
   	$template									= "list_group_admins.tpl";
   	$header										= "groups";
   	$subheader									= "groups";
   	$menu										= "groups";
   
   	include ("$installBase/templates/framework.tpl");
   	
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
		$button									= _("New group");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_new'] ) ) {
		$button									= _("New group");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} else {
		$button									= "undef";
	}
	
	$schema									= db_determine_schema();
 	
 	if( $button == _("New group") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: selectGroup.php" );
 		exit;
 		
 	} elseif( $button == _("Back") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: main.php" );
 		exit;
 		
 	} elseif( $button == _("<<") ) {
		
		$_SESSION['svn_sessid']['groupcounter']		= 0;
		$tGroups									= getGroups( 0, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountGroups( $dbh );
		$tPrevDisabled								= "disabled";
   	
   		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _("<") ) {
		
		$_SESSION['svn_sessid']['groupcounter']--;
		if( $_SESSION['svn_sessid']['groupcounter'] < 0 ) {
			
			$_SESSION['svn_sessid']['groupcounter']	= 0;
			$tPrevDisabled							= "disabled";
			
		} elseif( $_SESSION['svn_sessid']['groupcounter'] == 0 ) {
			
			$tPrevDisabled							= "disabled";
			
		}
		
		$start										= $_SESSION['svn_sessid']['groupcounter'] * $CONF['page_size'];
		$tGroups									= getGroups( $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountGroups( $dbh );
   	
   		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _(">") ) {
		
		$_SESSION['svn_sessid']['groupcounter']++;
		$start										= $_SESSION['svn_sessid']['groupcounter'] * $CONF['page_size'];
		$tGroups									= getGroups( $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountGroups( $dbh );
		
		$tRemainingRecords							= $tCountRecords - $start - $CONF['page_size'];
		
		if( $tRemainingRecords <= 0 ) {
			
			$tNextDisabled							= "disabled";
			
		}
		
	} elseif( $button == _(">>") ) {
		
		$count										= getCountGroups( $dbh );
		$rest   									= $count % $CONF['page_size'];
		if( $rest != 0 ) {
			
			$start									= $count - $rest + 1;
			$_SESSION['svn_sessid']['groupcounter'] = floor($count / $CONF['page_size'] );
			
		} else {
			
			$start									= $count - $CONF['page_size'] - 1;
			$_SESSION['svn_sessid']['groupcounter'] = floor($count / $CONF['page_size'] ) - 1;
			
		}
		
		$_SESSION['svn_sessid']['groupcounter'] 	= floor($count / $CONF['page_size'] );
		$tGroups									= getGroups( $start, $CONF['page_size'], $dbh );
		$tNextDisabled								= "disabled";
				
	} else {
		
		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
 	
   	$template		= "list_group_admins.tpl";
   	$header			= "groups";
   	$subheader		= "groups";
   	$menu			= "groups";
   
   	include ("$installBase/templates/framework.tpl");
 
 	db_disconnect( $dbh );
}
?>