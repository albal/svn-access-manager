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

File:  workOnGroupAccessRight.php
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
#require ("./config/config.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$SESSID_USERNAME 								= check_session ();
check_password_expired();
$dbh 											= db_connect ();
$preferences									= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']								= $preferences['page_size'];
$rightAllowed									= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );
$_SESSION['svn_sessid']['helptopic']			= "workongroupaccessright";

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}

$schema											= db_determine_schema();

$tUsers											= array();
$query											= "SELECT * " .
												  "  FROM ".$schema."svnusers " .
												  " WHERE (deleted = '00000000000000') ".
												  "ORDER BY ".$CONF['user_sort_fields']." ".$CONF['user_sort_order'];
$result											= db_query( $query, $dbh );
while( $row = db_assoc( $result['result'] ) ) {
	
	$userid										= $row['userid'];
	$name										= $row['name'];
	$givenname									= $row['givenname'];
	
	if( $givenname != "" ) {
		
		$name 									= $givenname." ".$name;
		
	}
	
	$tUsers[$userid] 							= $name;
}									  


if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tUser										= "";
	$tRight										= "";											
	$tReadonly									= "";
	$tTask										= db_escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId									= db_escape_string( $_GET['id'] );
		
	} else {

		$tId									= "";

	}
	
	if( ($rightAllowed == "add") and ($tTask != "new") ) {
	
		db_disconnect( $dbh );
		header( "Location: nopermission.php" );
		exit;
	
	}		
	
	$_SESSION['svn_sessid']['task']				= strtolower( $tTask );
	
	if( $_SESSION['svn_sessid']['task'] == "new" ) {

		$tRight									= "";
		$tUser									= "";
		
		$query										= "SELECT groupname " .
													  "  FROM ".$schema."svngroups " .
													  " WHERE id=".$_SESSION['svn_sessid']['groupid'];
		$result										= db_query( $query, $dbh );
		if( $result['rows'] > 0 ) {
			
			$row									= db_assoc( $result['result'] );
			$tGroupName								= $row['groupname'];
			
		} else {
			
			$tGroupName								= "undefined";
			
		}	
		
		$_SESSION['svn_sessid']['userid']			= $tUser;
   		$_SESSION['svn_sessid']['right']			= $tRight;	
		
	} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
		
		$query											= "SELECT groupname " .
														  "  FROM ".$schema."svngroups, ".$schema."svn_groups_responsible " .
														  " WHERE (svn_groups_responsible.id=$tId) " .
														  "   AND (svngroups.id = svn_groups_responsible.group_id)";
		$result											= db_query( $query, $dbh );
		if( $result['rows'] > 0 ) {
			
			$row										= db_assoc( $result['result'] );
			$tGroupName									= $row['groupname'];
			
		} else {
			
			$tGroupName									= "undefined";
			
		}		
		
		$_SESSION['svn_sessid']['groupid']		= $tId;
		$tReadonly								= "disabled";
		$query									= "SELECT svngroups.groupname, svnusers.userid, svn_groups_responsible.allowed " .
												   "  FROM ".$schema."svnusers, ".$schema."svn_groups_responsible, ".$schema."svngroups " .
												   " WHERE (svngroups.id = svn_groups_responsible.group_id) " .
												   "   AND (svn_groups_responsible.id=$tId) " .
												   "   AND (svnusers.id = svn_groups_responsible.user_id) " .
												   "   AND (svnusers.deleted = '00000000000000') " .
												   "   AND (svngroups.deleted = '00000000000000') " .
												   "   AND (svn_groups_responsible.deleted = '00000000000000')";
		$result									= db_query( $query, $dbh );
		if( $result['rows'] > 0 ) {
			
			$row								= db_assoc( $result['result'] );
			$tRight								= $row['allowed'];
			$tUser								= $row['userid'];
			
		} else {
			
			$tUser								= "";
			$tRight								= "";
		}
		
		$_SESSION['svn_sessid']['userid']		= $tUser;
   		$_SESSION['svn_sessid']['right']		= $tRight;
		
	} else {
   			
   			$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   	}
   	
   	$header										= "access";
	$subheader									= "access";
	$menu										= "access";
	$template									= "workOnGroupAccessRight.tpl";
		
   	include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
   	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
   		$button									= _("Set access rights");
   	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button									= _("Set access rights");
	} else {
		$button									= "";
	}
   	
   	if( $button == _("Back") ) {
   		
   		db_disconnect( $dbh );
   		header( "location: list_group_admins.php" );
   		exit;
   		
   	} elseif( $button == _("Set access rights") ) {
   		
   		$tUser									= isset( $_POST['fUser'] )  ? db_escape_string( $_POST['fUser'] )  : "";
   		$tRight									= isset( $_POST['fRight'] ) ? db_escape_string( $_POST['fRight'] ) : "";
   		
   		if( $_SESSION['svn_sessid']['task'] == "new") {
   			
   			if( $tUser == "" ) {
   				
   				$tMessage						= _("Please select user!" );
   				$error							= 1;
   				
   			} elseif( $tRight == "" ) {
   				
   				$tMessage						= _("Please select right!" );
   				$error							= 1;
   				
   			} else {
   			
   				$tGroupResponsibleId			= -1;	
   				$userid							= db_getIdByUserid( $tUser, $dbh );
   				$groupid						= $_SESSION['svn_sessid']['groupid'];
   				$groupname						= db_getGroupById( $groupid, $dbh );
   				$query							= "SELECT * " .
   												  "  FROM ".$schema."svn_groups_responsible " .
   												  " WHERE (group_id=$groupid) " .
   												  "   AND (user_id=$userid) " .
   												  "   AND (deleted = '00000000000000')";
   				$result							= db_query( $query, $dbh );
   				if( $result['rows'] == 0 ) {
	   				
	   				$dbnow						= db_now();
	   				$query						= "INSERT INTO ".$schema."svn_groups_responsible (user_id, group_id, allowed, created, created_user) " .
	   											  "     VALUES ('$userid', '$groupid', '$tRight', '$dbnow', '".$_SESSION['svn_sessid']['username']."')";
	   				db_ta( 'BEGIN', $dbh );
	   				db_log( $_SESSION['svn_sessid']['username'], "added $tUser as responsible for group $groupname with right $tRight", $dbh );
	   				
	   				$result						= db_query( $query, $dbh );
	   				if( $result['rows'] != 1 ) {
	   					
	   					db_ta( 'ROLLBACK', $dbh );
	   					
	   					$tMessaage				= _( "Error during database insert" );
	   					
	   				} else {
	   					
	   					$tGroupResponsibleId	= db_get_last_insert_id( 'svn_groups_responsibles', 'id', $dbh );
	   					db_ta( 'COMMIT', $dbh );
	   					
	   					$tMessage				= _( "Group responsible user successfully inserted" );
	   					
	   				}
   				
   				} else {
   					
   					$tMessage					= sprintf( _("Group responsible user for group %s (%s/%s) already exists!" ), $groupname, $groupid, $userid );
   					$error						= 1;
   					
   				}
   			}
   			
   			$tReadonly							= "";
			$query									= "SELECT svngroups.groupname, svnusers.userid, svn_groups_responsible.allowed " .
													   "  FROM ".$schema."svnusers, ".$schema."svn_groups_responsible, ".$schema."svngroups " .
													   " WHERE (svngroups.id = svn_groups_responsible.group_id) " .
													   "   AND (svn_groups_responsible.id=".$tGroupResponsibleId.") " .
													   "   AND (svnusers.id = svn_groups_responsible.user_id) " .
													   "   AND (svnusers.deleted = '00000000000000') " .
													   "   AND (svngroups.deleted = '00000000000000') " .
													   "   AND (svn_groups_responsible.deleted = '00000000000000')";
			$result									= db_query( $query, $dbh );
			if( $result['rows'] > 0 ) {
				
				$row								= db_assoc( $result['result'] );
				$tRight								= $row['allowed'];
				$tUser								= $row['userid'];
				$tGroupName							= $row['groupname'];
				
			} else {
				
				$tUser								= "";
				$tRight								= "";
				$tGroupName							= "undefined";
			}
   			
   		} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   			$tUser								= $_SESSION['svn_sessid']['userid'];
   			if( $tUser == "" ) {
   				
   				$tMessage						= _("Please select user!" );
   				$error							= 1;
   				
   			} elseif( $tRight == "" ) {
   				
   				$tMessage						= _("Please select right!" );
   				$error							= 1;
   				
   			} else {
   				
   				$groupid						= $_SESSION['svn_sessid']['groupid'];
   				$groupname						= db_getGroupById( $groupid, $dbh );
   				$query							= "SELECT * " .
   												  "  FROM ".$schema."svn_groups_responsible " .
   												  " WHERE (id=".$_SESSION['svn_sessid']['groupid'].")";
   				$result							= db_query( $query, $dbh );
   				if( $result['rows'] > 0 ) {
	   				
	   				$dbnow						= db_now();
	   				$query						= "UPDATE ".$schema."svn_groups_responsible " .
	   											  "   SET allowed='$tRight', " .
	   											  "       modified='$dbnow', " .
	   											  "       modified_user='".$_SESSION['svn_sessid']['username']."' " .
	   											  " WHERE (id=".$_SESSION['svn_sessid']['groupid'].")";
	   				db_ta( 'BEGIN', $dbh );
	   				db_log( $_SESSION['svn_sessid']['username'], "changed $tUser as responsible for group $groupname to right $tRight", $dbh );
	   				
	   				$result						= db_query( $query, $dbh );
	   				if( $result['rows'] != 1 ) {
	   					
	   					db_ta( 'ROLLBACK', $dbh );
	   					
	   					$tMessaage				= _( "Error during database insert" );
	   					
	   				} else {
	   					
	   					db_ta( 'COMMIT', $dbh );
	   					
	   					$tMessage				= _( "Group responsible user successfully changed" );
	
	   				}
	   				
   				} else {
   					
   					$tMessage					= sprintf( _("Group responsible user for group %s (%s) does not exist!"), $groupname, $groupid );
   					$error						= 1;
   				}
   				
   			}
   			
   			$tReadonly							= "disabled";
			$query								= "SELECT svngroups.groupname, svnusers.userid, svn_groups_responsible.allowed " .
												   "  FROM ".$schema."svnusers, ".$schema."svn_groups_responsible, ".$schema."svngroups " .
												   " WHERE (svngroups.id = svn_groups_responsible.group_id) " .
												   "   AND (svn_groups_responsible.id=".$_SESSION['svn_sessid']['groupid'].") " .
												   "   AND (svnusers.id = svn_groups_responsible.user_id) " .
												   "   AND (svnusers.deleted = '00000000000000') " .
												   "   AND (svngroups.deleted = '00000000000000') " .
												   "   AND (svn_groups_responsible.deleted = '00000000000000')";
			$result								= db_query( $query, $dbh );
			if( $result['rows'] > 0 ) {
				
				$row							= db_assoc( $result['result'] );
				$tRight							= $row['allowed'];
				$tUser							= $row['userid'];
				$tGroupName						= $row['groupname'];
				
			} else {
				
				$tUser							= "";
				$tRight							= "";
				$tGroupName						= "undefined";
			}
   			
   		} else {
   			
   			$tMessage							= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   		}
   		
   	} else {
   		
   		$tMessage								= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
   		
   	}
   	
	$header										= "access";
	$subheader									= "access";
	$menu										= "access";
	$template									= "workOnGroupAccessRight.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

db_disconnect ( $dbh );
?>
