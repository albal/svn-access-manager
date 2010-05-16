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
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();
check_password_expired();

$_SESSION['svn_sessid']['helptopic']	= "addmembertoproject";

function addMemberToGroup($group, $currentMembers, $dbh) {

	global $CONF;
	
	$installBase				= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";
	$schema						= db_determine_schema();
    
	$cs_array					= array();
	
	foreach( $currentMembers as $userid => $name ) {
		
		$cs_array[]				= $userid;
		
	}
	
	$tUsers						= array();
	$query						= "SELECT * " .
								  "  FROM ".$schema."svnusers " .
								  " WHERE (deleted = '00000000000000') " .
								  "ORDER BY ".$CONF['user_sort_fields']." ".$CONF['user_sort_order'];
	$result						= db_query( $query, $dbh );
	
	while( $row = db_assoc( $result['result'] ) ) {
		
		$name					= $row['name'];
		$givenname				= $row['givenname'];
		$userid					= $row['userid'];
		
		if( $givenname != "" ) {
			
			$name 							= $givenname." ".$name;
			
		}
		
		if( ! in_array($userid, $cs_array) ) {
			
			$tUsers[ $userid ] 				= $name;
		}
		
	}
	
	$tMessage								= "";
	$header									= "projects";
	$subheader								= "projects";
	$menu									= "projects";
	$template								= "addMemberToProject.tpl";
	
	include ("$installBase/templates/framework.tpl");
}
?>