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

File:  securityquestion.php
$LastChangedDate$
$LastChangedBy$

$Id$

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
require ("$installBase/include/db-functions-adodb.inc.php");
require ("$installBase/include/functions.inc.php");

initialize_i18n();

$SESSID_USERNAME 						= check_session_lpw();
$dbh 									= db_connect();
$_SESSION['svn_lpw']['helptopic']		= "securityquestion";
$schema									= db_determine_schema();
 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	if (!preg_match ("/lostpassword.php/", $_SERVER['HTTP_REFERER'])) {
   	
   		header("Location: lostpassword.php");
   		exit;	
   	}
    
   	$tAnswer						= "";
	$query							= "SELECT * " .
									  "  FROM ".$schema."svnusers " .
									  " WHERE userid = '$SESSID_USERNAME'";
	$result							= db_query( $query, $dbh );
	if( $result['rows'] == 1 ) {
		$row						= db_assoc( $result['result'] );
		$tQuestion					= $row['securityquestion'];
		
		if( $tQuestion == "" ) {

			$_SESSION['svn_lpw']['error'] = _("Password reset not available for this user! Please contact the administrator!");
			db_disconnect ($dbh);
  			header("Location: lostpassword.php");
  			exit;
		}
		
	} else {
		db_disconnect ($dbh);
  		header("Location: lostpassword.php");
  		exit;
	}
   
   include ("$installBase/templates/securityquestion.tpl");
   
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$error							= 0;
   	$tAnswer 						= db_escape_string ($_POST['fAnswer']);
   	$tUsername						= $SESSID_USERNAME;
   	$result 						= db_query( "SELECT * " .
   												"  FROM ".$schema."svnusers " .
   												" WHERE userid = '$tUsername'", $dbh );
   
   	if ($result['rows'] == 1) {
      $row							= db_assoc( $result['result'] );
      $tQuestion					= $row['securityquestion'];
      $tEmailaddress				= $row['emailaddress'];
      $givenname					= $row['givenname'];
      $name							= $row['name'];
      
      if( $tAnswer != $row['securityanswer'] ) {
      
      		$error					= 1;
      		$tMessage				= _("Wrong answer!");
      			
      } else {
      	
      	$protocol					= empty($_SERVER['HTTPS']) ? "http" : "https";
      	$path						= dirname( $_SERVER['PHP_SELF'] );
      	$idstr						= create_verify_string();
      	$token						= create_verify_string();
      	$link						= $protocol."://".$_SERVER['SERVER_NAME'].$path."/resetpassword.php?id=$idstr";
      	$sender						= isset( $CONF['lostPwSender'] ) ? $CONF['lostPwSender'] : "noreply";
      	$days						= isset( $CONF['lostPwLinkValid']) ? $CONF['lostPwLinkValid'] : 2;
      	
      	$query						= "INSERT INTO ".$schema."svnpasswordreset (unixtime, username, token, idstr) " .
      								  "     VALUES (".time().", '$tUsername', '$token', '$idstr')";
      	
      	db_ta( "BEGIN", $dbh );
      	$result						= db_query( $query, $dbh );
      	if( $result['rows'] > 0 ) {
      		
      		db_ta( "COMMIT", $dbh );
      		
      		$$header 				= "From:$sender\nReply-To:$sender\nX-Mailer: PHP/" . phpversion();
	      	$text					=  sprintf(_("Hello %s %s"), $givenname, $name)."\n\n";
	      	$text					.= wordwrap(_("you requested to reset your lost password for your subversion account."))."\n\n";
	      	$text					.= wordwrap(_("Please follow the link below and enter the security token you got after answering the security question."))."\n\n";
	      	$text					.= $link."\n\n";
	      	$text					.= wordwrap(sprintf( _("The link is only valid for %s day(s)!"), $days))."\n\n";
	      	$text					.= _("Regards")."\n\n";
	      	$text					.= _("Administration")."\n";
	      	$subject				= encode_subject( _("Lost password reset"), "iso-8859-1" );
	      	
	      	if( mail($tEmailaddress, $subject, $text, $header) ) {
	      		
	      		$error					= 0;
	      		$tMessage				= sprintf( _("You received an email to reset your password! Please remember the following token: %s"), $token );
	      		
	      		db_disconnect ($dbh);
	      		
	      		include ("$installBase/templates/securityquestionresult.tpl");
	      		
	      		session_unset ();
				session_destroy ();
	      		
	      	} else {
	      		
	      		$error					= 1;
	      		$tMessage				= _("Sorry, mail could not be sent to you. Try again later please!");
	      	}
	      	
      	} else {
      		
      		db_ta( "ROLLBACK", $dbh );
      		
      		$error						= 1;
      		$tMessage					= _("Sorry password reset does not work at the moment. Please come back later!");
      		
      	}
      	
      	
	      	
      }
      
   	} else {
      
      $error 						= 1;
      $tMessage 					= _('Unknown user, anyone tampered arround with the form data? Sorry, can\'t continue');
      
   	}
   
   	include ("$installBase/templates/securityquestion.tpl");
   
} 

db_disconnect ($dbh);

?>
