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
include_once ("$installBase/addMemberToGroup.php");



function getAccessRights( $user_id, $start, $count, $dbh ) {
	
	global $CONF;
	
	$schema									= db_determine_schema();
    
	if( $user_id != -1 ) {
		$id									= db_getIdByUserid( $user_id, $dbh );
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					      				  " WHERE (user_id = $id) " .
	  					      				  "   AND (deleted = '00000000000000')";
	} else {
		
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					      				  " WHERE (deleted = '00000000000000')";
	  					      				  
	}
	
  	$result									= db_query( $query, $dbh );
  	while( $row = db_assoc( $result['result'] ) ) {
  		
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
											  "  FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.id IN (".$tProjectIds."))" .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '00000000000000') " .
											  "ORDER BY svnrepos.reponame, svn_access_rights.path ";
		$result								= db_query( $query, $dbh, $count, $start );
		
		while( $row = db_assoc( $result['result'] ) ) {
			
			$entry							= $row;
			$userid							= $row['user_id'];
			if( empty( $userid) ) {
				$userid						= 0;
			}
			
			$groupid						= $row['group_id'];
			if( empty( $groupid) ) {
				$groupid					= 0;
			}
			
			$entry['groupname']				= "";
			$entry['username']				= "";
			
			if( $userid != "0" ) {
			
				$query						= "SELECT * " .
											  "  FROM ".$schema."svnusers " .
											  " WHERE id = $userid";
				$resultread					= db_query( $query, $dbh );
				if( $resultread['rows'] == 1 ) {
					
					$row					= db_assoc( $resultread['result'] );
					$entry['username']		= $row['userid'];
					
				}
		
			}
			
			if( $groupid != "0" ) {
				
				$query						= "SELECT * " .
											  "  FROM ".$schema."svngroups " .
											  " WHERE id = $groupid";
				$resultread					= db_query( $query, $dbh );
				if( $resultread['rows'] == 1 ) {
					
					$row					= db_assoc( $resultread['result'] );
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
	
	global $CONF;
	
	$schema									= db_determine_schema();
    
	if( $user_id != -1 ) {
		$id									= db_getIdByUserid( $user_id, $dbh );
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					      				  " WHERE (user_id = $id) " .
	  					      				  "   AND (deleted = '00000000000000')";
	} else {
		
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					      				  " WHERE (deleted = '00000000000000')";
	  					      				  
	}
	
  	$result									= db_query( $query, $dbh );
  	while( $row = db_assoc( $result['result'] ) ) {
  		
  		if( $tProjectIds == "" ) {
  			
  			$tProjectIds 					= $row['project_id'];
  			
  		} else {
  			
  			$tProjectIds					= $tProjectIds.",".$row['project_id'];
  			
  		}
  		
  	}
	
	if( $tProjectIds != "" ) {
	
		$tAccessRights						= array();
		$query								= "SELECT COUNT(*) AS anz " .
											  "  FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.id IN (".$tProjectIds."))" .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '00000000000000') ";
											  
		$result								= db_query( $query, $dbh );
		
		if( $result['rows'] == 1 ) {
			
			$row							= db_assoc( $result['result'] );
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
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Access rights admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "listaccessrights";

if( $rightAllowed == "none" ) {
	
	if( $_SESSION['svn_sessid']['admin'] == "p" ) {
		
		$tSeeUserid							= $SESSID_USERNAME;
		
	} else {
		
		db_log( $SESSID_USERNAME, "tried to use list_access_rights without permission", $dbh );
		db_disconnect( $dbh );
		header( "Location: nopermission.php" );
		exit;
		
	}
	
} else {
	
	$tSeeUserid								= -1;
	
}  

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$tAccessRights							= getAccessRights( $tSeeUserid, 0, -1, $dbh );
	$_SESSION['svn_sessid']['rightcounter']	= 0;
	$tCountRecords							= getCountAccessRights( $tSeeUserid, $dbh );
	$tPrevDisabled							= "disabled";
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "list_access_rights.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
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
	} elseif( isset( $_POST['fSearchBtn'] ) ) {
        $button                                 = _("search");
    } elseif( isset( $_POST['fSearchBtn_x'] ) ) {
        $button                                 = _("search");
	}  else {
		$button									= "undef";
	}
	
	$schema										= db_determine_schema();
	
	$tSearch                                    = isset( $_POST['fSearch'] )    ? escape_string( $_POST['fSearch'] )        : "";
 	
 	if( ($button == "search") or ($tSearch != "") ) {

    	$tSearch                               	= html_entity_decode($tSearch);
    	$_SESSION['svn_sessid']['search']       = $tSearch;
        $_SESSION['svn_sessid']['searchtype']   = "access_right";
        
    	if( $tSearch == "" ) {

        	$tErrorClass                    	= "error";
            $tMessage                       	= _("No search string given!");
            $tAccessRights						= array();

        } else {
    	
    		$schema								= db_determine_schema();
    		$tArray								= array();
    		list($repopath, $path, $reponame) 	= explode( "|", $tSearch );
    		$tProjectIds													= "";
			$query															= "SELECT * " .
									  					      				  "  FROM ".$schema."svn_projects_responsible " .
							    							  				  " WHERE (deleted = '00000000000000')";
		  	$result																= db_query( $query, $dbh );
		  	while( $row = db_assoc( $result['result'] ) ) {
		  		
		  		if( $tProjectIds == "" ) {
		  			
		  			$tProjectIds 												= $row['project_id'];
		  			
		  		} else {
		  			
		  			$tProjectIds												= $tProjectIds.",".$row['project_id'];
		  			
		  		}
		  		
		  	}
    		$query								= "SELECT svn_access_rights.id AS rid, svnmodule, modulepath, svnrepos." .
												  "       reponame, valid_from, valid_until, path, access_right, recursive," .
												  "       svn_access_rights.user_id, svn_access_rights.group_id, repopath " .
												  "  FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
												  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
												  "   AND (svnprojects.id IN (".$tProjectIds."))" .
												  "   AND (svnprojects.repo_id = svnrepos.id) " .
												  "   AND (svn_access_rights.deleted = '00000000000000') " .
												  "   AND ((svnrepos.repopath like '%$repopath%') ".
												  "    OR  (svnrepos.reponame like '%$reponame%') ".
												  "    OR  (path like '%$path%')) ".
												  "ORDER BY svnrepos.reponame, svn_access_rights.path ";
			error_log( "search = ".$query );
    		$result								= db_query( $query, $dbh );
    		while( $row = db_assoc( $result['result'])) {
    			
    			$entry							= $row;
    			$userid							= $row['user_id'];
				if( empty( $userid) ) {
					$userid						= 0;
				}
				
				$groupid						= $row['group_id'];
				if( empty( $groupid) ) {
					$groupid					= 0;
				}
				
				$entry['groupname']				= "";
				$entry['username']				= "";
				
				if( $userid != "0" ) {
				
					$query						= "SELECT * " .
												  "  FROM ".$schema."svnusers " .
												  " WHERE id = $userid";
					$resultread					= db_query( $query, $dbh );
					if( $resultread['rows'] == 1 ) {
						
						$row					= db_assoc( $resultread['result'] );
						$entry['username']		= $row['userid'];
						
					}
			
				}
				
				if( $groupid != "0" ) {
					
					$query						= "SELECT * " .
												  "  FROM ".$schema."svngroups " .
												  " WHERE id = $groupid";
					$resultread					= db_query( $query, $dbh );
					if( $resultread['rows'] == 1 ) {
						
						$row					= db_assoc( $resultread['result'] );
						$entry['groupname']		= $row['groupname'];
						
					} else {
						$entry['groupname']		= "unknown";
					}
				}
    			$tArray[]						= $entry;
    			
    		}
    		
    		if( count($tArray) == 0 ) {
    			
    			$tErrorClass                    = "info";
                $tMessage                       = _("No access rights found!");
    			
    		} elseif( count($tArray) == 1) {
    			
    			$id								= $tArray[0]['rid'];
    			$url							= "workOnAccessRight.php?id=".urlencode($id)."&task=change";
    			db_disconnect( $dbh );
    			header( "location: $url" );
    			exit;
    			
    		} else {
    			
    			db_disconnect( $dbh );
    			$_SESSION['svn_sessid']['searchresult']	= $tArray;
                header("location: searchresult.php");
                exit;
                
    		}
    	}
    	
 	} elseif( $button == _( "Back" ) ) {
		
		db_disconnect( $dbh );
		header( "Location: main.php" );
		exit;
		
	} elseif( $button == _( "New access right" ) ) {
		
		db_disconnect( $dbh );
		header( "Location: selectProject.php" );
		exit;
		
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
				
				$dbnow								= db_now();
				$query								= "UPDATE ".$schema."svn_access_rights " .
													  "   SET deleted = '$dbnow', " .
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
	
   	include ("$installBase/templates/framework.tpl");
}

db_disconnect( $dbh );
?>
