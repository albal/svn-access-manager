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
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Repository admin", $dbh );

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tReadonly								= "";
	$tTask									= escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId								= escape_string( $_GET['id'] );
		
	} else {

		$tId								= "";

	}
	
	$_SESSION['svn_sessid']['task']			= strtolower( $tTask );
	$_SESSION['svn_sessid']['repoid']		= $tId;
	
	if( $_SESSION['svn_sessid']['task'] == "new" ) {
   		
   		$tReponame							= "";
		$tRepopath							= "";
		$tRepouser							= "";
		$tRepopassword						= "";
			
   	} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   		$tReadonly								= "readonly";
   		$query									= "SELECT * FROM svnrepos WHERE id = $tId";
		$result									= db_query( $query, $dbh );
		if( $result['rows'] == 1 ) {
			
			$row								= db_array( $result['result'] );
			$tReponame							= $row['reponame'];
			$tRepopath							= $row['repopath'];
			$tRepouser							= $row['repouser'];
			$tRepopassword						= $row['repopassword'];
			
		} else {
		
			$tMessage							= _( "Invalid userid $id requested!" );	
			
		}
		
	} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   	}
   		
		
	
	$header									= "repos";
	$subheader								= "repos";
	$menu									= "repos";
	$template								= "workOnRepo.tpl";
	
   	include ("./templates/framework.tpl");

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	$tReponame								= escape_string( $_POST['fReponame'] );
   	$tRepopath								= escape_string( $_POST['fRepopath'] );
   	$tRepouser								= escape_string( $_POST['fRepouser'] );
   	$tRepopassword							= escape_string( $_POST['fRepopassword'] );
   	
   	if( isset( $_POST['fSubmit'] ) ) {
		$button								= escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button								= _("Submit");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button								= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button								= _("Submit");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button								= _("Back" );
	} else {
		$button								= "undef";
	}
   	   	
   	if( $button == _("Back" ) ) {
   	
   		db_disconnect( $dbh );	
   		header( "Location: list_repos.php" );
   		exit;
   		
   	} elseif( $button == _( "Submit" ) ) {
   		
   		if( $_SESSION['svn_sessid']['task'] == "new" ) {
   			
   			$error							= 0;
   			
   			if( $tReponame == "" ) {
   				
   				$tMessage					= _( "Repository name is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( $tRepopath == "" ) {
   				
   				$tMessage					= _( "Repository path missing, please fill in!" );
   				$error						= 1;
   			
   			} else {

   				$query						= "SELECT * " .
   											  "  FROM svnrepos " .
   											  " WHERE (reponame = '$tReponame') " .
   											  "   AND (deleted = '0000-00-00 00:00:00')";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= _( "The repository with the name $tReponame exists already" );
   					$error					= 1;
   					
   				} 
   			}
  			   			
   			if( $error == 0 ) {
   				
   				$query 						= "INSERT INTO svnrepos (reponame, repopath, repouser, repopassword, created, created_user) " .
   											  "     VALUES ('$tReponame', '$tRepopath', '$tRepouser', '$tRepopassword', now(), '".$_SESSION['svn_sessid']['username']."')";
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "addes repository $reponame ($repopath)", $dbh );
   				
   				$result						= db_query( $query, $dbh );
   				if( $result['rows'] != 1 ) {
   					
   					db_ta( 'ROLLBACK', $dbh );
   					
   					$tMessaage				= _( "Error during database insert" );
   					
   				} else {
   					
   					db_ta( 'COMMIT', $dbh );
   					
   					$tMessage				= _( "Repository successfully inserted" );
   					
   				}
   			}
   			
   		} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   			$error							= 0;
   			$tReadonly						= "readonly";
   			
   			if( $tReponame == "" ) {
   				
   				$tMessage					= _( "Repository name is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( $tRepopath == "" ) {
   				
   				$tMessage					= _( "Repository path missing, please fill in!" );
   				$error						= 1;
   			
   			} else {

   				$query						= "SELECT * " .
   											  "  FROM svnrepos " .
   											  " WHERE (reponame = '$tReponame') " .
   											  "   AND (deleted = '0000-00-00 00:00:00') " .
   											  "   AND (id != ".$_SESSION['svn_sessid']['repoid'].")";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= _( "The repository with the name $tReponame exists already" );
   					$error					= 1;
   					
   				}
   			}
  			   			
   			if( $error == 0 ) {
   				
   				$reponame					= db_getRepoById( $_SESSION['svn_sessid']['repoid'], $dbh );
   				$query						=  "UPDATE svnrepos " .
   											   "   SET reponame = '$tReponame', " .
   											   "       repopath = '$tRepopath', " .
   											   "       repouser = '$tRepouser', " .
   											   "       repopassword = '$tRepopassword', " .
   											   "       modified = now(), " .
   											   "       modified_user = '".$_SESSION['svn_sessid']['username']."' " .
   											   " WHERE (id = ".$_SESSION['svn_sessid']['repoid'].")";
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "updated repository $reponame", $dbh );
   				
   				$result						= db_query( $query, $dbh );
   				
   				if ( $result['rows'] == 1 ) {
   					
   					db_ta( 'COMMIT', $dbh );
   					
   					$tMessage				= _( "Repository successfully modified" );
   					
   				} else {
   					
   					db_ta( 'ROLLBACK', $dbh );
   					
   					$tMessage 				= _( "Repository not modified due to database error" );
   					
   				}
   			}
   			
   		} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   		}
   		
   	} else {
   		
   		$tMessage							= _( "Invalid button $button, anyone tampered arround with?" );
   		
   	}
   	
   	$header									= "repos";
	$subheader								= "repos";
	$menu									= "repos";
	$template								= "workOnRepo.tpl";
	
   	include ("./templates/framework.tpl");
   
}

db_disconnect ( $dbh );
?>
