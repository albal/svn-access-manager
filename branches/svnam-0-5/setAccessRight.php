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

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Access rights admin", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "setaccessright";

if( ($rightAllowed != "edit") and ($rightAllowed != "delete") ) {
	
	if( $_SESSION['svn_sessid']['admin'] == "p" ) {
		
	} else {
	
		db_disconnect( $dbh );
		header( "Location: nopermission.php" );
		exit;
		
	}
	
}		

$schema										= db_determine_schema();
    
$tUsers										= array();
$query										= "SELECT * " .
											  "  FROM ".$schema."svnusers " .
											  " WHERE (deleted = '00000000000000') " .
											  "ORDER BY ".$CONF['user_sort_fields']." ".$CONF['user_sort_order'];
$result										= db_query( $query, $dbh );
while( $row = db_assoc( $result['result'] ) ) {
	
	$id										= $row['userid'];
	$name									= $row['name'];
	$givenname								= $row['givenname'];
	
	if( $givenname != "" ) {
		
		$name = $givenname." ".$name;
		
	}
	
	$tUsers[$id] 							= $name;
}	

$tGroups									= array();
$query										= "SELECT * " .
											  "  FROM ".$schema."svngroups " .
											  " WHERE (deleted = '00000000000000')";
$result										= db_query( $query, $dbh );

