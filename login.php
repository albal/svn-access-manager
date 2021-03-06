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

#error_log( "install_base is: $installBase" );

require ("$installBase/include/variables.inc.php");
#require ("./config/config.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
require ("$installBase/include/functions.inc.php");

initialize_i18n();

$dbh 									= db_connect ();
$_SESSION['svn_sessid']['helptopic']	= "login";
$schema									= db_determine_schema();
 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   include ("$installBase/templates/login.tpl");
   
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error							= 0;
   	$fUsername 						= db_escape_string ($_POST['fUsername']);
   	$fPassword 						= db_escape_string ($_POST['fPassword']);
   	$tPasswordExpired				= 0;
   	#error_log( "user = $fUsername");
   	$result 						= db_query( "SELECT password " .
   												"  FROM ".$schema."svnusers " .
   												" WHERE (userid = '$fUsername')" .
   												"   AND (deleted = '00000000000000')", $dbh );
   
   	if ($result['rows'] == 1) {

	  if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES") ) {
	  	  
	  	  $ldapres					= check_ldap_password( $fUsername, $fPassword );
	  	  if( $ldapres == 1 ) {
		  	  $result 				= db_query( "SELECT * " .
		      									"  FROM ".$schema."svnusers " .
		      									" WHERE (userid = '$fUsername') " .
		      									"   AND (deleted = '00000000000000')", $dbh );
	  	  } elseif( $ldapres == -1 ) {
	  	  	
	  	  	$error					= 1;
	  	  	$tMessage				= _("LDAP server not reachable!");
	  	  	$tUsername				= $fUsername;
	  	  	
	  	  } else {
	  	  	
	  	  	$error 					= 1;
         	$tMessage 				= _('Username and/or password wrong');
         	$tUsername 				= $fUsername;
         	
	  	  }
	  	  
	  } else {
	  	  
	  	  $row 						= db_assoc ($result['result']);
	      $password 				= addslashes( pacrypt ($fPassword, $row['password']) );
	      $result 					= db_query( "SELECT * " .
	      										"  FROM ".$schema."svnusers " .
	      										" WHERE (userid = '$fUsername') " .
	      										"   AND (password = '$password')", $dbh );
	      	  
	  }
	  
      if( ($error == 0) and ($result['rows'] != 1) ) {
         
         $error 					= 1;
         $tMessage 					= _('Username and/or password wrong');
         $tUsername 				= $fUsername;
      
      }
      
      if( $error == 0 ) {
      
      	$row 						= db_assoc ($result['result']);
      	$id							= $row['id'];
      	$tName						= $row['name'];
      	$tGivenname					= $row['givenname'];
      	$tAdmin						= $row['admin'];
      	$tPasswordExpires			= $row['passwordexpires'];
      	if( ($tPasswordExpires != 0) and (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES") ) {
	      	
	      	$tPwModified			= mkUnixTimestampFromDateTime( $row['password_modified'] );
	      	$today					= time();
	      	$maxDiff				= $CONF['password_expires'] * 86400;
	      	if( ($today - $tPwModified ) > $maxDiff ) {
	      		
	      		$tPasswordExpired	= 1;
	      		
	       	} else {
	       		
	       		$tPasswordExpired	= 0;
	       		
	       	}
       	
      	} else {
      		
      		$tPasswordExpired		= 0;
      		
      	}
	      
  		$query						= "SELECT * " .
  					      			  "  FROM ".$schema."svn_projects_responsible " .
  					      			  " WHERE (user_id = $id) " .
  					      			  "   AND (deleted = '00000000000000')";
  		$result						= db_query( $query, $dbh );
  		
  		if( ($result['rows'] > 0) and ($tAdmin == "n") ) {
  			
  			$tAdmin					= 'p';
  		}

      }
      
   	} else {
      
      $error 						= 1;
      $tMessage 					= _('Username and/or password wrong');
      
   	}

   	if ( $error != 1 ) {

	  $s 											= new Session;
      session_start();
      #session_register("svn_sessid");
      if( ! isset( $_SESSION['svn_sessid'] ) ) {
      		$_SESSION['svn_sessid']					= array();
      }
	  #error_log( "session started" );
      $_SESSION['svn_sessid']['username'] 			= $fUsername;
      $_SESSION['svn_sessid']['name']				= $tName;
      $_SESSION['svn_sessid']['givenname']			= $tGivenname;
      $_SESSION['svn_sessid']['admin']				= $tAdmin;
      $_SESSION['svn_sessid']['password_expired']	= $tPasswordExpired;
      if(isset($CONF['ldap_bind_use_login_data']) && $CONF['ldap_bind_use_login_data'] == 1) {
	  		$_SESSION['svn_sessid']['password'] 	= $fPassword;
	  }
      
      #error_log( "session data written" );
      db_log( $_SESSION['svn_sessid']['username'], "user $tUsername logged in", $dbh );
	  #error_log( "log data written" );
	  if( $tPasswordExpired == 1 ) {
	  	
	  		db_log( $_SESSION['svn_sessid']['username'], "password of user $tUsername expired, force password change", $dbh );
	  		db_disconnect ($dbh);
	  		header("Location: password.php");
	  		exit;
	  }
	  #error_log( "main");
	  db_disconnect ($dbh);
      header("Location: main.php");
      exit;
      
   	}
   
   	include ("$installBase/templates/login.tpl");
   
} 

db_disconnect ($dbh);
?>
