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
require ("./include/createAuthFiles.php");

initialize_i18n();

$SESSID_USERNAME 							= check_session ();
$dbh 										= db_connect ();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, "Create files", $dbh );

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		  

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	
	$header									= "access";
	$subheader								= "access";
	$menu									= "access";
	$template								= "createAccessFiles.tpl";
	
   	include ("./templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	if( isset( $_POST['fSubmit'] ) ) {
		$button									= escape_string( $_POST['fSubmit'] );
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
	
	if( $button == _("Yes") ) {

		$tRetAuthUser						= createAuthUserFile( $dbh );
		$tRetAccess							= createAccessFile( $dbh );
		
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
	
   	include ("./templates/framework.tpl");
}

db_disconnect( $dbh );
?>