while( $row = db_assoc( $result['result'] ) ){
	
	$id										= $row['id'];
	$groupname								= $row['groupname'];
	$tGroups[$id]							= $groupname;
	
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	if( isset( $_GET['task'] ) ) {
		
		$_SESSION['svn_sessid']['task'] 	= db_escape_string( strtolower( $_GET['task'] ) );
		
	} else {
		
		$_SESSION['svn_sessid']['task']		= "";

	}
	
	if ( $_SESSION['svn_sessid']['task'] == "change" ) {
		
		$tReadonly							= "disabled";
		
	} else {
		
		$tReadonly							= "";
		
	}
	
	$lang									= check_language();
			
	if( $lang == "de" ) {
		
		$tDate								= "TT.MM.JJJJ";
		$tDate								= date("d").".".date("m").".".date("Y");
		$tDateFormat						= "dd-mm-yy";
		$tLocale							= "de";
		
	} else {
		
		$tDate								= "MM/DD/YYYY";
		$tDate								= date("m")."/".date("d")."/".date("Y");
		$tDateFormat						= "mm-dd-yy";
		$tLocale							= "en";
	}
	
	$tProjectName							= $_SESSION['svn_sessid']['svnmodule'];
   	$tRepoName								= $_SESSION['svn_sessid']['reponame'];
	$tRepoPath								= $_SESSION['svn_sessid']['repopath'];
	$tRepoUser								= $_SESSION['svn_sessid']['repouser'];
	$tRepoPassword							= $_SESSION['svn_sessid']['repopassword'];
	$tModulePath							= $_SESSION['svn_sessid']['modulepath'];
	$tPathSelected							= $tModulePath.$_SESSION['svn_sessid']['pathselected'];
	#error_log( $tPathSelected );
	$tPathSelected							= str_replace( '//', '/', $tPathSelected );
	#error_log( $tPathSelected );
	$tNone									= "checked";
	$tRecursive								= "checked";
	
	if( isset( $_SESSION['svn_sessid']['validfrom']) ) {
		
		$tValidFrom							= $_SESSION['svn_sessid']['validfrom'];
		
	} else {
	
		$tValidFrom							= "";
		
	}
	
	if( isset( $_SESSION['svn_sessid']['validuntil']) ) {
		
		$tValidUntil						= $_SESSION['svn_sessid']['validuntil'];
		
	} else {
	
		$tValidUntil						= "";
		
	}
	
	if( $tValidFrom == "00.00.0000" ) {
   		
   		$tValidFrom							= "";
   		
   	}
   	
   	if( $tValidUntil == "99.99.9999" ) {
   		
   		$tValidUntil						= "";
   		
   	}
	
	if( isset( $_SESSION['svn_sessid']['accessright'] ) ) {
		
		$tAccessRight						= $_SESSION['svn_sessid']['accessright'];
		
		if( $tAccessRight == "none" ) {
			
			$tNone							= "checked";
			$tRead							= "";
			$tWrite							= "";
			
		} elseif( $tAccessRight == "read" ) {
			
			$tNone							= "";
			$tRead							= "checked";
			$tWrite							= "";
			
		} elseif( $tAccessRight == "write" ) {
			
			$tNone							= "";
			$tRead							= "";
			$tWrite							= "checked";
			
		}  
		
	} else {
		
		$tAccessRight						= "";
		
	}
	
	if( isset( $_SESSION['svn_sessid']['userid'] ) ) {
		
		$tUid								= $_SESSION['svn_sessid']['userid'];
		
	} else {
		
		$tUid								= "";
		
	}
	
	if( isset( $_SESSION['svn_sessid']['groupid'] ) ) {
		
		$tGid								= $_SESSION['svn_sessid']['groupid'];
		
	} else {
		
		$tGid								= "";
		
	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "setAccessRight.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	$tProjectName							= $_SESSION['svn_sessid']['svnmodule'];
   	$tProjectid								= $_SESSION['svn_sessid']['projectid'];
   	$tRepoName								= $_SESSION['svn_sessid']['reponame'];
	$tRepoPath								= $_SESSION['svn_sessid']['repopath'];
	$tRepoUser								= $_SESSION['svn_sessid']['repouser'];
	$tRepoPassword							= $_SESSION['svn_sessid']['repopassword'];
	$tModulePath							= $_SESSION['svn_sessid']['modulepath'];
	$tPathSelected							= $tModulePath.$_SESSION['svn_sessid']['pathselected'];
	#error_log( $tPathSelected );
	$tPathSelected							= str_replace( '//', '/', $tPathSelected );
	#error_log( $tPathSelected );
   	$tAccessRight							= isset( $_POST['fAccessRight']) 	? db_escape_string( $_POST['fAccessRight'] ) 	: "";
   	$tRecursive								= isset( $_POST['fRecursive'] ) 	? db_escape_string( $_POST['fRecursive'] )		: "";
   	$tValidFrom								= isset( $_POST['fValidFrom'] )		? db_escape_string( $_POST['fValidFrom'] )		: "";
   	$tValidUntil							= isset( $_POST['fValidUntil'] )	? db_escape_string( $_POST['fValidUntil'] )		: "";
   	$tUsers									= isset( $_POST['fUsers'] )			? db_escape_string( $_POST['fUsers'] )			: array();
   	$tGroups								= isset( $_POST['fGroups'] )		? db_escape_string( $_POST['fGroups'] )			: array();
   	
   	$lang									= check_language();
			
	if( $lang == "de" ) {
		
		$tDate								= "TT.MM.JJJJ";
		$tDate								= date("d").".".date("m").".".date("Y");
		$tDateFormat						= "dd-mm-yy";
		$tLocale							= "de";
		
	} else {
		
		$tDate								= "MM/DD/YYYY";
		$tDate								= date("m")."/".date("d")."/".date("Y");
		$tDateFormat						= "mm-dd-yy";
		$tLocale							= "en";
	}
   	
   	if( isset( $_POST['fSubmit'] ) ) {
		$button								= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button								= _("Submit");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button								= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button								= _("Submit");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button								= _("Back" );
	} else {
		$button								= "undef";
	}
   	
   	if( $tAccessRight == "none" ) {
   		
   		$tNone								= "checked";
   		$tRead								= "";
   		$tWrite								= "";
   		
   	} elseif( $tAccessRight == "read" ) {
   		
   		$tNone								= "";
   		$tRead								= "checked";
   		$tWrite								= "";
   		
   	} elseif( $tAccessRight == "write" ) {
   		
   		$tNone								= "";
   		$tRead								= "";
   		$tWrite								= "checked";
   		
   	} 
   	
   	if( $button == _("Back") ) {
   		
   		db_disconnect( $dbh );
   		header( "location: list_access_rights.php" );
   		exit;
   		
   	} elseif( $button == _("Submit") ) {
   	
   		$error								= 0;   		
   		$lang								= strtolower( check_language() );
	   		
   		if( $tValidFrom != "" ) {
   			
   			if( $lang == "de" ) {
   				
   				$day						= substr( $tValidFrom, 0, 2 );
   				$month						= substr( $tValidFrom, 3, 2 );
   				$year						= substr( $tValidFrom, 6, 4 );
   				
   			} else {
   			
   				$day						= substr( $tValidFrom, 3, 2 );
   				$month						= substr( $tValidFrom, 0, 2 );
   				$year						= substr( $tValidFrom, 6, 4 );
   			}
   			
   			if( ! check_date( $day, $month, $year ) ) {
   				
   				$tMessage					= sprintf( _("Not a valid date: %s"), $tValidFrom );
   				$error						= 1;
   				
   			} else {
   				
   				$validFrom					= sprintf( "%04s%02s%02s", $year, $month, $day );
   				
   			}
   			
   		} else {
   			
   			$validFrom						= "00000000";
   		}
   		
   		if( $tValidUntil != "" ) {
   			
   			if( $lang == "de" ) {
   				
   				$day						= substr( $tValidUntil, 0, 2 );
   				$month						= substr( $tValidUntil, 3, 2 );
   				$year						= substr( $tValidUntil, 6, 4 );
   				
   			} else {
   			
   				$day						= substr( $tValidUntil, 3, 2 );
   				$month						= substr( $tValidUntil, 0, 2 );
   				$year						= substr( $tValidUntil, 6, 4 );
   				
   			}
   			
   			if( ! check_date( $day, $month, $year ) ) {
   				
   				$tMessage					= sprintf( _("Not a valid date: %s"), $tValidUntil );
   				$error						= 1;
   				
   			} else {
   				
   				$validUntil					= sprintf( "%04s%02s%02s", $year, $month, $day );
   			}
   			
   		} else {
   			
   			$validUntil						= "99999999";
   			
   		}
   		
   		if( substr( $tPathSelected, 0, 1) != "/" ) {
	   				
	   		$tPathSelected					= "/".$tPathSelected;
	   		
	   	}
	   	
	   	foreach( $tUsers as $userid ) {
	   	
	   		if( $error == 0 ) {
	   		
		   		$mode						= db_getUserRightByUserid( $userid, $dbh );
		   		if( ($tAccessRight == "write") and ($mode != "write") ) {
		   			
		   			$tMessage				= _("User is not allowed to have write access, global right is read only" );
		   			$error					= 1;
		   		}
	   		
	   		}
	   			
	   	}
	   	
	   	if( $error == 0 ) {
	   		
	   		if( $_SESSION['svn_sessid']['task'] == "change" ) {
	   	
	   			$mode						= db_getUserRightByUserid( $_SESSION['svn_sessid']['userid'], $dbh );
	   			if( ($tAccessRight == "write") and ($mode != "write") ) {
		   			
		   			$tMessage				= _("User is not allowed to have write access, global right is read only" );
		   			$error					= 1;
	   			}
	   			
	   		}
	   	
	   	}
	   	
	   	if( ($_SESSION['svn_sessid']['task'] == "new") and (count($tUsers) == 0) and (count($tGroups) == 0) ) {
	   		
	   		$tMessage						= _("No user and no group selected!");
	   		$error							= 1; 
	   		
	   	}
	   			
	   	$curdate							= strftime( "%Y%m%d" );
	   	
   		if( $error == 0 ) {
	   		
	   		if( $_SESSION['svn_sessid']['task'] == "change" ) {
	   			
	   			db_ta( 'BEGIN', $dbh );
	   			
	   			$tId							= $_SESSION['svn_sessid']['rightid'];
	   			$olddata						= db_getRightData( $tId, $dbh );
	   			$dbnow							= db_now();
	   			$query							= "UPDATE ".$schema."svn_access_rights " .
	   											  "   SET modified = '$dbnow', " .
	   											  "       modified_user = '".$_SESSION['svn_sessid']['username']."', " .
	   											  "       valid_from = '$validFrom', " .
	   											  "       valid_until = '$validUntil', " .
	   											  "       access_right = '$tAccessRight' " .
	   											  " WHERE (id = $tId)";
	   			$result							= db_query( $query, $dbh );
	   			
	   			if( $result['rows'] == 1 ) {
	   				
	   				$user						= db_getUseridById ( $olddata['user_id'], $dbh );
	   				$repo						= db_getRepoById ($olddata['repo_id'], $dbh );
	   				$path						= $olddata['path'];
	   				$oldright					= $olddata['access_right'];
	   				
	   				db_log( $_SESSION['svn_sessid']['username'], "updated access right from $oldright to $tAccessRight for $user in $repo for $path", $dbh );
	   				db_ta( 'COMMIT', $dbh );
	   				db_disconnect( $dbh );
	   				
	   				header( "location: list_access_rights.php" );
	   				exit;
	   				
	   			} else {
	   				
	   				db_ta( 'ROLLBACK', $dbh );
	   				$tMessage				= _("Error while writing access right modification" );
	   				
	   			}
	   			
	   		} else {
	   			
		   		if( $error == 0 ) {
		   			
		   			db_ta( 'BEGIN', $dbh );
		   			
		   			foreach( $tUsers as $userid ) {
		   				
		   				$id							= db_getIdByUserid( $userid, $dbh );
		   				$mode						= db_getUserRightByUserid( $userid, $dbh );
		   				$query						= "SELECT * " .
		   											  "  FROM ".$schema."svn_access_rights " .
		   											  " WHERE (user_id = '$id') " .
		   											  "   AND (path = '$tPathSelected') " .
		   											  "   AND (deleted = '00000000000000') " .
		   											  "   AND (project_id = '$tProjectid') ";
		   				$result						= db_query( $query, $dbh );
		   				
		   				while( ($row = db_assoc( $result['result'] )) and ($error == 0) ) {
		   				
		   					$rightid				= $row['id'];
		   					$tPathSelected			= $row['path'];
		   					$dbnow					= db_now();
		   					$query					= "UPDATE ".$schema."svn_access_rights " .
		   											  "   SET deleted = '$dbnow', " .
		   											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
		   											  " WHERE (id = $rightid)";
		   					$resultupd				= db_query( $query, $dbh );
		   					if( $resultupd['rows'] != 1 ) {
		   						
		   						$tMessage			= _("Error while deleting access right");
		   						$error				= 1;
		   					}	
		   					
		   					db_log( $_SESSION['svn_sessid']['username'], "deleted access right for $userid for $tPathSelected", $dbh );
		   				}
		   				
		   				$dbnow						= db_now();
		   				$query						= "INSERT INTO ".$schema."svn_access_rights " .
		   											  "            (project_id, user_id, path, valid_from, valid_until, access_right, created, created_user) " .
		   											  "     VALUES ('$tProjectid', '$id', '$tPathSelected', '$validFrom', '$validUntil', '$tAccessRight', '$dbnow', '".$_SESSION['svn_sessid']['username']."')";
		   				$result						= db_query( $query, $dbh );
		   				if( $result['rows'] != 1 ) {
		   					
		   					$tMessage				= sprintf( _("Error while inserting access right for user %s" ), $userid );
		   					$error					= 1;
		   				}	
		   				
		   				db_log( $_SESSION['svn_sessid']['username'], "added access right $tAccessRight for ".$userid." to $tPathSelected", $dbh );
		   			} 
		   			
		   			if( $error == 0 ) {
		   			
		   				foreach( $tGroups as $groupid ) {
		   				
		   					$query						= "SELECT * " .
		   											      "  FROM ".$schema."svn_access_rights " .
		   											      " WHERE (group_id = '$groupid') " .
		   											      "   AND (path = '$tPathSelected') " .
		   											      "   AND (deleted = '00000000000000') " .
		   											      "   AND (project_id = '$tProjectid') ";
			   				$result						= db_query( $query, $dbh );
			   				
			   				while( ($row = db_assoc( $result['result'] )) and ($error == 0) ) {
			   				
			   					$rightid				= $row['id'];
			   					$dbnow					= db_now();
			   					$query					= "UPDATE ".$schema."svn_access_rights " .
			   											  "   SET deleted = '$dbnow', " .
			   											  "       deleted_user = '".$_SESSION['svn_sessid']['username']."' " .
			   											  " WHERE (id = $rightid)";
			   					$resultupd				= db_query( $query, $dbh );
			   					if( $resultupd['rows'] != 1 ) {
			   						
			   						$tMessage			= _("Error while deleting access right");
			   						$error				= 1;
			   					}	
			   					
			   					db_log( $_SESSION['svn_sessid']['username'], "deleted access right for $userid for $tPathSelected", $dbh );
			   				}
		   				
		   					$dbnow						= db_now();
		   					$query						= "INSERT INTO ".$schema."svn_access_rights " .
		   												  "            (project_id, group_id, path, valid_from, valid_until, access_right, created, created_user) " .
		   												  "     VALUES ('$tProjectid', '$groupid', '$tPathSelected', '$validFrom', '$validUntil', '$tAccessRight', '$dbnow', '".$_SESSION['svn_sessid']['username']."')";
		   					$result						= db_query( $query, $dbh );
		   					if( $result['rows'] != 1 ) {
		   					
		   						$tMessage				= sprintf( _("Error while inserting access right for group %s" ), $groupid );
		   						$error					= 1;
		   					}	
		   					
		   					db_log( $_SESSION['svn_sessid']['username'], "added access right $tAccessRight for $groupid to $tPathSelected", $dbh );
		   				}
		   				
		   			}
		   			
		   			if( $error == 0 ) {
		   				
		   				db_ta( 'COMMIT', $dbh );
		   				
		   			} else {
		   				
		   				db_ta( 'ROLLBACK', $dbh );
		   			}
	   			}
	   		}
   		}
   		
   		if( $error == 0 ) {
   			
   			db_disconnect( $dbh );
   			header( "location: list_access_rights.php" );
   			exit;
   			
   		}
   	
   	} else {
   		
   		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
   		
   	}
   	
   	$tUsers										= array();
	$query										= "SELECT * " .
												  "  FROM ".$schema."svnusers " .
												  " WHERE (deleted = '00000000000000') " .
												  "ORDER BY ".$CONF['user_sort_fields']." ".$CONF['user_sort_order'];
	$result										= db_query( $query, $dbh );
	
	while( $row = db_assoc( $result['result'] ) ) {
		
		$id										= $row['userid'];
		$name									= $row['name'];
		$givenname								= $row['givenname'];
		
		if( $givenname != "" ) {
			
			$name = $givenname." ".$name;
			
		}
		
		$tUsers[$id] 							= $name;
	}	
	
	$tGroups									= array();
	$query										= "SELECT * " .
												  "  FROM ".$schema."svngroups " .
												  " WHERE (deleted = '00000000000000')";
	$result										= db_query( $query, $dbh );
	
	while( $row = db_assoc( $result['result'] ) ){
		
		$id										= $row['id'];
		$groupname								= $row['groupname'];
		$tGroups[$id]							= $groupname;
		
	}
	
	if( isset( $_SESSION['svn_sessid']['userid'] ) ) {
		
		$tUid								= $_SESSION['svn_sessid']['userid'];
		
	} else {
		
		$tUid								= "";
		
	}
	
	if( isset( $_SESSION['svn_sessid']['groupid'] ) ) {
		
		$tGid								= $_SESSION['svn_sessid']['groupid'];
		
	} else {
		
		$tGid								= "";
		
	}
	
	if ( $_SESSION['svn_sessid']['task'] == "change" ) {
		
		$tReadonly							= "disabled";
		
	} else {
		
		$tReadonly							= "";
		
	}
   	
   	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "setAccessRight.tpl";
	
   	include ("$installBase/templates/framework.tpl");
  
}
?>
