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
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );

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
	$_SESSION['svn_sessid']['groupid']		= $tId;
	
	if( $_SESSION['svn_sessid']['task'] == "delete" ) {
		
		$query								= "SELECT * " .
											  "  FROM svngroups " .
											  " WHERE id = $tId";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_array( $result['result'] );
			$tGroup							= $row["groupname"];
			$tDescription					= $row["description"];
			$tMembers						= "";
			
			$query							= "  SELECT svnusers.userid, svnusers.name, svnusers.givenname " .
											  "    FROM svnusers, svn_users_groups " .
											  "   WHERE (svnusers.id = svn_users_groups.user_id)" .
											  "     AND (svn_users_groups.group_id = $tId) " .
											  "     AND (svnusers.deleted = '0000-00-00 00:00:00') " .
											  "     AND (svn_users_groups.deleted = '0000-00-00 00:00:00') " .
											  "ORDER BY svnusers.name, svnusers.givenname";
			$result							= db_query( $query, $dbh );
			
			while( $row = db_array( $result['result'] ) ) {
				
				$userid						= $row['userid'];
				$name						= $row['name'];
				$givenname					= $row['givenname'];
				
				if( $givenname != "" ) {
					
					$name					= $givenname." ".$name;
					
				}
				
				$tMembers 					.= $name." [$userid]<br />";
			}
			
		} else {
		
			$tMessage						= _( "Invalid groupid $id requested!" );	
			
		}
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
		
	}
	
	$header									= "groups";
	$subheader								= "groups";
	$menu									= "groups";
	$template								= "deleteGroup.tpl";
	
   	include ("./templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	$button									= escape_string( $_POST['fSubmit'] );
	
	if( $button == _("Delete") ) {
		
		$groupname							= db_getGroupById( $_SESSION['svn_sessid']['groupid'], $dbh );
		$query								= "  UPDATE svngroups " .
											   "    SET deleted = now(), " .
											   "        deleted_user = '".$_SESSION['svn_sessid']['username'].
											   "' WHERE id = ".$_SESSION['svn_sessid']['groupid'];
		
		db_ta( 'BEGIN', $dbh );
		db_log( $_SESSION['svn_sessid']['username'], "deleted group $groupname", $dbh );
		
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$error							= 0;
			$query							= "UPDATE svn_users_groups " .
											  "   SET deleted = now(), " .
											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE (group_id = '".$_SESSION['svn_sessid']['groupid']."') " .
											  "   AND (deleted = '0000-00-00 00:00:00')";
			$result							= db_query( $query, $dbh );
			
			if( $result['rows'] >= 0 ) {
				
				$query						= " UPDATE svn_access_rights " .
											   "   SET deleted = now(), " .
											   "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											   	"WHERE (group_id = '".$_SESSION['svn_sessid']['groupid']."') " .
											   	"  AND (deleted = '0000-00-00 00:00:00')";
				$result						= db_query( $query, $dbh );
				
				if( $result['rows'] < 0 ) {
				
					$error					= 1;
						
				}
				
			} else {
				
				$error						= 1;
				
			}
			
			if( $error == 0 ) {
				
				db_ta( 'COMMIT', $dbh );
				$tMessage						= _("Group successfully deleted" );
			
				db_disconnect( $dbh );
			
				header( "Location: list_groups.php" );
				exit;
				
			} else {
				
				db_ta( 'ROLLBACK', $dbh );
				$tMessage					= _("Group not deleted due to errors while deleting users/groups relations" );
				
			}
			
		} else {
			
			db_ta( 'ROLLBACK', $dbh );
			$tMessage						= _( "Group not deleted due to database error" );
			
		}
		
	} elseif( $button == _("Back") ) {
		
		db_disconnect( $dbh );
		header( "Location: list_groups.php" );
		exit;
		
	} else {
	
		$tMessage							= _( "Invalid button $button, anyone tampered arround with?" );
			
	}
	
	$header									= "groups";
	$subheader								= "groups";
	$menu									= "groups";
	$template								= "deleteGroup.tpl";
	
   	include ("./templates/framework.tpl");
}

db_disconnect( $dbh );
?>
