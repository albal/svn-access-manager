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
	die( "can't load config.inc.php. Check your installation!\n" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

$error                                                                  = 0;    
$tErrorClass                                                    		= "";   
$tMessage                                                               = "";
$callback                                                               = isset( $_GET['callback'] )            ? ( $_GET['callback'] )             : "";
$maxRows                                                                = isset( $_GET['maxRows'] )             ? ( $_GET['maxRows'] )              : 10;
$filter                                                                 = isset( $_GET['name_startsWith'] )     ? ( $_GET['name_startsWith'] )  	: "";
$db                                                                     = isset( $_GET['db'] )              	? ( $_GET['db'] )                   : "";
$tArray                                                                 = array();

list($ret, $SESSID_USERNAME)            								= check_session_status();
if( $ret == 0 ) {
    	
    $tArray[]           	                                            = array( "name" => "Session expired!" );
	$data             	    	                                        = json_encode($tArray);
	
} elseif( strtolower($db) == "people" ) {

	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT id, name, givenname " .
																		  "  FROM ".$schema."svnusers " .
																		  " WHERE ((userid like '%$filter%') ".
																		  "    OR  (CONCAT(givenname, ' ', name) like '%$filter%') ".
																		  "    OR  (CONCAT(name, ' ', givenname) like '%$filter%') ".
																		  "    OR  (name like '%$filter%') ".
																		  "    OR  (givenname like '%$filter%')) ".
																		  "   AND (deleted = '00000000000000') ".
																		  "ORDER BY name ASC, givenname ASC";
	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		if( $row['givenname'] == "" ) {
			$data['name']												= $row['name'];
		} else {
			$data['name']												= $row['givenname']." ".$row['name'];
		}
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "groups" ) {
	
	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT id, groupname " .
																		  "  FROM ".$schema."svngroups " .
																		  " WHERE ((groupname like '%$filter%') ".
																		  "    OR (description like '%$filter%')) ".
																		  "   AND (deleted = '00000000000000') ".
																		  "ORDER BY groupname ASC";
	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		$data['name']													= $row['groupname'];
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "repos" ) {

	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT id, reponame " .
																		  "  FROM ".$schema."svnrepos " .
																		  " WHERE ((repouser like '%$filter%') ".
																		  "    OR (reponame like '%$filter%')) ".
																		  "   AND (deleted = '00000000000000') ".
																		  "ORDER BY reponame ASC";
	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		$data['name']													= $row['reponame'];
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
		
} elseif( strtolower($db) == "projects" ) {
	
	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT * ".
    											  						  "  FROM ".$schema."svnprojects ".
    											  						  " WHERE (svnmodule like '%$filter%') ".
    											  						  "   AND (deleted = '00000000000000') ".
    											  						  "ORDER BY svnmodule ASC";
  	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		$data['name']													= $row['svnmodule'];
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
}

$data                                                                   = json_encode($tArray);

print $callback."(".$data.");"
?>
