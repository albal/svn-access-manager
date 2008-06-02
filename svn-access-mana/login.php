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
require ("./include/db-functions.inc.php");
require ("./include/functions.inc.php");

initialize_i18n();

$dbh 		= db_connect ();
 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   include ("./templates/login.tpl");
   
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error			= 0;
   	$fUsername 		= escape_string ($_POST['fUsername']);
   	$fPassword 		= escape_string ($_POST['fPassword']);
   	$result 		= db_query( "SELECT password " .
   								"  FROM svnusers " .
   								" WHERE userid = '$fUsername'", $dbh );
   
   	if ($result['rows'] == 1) {

      $row 			= db_array ($result['result']);
      $password 	= addslashes( pacrypt ($fPassword, $row['password']) );
      $result 		= db_query( "SELECT * " .
      							"  FROM svnusers " .
      							" WHERE userid = '$fUsername' " .
      							"   AND password = '$password'", $dbh );
      
      if ($result['rows'] != 1) {
         $error 		= 1;
         $tMessage 		= _('Username and/or password wrong');
         $tUsername 	= $fUsername;
      
      } else {
      
      	$row 			= db_array ($result['result']);
      	$id				= $row['id'];
      	$tName			= $row['name'];
      	$tGivenname		= $row['givenname'];
      	$tAdmin			= "n";
      		
  		$query			= "SELECT * " .
  					      "  FROM svn_projects_responsible " .
  					      " WHERE (user_id = $id) " .
  					      "   AND (deleted = '0000-00-00 00:00:00')";
  		$result			= db_query( $query, $dbh );
  		
  		if( $result['rows'] > 0 ) {
  			
  			$tAdmin	= 'p';
  		}

      }
      
   	} else {
      
      $error 		= 1;
      $tMessage 	= _('Username and/or password wrong');
      
   	}

   	if ( $error != 1 ) {

	  $s 									= new Session;
      session_start();
      session_register("svn_sessid");

      $_SESSION['svn_sessid']['username'] 	= $fUsername;
      $_SESSION['svn_sessid']['name']		= $tName;
      $_SESSION['svn_sessid']['givenname']	= $tGivenname;
      $_SESSION['svn_sessid']['admin']		= $tAdmin;
      
      db_log( $_SESSION['svn_sessid']['username'], "user $tUsername logged in", $dbh );

      header("Location: main.php");
      exit;
      
   	}
   
   	include ("./templates/login.tpl");
   
} 

db_disconnect ($dbh);
?>
