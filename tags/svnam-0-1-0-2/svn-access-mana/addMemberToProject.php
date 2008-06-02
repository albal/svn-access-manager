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
require_once ("./include/functions.inc.php");
require_once ("./include/db-functions.inc.php");
include_once ("./include/output.inc.php");

initialize_i18n();

function addMemberToGroup($group, $currentMembers, $dbh) {

	$cs_array					= array();
	
	foreach( $currentMembers as $userid => $name ) {
		
		$cs_array[]				= $userid;
		
	}
	
	$tUsers						= array();
	$query						= "SELECT * FROM svnusers WHERE (deleted = '0000-00-00 00:00:00') ORDER BY name, givenname";
	$result						= db_query( $query, $dbh );
	
	while( $row = db_array( $result['result'] ) ) {
		
		$name					= $row['name'];
		$givenname				= $row['givenname'];
		$userid					= $row['userid'];
		
		if( $givenname != "" ) {
			
			$name 							= $givenname." ".$name;
			
		}
		
		if( ! in_array($userid, $cs_array) ) {
			
			$tUsers[ $userid ] 				= $name;
		}
		
	}
	
	$tMessage								= "";
	$header									= "projects";
	$subheader								= "projects";
	$menu									= "projects";
	$template								= "addMemberToProject.tpl";
	
	include ("./templates/framework.tpl");
}
?>