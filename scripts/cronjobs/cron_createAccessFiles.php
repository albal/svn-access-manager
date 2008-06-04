#!/usr/bin/php

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

File:  cron_createAccessFiles.php
$LastChangedDate: 2008-06-04 14:03:46 +0200 (Wed, 04 Jun 2008) $
$LastChangedBy: kriegeth $

$Id: cron_createAccessFiles.php 213 2008-06-04 12:03:46Z kriegeth $


!!!!!=========================================================!!!!!
     Please change $INCLUDEPATH according to your installation
!!!!!=========================================================!!!!!

*/

$INCLUDEPATH						= ".";
$INCLUDEPATH						= "/home/kriegeth/svn_access_manager";

require ("$INCLUDEPATH/include/variables.inc.php");
require ("$INCLUDEPATH/config/config.inc.php");
require ("$INCLUDEPATH/include/functions.inc.php");
require ("$INCLUDEPATH/include/db-functions.inc.php");
require ("$INCLUDEPATH/include/createAuthFiles.php");

ini_set( 'max_execution_time', '3600' );
ini_set( 'display_errors', 'Off');
ini_set( 'log_errors', 'On');
ini_set( 'error_log', '/var/tmp/cron_createAccessFiles.log' );
ini_set( 'error_reporting', 'E_ALL' );


initialize_i18n();


if( $CONF['createAccessFile'] == 'YES' ) {
	
	$dbh								= db_connect();
	$tRetAccess							= createAccessFile( $dbh );
	
	if( $tRetAccess['error'] != 0 ) {
		
		print $tRetAccess['errormsg']." \n";
		
	}
	db_disconnect( $dbh );
	
}

if( $CONF['createUserFile']	== 'YES' ) {
	
	$dbh								= db_connect();
	$tRetAuthUser						= createAuthUserFile( $dbh );
	
	if( $tRetAuthUser['error'] != 0 ) {
		
		print $tRetAuthUser['errormsg']."\n";
		
	}
	
	db_disconnect( $dbh );
}

exit;
?>
