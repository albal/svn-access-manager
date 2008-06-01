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
include_once ("./addMemberToProject.php");

initialize_i18n();

$SESSID_USERNAME 					= check_session ();
$dbh 								= db_connect ();
$rightAllowed						= db_check_acl( $SESSID_USERNAME, "Project admin", $dbh );

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

$query								= "SELECT * " .
									  "  FROM svnrepos " .
									  " WHERE (deleted = '0000-00-00 00:00:00')";
$result								= db_query( $query, $dbh );
$tRepos								= array();

while( $row = db_array( $result['result'] ) ) {
	
	$id								= $row['id'];
	$name							= $row['reponame'];
	$tRepos[$id]					= $name;
	
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tReadonly								= "";
	$tTask									= escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId								= escape_string( $_GET['id'] );
		
	} else {

		$tId								= "";

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
		
	} elseif( $_SESSION['svn_sessid']['task'] == "new" ) {
   		
   		$tProject								= "";
		$tModulepath							= "";
		$tRepo									= "";
		$tMembers								= array();
		$_SESSION['svn_sessid']['members']		= array();
		$_SESSION['svn_sessid']['project']		= "";
		$_SESSION['svn_sessid']['modulepath']	= "";
		$_SESSION['svn_sessid']['repo']			= "";
			
   	} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   		$_SESSION['svn_sessid']['projectid']	= $tId;
   		$tReadonly								= "readonly";
   		$query									= "SELECT * " .
   												  "  FROM svnprojects, svnrepos " .
   												  " WHERE (svnprojects.id = $tId) " .
   												  "   AND (svnrepos.id = svnprojects.repo_id)";
		$result									= db_query( $query, $dbh );
		if( $result['rows'] == 1 ) {
			
			$row								= db_array( $result['result'] );
			$tProject							= $row['svnmodule'];
			$tModulepath						= $row['modulepath'];
			$tRepo								= $row['repo_id'];
			
			$query								= "SELECT svnusers.userid, svnusers.name, svnusers.givenname " .
												  "  FROM svnusers, svn_projects_responsible " .
												  " WHERE (svn_projects_responsible.project_id = $tId) " .
												  "   AND (svn_projects_responsible.deleted = '0000-00-00 00:00:00') " .
												  "   AND (svnusers.deleted = '0000-00-00 00:00:00') " .
												  "   AND (svn_projects_responsible.user_id = svnusers.id) " .
												  "ORDER BY svnusers.userid";
			$result								= db_query( $query, $dbh );
			
			while( $row = db_array( $result['result'] ) ) {
				
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
	
   	include ("./templates/framework.tpl");

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	$button									= "";
	$buttonadd								= "";
	
   	if( isset( $_POST['fSubmit'] ) ) {
		$button									= escape_string( $_POST['fSubmit'] );
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
	} else {
		$button									= "undef";
	}
	
	if( isset( $_POST['fSubmitAdd'] ) ) {
		$buttonadd 								= escape_string( $_POST['fSubmitAdd'] );
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
   	
   	if( isset( $_POST['fProject'] ) ) {
   		
   		$tProject							= escape_string( $_POST['fProject'] );
   		
   	} else {
   		
   		$tProject							= "";
   	}
   	
   	if( isset( $_POST['fModulepath'] ) ) {
   		
   		$tModulepath						= escape_string( $_POST['fModulepath'] );
   			
   	} else {
   		
   		$tModulepath						= "";
   	}
   	
   	if( isset( $_POST['fRepo'] ) ) {
   		
   		$tRepo								= escape_string( $_POST['fRepo'] );
   		
   	} else {
   			
   			$tRepo							= "";
   	}
   	
   	if( isset( $_POST['members'] ) ) {
	
		$tMembers  							= escape_string($_POST['members']);
		
	} else {
		
		$tMembers							= array();
	}
   	   	
   	if( $button == _("Add responsible") ) {
   		
   		$_SESSION['svn_sessid']['project']		= $tProject;
		$_SESSION['svn_sessid']['modulepath']	= $tModulepath;
		$_SESSION['svn_sessid']['repo']			= $tRepo;
			
   		addMemberToGroup($tGroup, $_SESSION['svn_sessid']['members'], $dbh );

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
			
			$tMembers						= $_SESSION['svn_sessid']['members'];
			
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
   											  "  FROM svnprojects " .
   											  " WHERE (svnmodule = '$tProject') " .
   											  "   AND (deleted = '0000-00-00 00:00:00')";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= _( "The project with the name $tProject exists already" );
   					$error					= 1;
   					
   				} 
   			}
  			   			
   			if( $error == 0 ) {
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "project $tProject ($tModulepath) added", $dbh );
   				
   				$query 						= "INSERT INTO svnprojects (svnmodule, modulepath, repo_id, created, created_user) " .
   										      "     VALUES ('$tProject', '$tModulepath', '$tRepo', now(), '".$_SESSION['svn_sessid']['username']."')";
   				
   				$result						= db_query( $query, $dbh );
   				if( $result['rows'] != 1 ) {
   					
   					$tMessaage				= _( "Error during database insert" );
   					$error					= 1;
   					
   				} else {
   					
   					$projectid				= mysql_insert_id( $dbh );
   					
   					foreach( $_SESSION['svn_sessid']['members'] as $userid => $name ) {
						
						$query				= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE (userid = '$userid') " .
											  "   AND (deleted = '0000-00-00 00:00:00')";
						$result				= db_query( $query, $dbh );
						
						if( $result['rows'] == 1 ) {
							
							db_log( $_SESSION['svn_sessid']['username'], "added project responsible $userid", $dbh );
							
							$row			= db_array( $result['result'] );
							$id				= $row['id'];
														
							$query			= "INSERT INTO svn_projects_responsible (user_id, project_id, created, created_user) " .
											  "     VALUES ($id, $projectid, now(), '".$_SESSION['svn_sessid']['username']."')";
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
   											  "  FROM svnprojects " .
   											  " WHERE (svnmodule = '$tProject') " .
   											  "   AND (deleted = '0000-00-00 00:00:00') " .
   											  "   AND (id != ".$_SESSION['svn_sessid']['projectid'].")";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= _( "The project with the name $tProject exists already" );
   					$error					= 1;
   					
   				}
   			}
  			   			
   			if( $error == 0 ) {
   				
   				$query						=  "UPDATE svnprojects " .
   											   "   SET svnmodule = '$tProject', " .
   											   "       modulepath = '$tModulepath', " .
   											   "       repo_id = $tRepo, " .
   											   "       modified = now(), " .
   											   "       modified_user = '".$_SESSION['svn_sessid']['username']."' " .
   											   " WHERE (id = ".$_SESSION['svn_sessid']['projectid'].")";
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "updated project $tProject ($tModulepath/$tRepo)", $dbh );
   				
   				$result						= db_query( $query, $dbh );
   				
   				if ( $result['rows'] == 1 ) {
   					
   					$tUids					= array();
					
					foreach( $_SESSION['svn_sessid']['members'] as $uid => $name ) {
						
						$tUids[]			= $uid;
						
					}
					
					$query					= "SELECT * " .
											  "  FROM svn_projects_responsible " .
											  " WHERE (project_id = $projectid) " .
											  "   AND (deleted = '0000-00-00 00:00:00')";
					$result					= db_query( $query, $dbh );
					
					while( ($row = db_array( $result['result'])) and ($error == 0) ) {
						
						$userid				= db_getUseridById( $row['user_id'], $dbh );

						if( ! in_array( $userid, $tUids) ) {
							
							db_log( $_SESSION['svn_sessid']['username'], "deleted $userid from $tProject as responsible", $dbh );
							$id				= $row['id'];
							$query			= "UPDATE svn_projects_responsible " .
												 "SET deleted = now(), " .
												 "    deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
											  " WHERE id = ".$id;
							$result_del		= db_query( $query, $dbh );
							
							if( $result_del['rows'] != 1 ) {
								
								$tMessage	= sprintf( _("Delete of svn_projects_responsiblep record with id %s failed"), $id );
								$error		= 1;
								
							}
						}
					}
					

					foreach( $_SESSION['svn_sessid']['members'] as $userid => $name ) {
						
						$query				= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE (userid = '$userid') " .
											  "   AND (deleted = '0000-00-00 00:00:00')";
						$result				= db_query( $query, $dbh );
						
						if( $result['rows'] == 1 ) {
							
							$row			= db_array( $result['result'] );
							$id				= $row['id'];
														
							$query			= "SELECT * " .
											  "  FROM svn_projects_responsible " .
											  " WHERE (user_id = $id) " .
											  "   AND (project_id = $projectid) " .
											  "   AND (deleted = '0000-00-00 00:00:00')";
							$result			= db_query( $query, $dbh );
							
							#print_r($id);
							#print_r($groupid);
							#print_r($userid);
							#print_r($result);
							
							if( $result['rows'] == 0 ) {
								
								db_log( $_SESSION['svn_sessid']['username'], "added project responsible $userid to project $tProject", $dbh );
								$query		= "INSERT INTO svn_projects_responsible (user_id, project_id, created, created_user) " .
										      "     VALUES ($id, $projectid, now(), '".$_SESSION['svn_sessid']['username']."')";
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
		
			$membersadd 					= escape_string($_POST['membersadd']);
			
		} else {
			
			$membersadd						= array();
			
		}
		
		foreach( $membersadd as $userid ) {
			
			$query							= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE (userid = '$userid') " .
											  "   AND (deleted = '0000-00-00 00:00:00' )";
			$result							= db_query( $query, $dbh );
			
			if( $result['rows'] == 1 ) {
				
				$row						= db_array( $result['result'] );
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
		
   	} else {
   		
   		$tMessage							= sprintf( _( "Invalid button (%s/%s), anyone tampered arround with?" ), $button, $buttonadd );
   		
   	}
   	
   	$header									= "projects";
	$subheader								= "projects";
	$menu									= "projects";
	$template								= "workOnProject.tpl";
	
   	include ("./templates/framework.tpl");
   
}

db_disconnect ( $dbh );
?>
