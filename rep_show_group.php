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
	die( "can't load config.inc.php. Check your installation!\n" );
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
	$query				= " SELECT * " .
						  "   FROM ".$schema."svngroups " .
						  "   WHERE (deleted = '00000000000000') " .
						  "ORDER BY groupname ASC";
	$result				= db_query( $query, $dbh, $count, $start );
	   	
	while( $row = db_assoc( $result['result']) ) {
		
		$tGroups[] 		= $row;
	   		
	}

	return $tGroups;
}


function getUsersForGroup( $tGroupId, $dbh ) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
	$tUsers				= array();
	$query				= "SELECT * ".
						  "  FROM ".$schema."svnusers, ".$schema."svn_users_groups ".
						  " WHERE (svn_users_groups.group_id = '$tGroupId') ".
						  "   AND (svn_users_groups.user_id = svnusers.id) ".
						  "   AND (svnusers.deleted = '00000000000000') ".
						  "   AND (svn_users_groups.deleted = '00000000000000')";
	$result				= db_query( $query, $dbh );

	while( $row = db_assoc( $result['result'] ) ) {
		
		$tUsers[]		= $row;
		
	}
	
	return( $tUsers );
}

function getGroupAdminsForGroup( $tGroupId, $dbh ) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
	$tAdmins			= array();
	$query				= "SELECT svnusers.userid, svnusers.name, svnusers.givenname, svn_groups_responsible.allowed ".
						  "  FROM ".$schema."svnusers, ".$schema."svn_groups_responsible, ".$schema."svngroups ".
						  " WHERE (svn_groups_responsible.group_id = '$tGroupId') ".
						  "   AND (svn_groups_responsible.deleted = '00000000000000') ".
						  "   AND (svn_groups_responsible.user_id = svnusers.id) ".
						  "   AND (svnusers.deleted = '00000000000000') ".
						  "   AND (svngroups.id = svn_groups_responsible.group_id) ".
						  "   AND (svngroups.deleted = '00000000000000') ".
						  "ORDER BY userid ASC";
	$result				= db_query( $query, $dbh );
	
	while( $row = db_assoc( $result['result'] ) ) {
		
		$tAdmins[]		= $row;
		
	}
	
	return( $tAdmins );
}

function getAccessRightsForGroup( $tGroupId, $dbh ) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
	$tAccessRights		= array();
	$curdate			= strftime( "%Y%m%d" );
	$query				= "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " .
						  "    FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
						  "   WHERE (svn_access_rights.deleted = '00000000000000') " .
						  "     AND (svn_access_rights.valid_from <= '$curdate') " .
						  "     AND (svn_access_rights.valid_until >= '$curdate') " .
						  "     AND (svn_access_rights.project_id = svnprojects.id) " .
						  "     AND (svn_access_rights.group_id = $tGroupId) " .
						  "     AND (svnprojects.repo_id = svnrepos.id) " .
						  "ORDER BY svnprojects.repo_id ASC, LENGTH(svn_access_rights.path) DESC";

	$result				= db_query( $query, $dbh );
	
	while( $row = db_assoc( $result['result'] ) ) {
		
		$tAccessRights[]= $row;
	}
	
	return( $tAccessRights );
} 

function getGroupData( $tGroupId, $dbh ) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
	$query				= "SELECT * ".
						  "  FROM ".$schema."svngroups ".
						  " WHERE (id = $tGroupId)";
	$result				= db_query( $query, $dbh );
	$row				= db_assoc( $result['result'] );
	
	return( $row );
}


initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh										= db_connect();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Reports", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "rep_show_group";

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$lang									= check_language();
	$tGroups								= getGroups(0, -1, $dbh );
	
	$template								= "rep_show_group.tpl";
   	$header									= "reports";
   	$subheader								= "reports";
   	$menu									= "reports";
   
   	include ("$installBase/templates/framework.tpl");
   	
   	db_disconnect( $dbh );
   	
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error										= 0;
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_show_x'] ) ) {
		$button									= _("Create report");
	} elseif( isset( $_POST['fSubmit_show'] ) ) {
		$button									= _("Create report");
	} else {
		$button									= "undef";
	}
	
	if( $button == _("Create report") ) {
		
		$tGroupId								= isset( $_POST['fGroup'] ) ? db_escape_string( $_POST['fGroup'] ) : "";
		$_SESSION['svn_sessid']['group']		= $tGroupId;
		
		if( $tGroupId == "default" ) {
			
			$tMessage							= _("No group selected!");
			$lang								= check_language();
			$tGroups							= getGroups(0, -1, $dbh );
			$template							= "rep_show_group.tpl";
   			$header								= "reports";
   			$subheader							= "reports";
   			$menu								= "reports";
   
   			include ("$installBase/templates/framework.tpl");
	 
 			db_disconnect( $dbh );
 			
 			exit;
			
		} else {
			
			$tGroupData							= getGroupData( $tGroupId, $dbh );
			$tGroupname							= $tGroupData['groupname'];
			$tDescription						= $tGroupData['description'];
			$lang								= check_language();
			$tUsers								= getUsersForGroup( $tGroupId, $dbh );
			$tAccessRights						= getAccessRightsForGroup( $tGroupId, $dbh );
			$tAdmins							= getGroupAdminsForGroup( $tGroupId, $dbh );
			
		}
		
	} else {
		
		$tMessage								= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
 	
   	$template		= "rep_show_group_result.tpl";
   	$header			= "reports";
   	$subheader		= "reports";
   	$menu			= "reports";
   
   	include ("$installBase/templates/framework.tpl");
 
 	db_disconnect( $dbh );
}

?>
