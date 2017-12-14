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

File:  selectGroup.php
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
	die( "can't load config.inc.php. Check your installation!\n" );
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
$uId										= db_getIdByUserid( $SESSID_USERNAME, $dbh );
$_SESSION['svn_sessid']['helptopic']		= "selectgroup";
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );

if( $rightAllowed == "none" ) {
	
	db_log( $SESSID_USERNAME, "tried to use selectGroup without permission", $dbh );
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
		
	
} 

$schema										= db_determine_schema();

$tGroups									= array();
$query										= "SELECT * " .
											  "  FROM ".$schema."svngroups " .
											  " WHERE (svngroups.deleted = '00000000000000') ".
											  "ORDER BY svngroups.groupname ASC";
$result										= db_query( $query, $dbh );
while( $row = db_assoc( $result['result'] ) ) {

	$tGroups[ $row['id'] ]			= $row['groupname'];
		
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "selectGroup.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	if( isset( $_POST['fSubmit'] ) ) {
		$button								= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button								= _("Select group");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button								= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button								= _("Select group");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button								= _("Back" );
	} else {
		$button								= "undef";
	}
   	
   	if( $button == _("Back" ) ) {
   		
   		db_disconnect( $dbh );
   		header( "Location: list_group_admins.php" );
   		exit;
   		
   	} elseif( $button == _("Select group" ) ) {
   		
   		$tGroup								= db_escape_string( $_POST['fGroup'] );
   		$_SESSION['svn_sessid']['groupid']	= $tGroup;
   		
   		db_disconnect( $dbh );
   		header( "Location: workOnGroupAccessRight.php?task=new" );
   		exit;
   		
   	}
   	
   	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "selectProject.tpl";
	
   	include ("$installBase/templates/framework.tpl");
  
}
?>
