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



function getAccessRights( $valid, $start, $count, $dbh ) {
	
	$tAccessRights							= array();
	$query									= "SELECT svn_access_rights.id, svnmodule, modulepath, svnrepos." .
											  "       reponame, valid_from, valid_until, path, access_right, recursive," .
											  "       svn_access_rights.user_id, svn_access_rights.group_id " .
											  "  FROM svn_access_rights, svnprojects, svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '0000-00-00 00:00:00') " .
											  "   AND (valid_from <= '$valid' ) " .
											  "   AND (valid_until >= '$valid') " .
											  "ORDER BY svnrepos.reponame, svn_access_rights.path " .
											  "   LIMIT $start, $count";
	$result									= db_query( $query, $dbh );
	
	while( $row = db_array( $result['result'] ) ) {
		
		$entry								= $row;
		$userid								= $row['user_id'];
		$groupid							= $row['group_id'];
		$entry['groupname']					= "";
		$entry['username']					= "";
		
		if( $userid != "0" ) {
		
			$query							= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE id = $userid";
			$resultread						= db_query( $query, $dbh );
			if( $resultread['rows'] == 1 ) {
				
				$row						= db_array( $resultread['result'] );
				$entry['username']			= $row['userid'];
				
			}
	
		}
		
		if( $groupid != "0" ) {
			
			$query							= "SELECT * " .
											  "  FROM svngroups " .
											  " WHERE id = $groupid";
			$resultread						= db_query( $query, $dbh );
			if( $resultread['rows'] == 1 ) {
				
				$row						= db_array( $resultread['result'] );
				$entry['groupname']			= $row['groupname'];
				
			} else {
				$entry['groupname']			= "unknown";
			}
		}
		
		$tAccessRights[]					= $entry;
	}

	return $tAccessRights;
	
}

function getCountAccessRights( $valid, $dbh ) {
	
	$tAccessRights							= array();
	$query									= "SELECT COUNT(*) AS anz " .
											  "  FROM svn_access_rights, svnprojects, svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '0000-00-00 00:00:00') " .
											  "   AND (valid_from <= '$valid' ) " .
											  "   AND (valid_until >= '$valid') ";
	$result									= db_query( $query, $dbh );
	
	if( $result['rows'] == 1 ) {
		
		$row								= db_array( $result['result'] );
		$count								= $row['anz'];
		
		return $count;
	} else {
		
		return false;
		
	}
	
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
	
	$lang									= check_language();
			
	if( $lang == "de" ) {
		
		$tDate								= "TT.MM.JJJJ";
		
	} else {
		
		$tDate								= "MM/DD/YYYY";
	}
	
	$template								= "getDateForAccessRights.tpl";
   	$header									= "reports";
   	$subheader								= "reports";
   	$menu									= "reports";
   
   	include ("./templates/framework.tpl");
   	
   	db_disconnect( $dbh );
   	
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error									= 0;
	$button									= escape_string( $_POST['fSubmit'] );
	
	if( $button == _("Create report") ) {
		
		$tDate								= isset( $_POST['fDate'] ) ? escape_string( $_POST['fDate'] ) : "";
		$_SESSION['svn_sessid']['date']		= $tDate;
		$lang								= check_language();
			
		if( $lang == "de" ) {
			
			$day							= substr($tDate, 0, 2);
			$month							= substr($tDate, 3, 2);
			$year							= substr($tDate, 6, 4);
			
		} else {
			
			$day							= substr($tDate, 3, 2);
			$month							= substr($tDate, 0, 2);
			$year							= substr($tDate, 6, 4);
			
		}
		
		if( ! check_date( $day, $month, $year ) ) {
			
			$tMessage						= sprintf( _("Not a valid date: %s (%s-%s-%s)"), $tDate, $day, $month, $year );
			$error							= 1;
			$template						= "getDateForAccessRights.tpl";
   			$header							= "reports";
   			$subheader						= "reports";
   			$menu							= "reports";
   
   			include ("./templates/framework.tpl");
 
 			db_disconnect( $dbh );
 			exit;
			
		} else {
			
			$valid									= $year.$month.$day;
			$_SESSION['svn_sessid']['valid']		= $valid;
			$_SESSION['svn_sessid']['rightcounter']	= 0;
   			$tAccessRights							= getAccessRights( $_SESSION['svn_sessid']['valid'], 0, $CONF['page_size'], $dbh );
   			$tCountRecords							= getCountAccessRights( $_SESSION['svn_sessid']['valid'], $dbh );
   			$tPrevDisabled							= "disabled";
	
			if( $tCountRecords <= $CONF['page_size'] ) {
		
				$tNextDisabled 						= "disabled";
		
			}
			
		}
			
	} elseif( $button == _("<<") ) {
		
		$_SESSION['svn_sessid']['rightcounter']		= 0;
		$tAccessRights								= getAccessRights( $_SESSION['svn_sessid']['valid'], 0, $CONF['page_size'], $dbh );
   		$tCountRecords								= getCountAccessRights( $_SESSION['svn_sessid']['valid'], $dbh );
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
		$tAccessRights								= getAccessRights( $_SESSION['svn_sessid']['valid'], 0, $CONF['page_size'], $dbh );
   		$tCountRecords								= getCountAccessRights( $_SESSION['svn_sessid']['valid'], $dbh );
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _(">") ) {
		
		$_SESSION['svn_sessid']['rightcounter']++;
		$start										= $_SESSION['svn_sessid']['rightcounter'] * $CONF['page_size'];
		$tAccessRights								= getAccessRights( $_SESSION['svn_sessid']['valid'], $start, $CONF['page_size'], $dbh );
   		$tCountRecords								= getCountAccessRights( $_SESSION['svn_sessid']['valid'], $dbh );
		$tRemainingRecords							= $tCountRecords - $start - $CONF['page_size'];
		
		if( $tRemainingRecords <= 0 ) {
			
			$tNextDisabled							= "disabled";
			
		}
		
	} elseif( $button == _(">>") ) {
		
		$count										= getCountAccessRights( $_SESSION['svn_sessid']['valid'], $dbh );
		$rest   									= $count % $CONF['page_size'];
		if( $rest != 0 ) {
			
			$start									= $count - $rest + 1;
			$_SESSION['svn_sessid']['rightcounter'] = floor($count / $CONF['page_size'] );
			
		} else {
		
			#$start									= $count - 1;
			$start									= $count - $CONF['page_size'] - 1;
			$_SESSION['svn_sessid']['rightcounter'] = floor($count / $CONF['page_size'] ) - 1;
			
		}
		
		
		$tAccessRights								= getAccessRights( $_SESSION['svn_sessid']['valid'], $start, $CONF['page_size'], $dbh );
		$tNextDisabled								= "disabled";
				
	} else {
		
		$tMessage									= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
 	
   	$template		= "rep_access_rights.tpl";
   	$header			= "reports";
   	$subheader		= "reports";
   	$menu			= "reports";
   
   	include ("./templates/framework.tpl");
 
 	db_disconnect( $dbh );
}

?>
