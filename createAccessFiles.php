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
	die( "can't load config.inc.php. Please check your installation!\n" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
require ("$installBase/include/createAuthFiles.php");

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Create files", $dbh );
$_SESSION['svn_sessid']['helptopic']		= "createacessfiles";

if( $rightAllowed == "none" ) {
	
	db_log( $SESSID_USERNAME, "tried to use createAccessFiles without permission", $dbh );
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	if( $CONF['createViewvcConf'] == "YES" ) {
		$tViewvcConfigNo					= "no";
		$tViewvcConfigYes					= "checked";
		$tReload							= $CONF['ViewvcApacheReload'];
	} else {
 		$tViewvcConfigNo					= "checked";
		$tViewvcConfigYes					= "";
		$tReload							= "";
 	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "createAccessFiles.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= db_escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_y_x'] ) ) {
		$button									= _("Yes");
	} elseif( isset( $_POST['fSubmit_n_x'] ) ) {
		$button									= _("No");
	} elseif( isset( $_POST['fSubmit_y'] ) ) {
		$button									= _("Yes");
	} elseif( isset( $_POST['fSubmit_n'] ) ) {
		$button									= _("No");
	} else {
		$button									= "undef";
	}
	
	$tViewvcConfig							= isset( $_POST['fViewvcConfig'] )	? db_escape_string( $_POST['fViewvcConfig'] )	: "";
	$tReload								= isset( $_POST['fReload'] )		? db_escape_string( $_POST['fReload'] )		: "";
	$tRetReload								= array();
	
	if( $button == _("Yes") ) {

		$tRetAuthUser						= createAuthUserFile( $dbh );
		$tRetAccess							= createAccessFile( $dbh );
		
		if( $tViewvcConfig == "YES" ) {
			
			$tRetViewvc						= createViewvcConfig( $dbh );
			
			if( ($tRetViewvc['error'] == 0) and ($tReload != "") ) {
				
				$output						= array();
				
				exec( escapeshellcmd($tReload), $output, $returncode );
				sleep(2);
				
				$tRetReload['error']		= $returncode;
				if( $returncode != 0 ) {
					$tRetReload['errormsg']	= _("Reloead of webserver configuration failed");
				} else {
					$tRetReload['errormsg']	= _("Reload of webserver configuration successfull");
				}
			} else {
				
				$tRetReload['error']		= 0;
				$tRetReload['errormsg']		= _("No reload sheduled");
			}
			
		} else {
			
			$tRetReload['error']			= 0;
			$tRetReload['errormsg']			= _("No reload sheduled");
			$tRetViewvc['error']			= 0;
			$tRetViewvc['errormsg']			= _("No viewvc configuration to create");
			
		}
		
		db_log( $SESSID_USERNAME, "created auth files", $dbh );
			
	} elseif( $button == _("No") ) {
	
		db_disconnect( $dbh );
		header( "location: main.php" );
		exit;
			
	} else {
		
		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
	}
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "createAccessFilesResult.tpl";
	
   	include ("$installBase/templates/framework.tpl");
}

db_disconnect( $dbh );
?>
