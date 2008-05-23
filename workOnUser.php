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



function getRights( $dbh ){

	$lang										= check_language();
	$tRightsAvailable							= array();
	$query										= "SELECT id, right_name, allowed_action, description_$lang AS description " .
												  "  FROM rights " .
												  " WHERE (deleted = '0000-00-00 00:00:00') " .
												  "ORDER BY id ASC";
	$result										= db_query( $query, $dbh );
	
	while( $row = db_array( $result['result'] ) ) {
		
		$tRightsAvailable[]						= $row;
		
	}		

	return $tRightsAvailable;
}		

function getRightsGranted( $user_id, $dbh ) {
	
	$tRightsGranted								= array();
	$query										= "SELECT right_id, allowed " .
												  "  FROM users_rights " .
												  " WHERE (user_id = $user_id) " .
												  "   AND (deleted = '0000-00-00 00:00:00')";
	$result										= db_query( $query, $dbh );
	
	while( $row = db_array( $result['result'] ) ) {
		
		$tRightsGranted[ $row['right_id'] ]		= $row['allowed'];
	}
	
	return $tRightsGranted;
}		


initialize_i18n();

$SESSID_USERNAME 							= check_session ();
$dbh 										= db_connect ();
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "User admin", $dbh );

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tReadonly								= "";
	$tTask									= escape_string( $_GET['task'] );
	if( isset( $_GET['id'] ) ) {

		$tId								= escape_string( $_GET['id'] );
		
	} else {

		$tId								= "";

	}
	
	$_SESSION['svn_sessid']['task']			= strtolower( $tTask );
	$_SESSION['svn_sessid']['userid']		= $tId;
	$tRightsAvailable						= getRights( $dbh );
	
	if( $_SESSION['svn_sessid']['task'] == "new" ) {
   		
   		$tUserid								= "";
		$tName									= "";
		$tGivenname								= "";
		$tEmail									= "";
		$tPasswordExpires						= 1;
		$tLocked								= 0;
		$tAdministrator							= "n";
		$tUserRight								= "read";
		$tRightsGranted							= array();
			
   	} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   		$tReadonly								= "readonly";
   		$query									= "SELECT * FROM svnusers WHERE id = $tId";
		$result									= db_query( $query, $dbh );
		if( $result['rows'] == 1 ) {
			
			$row								= db_array( $result['result'] );
			$tUserid							= $row['userid'];
			$tName								= $row['name'];
			$tGivenname							= $row['givenname'];
			$tEmail								= $row['emailaddress'];
			$tPasswordExpires					= $row['passwordexpires'];
			$tLocked							= $row['locked'];
			$tAdministrator						= $row['admin'];
			$tUserRight							= $row['mode'];
			$tRightsGranted						= getRightsGranted( $row['id'], $dbh );
			
		} else {
		
			$tMessage							= sprintf( _( "Invalid userid %s requested!" ), $id );	
			
		}
		
	} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   	}
   		
		
	
	$header									= "users";
	$subheader								= "users";
	$menu									= "users";
	$template								= "workOnUser.tpl";
	
   	include ("./templates/framework.tpl");

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
   	$tUserid								= escape_string( $_POST['fUserid'] );
   	$tName									= escape_string( $_POST['fName'] );
   	$tGivenname								= escape_string( $_POST['fGivenname'] );
   	$tPassword								= escape_string( $_POST['fPassword'] );
   	$tPassword2								= escape_string( $_POST['fPassword2'] );
   	$tEmail									= escape_string( $_POST['fEmail'] );
   	$tPasswordExpires						= escape_string( $_POST['fPasswordExpires'] );
   	$tLocked								= escape_string( $_POST['fLocked'] );
   	$tAdministrator							= escape_string( $_POST['fAdministrator'] );
   	$tUserRight								= escape_string( $_POST['fUserRight'] );
   	$tRightsAvailable						= getRights( $dbh );
   	
   	if( isset( $_POST['fSubmit'] ) ) {
		$button								= escape_string( $_POST['fSubmit'] );
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
   	
   	if( $button == _("Back" ) ) {
   	
   		db_disconnect( $dbh );	
   		header( "Location: list_users.php" );
   		exit;
   		
   	} elseif( $button == _( "Submit" ) ) {
   		
   		if( $_SESSION['svn_sessid']['task'] == "new" ) {
   			
   			$error							= 0;
   			
   			if( $tUserid == "" ) {
   				
   				$tMessage					= _( "Userid is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( $tName == "" ) {
   				
   				$tMessage					= _( "Name missing, please fill in!" );
   				$error						= 1;
   			
   			} elseif( ($tPassword == "") and ($tPassword2 == "") ) {
   				
				$tMessage					= _( "A new user needs a password!" );
				$error						= 1;
				 
   			} elseif( ($tPassword != "") or ($tPassword2 != "") ) {
   				
				if( $tPassword != $tPassword2 ) {
					
					$tMessage				= _( "Passwords do not match!" );
					$error					= 1;
					
				} else {
					
					$retval					= checkPasswordPolicy( $tPassword, $tAdministrator );
					if( $retval == 0 ) {
						
						$tMessage			= _( "Password does not match the password policy!" );
						$error				= 1;
						
					}
					
				}
				
   			}
   			
   			if( $tEmail == "" ) {
   				
   				$tMessage					= _( "Email address is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( ! check_email( $tEmail ) ) {
   				
   				$tMessage					= sprintf( _( "%s is not a valid email address!" ), $tEmail );
   				$error						= 1;
   				
   			} else {

   				$query						= "SELECT * " .
   											  "  FROM svnusers " .
   											  " WHERE (userid = '$tUserid') " .
   											  "   AND (deleted = '0000-00-00 00:00:00')";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] > 0 ) {
   					
   					$tMessage				= sprintf( _( "The user with the userid %s exists already" ), $tUserid );
   					$error					= 1;
   					
   				} 
   			}
  			   			
   			if( $error == 0 ) {
   				
   				$pwcrypt					= mysql_real_escape_string( pacrypt( $tPassword ) );
   				$query 						= "INSERT INTO svnusers (userid, name, givenname, password, passwordexpires, locked, emailaddress, admin, created, created_user, password_modified, mode) " .
   						                      "     VALUES ('$tUserid', '$tName', '$tGivenname', '$pwcrypt', '$tPasswordExpires', '$tLocked', '$tEmail', '$tAdministrator',now(), '".$_SESSION['svn_sessid']['username']."', '2000-01-01', '$tUserRight')";
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "added user $tUserid, $tName, $tGivenname", $dbh ); 
   				
   				$result						= db_query( $query, $dbh );
   				if( $result['rows'] == 1 ) {
   					
   					$lastid					= mysql_insert_id( $dbh );
   					
   					foreach( $tRightsAvailable as $right ) {
   					
   						$right_id			= $right['id'];
   						$field				= "fId".$right_id;
   						$value				= isset( $_POST[$field] ) ? escape_string( $_POST[$field] ) : "";
   						
   						if( $value != "" ) {
   							$query			= "SELECT * " .
   									 		  "  FROM users_rights " .
   									 		  " WHERE (right_id = $right_id) " .
   									 		  "   AND (user_id = $lastid) " .
   									 		  "   AND (deleted = '0000-00-00 00:00:00')";
   							$result			= db_query( $query, $dbh );
   							
   							if( $result['rows'] > 0 ) {
   								
   								$query			= "UPDATE users_rights " .
   												  "   SET modified = now(), " .
   												  "       modified_user = '".$_SESSION['svn_sessid']['username']."'," .
   												  "       allowed = '$value' " .
   												  " WHERE (user_id = $lastid) " .
   											  	"   AND (right_id = $right_id)";
   							
   							} else{
   							
   								$query			= "INSERT INTO users_rights (right_id, user_id, allowed, created, created_user) " .
   												  "     VALUES ($right_id, $lastid, '$value', now(), '".$_SESSION['svn_sessid']['username']."')";
   							
   							}
   							
   							$result				= db_query( $query, $dbh );
   							
   							if( $result['rows'] == 0 ) {
   								
   								$tMessage	= _("Error during database write of user rights" );
   								$error		= 1;
   								
   							}
   						}
   					}
   					
   					$tRightsGranted			= getRightsGranted( $lastid, $dbh );
   					
   				} else {
   					
   					$error					= 1;
   					$tMessaage				= _( "Error during database insert of user data" );
   					
   				}
   					
   				if( $error != 0 ) {
   					
   					db_ta( 'ROLLBACK', $dbh );
   					
   				} else {
   					
   					db_ta( 'COMMIT', $dbh );
   					
   					$tMessage				= _( "User successfully inserted" );
   					
   				}
   			}
   			
   		} elseif( $_SESSION['svn_sessid']['task'] == "change" ) {
   			
   			$error							= 0;
   			$tReadonly						= "readonly";
   			
   			if( $tUserid == "" ) {
   				
   				$tMessage					= _( "Userid is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( $tName == "" ) {
   				
   				$tMessage					= _( "Name missing, please fill in!" );
   				$error						= 1;
   			
   			} elseif( ($tPassword != "") or ($tPassword2 != "") ) {
   				
				if( $tPassword != $tPassword2 ) {
					
					$tMessage				= _( "Passwords do not match!" );
					$error					= 1;
					
				} else {
					
					$retval					= checkPasswordPolicy( $tPassword );
					if( $retval == 0 ) {
						
						$tMessage			= _( "Password does not match the password policy!" );
						$error				= 1;
						
					}
					
				}
				
   			}
   			
   			if( $tEmail == "" ) {
   				
   				$tMessage					= _( "Emailaddress is missing, please fill in!" );
   				$error						= 1;
   				
   			} elseif( ! check_email( $tEmail ) ) {
   				
   				$tMessage					= sprintf( _( "%s is not a valid email address!" ), $tEmail );
   				$error						= 1;
   				
   			} else {

   				$query						= "SELECT * " .
   											  "  FROM svnusers " .
   											  "  WHERE (userid = '$tUserid') " .
   											  "    AND (deleted = '0000-00-00 00:00:00')";
   				$result						= db_query( $query, $dbh );
   				
   				if( $result['rows'] == 0 ) {
   					
   					$tMessage				= sprintf( _( "The user %s does not exist" ), $tUserid );
   					$error					= 1;
   					
   				}
   			}
  			   			
   			if( $error == 0 ) {
   				
   				$pwcrypt					=  mysql_real_escape_string( pacrypt( $tPassword ) );
   				$query						=  "UPDATE svnusers " .
   											   "   SET name 			= '$tName', " .
   											   "       givenname 		= '$tGivenname', " .
   											   "       emailaddress 	= '$tEmail', " .
   											   "       passwordexpires 	= '$tPasswordExpires', " .
   											   "       locked 			= '$tLocked', " .
   											   "       admin 			= '$tAdministrator', " .
   											   "       mode  		    = '$tUserRight', " .
   											   "       modified 		= now(), " .
   											   "       modified_user 	= '".$_SESSION['svn_sessid']['username']."'";
   				
   				if( $tPassword != "" ) {
   				
   					$query					.= ", password = '$pwcrypt'";
   						
   				}
   				
   				$query						.= " WHERE (id = ".$_SESSION['svn_sessid']['userid'].")";
   				
   				db_ta( 'BEGIN', $dbh );
   				db_log( $_SESSION['svn_sessid']['username'], "updated user $tUserid", $dbh );
   				
   				$result						= db_query( $query, $dbh );
   				
   				if ( $result['rows'] == 1 ) {
   					
   					foreach( $tRightsAvailable as $right ) {
   					
   						$right_id			= $right['id'];
   						$field				= "fId".$right_id;
   						$value				= isset( $_POST[$field] ) ? escape_string( $_POST[$field] ) : "";
   						
   						if( $value != "" ) {
   							$query			= "SELECT * " .
   									 		  "  FROM users_rights " .
   									 		  " WHERE (right_id = $right_id) " .
   									 		  "   AND (user_id = ".$_SESSION['svn_sessid']['userid'].") " .
   									 		  "   AND (deleted = '0000-00-00 00:00:00')";
   							$result			= db_query( $query, $dbh );
   							
   							if( $result['rows'] > 0 ) {
   								
   								$query			= "UPDATE users_rights " .
   												  "   SET modified = now(), " .
   												  "       modified_user = '".$_SESSION['svn_sessid']['username']."'," .
   												  "       allowed = '$value' " .
   												  " WHERE (user_id = ".$_SESSION['svn_sessid']['userid'].") " .
   											  	"   AND (right_id = $right_id)";
   							
   							} else {
   							
   								$query			= "INSERT INTO users_rights (right_id, user_id, allowed, created, created_user) " .
   												  "     VALUES ($right_id, ".$_SESSION['svn_sessid']['userid'].", '$value', now(), '".$_SESSION['svn_sessid']['username']."')";
   							
   							}
   							
   							$result				= db_query( $query, $dbh );
   							
   							if( $result['rows'] == 0 ) {
   								
   								$tMessage	= _("Error during database write of user rights" );
   								$error		= 1;
   								
   							}
   						}
   					}
   					
   					$tRightsGranted			= getRightsGranted( $_SESSION['svn_sessid']['userid'], $dbh );
   						
   				} else {
   					
   					$tMessage 				= _( "User not modified due to database error" );
   					$error					= 1;
   				}
   				
   				if( $error == 0 ) {
   					
   					db_ta( 'COMMIT', $dbh );
   					
   					$tMessage				= _( "User successfully modified" );
   					
   				} else {
   					
   					db_ta( 'ROLLBACK', $dbh );
   					
   				}
   			}
   			
   		} else {
   			
   			$tMessage						= sprintf( _( "Invalid task %s, anyone tampered arround with?" ), $_SESSION['svn_sessid']['task'] );
   			
   		}
   		
   	} else {
   		
   		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
   		
   	}
   	
   	$header									= "users";
	$subheader								= "users";
	$menu									= "users";
	$template								= "workOnUser.tpl";
	
   	include ("./templates/framework.tpl");
   
}

db_disconnect ( $dbh );
?>
