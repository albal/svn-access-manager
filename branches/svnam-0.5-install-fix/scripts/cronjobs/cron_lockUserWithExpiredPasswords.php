#!/usr/bin/php

<?php

/*
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>

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

File:  cron_lockUserWithExpiredPasswords.php
$LastChangedDate$
$LastChangedBy$

$Id$



!!!!!=========================================================!!!!!
     Please change $INCLUDEPATH according to your installation
!!!!!=========================================================!!!!!

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

$INCLUDEPATH						= isset( $CONF[INSTALLBASE] ) ? $CONF['install_base' ] : "";

require ("$INCLUDEPATH/include/variables.inc.php");
require ("$INCLUDEPATH/include/functions.inc.php");
require ("$INCLUDEPATH/include/db-functions-adodb.inc.php");


ini_set( 'max_execution_time', '3600' );
ini_set( 'display_errors', 'Off');
ini_set( 'log_errors', 'On');
ini_set( 'error_log', '/var/tmp/cron_lockUserWithExpiredPasswords.log' );
ini_set( 'error_reporting', 'E_ALL' );


initialize_i18n();


$dbh								= db_connect();
$url								= $CONF['website_url'];

$query								= "SELECT * " .
									  "  FROM svnusers " .
									  " WHERE (deleted = '00000000000000') " .
									  "   AND (passwordexpires = 1) ";
$result								= db_query( $query, $dbh );

while( $row = db_assoc( $result['result'] ) ) {
	
	$userid							= $row['userid'];
	$id								= $row['id'];
	$password_modified				= mkUnixTimestampFromDateTime( $row['password_modified'] );
	$diff_warn						= $CONF['password_expires_warn'] * 86400;
	$diff_expire					= $CONF['password_expires'] * 86400;
	$emailaddress					= $row['emailaddress'];
	$name							= $row['name'];
	
	if( $row['givenname'] != "" ) {
	
		$name						= $row['givenname']." ".$name;
		
	}
	
	#error_log( "start working on user $userid( $name)");
	
	$curtime						= time();
	
	if( ($curtime - $password_modified) > $diff_expire ) {
		
		db_ta( 'BEGIN', $dbh );
		
		$query						= "UPDATE svnusers " .
									  "   SET locked = 1 " .
									  " WHERE id = $id";
		$resultupd					= db_query( $query, $dbh );
		
		if( $resultupd['rows'] == 1 ) {
		
			$mailtext				= sprintf( $CONF['mail_password_warn'], $name, $url, $CONF['password_expires'] );
			$mailtext				= wordwrap( $mailtext, 70 );
			$header					= "From: ".$CONF['admin_email']."\r\n" .
									  "Reply-To: ".$CONF['admin_email'];
			
			db_ta( 'COMMIT', $dbh );
			db_log( 'expiredUserCron', "locked user $userid ($name) because password is expired", $dbh );
			#error_log( "locked user $userid ($name) because password is expired" );
			
			mail( $emailaddress, "SVN Access Manager account locked - password expired", $mailtext, $header );
		
		} else {
			
			error_log( "can not lock user $userid ($name) because of database error" );
			db_ta( 'ROLLBACK', $dbh );
			
		}
		
	} elseif( ($curtime - $password_modified) > $diff_warn ) {
	
		$mailtext				= sprintf( $CONF['mail_password_warn'], $name, $url, $CONF['password_expires'] );
		$mailtext				= wordwrap( $mailtext, 70 );
		$header					= "From: ".$CONF['admin_email']."\r\n" .
								  "Reply-To: ".$CONF['admin_email'];
		
		db_log( 'expiredUserCron', "notified user $userid ($name) about account to expire", $dbh );
		#error_log( "notified user $userid ($name) about account to expire" );
		
		mail( $emailaddress, "SVN Access Manager account about to expire", $mailtext, $header );
	
	} else {
		
		#error_log( "$userid password not expired, nothing to do" );
		
	}
	
}								  

db_disconnect( $dbh );

?>
