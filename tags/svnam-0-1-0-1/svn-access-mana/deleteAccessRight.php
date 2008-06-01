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
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Access rights admin", $dbh );

if( $rightAllowed != "delete" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	if( isset( $_GET['task'] ) ) {
		
		$_SESSION['svn_sessid']['task'] 	= escape_string( strtolower( $_GET['task'] ) );
		
	} else {
		
		$_SESSION['svn_sessid']['task']		= "";

	}
	
	if( isset( $_GET['id'] ) ) {
		
		$tId								= escape_string( $_GET['id'] );
		
	} else {
		
		$tId								= "";
		
	}
	
	$_SESSION['svn_sessid']['rightid']		= $tId;
	
	if( $_SESSION['svn_sessid']['task'] == "delete" ) {
		
		$query								= "SELECT * " .
											  "  FROM svn_access_rights " .
											  " WHERE id = $tId";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_array( $result['result'] );
			$projectid						= $row['project_id'];
			$userid							= $row['user_id'];
			$groupid						= $row['group_id'];
			$tPathSelected					= $row['path'];
			$validfrom						= $row['valid_from'];
			$validuntil						= $row['valid_until'];
			$tAccessRight					= $row['access_right'];
			$lang							= strtolower( check_language() );
			
			if( $lang == "de" ) {
			
				$tValidFrom					= substr($validfrom, 6, 2).".".substr($validfrom, 4, 2).".".substr($validfrom, 0, 4);
				$tValidUntil				= substr($validuntil, 6, 2).".".substr($validuntil, 4, 2).".".substr($validuntil, 0, 4);
				
			} else {
				
				$tValidFrom					= substr($validfrom, 4, 2)."/".substr($validfrom, 6, 2)."/".substr($validfrom, 0, 4);
				$tValidUntil				= substr($validuntil, 4, 2)."/".substr($validuntil, 6, 2)."/".substr($validuntil, 0, 4);
				
			}
			
			
			$query							= "SELECT * " .
											  "  FROM svnprojects, svnrepos " .
											  " WHERE (svnprojects.id = $projectid) " .
											  "   AND (repo_id = svnrepos.id)";
			$result							= db_query( $query, $dbh );
			if( $result['rows'] == 1 ) {
				
				$row						= db_array( $result['result'] );
				$tProjectName				= $row['svnmodule'];
				$tModulePath				= $row['modulepath'];
				
				if( $userid != "0" ) {
					
					$query					= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE id = $userid";
					$result					= db_query( $query, $dbh );
					
					if( $result['rows'] == 1 ) {
						
						$row				= db_array( $result['result'] );
						$name				= $row['name'];
						$givenname			= $row['givenname'];
						if( $givenname != "" ) {
							$name			= $givenname." ".$name;
						}
						$tUsers				= $name." (".$row['userid'].")";
						
					} else {
						
						$tMessage					= _( "Invalid user id $id requested!" );	
						
					}
					
				} else {
					
					$tUsers							= _("none");
					
				}
				
				if( $groupid != "0" ) {
					
					$query					= "SELECT * " .
											  "  FROM svngroups " .
											  " WHERE id = $groupid";
					$result					= db_query( $query, $dbh );
					
					if( $result['rows'] == 1 ) {
						
						$row						= db_array( $result['result'] );
						$tGroups					= $row['groupname'];
						
					} else {
						
						$tMessage					= _( "Invalid group id $groupid requested!" );	
						
					}
					
				} else {
					
					$tGroups						= _("none");
					
				}
				
			} else {
				
				$tMessage					= _( "Invalid project id $projectid requested!" );	
				
			}
			
		} else {
			
			$tMessage						= _( "Invalid access right id $tId requested!" );	
			
		}
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
		
	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "deleteAccessRight.tpl";
	
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
		
		$rightdata							= db_getRightdata( $_SESSION['svn_sessid']['rightid'], $dbh );
		if( $rightdata['user_id'] != 0 ) {
			$username						= db_getUseridById( $rightdata['user_id'], $dbh );
		} else {
			$username						= "";
		}
		
		if( $rightdata['group_id'] != 0 ) {
			$groupname						= db_getGroupById( $rightdata['group_id'], $dbh );
		}
		
		$projectname						= db_getProjectbyId( $rightdata['project_id'], $dbh );
		$reponame							= db_getRepoById( $rightdata['repo_id'], $dbh );
		$path								= $rightdata['path'];
		$accessright						= $rightdata['access_right'];
		
		db_ta( 'BEGIN', $dbh );
		db_log( $_SESSION['svn_sessid']['username'], "deleted access right $accessright for repository $reponame, path $path, project $projectname", $dbh );
		
		$query								= "UPDATE svn_access_rights " .
											  "   SET deleted = now(), " .
											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE id = ".$_SESSION['svn_sessid']['rightid'];
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			db_ta( 'COMMIT', $dbh );
			db_disconnect( $dbh );
			header( "location: list_access_rights.php" );
			exit;
			
		} else {
			
			db_ta( 'ROLLBACK', $dbh );
			
			$tMessage						= sprintf( _("Error while updating right id %s for delete"), $_SESSION['svn_sessid']['rightid'] );
			
		}
		
	} elseif( $button == _("Back") ) {
		
		db_disconnect( $dbh );
		header( "location: list_access_rights.php" );
		exit;
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "deleteAccessRight.tpl";
	
   	include ("./templates/framework.tpl");
}
?>
