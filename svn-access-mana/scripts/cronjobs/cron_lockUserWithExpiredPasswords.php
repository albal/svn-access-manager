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

File:  cron_lockUserWithExpiredPasswords.php
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


$dbh								= db_connect();
$url								= "https://atest.asamnet.de/svn_access_manager/";

$qurey								= "SELECT * " .
									  "  FROM svnusers " .
									  " WHERE (deleted = '0000-00-00 00:00:00') " .
									  "   AND (passwordexpires = 1) " .
									  "   AND ((password_modified + INTERVAL 50 DAY) < now()) " .
									  "   AND ((password_modified + INTERVAL 60 DAY) >= now())";
$result								= db_query( $query, $dbh );
while( $row = db_array( $result['result'] ) ) {
	
	$userid							= $row['userid'];
	$emailaddress					= $row['emailaddress'];
	$name							= $row['name'];
	if( $row['givenname'] != "" ) {
	
		$name						= $row['givenname']." ".$name;
		
	}
	
	$mailtext						= sprintf( $CONF['mail_password_warn'], $name, $url, $CONF['password_expires'] );
	$mailtext						= wordwrap( $mailtext, 70 );
	$header							= "From: ".$CONF['admin_email']."\r\n" .
									  "Reply-To: ".$CONF['admin_email'];
	
	mail( $emailaddress, "SVN Access Manager account about to expire", $mailtext, $header );
	
	db_log( 'expiredUserCron', "notified user $userid ($name) about account to expire", $dbh );
	
}								  
									  
$qurey								= "SELECT * " .
									  "  FROM svnusers " .
									  " WHERE (deleted = '0000-00-00 00:00:00') " .
									  "   AND (passwordexpires = 1) " .
									  "   AND ((password_modified + INTERVAL 60 DAY) < now())";
$result								= db_query( $query, $dbh );
while( $row = db_array( $result['result'] ) ) {
	
	$id								= $row['id'];
	$userid							= $row['userid'];
	$emailaddress					= $row['emailaddress'];
	$name							= $row['name'];
	if( $row['givenname'] != "" ) {
	
		$name						= $row['givenname']." ".$name;
		
	}
	
	$query							= "UPDATE svnusers " .
									  "   SET locked = 1 " .
									  " WHERE id = $id";
	$resultupd						= db_query( $query, $dbh );
	
	if( $resultupd['rows'] == 1 ) {
	
		$mailtext						= sprintf( $CONF['mail_password_warn'], $name, $CONF['password_expires'], $url );
		$mailtext						= wordwrap( $mailtext, 70 );
		$header							= "From: ".$CONF['admin_email']."\r\n" .
										  "Reply-To: ".$CONF['admin_email'];
										  
		mail( $emailaddress, "SVN Access Manager account about to expire", $mailtext, $header );
		
		db_log( 'expiredUserCron', "locked user $userid ($name) because password is expired", $dbh );
	
	} else {
		
		print "can not lock user $userid ($name) because of database error\n";
		
	}
}

db_disconnect( $dbh );

?>
