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
require_once ("./include/db-functions.inc.php");
require_once ("./include/functions.inc.php");
include_once ("./include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh										= db_connect();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['user_sort_fields']					= $preferences['user_sort_fields'];
$CONF['user_sort_order']					= $preferences['user_sort_order'];
$CONF['page_size']							= $preferences['page_size'];
$_SESSION['svn_sessid']['helptopic']		= "general";
   	
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$query			= "SELECT * " .
   					  "  FROM svnusers " .
   					  " WHERE (deleted = '0000-00-00 00:00:00') " .
   					  "   AND (userid = '".$SESSID_USERNAME."') " .
   					  "ORDER BY userid ASC";
	$result			= db_query( $query, $dbh );
	if( $result['rows'] == 1 ) {
		
		$row				= db_array( $result['result'] );
		$tUserid			= $row['userid'];
		$tName				= $row['name'];
		$tGivenname			= $row['givenname'];
		$tEmail				= $row['emailaddress'];
		list($date, $time)	= splitdateTime( $row['password_modified'] );
		$tPwModified		= $date." ".$time;
		$tLocked			= $row['locked'] == 0 ? _("no" ) : _( "yes" );
		$tSecurityQuestion	= $row['securityquestion'];
		$tAnswer			= $row['securityanswer'];
		
		$_SESSION['svn_sessid']['userid']		= $row['id'];
		
	} else {
		
		$tUser				= array();
		$tMessage			= _("User ".$SESSID_USERNAME." does not exist!" );
		
	}

   	$template				= "general.tpl";
   	$header					= "general";
   	$subheader				= "general";
   	$menu					= "general";
   
   	include ("./templates/framework.tpl");
   	
   	db_disconnect( $dbh );
 
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
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
	} else {
		$button									= "undef";
	}
 	
 	if( $button == _("Submit") ) {
 		
 		$tGivenname				= escape_string( $_POST['fGivenname'] );
 		$tName					= escape_string( $_POST['fName'] );
 		$tEmail					= escape_string( $_POST['fEmail'] );
 		$tSecurityQuestion		= escape_string( $_POST['fSecurityQuestion'] );
 		$tAnswer				= escape_string( $_POST['fAnswer'] );
 		$error					= 0;
 		
 		if( $tName == "" ) {
 			
 			$error				= 1;
 			$tMessage			= _( "Please fill in your name!" );
 			
 		} elseif( $tEmail == "" ) {
 			
 			$error				= 1;
 			$tMessage			= _( "Please fill in your email address!" );
 			
 		} elseif( ! check_email( $tEmail ) ) {
 			
 			$error				= 1;
 			$tMessage			= sprintf( _("%s is not a valid email address!"), $tEmail );
 			
 		} elseif( ($tAnswer != "") and ($tSecurityQuestion == "") ) {
 			
 			$error				= 1;
 			$tMessage			= _("Please fill in a security question too!" );
 			
 		} elseif( ($tAnswer == "") and ($tSecurityQuestion != "") ) {
 			
 			$error				= 1;
 			$tMessage			= _("Please fill in an answer for the security question too!");
 			
 		}
 		
 		if( $error == 0 ) {
 		
 			db_ta( 'BEGIN', $dbh );
 			db_log( $_SESSION['svn_sessid']['username'], "user changed his data( $tName, $tGivenname, $tEmail)", $dbh );
 			
			$query			= "UPDATE svnusers " .
							  "   SET givenname = '$tGivenname', " .
							  "       name = '$tName', " .
							  "       emailaddress = '$tEmail', " .
							  "       securityquestion = '$tSecurityQuestion', " .
							  "       securityanswer = '$tAnswer' ".
							  " WHERE (id = ".$_SESSION['svn_sessid']['userid'].")";
			$result			= db_query( $query, $dbh );
			
			if( mysql_errno( $dbh ) == 0 ) {
				
				db_ta( 'COMMIT', $dbh );
				$tMessage		= _("Changed data successfully" );
				
			} else {
				
				db_ta( 'ROLLBACK', $dbh );
				$tMessage		= _("Data not changed due to database errors");
			}
 		}
 		
 	} elseif( $button == _("Back") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: main.php" );
 		exit;
 	}
 	
 	$query			= "SELECT * " .
 					  "  FROM svnusers " .
 					  " WHERE (deleted = '0000-00-00 00:00:00') " .
 					  "   AND (userid = '".$SESSID_USERNAME."') " .
 					  "ORDER BY userid ASC";
	$result			= db_query( $query, $dbh );
	if( $result['rows'] == 1 ) {
		
		$row				= db_array( $result['result'] );
		$tUserid			= $row['userid'];
		$tName				= $row['name'];
		$tGivenname			= $row['givenname'];
		$tEmail				= $row['emailaddress'];
		list($date, $time)	= splitdateTime( $row['password_modified'] );
		$tPwModified		= $date." ".$time;
		$tLocked			= $row['locked'] == 0 ? _("no" ) : _( "yes" );
		$tSecurityQuestion	= $row['securityquestion'];
		$tAnswer			= $row['securityanswer'];
		
	} else {
		
		$tUser		= array();
		$tMessage	= _("User ".$SESSID_USERNAME." does not exist!" );
	}
	
   	$template		= "general.tpl";
   	$header			= "general";
   	$subheader		= "general";
   	$menu			= "general";
   
   	include ("./templates/framework.tpl");
 
 	db_disconnect( $dbh );
}
?>
