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
	die( "can't load config.inc.php. Check your installation!\n'" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
#require ("./config/config.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/addMemberToProject.php");
include_once ("$installBase/addGroupToProject.php");

initialize_i18n();

$SESSID_USERNAME 						= check_session();
check_password_expired();
$dbh 									= db_connect();
$preferences							= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['user_sort_fields']				= $preferences['user_sort_fields'];
$CONF['user_sort_order']				= $preferences['user_sort_order'];
$CONF['page_size']						= $preferences['page_size'];
$rightAllowed							= db_check_acl( $SESSID_USERNAME, "Project admin", $dbh );
$_SESSION['svn_sessid']['helptopic']	= "workonproject";

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		 

$schema								= db_determine_schema();

$query								= "SELECT * " .
									  "  FROM ".$schema."svnrepos " .
									  " WHERE (deleted = '00000000000000')";
$result								= db_query( $query, $dbh );
$tRepos								= array();

while( $row = db_assoc( $result['result'] ) ) {
	
	$id								= $row['id'];
	$name							= $row['reponame'];
	$tRepos[$id]					= $name;
	
}					  

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tMembers								= array();
	$tReadonly								= "";
	$tTask									= db_escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId								= db_escape_string( $_GET['id'] );
		
	} else {

		$tId								= "";

	}
	
	if( ($rightAllowed == "add") and (($tTask != "new") and ($tTask != "relist")) ) {
	
		db_disconnect( $dbh );
		header( "Location: nopermission.php" );
		exit;
	
	}		
	
	if(strtolower($tTask) != "relist") {
		
		$_SESSION['svn_sessid']['task']		= strtolower( $tTask );
		
	}
	
	$_SESSION['svn_sessid']['projectid']	= $tId;
	
	if( $tTask == "relist" ) {
		
		$tProject								= $_SESSION['svn_sessid']['project'];
		$tModulepath							= $_SESSION['svn_sessid']['modulepath'];
		$tRepo									= $_SESSION['svn_sessid']['repo'];
		$tMembers								= $_SESSION['svn_sessid']['members'];
		$tGroups								= $_SESSION['svn_sessid']['groups'];
		
	} elseif( $_SESSION['svn_sessid']['task'] == "new" ) {
   		
   		$tProject								= "";
		$tModulepath							= "";
		$tRepo									= "";
		$tMembers								= array();
		$_SESSION['svn_sessid']['members']		= array();
		$_SESSION['svn_sessid']['groups']		= array();
		$_SESSION['svn_sessid']['project']		= "";
		$_SESSION['svn_sessid']['modulepath']	= "";
		$_SESSION['svn_sessid']['repo']			= "";
			
   	} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   		$_SESSION['svn_sessid']['projectid']	= $tId;
   		$tReadonly								= "readonly";
   		$query									= "SELECT * " .
   												  "  FROM ".$schema."svnprojects, ".$schema."svnrepos " .
   												  " WHERE (svnprojects.id = $tId) " .
   												  "   AND (svnrepos.id = svnprojects.repo_id)";
		$result									= db_query( $query, $dbh );
		if( $result['rows'] == 1 ) {
			
			$row								= db_assoc( $result['result'] );
			$tProject							= $row['svnmodule'];
			$tModulepath						= $row['modulepath'];
			$tRepo								= $row['repo_id'];
			
			$query								= "SELECT svnusers.userid, svnusers.name, svnusers.givenname " .
												  "  FROM ".$schema."svnusers, ".$schema."svn_projects_responsible " .
												  " WHERE (svn_projects_responsible.project_id = $tId) " .
												  "   AND (svn_projects_responsible.deleted = '00000000000000') " .
												  "   AND (svnusers.deleted = '00000000000000') " .
												  "   AND (svn_projects_responsible.user_id = svnusers.id) " .
												  "ORDER BY ".$CONF['user_sort_fields']." ".$CONF['user_sort_order'];
			$result								= db_query( $query, $dbh );
			
			while( $row = db_assoc( $result['result'] ) ) {
				
				$userid								= $row['userid'];
				$name								= $row['name'];
				$givenname							= $row['givenname'];
				
				if( $givenname != "" ) {
					
					$name							= $givenname." ".$name;
					
				}
				
				$tMembers[$userid]					= $name;
										
			}	
			
			$_SESSION['svn_sessid']['members']		= $tMembers;
			$_SESSION['svn_sessid']['project']		= $tProject;
			$_SESSION['svn_sessid']['modulepath']	= $tModulepath;
			$_SESSION['svn_sessid']['repo']			= $tRepo;
							
		} else {
		
			$tMessage							= _( "Invalid projectid $id requested!" );	
			
		}
		
	} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   	}
   		
	$header									= "projects";
	$subheader								= "projects";
	$menu									= "projects";
	$template								= "workOnProject.tpl";
	
   	include ("$installBase/templates/framework.tpl");

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	$button									= "";
	$buttonadd								= "";
	
   	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button									= _("Submit");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button									= _("Submit");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_add_x'] ) ) {
		$button									= _("Add responsible");
	} elseif( isset( $_POST['fSubmit_add'] ) ) {
		$button									= _("Add responsible");
	} elseif( isset($_POST['fSubmit_remove_x'] ) ) {
		$button									= _("Remove responsible");
	} elseif( isset( $_POST['fSubmit_remove'] ) ) {
		$button									= _("Remove responsible");
   	} elseif( isset( $_POST['fSubmit_add_group'] ) ) {
   		$button									= _("Add group");
   	} elseif( isset( $_POST['fSubmit_add_group_x'] ) ) {
   		$button									= _("Add group");
   	} elseif( isset( $_POST['fSubmit_remove_group'] ) ) {
   		$button									= _("Remove group");
   	} elseif( isset( $_POST['fSubmit_remove_group_x'] ) ) {
   		$button									= _("Remove group");
	} else {
		$button									= "undef";
	}
	
	if( isset( $_POST['fSubmitAdd'] ) ) {
		$buttonadd 								= db_escape_string( $_POST['fSubmitAdd'] );
	} elseif( isset( $_POST['fSubmitAdd_ok_x'] ) ) {
		$buttonadd								= _("Add");
	} elseif( isset( $_POST['fSubmitAdd_ok'] ) ) {
		$buttonadd								= _("Add");
	} elseif( isset( $_POST['fSubmitAdd_back_x'] ) ) {
		$buttonadd								= _("Cancel");
	} elseif( isset( $_POST['fSubmitAdd_back'] ) ) {
		$buttonadd								= _("Cancel");
	} else {
		$buttonadd								= "undef";
	}
	
	if( isset( $_POST['fSubmitAddGroup'] ) ) {
		$buttonaddgroup							= db_escape_string( $_POST['fSubmitAdd'] );
	} elseif( isset( $_POST['fSubmitAddGroup_ok_x'] ) ) {
		$buttonaddgroup							= _("Add");
	} elseif( isset( $_POST['fSubmitAddGroup_ok'] ) ) {
		$buttonaddgroup							= _("Add");
	} elseif( isset( $_POST['fSubmitAddGroup_back_x'] ) ) {
		$buttonaddgroup							= _("Cancel");
	} elseif( isset( $_POST['fSubmitAddGroup_back'] ) ) {
		$buttonaddgroup							= _("Cancel");
	} else {
		$buttonaddgroup							= "undef";
	}
   	
   	if( isset( $_POST['fProject'] ) ) {
   		
   		$tProject							= db_escape_string( $_POST['fProject'] );
   		
   	} else {
   		
   		$tProject							= "";
   	}
   	
   	if( isset( $_POST['fModulepath'] ) ) {
   		
   		$tModulepath						= db_escape_string( $_POST['fModulepath'] );
   			
   	} else {
   		
   		$tModulepath						= "";
   	}
   	
   	if( isset( $_POST['fRepo'] ) ) {
   		
   		$tRepo								= db_escape_string( $_POST['fRepo'] );
   		
   	} else {
   			
   			$tRepo							= "";
   	}
   	
   	if( isset( $_POST['members'] ) ) {
	
		$tMembers  							= db_escape_string($_POST['members']);
		
	} else {
		
		$tMembers							= array();
	}
	
	if( isset( $_POST['groupsallowed'] ) ) {
		
		$tGroups							= db_escape_string( $_POST['groupsallowed'] );
		
	} else {
		
		$tGroups							= array();
		
	}
   	   	
   	if( $button == _("Add responsible") ) {
   		
   		$_SESSION['svn_sessid']['project']		= $tProject;
		$_SESSION['svn_sessid']['modulepath']	= $tModulepath;
		$_SESSION['svn_sessid']['repo']			= $tRepo;
			
   		addMemberToGroup($tGroups, $_SESSION['svn_sessid']['members'], $dbh );

		db_disconnect( $dbh );
		exit;
		
   	} elseif( $button == _("Remove responsible") ) {
   		
   		if( count( $tMembers ) > 0 ) {
		
			$new							= array();
			$old							= $_SESSION['svn_sessid']['members'];
			
			foreach( $old as $userid => $name ) {
			
				if( ! in_array( $userid, $tMembers ) ) {

					$new[$userid]			= $name;
										
				}
					
			}
			
			$_SESSION['svn_sessid']['members'] 	= $new;
			$tMembers						  	= $new;	
			
		} else {
			
			$tMembers							= $_SESSION['svn_sessid']['members'];
			
		}
		
   	} elseif( $button == _("Add group") ) {
   		
   		$_SESSION['svn_sessid']['project']		= $tProject;
		$_SESSION['svn_sessid']['modulepath']	= $tModulepath;
		$_SESSION['svn_sessid']['repo']			= $tRepo;
			
   		addGroupToProject($tGroups, $_SESSION['svn_sessid']['groups'], $dbh );

		db_disconnect( $dbh );
		exit;
		
   	} elseif( $button == _("Remove group" ) ) {
   	
   		if( count ( $tGroups ) > 0 ) {
   			$new								= array();
   			$old								= $_SESSION['svn_sessid']['groups'];
   			
   			foreach( $old as $groupid => $name ) {
   				
   				if( ! in_array( $groupid, $tGroups ) ) {
   					
   					$new[$groupid]				= $name;
   					
   				}
   			}
   			
   			$_SESSION['svn_sessid']['groups']	= $new;
   			$tGroups							= $new;
   			
   		} else {
   			
   			$tGroups							= $_SESSION['svn_sessid']['groups'];
   			
   		}
   		
   	} elseif( $button == _("Back" ) ) {
   	
   		db_disconnect( $dbh );	
   		header( "Location: list_projects.php" );
   		exit;
   		
   	} elseif( $button == _( "Submit" ) ) {
   		
   		if( $_SESSION['svn_sessid']['task'] == "new" ) {
   			
   			$error							= 0;
   			
   			if( $tProject == "" ) {
   				
   				$tMessage					= _( "Subversion project is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( $tModulepath == "" ) {
   				
   				$tMessage					= _( "Subversion module path missing, please fill in!" );
   				$error						= 1;
   			
   			} else {

   				$query						= "SELECT * " .
   											  "  FROM ".$schema."svnprojects " .
   											  " WHERE (svnmodule = '$tProject') " .
   											  "   AND (deleted = '00000000000000')";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= _( "The project with the name $tProject exists already" );
   					$error					= 1;
   					
   				} 
   			}
  			   			
   			if( $error == 0 ) {
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "project $tProject ($tModulepath) added", $dbh );
   				
   				$dbnow						= db_now();
   				$query 						= "INSERT INTO ".$schema."svnprojects (svnmodule, modulepath, repo_id, created, created_user) " .
   										      "     VALUES ('$tProject', '$tModulepath', '$tRepo', '$dbnow', '".$_SESSION['svn_sessid']['username']."')";
   				
   				$result						= db_query( $query, $dbh );
   				if( $result['rows'] != 1 ) {
   					
   					$tMessaage				= _( "Error during database insert" );
   					$error					= 1;
   					
   				} else {
   					
   					$projectid				= db_get_last_insert_id( 'svnprojects', 'id', $dbh );
   					
   					foreach( $_SESSION['svn_sessid']['members'] as $userid => $name ) {
						
						$query				= "SELECT * " .
											  "  FROM ".$schema."svnusers " .
											  " WHERE (userid = '$userid') " .
											  "   AND (deleted = '00000000000000')";
						$result				= db_query( $query, $dbh );
						
						if( $result['rows'] == 1 ) {
							
							db_log( $_SESSION['svn_sessid']['username'], "added project responsible $userid", $dbh );
							
							$row			= db_assoc( $result['result'] );
							$id				= $row['id'];
							$dbnow			= db_now();					
							$query			= "INSERT INTO ".$schema."svn_projects_responsible (user_id, project_id, created, created_user) " .
											  "     VALUES ($id, $projectid, '$dbnow', '".$_SESSION['svn_sessid']['username']."')";
							$result			= db_query( $query, $dbh );
							
							if( $result['rows'] != 1 ) {
								
								$tMessage	= sprintf( _("Insert of user project relation failed for user_id %s and project_id %s"), $id, $projectid );
								$error		= 1;
								
							} 
							
						} else {
							
							$tMessage		= sprintf( _("User %s not found!"), $userid );
							$error			= 1;
						}
					}
   					
   				}
   				
   				if( $error == 0 ) {
					
					db_ta( 'COMMIT', $dbh );
					db_disconnect( $dbh );
					header( "Location: list_projects.php" );
					exit;
					
				} else {
					
					db_ta( 'ROLLBACK', $dbh );
					
				}
   			}
   			
   		} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   			$error							= 0;
   			$tReadonly						= "readonly";
   			$projectid						= $_SESSION['svn_sessid']['projectid'];
   			
   			if( $tProject == "" ) {
   				
   				$tMessage					= _( "Subversion project name is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( $tModulepath == "" ) {
   				
   				$tMessage					= _( "Subversion module path missing, please fill in!" );
   				$error						= 1;
   			
   			} else {

   				$query						= "SELECT * " .
   											  "  FROM ".$schema."svnprojects " .
   											  " WHERE (svnmodule = '$tProject') " .
   											  "   AND (deleted = '00000000000000') " .
   											  "   AND (id != ".$_SESSION['svn_sessid']['projectid'].")";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= _( "The project with the name $tProject exists already" );
   					$error					= 1;
   					
   				}
   			}
  			   			
   			if( $error == 0 ) {
   				
   				$dbnow						= db_now();
   				$query						=  "UPDATE ".$schema."svnprojects " .
   											   "   SET svnmodule = '$tProject', " .
   											   "       modulepath = '$tModulepath', " .
   											   "       repo_id = $tRepo, " .
   											   "       modified = '$dbnow', " .
   											   "       modified_user = '".$_SESSION['svn_sessid']['username']."' " .
   											   " WHERE (id = ".$_SESSION['svn_sessid']['projectid'].")";
   				
   				db_ta( 'BEGIN', $dbh );
   				
   				$project						= db_getProjectById( $tProject, $dbh );
   				$repo							= db_getRepoById( $tRepo, $dbh );
   					
   				db_log( $_SESSION['svn_sessid']['username'], "updated project $tProject ($tModulepath/$repo)", $dbh );
   				
   				$result						= db_query( $query, $dbh );
   				
   				if ( $result['rows'] == 1 ) {
   					
   					$tUids					= array();
					
					foreach( $_SESSION['svn_sessid']['members'] as $uid => $name ) {
						
						$tUids[]			= $uid;
						
					}
					
					$tGroupIds				= array();
					
					foreach( $_SESION['svn_sessid']['groups'] as $groupid => $groupname ) {
						
						$tGroupIds[]		= $groupid;
					}
					
					$query					= "SELECT * " .
											  "  FROM ".$schema."svn_projects_responsible " .
											  " WHERE (project_id = $projectid) " .
											  "   AND (deleted = '00000000000000')";
					$result					= db_query( $query, $dbh );
					
					while( ($row = db_assoc( $result['result'])) and ($error == 0) ) {
						
						$userid				= db_getUseridById( $row['user_id'], $dbh );
						$uid				= $row['user_id'];
						$projectid			= $row['project_id'];

						if( ! in_array( $userid, $tUids) ) {
							
							db_log( $_SESSION['svn_sessid']['username'], "deleted $userid from $tProject as responsible", $dbh );
							$id				= $row['id'];
							$dbnow			= db_now();
							$query			= "UPDATE ".$schema."svn_projects_responsible " .
												 "SET deleted = '$dbnow', " .
												 "    deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE id = ".$id;
							$result_del		= db_query( $query, $dbh );
							
							if( $result_del['rows'] != 1 ) {
								
								$tMessage	= sprintf( _("Delete of svn_projects_responsible record with id %s failed"), $id );
								$error		= 1;
								
							} 
						}
					}

					foreach( $_SESSION['svn_sessid']['members'] as $userid => $name ) {
						
						$query				= "SELECT * " .
											  "  FROM ".$schema."svnusers " .
											  " WHERE (userid = '$userid') " .
											  "   AND (deleted = '00000000000000')";
						$result				= db_query( $query, $dbh );
						
						if( $result['rows'] == 1 ) {
							
							$row			= db_assoc( $result['result'] );
							$id				= $row['id'];
														
							$query			= "SELECT * " .
											  "  FROM ".$schema."svn_projects_responsible " .
											  " WHERE (user_id = $id) " .
											  "   AND (project_id = $projectid) " .
											  "   AND (deleted = '00000000000000')";
							$result			= db_query( $query, $dbh );
							
							#print_r($id);
							#print_r($groupid);
							#print_r($userid);
							#print_r($result);
							
							if( $result['rows'] == 0 ) {
								
								db_log( $_SESSION['svn_sessid']['username']," added project responsible $userid to project $tProject", $dbh );
								$dbnow		= db_now();
								$query		= "INSERT INTO ".$schema."svn_projects_responsible (user_id, project_id, created, created_user) " .
										      "     VALUES ($id, $projectid, '$dbnow', '".$_SESSION['svn_sessid']['username']."')";
								$result		= db_query( $query, $dbh );
								
								if( $result['rows'] == 1 ) {
									
									
									
								} else {
									
									$tMessage	= sprintf( _("Insert of user/project relation (%s/%s) failed due to database error"), $id, $projectid );
									$error		= 1;
									
								}
							}
					
						} else {
							
							$tMessage		= sprintf( _("User %s not found!"), $userid );
							$error			= 1;
							
						}		
					}
					
						
   					
   				} else {
   					
   					$tMessage 				= _( "Project not modified due to database error" );
   					$error					= 1;
   					
   				}
   				
   				if( $error == 0 ) {
					
					db_ta( 'COMMIT', $dbh );
					db_disconnect( $dbh );
					header( "Location: list_projects.php" );
					exit;
					
				} else {
					
					db_ta( 'ROLLBACK', $dbh );
					
				}
   			}
   			
   		} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   		}
   		
   	} elseif( $buttonadd == _("Add") ) {
   		
   		if( isset( $_POST['membersadd'] ) ) {
		
			$membersadd 					= db_escape_string($_POST['membersadd']);
			
		} else {
			
			$membersadd						= array();
			
		}
		
		foreach( $membersadd as $userid ) {
			
			$query							= "SELECT * " .
											  "  FROM ".$schema."svnusers " .
											  " WHERE (userid = '$userid') " .
											  "   AND (deleted = '00000000000000' )";
			$result							= db_query( $query, $dbh );
			
			if( $result['rows'] == 1 ) {
				
				$row						= db_assoc( $result['result'] );
				$name						= $row['name'];
				$givenname					= $row['givenname'];
				
				if( $givenname != "" ) {
					
					$name					= $givenname." ".$name;
					
				}
			}
			
			$_SESSION['svn_sessid']['members'][$userid]		= $name;
			
		}
		
		$project							= $_SESSION['svn_sessid']['projectid'];
		$tProject							= $_SESSION['svn_sessid']['project'];
		$tModulepath						= $_SESSION['svn_sessid']['modulepath'];
		$tRepo								= $_SESSION['svn_sessid']['repo'];
		$tMembers							= $_SESSION['svn_sessid']['members'];

		db_disconnect( $dbh );
		header("Location: workOnProject.php?id=$project&task=relist");
		exit;
		
   	} elseif( $buttonadd == _("Cancel") ) {
   	
   		$project							= $_SESSION['svn_sessid']['projectid'];
		$task								= $_SESSION['svn_sessid']['task'];
		
		db_disconnect( $dbh );
		header("Location: workOnProject.php?id=$project&task=relist");
		exit;
		
   	} elseif( $buttonaddgroup == _("Add") ) {
   		
   		if( isset( $_POST['groupsadd'] ) ) {
		
			$groupsadd 						= db_escape_string($_POST['groupsadd']);
			
		} else {
			
			$groupsadd						= array();
			
		}
		
		foreach( $groupsadd as $groupid ) {
			
			$query							= "SELECT * " .
											  "  FROM ".$schema."svngroups " .
											  " WHERE (id = '$groupid') " .
											  "   AND (deleted = '00000000000000' )";
			$result							= db_query( $query, $dbh );
			
			if( $result['rows'] == 1 ) {
				
				$row						= db_assoc( $result['result'] );
				$name						= $row['groupname'];
				
			}
			
			$_SESSION['svn_sessid']['groups'][$groupid]		= $name;
			
		}
		
		$project							= $_SESSION['svn_sessid']['projectid'];
		$tProject							= $_SESSION['svn_sessid']['project'];
		$tModulepath						= $_SESSION['svn_sessid']['modulepath'];
		$tRepo								= $_SESSION['svn_sessid']['repo'];
		$tMembers							= $_SESSION['svn_sessid']['members'];

		db_disconnect( $dbh );
		header("Location: workOnProject.php?id=$project&task=relist");
		exit;
		
   	} elseif( $buttonaddgroup == _("Cancel") ) {
   	
   		$project							= $_SESSION['svn_sessid']['projectid'];
		$task								= $_SESSION['svn_sessid']['task'];
		
		db_disconnect( $dbh );
		header("Location: workOnProject.php?id=$project&task=relist");
		exit;
		
   	} else {
   		
   		$tMessage							= sprintf( _( "Invalid button (%s/%s), anyone tampered arround with?" ), $button, $buttonadd );
   		
   	}
   	
   	$header									= "projects";
	$subheader								= "projects";
	$menu									= "projects";
	$template								= "workOnProject.tpl";
	
   	include ("$installBase/templates/framework.tpl");
   
}

db_disconnect ( $dbh );
?>
