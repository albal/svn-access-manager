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
require_once ("./include/functions.inc.php");
require_once ("./include/db-functions.inc.php");
include_once ("./include/output.inc.php");



function getGrantedRights( $start, $count, $dbh ) {
	
	$tGrantedRights							= array();
	$query									= "SELECT svnusers.userid, svnusers.name, svnusers.givenname, rights.right_name, users_rights.allowed " .
											  "  FROM svnusers, rights, users_rights " .
											  " WHERE (svnusers.id = users_rights.user_id) " .
											  "   AND (rights.id = users_rights.right_id) " .
											  "   AND (svnusers.deleted = '0000-00-00 00:00:00') " .
											  "   AND (users_rights.deleted = '0000-00-00 00:00:00') " .
											  "   AND (rights.deleted = '0000-00-00 00:00:00') " .
											  "ORDER BY svnusers.userid ASC " .
											  "   LIMIT $start, $count";
	$result									= db_query( $query, $dbh );
	$olduserid								= "";
	$rights									= "";
	$entry									= array();
	
	while( $row = db_array( $result['result'] ) ) {
		
		if( $olduserid != $row['userid'] ) {
			
			if( $olduserid != "" ) {
				
				$entry['rights']			= $rights;
				$rights						= "";
				$tGrantedRights[]			= $entry;
				$entry						= array();
			}
			
			if( $row['givenname'] != "" ) {
				
				$entry['name']				= $row['givenname']." ".$row['name'];
				
			} else {
				
				$entry['name']				= $row['name'];
				
			}
			
			$entry['userid']				= $row['userid'];
			$olduserid						= $row['userid'];

		} 
	
		if( $rights == "" ) {
			
			$rights							= $row['right_name']." (".$row['allowed'].")";
			
		} else {
			
			$rights							= $rights.", ".$row['right_name']." (".$row['allowed'].")";
			
		}
	}
	
	if( ($olduserid != "") and ($rights != "") ) {
		
		$entry['rights']					= $rights;
		$tGrantedRights[]					= $entry;
		
	}

	return $tGrantedRights;
	
}

function getCountGrantedRights( $dbh ) {
	
	$tGrantedRights							= array();
	$query									= "SELECT DISTINCT * " .
											  "  FROM svnusers, rights, users_rights " .
											  " WHERE (svnusers.id = users_rights.user_id) " .
											  "   AND (rights.id = users_rights.right_id) " .
											  "   AND (svnusers.deleted = '0000-00-00 00:00:00') " .
											  "   AND (users_rights.deleted = '0000-00-00 00:00:00') " .
											  "   AND (rights.deleted = '0000-00-00 00:00:00') " .
											  "GROUP BY svnusers.userid";
	$result									= db_query( $query, $dbh );
	
	$count									= $result['rows'];
	
	return $count;
	
}


initialize_i18n();

$SESSID_USERNAME 							= check_session ();
$dbh										= db_connect();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Reports", $dbh );

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$tGrantedRights							= getGrantedRights( 0, $CONF['page_size'], $dbh );	
	$tCountRecords							= getCountGrantedRights( $dbh );
	$tPrevDisabled							= "disabled";
	
	if( $tCountRecords <= $CONF['page_size'] ) {
		
		$tNextDisabled 						= "disabled";
		
	}
	
	$template								= "rep_granted_user_rights.tpl";
   	$header									= "reports";
   	$subheader								= "reports";
   	$menu									= "reports";
   
   	include ("./templates/framework.tpl");
   	
   	db_disconnect( $dbh );
   	
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error											= 0;
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_f_x'] ) ) {
		$button									= _("<<");
	} elseif( isset( $_POST['fSubmit_p_x'] ) ) {
		$button									= _("<");
	} elseif( isset( $_POST['fSubmit_n_x'] ) ) {
		$button									= _(">");			
	} elseif( isset( $_POST['fSubmit_l_x'] ) ) {
		$button									= _(">>");
	}
	
	if( $button == _("<<") ) {
		
		$_SESSION['svn_sessid']['rightcounter']		= 0;
		$tGrantedRights								= getGrantedRights( 0, $CONF['page_size'], $dbh );
   		$tCountRecords								= getCountGrantedRights( $dbh );
   		$tPrevDisabled								= "disabled";
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _("<") ) {
		
		$_SESSION['svn_sessid']['rightcounter']--;
		if( $_SESSION['svn_sessid']['rightcounter'] < 0 ) {
			
			$_SESSION['svn_sessid']['rightcounter']	= 0;
			$tPrevDisabled							= "disabled";
			
		} elseif( $_SESSION['svn_sessid']['rightcounter'] == 0 ) {
			
			$tPrevDisabled							= "disabled";
			
		}
		
		$start										= $_SESSION['svn_sessid']['rightcounter'] * $CONF['page_size'];
		$tGrantedRights								= getGrantedRights( 0, $CONF['page_size'], $dbh );
   		$tCountRecords								= getCountGrantedRights( $dbh );
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _(">") ) {
		
		$_SESSION['svn_sessid']['rightcounter']++;
		$start										= $_SESSION['svn_sessid']['rightcounter'] * $CONF['page_size'];
		$tGrantedRights								= getGrantedRights( $start, $CONF['page_size'], $dbh );
   		$tCountRecords								= getCountGrantedRights( $dbh );
		$tRemainingRecords							= $tCountRecords - $start - $CONF['page_size'];
		
		if( $tRemainingRecords <= 0 ) {
			
			$tNextDisabled							= "disabled";
			
		}
		
	} elseif( $button == _(">>") ) {
		
		$count										= getCountGrantedRights( $dbh );
		$rest   									= $count % $CONF['page_size'];
		if( $rest != 0 ) {
			
			$start									= $count - $rest + 1;
			$_SESSION['svn_sessid']['rightcounter'] = floor($count / $CONF['page_size'] );
			
		} else {
		
			$start									= $count - $CONF['page_size']- 1;
			$_SESSION['svn_sessid']['rightcounter'] = floor($count / $CONF['page_size'] ) - 1;
			
		}
		
		
		$tGrantedRights								= getGrantedRights( $start, $CONF['page_size'], $dbh );
		$tNextDisabled								= "disabled";
				
	} else {
		
		$tMessage									= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
 	
   	$template		= "rep_granted_user_rights.tpl";
   	$header			= "reports";
   	$subheader		= "reports";
   	$menu			= "reports";
   
   	include ("./templates/framework.tpl");
 
 	db_disconnect( $dbh );
}
?>
