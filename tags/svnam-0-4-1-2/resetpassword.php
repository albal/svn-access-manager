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

/*

File:  resetpassword.php
$LastChangedDate: 2010-01-19 23:19:26 +0100 (Tue, 19 Jan 2010) $
$LastChangedBy: kriegeth $

$Id: resetpassword.php 375 2010-01-19 22:19:26Z kriegeth $

*/


require ("./include/variables.inc.php");
require ("./config/config.inc.php");
require ("./include/db-functions.inc.php");
require ("./include/functions.inc.php");

initialize_i18n();

$dbh 									= db_connect ();
 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   $id									= isset( $_GET['id'] ) ? escape_string( $_GET['id'] ) : "";
   $tMessage							= "";
   $tToken								= "";
   $tPassword1							= "";
   $tPassword2							= ""; 
   
   include ("./templates/resetpassword.tpl");
   
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error								= 0;
	$id									= isset( $_GET['id'] ) ? escape_string( $_GET['id'] ) : "";
	$tToken								= escape_string( $_POST['fToken'] );
	$tPassword1							= escape_string( $_POST['fPassword1'] );
	$tPassword2							= escape_string( $_POST['fPassword2'] );
	
	if( ($tPassword1 == "") or ($tPassword2 == "") ) {
		
		$tMessage						= _("Please fill in the new password twice!" );
		$error							= 1;
		
	} elseif( $tPassword1 != $tPassword2 ) {
		
		$tMessage						= _("Passwords are different!" );
		$error							= 1;
		
	} else {
		
	   	$query							= "SELECT * " .
	   									  "  FROM svnpasswordreset " .
	   									  " WHERE (token = '$tToken') " .
	   									  "   AND (idstr = '$id')";
	   	$result							= db_query( $query, $dbh );
	   	if( $result['rows'] == 1 ) {
	   		
	   		$row						= db_array( $result['result'] );
	   		$username					= $row['username'];
	   		$timestamp					= $row['unixtime'];
	   		$pkey						= $row['id'];
	   		$days						= isset( $CONF['lostPwLinkValid'] ) ? $CONF['lostPwLinkValid'] : 2;
	   		$timestamp					= $timestamp + ($days * 86400);
	   		if( time() > $timestamp ) {
	   			
	   			$tMessage				= _("Invalid data!" );
	   			$error					= 1;
	   			
	   		} else {
	   			
	   			$query					= "SELECT admin " .
	   									  "  FROM svnusers " .
	   									  " WHERE (userid = '$username') " .
	   									  "   AND (deleted = '0000-00-00 00:00:00')";
	   			$result					= db_query( $query, $dbh );
	   			if( $result['rows'] > 0 ) {
	   				$row				= db_array( $result['result'] );
	   				$admin				= $row['admin'];
	   				if( checkPasswordPolicy( $tPassword1, $admin ) == 0 ) {
   			      
         				$tMessage 		= _("Password not strong enough!" );
         				$error			= 1;
         	
					} else { 
			   			$password 		= mysql_real_escape_string( pacrypt ($tPassword1), $dbh );
			   			$query			= "UPDATE svnusers " .
			   							  "   SET password = '$password' " .
			   							  " WHERE (userid = '$username') " .
			   							  "   AND (deleted = '0000-00-00 00:00:00')";
			   									  
			   			db_ta( "BEGIN", $dbh );
			   			$result			= db_query( $query, $dbh );
			   			if( $result['rows'] > 0 ) {
			   				
			   				$query		= "DELETE FROM svnpasswordreset " .
			   							  "      WHERE id = $pkey";
			   				$result		= db_query( $query, $dbh );
			   				if( $result['rows'] >= 0 ) {
			   					
			   					db_ta( "COMMIT", $dbh );
			   					
			   					$tMessage	= _("Your new password was set successfully!" );
			   					
			   					include ("./templates/resetpasswordresult.tpl");
			   					db_disconnect ($dbh);
			   					
			   					exit;
			   					
			   				} else {
			   					
			   					$tMessage	= _("Can't update password. Please try again later.");
			   					$error		= 1;
			   					db_ta( "ROLLBACK", $dbh );
			   				}
			   				
			   			} else {
			   				
			   				$tMessage		= _("Can't update password. Please try again later.");
			   				$error			= 1;
			   				db_ta( "ROLLBACK", $dbh );
			   				
			   			}
		   			
	   				}
	   				
	   			} else {
	   				
	   				$tMessage				= _("Your user has been deleted meanwhile!" );
	   				$error					= 1;
	   				
	   			}
	   		}
	   		
	   	} else {
	   		
	   		$tMessage						= _("No valid data!" );
	   		$error							= 1;
	   		
	   	}
	}
	
   	include ("./templates/resetpassword.tpl");
   
} 

db_disconnect ($dbh);
?>
