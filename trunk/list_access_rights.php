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
include_once ("./addMemberToGroup.php");



function getAccessRights( $user_id, $start, $count, $dbh ) {
	
	if( $user_id != -1 ) {
		$id									= db_getIdByUserid( $user_id, $dbh );
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM svn_projects_responsible " .
	  					      				  " WHERE (user_id = $id) " .
	  					      				  "   AND (deleted = '0000-00-00 00:00:00')";
	} else {
		
		$tProjectIds						= "";
		$query								= "SELECT * " .
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
  	
  	$tAccessRights							= array();
	
	if( $tProjectIds != "" ) {
		
		$query								= "SELECT svn_access_rights.id, svnmodule, modulepath, svnrepos." .
											  "       reponame, valid_from, valid_until, path, access_right, recursive," .
											  "       svn_access_rights.user_id, svn_access_rights.group_id " .
											  "  FROM svn_access_rights, svnprojects, svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.id IN (".$tProjectIds."))" .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '0000-00-00 00:00:00') " .
											  "ORDER BY svnrepos.reponame, svn_access_rights.path " .
											  "   LIMIT $start, $count";
		$result								= db_query( $query, $dbh );
		
		while( $row = db_array( $result['result'] ) ) {
			
			$entry							= $row;
			$userid							= $row['user_id'];
			$groupid						= $row['group_id'];
			$entry['groupname']				= "";
			$entry['username']				= "";
			
			if( $userid != "0" ) {
			
				$query						= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE id = $userid";
				$resultread					= db_query( $query, $dbh );
				if( $resultread['rows'] == 1 ) {
					
					$row					= db_array( $resultread['result'] );
					$entry['username']		= $row['userid'];
					
				}
		
			}
			
			if( $groupid != "0" ) {
				
				$query						= "SELECT * " .
											  "  FROM svngroups " .
											  " WHERE id = $groupid";
				$resultread					= db_query( $query, $dbh );
				if( $resultread['rows'] == 1 ) {
					
					$row					= db_array( $resultread['result'] );
					$entry['groupname']		= $row['groupname'];
					
				} else {
					$entry['groupname']		= "unknown";
				}
			}
			
			$tAccessRights[]				= $entry;
		}
	
	}

	return $tAccessRights;
	
}

function getCountAccessRights( $user_id, $dbh ) {
	
	if( $user_id != -1 ) {
		$id									= db_getIdByUserid( $user_id, $dbh );
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM svn_projects_responsible " .
	  					      				  " WHERE (user_id = $id) " .
	  					      				  "   AND (deleted = '0000-00-00 00:00:00')";
	} else {
		
		$tProjectIds						= "";
		$query								= "SELECT * " .
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
	
	if( $tProjectIds != "" ) {
	
		$tAccessRights						= array();
		$query								= "SELECT COUNT(*) AS anz " .
											  "  FROM svn_access_rights, svnprojects, svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.id IN (".$tProjectIds."))" .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '0000-00-00 00:00:00') " .
											  "ORDER BY svnrepos.reponame, svn_access_rights.path ";
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_array( $result['result'] );
			$count							= $row['anz'];
			
			return $count;
			
		} else {
			
			return false;
			
		}
	
	} else {
		
		return 0;
		
	}
	
}


initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['user_sort_fields']					= $preferences['user_sort_fields'];
$CONF['user_sort_order']					= $preferences['user_sort_order'];
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Access rights admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "list_access_rights";

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

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$tAccessRights							= getAccessRights( $tSeeUserid, 0, $CONF['page_size'], $dbh );
	$_SESSION['svn_sessid']['rightcounter']	= 0;
	$tCountRecords							= getCountAccessRights( $tSeeUserid, $dbh );
	$tPrevDisabled							= "disabled";
	
	if( $tCountRecords <= $CONF['page_size'] ) {
		
		$tNextDisabled 						= "disabled";
		
	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "list_access_rights.tpl";
	
   	include ("./templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
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
	} elseif( isset( $_POST['fSubmit_new_x'] ) ) {
		$button									= _("New access right");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_new'] ) ) {
		$button									= _("New access right");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_delete'] ) ) {
		$button									= _("Delete selected");
	} elseif( isset( $_POST['fSubmit_delete_x'] ) ) {
		$button									= _("Delete selected");
	} else {
		$button									= "undef";
	}
	
	if( $button == _( "Back" ) ) {
		
		db_disconnect( $dbh );
		header( "Location: main.php" );
		exit;
		
	} elseif( $button == _( "New access right" ) ) {
		
		db_disconnect( $dbh );
		header( "Location: selectProject.php" );
		exit;
		
	} elseif( $button == _("<<") ) {
		
		$_SESSION['svn_sessid']['rightcounter']		= 0;
		$tAccessRights								= getAccessRights( $tSeeUserid, 0, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountAccessRights( $tSeeUserid, $dbh );
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
		$tAccessRights								= getAccessRights( $tSeeUserid, $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountAccessRights( $tSeeUserid, $dbh );
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _(">") ) {
		
		$_SESSION['svn_sessid']['rightcounter']++;
		$start										= $_SESSION['svn_sessid']['rightcounter'] * $CONF['page_size'];
		$tAccessRights								= getAccessRights( $tSeeUserid, $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountAccessRights( $tSeeUserid, $dbh );
		$tRemainingRecords							= $tCountRecords - $start - $CONF['page_size'];
		
		if( $tRemainingRecords <= 0 ) {
			
			$tNextDisabled							= "disabled";
			
		}
		
	} elseif( $button == _(">>") ) {
		
		$count										= getCountAccessRights( $tSeeUserid, $dbh );
		$rest   									= $count % $CONF['page_size'];
		if( $rest != 0 ) {
			
			$start									= $count - $rest + 1;
			$_SESSION['svn_sessid']['rightcounter'] = floor($count / $CONF['page_size'] );
			
		} else {
			
			$start									= $count - $CONF['page_size'] - 1;
			$_SESSION['svn_sessid']['rightcounter'] = floor($count / $CONF['page_size'] ) - 1;
			
		}
		
		$_SESSION['svn_sessid']['rightcounter'] 	= floor($count / $CONF['page_size'] );
		$tAccessRights								= getAccessRights( $tSeeUserid, $start, $CONF['page_size'], $dbh );
		$tNextDisabled								= "disabled";
				
	} elseif( $button == _("Delete selected") ) {
	
		$max										= $_SESSION['svn_sessid']['max_mark'];
		$error										= 0;
		
		db_ta( 'BEGIN', $dbh );
		
		for( $i = 0; $i <= $max; $i++ ) {
		
			$field									= "fDelete".$i;
			
			if( isset( $_POST[$field] ) ) {
				
				$id									= $_SESSION['svn_sessid']['mark'][$i];
				$right								= db_getRightData( $id, $dbh );
				$projectname						= db_getProjectById( $right['project_id'], $dbh );
				
				if( $right['user_id'] != 0 ) {
					
					$userid							= db_getUseridById( $right['user_id'], $dbh );
				} else {
					
					$userid							= "";
				}
				
				if( $right['group_id'] != 0 ) {
					
					$groupname						= db_getGroupById( $right['group_id'], $dbh );
					
				} else {
					
					$groupname						= "";
					
				}
				
				$query								= "UPDATE svn_access_rights " .
													  "   SET deleted = now(), " .
													  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
													  " WHERE (id = $id)";
				$result								= db_query( $query, $dbh );
				if( $result['rows'] != 1 ) {
					
					$tMessage						= sprintf( _("Can not delete access right with id %s" ), $id );
					$error							= 1;
							
				}
				
				$logentry							= sprintf( "deleted access right %s in project %s, path %s", $right['access_right'], $projectname, $right['path'] );
				db_log( $_SESSION['svn_sessid']['username'], $logentry, $dbh );
			}
				
		}
		
		if( $error == 0 ) {
			
			db_ta( 'COMMIT', $dbh );
			db_disconnect( $dbh );
			header( "location: list_access_rights.php" );
			exit;
			
		} else {
			
			db_ta( 'ROLLBACK', $dbh );
			
		}
		
	} else {
		
		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "list_access_rights.tpl";
	
   	include ("./templates/framework.tpl");
}

db_disconnect( $dbh );
?>
