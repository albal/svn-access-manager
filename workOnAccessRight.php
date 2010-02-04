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

$SESSID_USERNAME 								= check_session ();
check_password_expired();
$dbh 											= db_connect ();
$preferences									= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['user_sort_fields']						= $preferences['user_sort_fields'];
$CONF['user_sort_order']						= $preferences['user_sort_order'];
$CONF['page_size']								= $preferences['page_size'];
$rightAllowed									= db_check_acl( $SESSID_USERNAME, "Access rights admin", $dbh );
$_SESSION['svn_sessid']['helptopic']			= "workonaccessright";
$accessControl									= isset( $CONF['accessControl'] ) ? $CONF['accessControl'] : "dirs";

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

if( $tSeeUserid != -1 ) {
	$id										= db_getIdByUserid( $SESSID_USERNAME, $dbh );
	$tProjectIds							= "";
	$query									= "SELECT * " .
  					      					  "  FROM svn_projects_responsible " .
  					      				  	  " WHERE (user_id = $id) " .
  					      				  	  "   AND (deleted = '0000-00-00 00:00:00')";
} else {
	
	$tProjectIds							= "";
	$query									= "SELECT * " .
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

$uId											= db_getIdByUserid( $SESSID_USERNAME, $dbh );
$tProjects										= array();
if( $tProjectIds != "" ) {
	$query										= "SELECT svnprojects.id, svnmodule, modulepath, reponame, " .
												  "       repopath, repouser, repopassword " .
												  "  FROM svn_projects_responsible, svnprojects, svnrepos " .
												  " WHERE (svnprojects.id IN (".$tProjectIds.")) " .
												  "   AND (svn_projects_responsible.project_id = svnprojects.id) " .
												  "   AND (svnprojects.repo_id = svnrepos.id) " .
												  "   AND (svn_projects_responsible.deleted = '0000-00-00 00:00:00') " .
												  "   AND (svnprojects.deleted = '0000-00-00 00:00:00')";
	$result										= db_query( $query, $dbh );
	while( $row = db_array( $result['result'] ) ) {
	
		$tProjects[ $row['id'] ]				= $row['svnmodule'];
			
	}
	
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tReadonly									= "";
	$fileSelect									= 0;
	$tTask										= escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId									= escape_string( $_GET['id'] );
		
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
		
		unset( $_SESSION['svn_sessid']['validfrom']	);
		unset( $_SESSION['svn_sessid']['validuntil'] );
		unset( $_SESSION['svn_sessid']['accessright'] );
		unset( $_SESSION['svn_sessid']['userid'] );
		unset( $_SESSION['svn_sessid']['groupid'] );
   		
		$query									= "SELECT * " .
												  "  FROM svnprojects " .
												  " WHERE id = ".$_SESSION['svn_sessid']['projectid'];
		$result									= db_query( $query, $dbh );
		if( $result['rows'] == 1 ) {
			
			$row								= db_array( $result['result'] );
			$tProject							= $row['id'];
			$tProjectName						= $row['svnmodule'];
			$_SESSION['svn_sessid']['svnmodule']= $tProjectName;
			$tModulePath						= $row['modulepath'];
			$_SESSION['svn_sessid']['modulepath']	= $tModulePath;
			$_SESSION['svn_sessid']['path']		= array();
			$_SESSION['svn_sessid']['path'][0]	= "";
			$_SESSION['svn_sessid']['pathcnt']	= 0;
			$tRepoId							= $row['repo_id'];
			$query								= "SELECT * " .
												  "  FROM svnrepos " .
												  " WHERE id = $tRepoId";
			$result								= db_query( $query, $dbh );
			if( $result['rows'] == 1 ) {
				
				$row							= db_array( $result['result'] );
				$tRepoName						= $row['reponame'];
				$tRepoPath						= $row['repopath'];
				$tRepoUser						= $row['repouser'];
				$tRepoPassword					= $row['repopassword'];
				
				$_SESSION['svn_sessid']['reponame']		= $tRepoName;
				$_SESSION['svn_sessid']['repopath']		= $tRepoPath;
				$_SESSION['svn_sessid']['repouser']		= $tRepoUser;
				$_SESSION['svn_sessid']['repopassword']	= $tRepoPassword;
				$os										= determineOs();
				
				if( $os == "windows" ) {
					$tempdir					= "c:/temp";
				} else {
					$tempdir					= "/var/tmp/";
				}
				
				if( strtolower(substr($tRepoPath, 0, 4) == "http") ) {
					$options					= " --username $tRepoUser --password $tRepoPassword ";
				} else {
					$options					= "";
				}
				
				$repopath						= preg_replace( '/\\\/', '/', $tRepoPath );
				$tRepodirs						= array();
				$cmd							= $CONF['svn_command'].' list --no-auth-cache --non-interactive --config-dir '.$tempdir.' '.$options.' '.$repopath.'/'.$tModulePath;
				if( strtolower($accessControl) != "files" ) {
					$cmd						.= '|'.$CONF['grep_command'].' "/$"';
				}
				error_log( $cmd );
				$errortext						= exec( $cmd, $tRepodirs, $retval );
				
				if( $retval == 0 ) {
					
					$tPathSelected				= "";
					
				} else {
					
					$tMessage					= sprintf( _("Error while accessing svn repository: %s (%s / retcode = %s)"), $errortext, $cmd, $retval);
					
				}
				
			} else {
				
				$tMessage						= sprintf( _("Invalid repository id %s requested!"), $tRepoId );
				
			}
			
		} else {
			
			$tMessage							= sprintf( _("Invalid project id %s requested"), $_SESSION['svn_sessid']['projectid'] );
		}
			
   	} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   		$tReadonly								= "readonly";
   		$query									= "SELECT * " .
   												  "  FROM svn_access_rights " .
   												  " WHERE id = $tId";
		$result									= db_query( $query, $dbh );
		if( $result['rows'] == 1 ) {
			
			$row								= db_array( $result['result'] );
			$rightid							= $row['id'];
			$projectid							= $row['project_id'];
			$tPathSelected						= $row['path'];
			$validfrom							= $row['valid_from'];
			$validuntil							= $row['valid_until'];
			$accessright						= $row['access_right'];
			$groupid							= $row['group_id'];
			$userid								= $row['user_id'];
			
			if( $userid != 0 ) {
			
				$userid							= db_getUseridById( $userid, $dbh );
			
			}

			$lang								= strtolower( check_language() );
			
			if( $lang == "de" ) {
			
				$validfrom						= substr($validfrom, 6, 2).".".substr($validfrom, 4, 2).".".substr($validfrom, 0, 4);
				$validuntil						= substr($validuntil, 6, 2).".".substr($validuntil, 4, 2).".".substr($validuntil, 0, 4);
				
			} else {
				
				$validfrom						= substr($validfrom, 4, 2).".".substr($validfrom, 0, 2).".".substr($validfrom, 0, 4);
				$validuntil						= substr($validuntil, 4, 2).".".substr($validuntil, 0, 2).".".substr($validuntil, 0, 4);
				
			}
			
			$_SESSION['svn_sessid']['pathselected']	=$tPathSelected;
			$_SESSION['svn_sessid']['validfrom']	= $validfrom;
			$_SESSION['svn_sessid']['validuntil']	= $validuntil;
			$_SESSION['svn_sessid']['accessright']	= $accessright;
			$_SESSION['svn_sessid']['userid']		= $userid;
			$_SESSION['svn_sessid']['groupid']		= $groupid;
			$_SESSION['svn_sessid']['rightid']		= $tId;
			
		
			$query								= "SELECT * " .
												  "  FROM svnprojects " .
												  " WHERE id = '$projectid'";
			$result								= db_query( $query, $dbh );
			if( $result['rows'] == 1 ) {
				
				$row								= db_array( $result['result'] );
				$tProject							= $row['id'];
				$tProjectName						= $row['svnmodule'];
				$_SESSION['svn_sessid']['svnmodule']= $tProjectName;
				$tModulePath						= $row['modulepath'];
				$_SESSION['svn_sessid']['modulepath']	= $tModulePath;
				$tRepoId							= $row['repo_id'];
				$query								= "SELECT * " .
													  "  FROM svnrepos " .
													  " WHERE id = $tRepoId";
				$result								= db_query( $query, $dbh );
				if( $result['rows'] == 1 ) {
					
					$row							= db_array( $result['result'] );
					$tRepoName						= $row['reponame'];
					$tRepoPath						= $row['repopath'];
					$tRepoUser						= $row['repouser'];
					$tRepoPassword					= $row['repopassword'];
					
					$_SESSION['svn_sessid']['reponame']		= $tRepoName;
					$_SESSION['svn_sessid']['repopath']		= $tRepoPath;
					$_SESSION['svn_sessid']['repouser']		= $tRepoUser;
					$_SESSION['svn_sessid']['repopassword']	= $tRepoPassword;
				}
				
			} else {
				
				$tMessage						= sprintf( _("Invalid project id %s requested"), $projectid );
				
			}
			
			db_disconnect( $dbh );
   			header( "location: setAccessRight.php?task=change" );
   			exit;
			
		} else {
		
			$tMessage							= _( "Invalid access right id $tId requested!" );	
			
		}
		
	} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   	}
   		
		
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "workOnAccessRight.tpl";
	
   	include ("./templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	$tProjectName							= $_SESSION['svn_sessid']['svnmodule'];
   	$tRepoName								= $_SESSION['svn_sessid']['reponame'];
	$tRepoPath								= $_SESSION['svn_sessid']['repopath'];
	$tRepoUser								= $_SESSION['svn_sessid']['repouser'];
	$tRepoPassword							= $_SESSION['svn_sessid']['repopassword'];
	$tModulePath							= $_SESSION['svn_sessid']['modulepath'];
		
   	if( isset( $_POST['fSubmit'] ) ) {
		$button								= escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_chdir_x'] ) ) {
		$button								= _("Change to directory");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button								= _("Back" );
	} elseif( isset( $_POST['fSubmit_chdir'] ) ) {
		$button								= _("Change to directory");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button								= _("Back" );
   	} elseif( isset( $_POST['fSubmit_set_x'] ) ) {
   		$button								= _("Set access rights");
   	} elseif( isset( $_POST['fSubmit_set'] ) ) {
		$button								= _("Set access rights");
	} else {
		$button								= "";
	}
   	
   	if( $button == _("Back") ) {
   		
   		db_disconnect( $dbh );
   		header( "location: list_access_rights.php" );
   		exit;
   		
   	} elseif( ($button == _("Change to directory")) or ($button == "") ) {
   		
   		$fileSelect							= 0;
   		
		if( isset( $_POST['fPath'] ) ) {
   			
   			$tPath							= escape_string( $_POST['fPath'] ) ;
   			
		} else {
			
			$tPath							= "";
			
		}
		
   		if( $tPath == '[back]' ) {
   			
   			$count 							= count ( $_SESSION['svn_sessid']['path'] ) - 1;
   			
   			if( $count > 0 ) {
   				
   				array_pop( $_SESSION['svn_sessid']['path'] );
   				$_SESSION['svn_sessid']['pathcnt']--;
   			}
   		
   		} elseif( $tPath == "" ) {
   			
   			# do nothing
   			
   		} else {
   		
   			$_SESSION['svn_sessid']['pathcnt']++;
   			if( preg_match( '/\/$/', $tPath ) ) {
   				
   				$tPath						= substr( $tPath, 0, (strlen($tPath) - 1) );
   				
   			} else {
   				$fileSelect					= 1;
   			}
   			$_SESSION['svn_sessid']['path'][ $_SESSION['svn_sessid']['pathcnt'] ]= $tPath;
   			
   		}
   		
   		$tRepodirs							= array();
   		$tPathSelected						= implode( "/", $_SESSION['svn_sessid']['path'] );
		$os									= determineOs();
				
		if( $os == "windows" ) {
			$tempdir						= "c:/temp";
		} else {
			$tempdir						= "/var/tmp/";
		}
		
		if( strtolower(substr($tRepoPath, 0, 4) == "http") ) {
			$options						= " --username $tRepoUser --password $tRepoPassword ";
		} else {
			$options						= "";
		}
		
		$tRepodirs							= array();
		$repopath							= preg_replace( '/\\\/', '/', $tRepoPath );
		$cmd								= $CONF['svn_command'].' list --no-auth-cache --non-interactive --config-dir '.$tempdir.' '.$options.' '.$repopath.'/'.$tModulePath.'/'.$tPathSelected;
		if( strtolower($accessControl) != "files" ) {
			$cmd							.= '|'.$CONF['grep_command'].' "/$"';
		}
		error_log( $cmd );
		$errortext							= exec( $cmd, $tRepodirs, $retval );
   		
   	} elseif( $button == _("Set access rights") ) {
   		
   		if( isset( $_POST['fPathSelected'] ) ) {
   		
   			$tPath							= escape_string( $_POST['fPathSelected'] );
   			
   		} else {
   			
   			$tPath							= "";
   			
   		}
   		
   		if( substr( $tPath, 0, 1) != "/" ) {
   			$tPath							= "/".$tPath;
   		}
   		
   		$_SESSION['svn_sessid']['pathselected']	= $tPath;
   			
   		db_disconnect( $dbh );
   		header( "location: setAccessRight.php" );
   		exit;
   		
   	} else {
   		
   		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
   		
   	}
   	
   	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "workOnAccessRight.tpl";
	
   	include ("./templates/framework.tpl");
  
}
?>
