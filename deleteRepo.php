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


$SESSID_USERNAME 							= check_session ();
$dbh 										= db_connect ();
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Repository admin", $dbh );

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
	$_SESSION['svn_sessid']['repoid']		= $tId;
	
	if( $_SESSION['svn_sessid']['task'] == "delete" ) {
		
		$query								= "SELECT * " .
											  "  FROM svnrepos " .
											  " WHERE id = $tId";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_array( $result['result'] );
			$tReponame						= $row['reponame'];
			$tRepopath						= $row['repopath'];
			$tRepouser						= $row['repouser'];
			$tRepopassword					= $row['repopassword'];
			$tDisabled						= "";
			$tClass							= "button";
			
			$query							= "SELECT * " .
											  "  FROM svnprojects " .
											  " WHERE (deleted = '0000-00-00 00:00:00') " .
											  "   AND (repo_id = '".$_SESSION['svn_sessid']['repoid']."')";
			$result							= db_query( $query, $dbh );
		
			if( $result['rows'] > 0 ) {
				
				$repos						= "";
			
				while( $row = db_array( $result['result'] ) ) {
				
					if( $repos == "" ) {
					
						$repos 				.= $row['svnmodule'];
					
					} else {
					
						$repos				.= ", ".$row['svnmodule'];
					
					}
				}
				
				$tMessage 					= sprintf( _("Repository can not be deleted because it's referenced from other projects (%s)"), $repos );
				$tDisabled					= "disabled";
				$tClass						= "button_disabled";
			}
			
		} else {
		
			$tMessage						= _( "Invalid repository id $id requested!" );	
			
		}
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
		
	}
	
	$header									= "repos";
	$subheader								= "repos";
	$menu									= "repos";
	$template								= "deleteRepo.tpl";
	
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
		
		$query								= "SELECT * " .
											  "  FROM svnprojects " .
											  " WHERE (deleted = '0000-00-00 00:00:00') " .
											  "   AND (repo_id = '".$_SESSION['svn_sessid']['repoid']."')";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 0 ) {
			
			$reponame						= db_getRepoById( $_SESSION['svn_sessid']['repoid'], $dbh );
			
			db_ta( 'BEGIN', $dbh );
			db_log( $_SESSION['svn_sesid']['username'], "deleted repository $reponame", $dbh);
			
			$query								= "UPDATE svnrepos " .
												  "   SET deleted = now(), " .
												  "       deleted_user = '".$_SESSION['svn_sessid']['username']."'".
  												  " WHERE id = ".$_SESSION['svn_sessid']['repoid'];
			$result								= db_query( $query, $dbh );
			
			if( $result['rows'] == 1 ) {
				
				db_ta( 'COMMIT', $dbh );
				$tMessage						= _("Repository successfully deleted" );
				
				db_disconnect( $dbh );
				
				header( "Location: list_repos.php" );
				exit;
				
			} else {
				
				db_ta( 'ROLLBACK', $dbh );
				$tMessage						= _( "Repository not deleted due to database error" );
				
			}
		} else {
			
			$repos								= "";
			
			while( $row = db_array( $result['result'] ) ) {
				
				if( $repos == "" ) {
					
					$repos 						.= $row['svnmodule'];
					
				} else {
					
					$repos						.= ", ".$row['svnmodule'];
					
				}
			}
			
			$tMessage							= sprintf( _( "Repository not deleted due to usage in other projects (%s)!" ), $repos );
			
		}
		
	} elseif( $button == _("Back") ) {
		
		db_disconnect( $dbh );
		header( "Location: list_repos.php" );
		exit;
		
	} else {
	
		$tMessage							= _( "Invalid button $button, anyone tampered arround with?" );
			
	}
	
	$header									= "repos";
	$subheader								= "repos";
	$menu									= "repos";
	$template								= "deleteRepo.tpl";
	
   	include ("./templates/framework.tpl");
}

db_disconnect( $dbh );
?>
