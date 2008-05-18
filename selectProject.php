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
$dbh 										= db_connect ();
$uId										= db_getIdByUserid( $SESSID_USERNAME, $dbh );
$tProjects									= array();
$query										= "SELECT svnprojects.id, svnmodule, modulepath, reponame, " .
											  "       repopath, repouser, repopassword " .
											  "  FROM svn_projects_responsible, svnprojects, svnrepos " .
											  " WHERE (svn_projects_responsible.user_id = $uId) " .
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
   
   	$button									= escape_string( $_POST['fSubmit'] );
   	
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
