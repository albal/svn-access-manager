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
$uId										= db_getIdByUserid( $SESSID_USERNAME, $dbh );
$_SESSION['svn_sessid']['helptopic']		= "selectproject";
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Access rights admin", $dbh );

if( $rightAllowed == "none" ) {
	
	if( $_SESSION['svn_sessid']['admin'] == "p" ) {
		
		$tSeeUserid							= $SESSID_USERNAME;
		
	} else {
		
		db_disconnect( $dbh );
		header( "Location: nopermission.php" );
		exit;
		
	}
	
} else {
	
	$tSeeUserid								= -1;
	
}  

if( $tSeeUserid != -1 ) {
	$id										= db_getIdByUserid( $SESSID_USERNAME, $dbh );
	$tProjectIds							= "";
	$query									= "SELECT * " .
  					      					  "  FROM svn_projects_responsible " .
  					      				  	  " WHERE (user_id = $id) " .
  					      				  	  "   AND (deleted = '0000-00-00 00:00:00')";
} else {
	
	$tProjectIds							= "";
	$query									= "SELECT * " .
	  					      				  "  FROM svn_projects_responsible " .
  						      				  " WHERE (deleted = '0000-00-00 00:00:00')";
  					      				  
}

$result									= db_query( $query, $dbh );
while( $row = db_array( $result['result'] ) ) {
	
	if( $tProjectIds == "" ) {
		
		$tProjectIds 					= $row['project_id'];
		
	} else {
		
		$tProjectIds					= $tProjectIds.",".$row['project_id'];
		
	}
	
}

$tProjects									= array();
$query										= "SELECT svnprojects.id, svnmodule, modulepath, reponame, " .
											  "       repopath, repouser, repopassword " .
											  "  FROM svn_projects_responsible, svnprojects, svnrepos " .
											  " WHERE (svnprojects.id IN (".$tProjectIds.")) " .
											  "   AND (svn_projects_responsible.project_id = svnprojects.id) " .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_projects_responsible.deleted = '0000-00-00 00:00:00') " .
											  "   AND (svnprojects.deleted = '0000-00-00 00:00:00')";
$result										= db_query( $query, $dbh );
while( $row = db_array( $result['result'] ) ) {

	$tProjects[ $row['id'] ]				= $row['svnmodule'];
		
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "selectProject.tpl";
	
   	include ("./templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	if( isset( $_POST['fSubmit'] ) ) {
		$button								= escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button								= _("Select project");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button								= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button								= _("Select project");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button								= _("Back" );
	} else {
		$button								= "undef";
	}
   	
   	if( $button == _("Back" ) ) {
   		
   		db_disconnect( $dbh );
   		header( "Location: list_access_rights.php" );
   		exit;
   		
   	} elseif( $button == _("Select project" ) ) {
   		
   		$tProject							= escape_string( $_POST['fProject'] );
   		$_SESSION['svn_sessid']['projectid']= $tProject;
   		
   		db_disconnect( $dbh );
   		header( "Location: workOnAccessRight.php?task=new" );
   		exit;
   		
   	}
   	
   	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "selectProject.tpl";
	
   	include ("./templates/framework.tpl");
  
}
?>
