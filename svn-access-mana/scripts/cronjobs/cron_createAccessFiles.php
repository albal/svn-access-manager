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
$LastChangedDate$
$LastChangedBy$

$Id$


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

initialize_i18n();


if( $CONF['createAccessFile'] == 'YES' ) {
	
	$tRetAccess							= createAccessFile( $dbh );
	
	if( $tRetAccess['error'] != 0 ) {
		
		print $tRetAccess['errormsg']." \n";
		
	}
	
}

if( $CONF['createUserFile']	== 'YES' ) {
	
	$tRetAuthUser						= createAuthUserFile( $dbh );
	
	if( $tRetAuthUser['error'] != 0 ) {
		
		print $tRetAuthUser['errormsg']."\n";
		
	}
}

exit;
?>
