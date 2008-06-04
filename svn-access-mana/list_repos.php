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



function getRepos( $start, $count, $dbh ) {

	$tRepos				= array();
	$query				= "SELECT   * " .
						  "    FROM svnrepos " .
						  "   WHERE (deleted = '0000-00-00 00:00:00') " .
						  "ORDER BY reponame ASC " .
						  "   LIMIT $start, $count";
	$result				= db_query( $query, $dbh );
	   	
	while( $row = db_array( $result['result']) ) {
	   
		$tRepos[] 		= $row;
	   		
	}

	return $tRepos;
}

function getCountRepos( $dbh ) {

	$tRepos				= array();
	$query				= "SELECT   COUNT(*) AS anz " .
						  "    FROM svnrepos " .
						  "   WHERE (deleted = '0000-00-00 00:00:00') ";
	$result				= db_query( $query, $dbh );
	   	
	if( $result['rows'] == 1 ) {
		
		$row			= db_array( $result['result'] );
		$count			= $row['anz'];
		
		return $count;
		
	} else {
		
		return false;
		
	}
	
}


initialize_i18n();

$SESSID_USERNAME 							= check_session ();
check_password_expired();
$dbh										= db_connect();
$preferences								= db_get_preferences($SESSID_USERNAME, $dbh );
$CONF['page_size']							= $preferences['page_size'];
$rightAllowed								= db_check_acl( $SESSID_USERNAME, 'Repository admin', $dbh );
$_SESSION['svn_sessid']['helptopic']		= "list_repos";

if( $rightAllowed == "none" ) {
	
	db_disconnect( $dbh );
	header( "Location: nopermission.php" );
	exit;
	
}		
   	
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$_SESSION['svn_sessid']['repocounter']	= 0;
   	$tRepos									= getRepos( 0, $CONF['page_size'], $dbh );
   	$tCountRecords							= getCountRepos( $dbh );
   	$tPrevDisabled							= "disabled";
	
	if( $tCountRecords <= $CONF['page_size'] ) {
		
		$tNextDisabled 						= "disabled";
		
	}
	
   	$template								= "list_repos.tpl";
   	$header									= "repos";
   	$subheader								= "repos";
   	$menu									= "repos";
   
   	include ("./templates/framework.tpl");
   	
   	db_disconnect( $dbh );
 
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   
 	if( isset( $_POST['fSubmit'] ) ) {
		$button									= escape_string( $_POST['fSubmit'] );
	} elseif( isset( $_POST['fSubmit_f_x'] ) ) {
		$button									= _("<<");
	} elseif( isset( $_POST['fSubmit_p_x'] ) ) {
		$button									= _("<");
	} elseif( isset( $_POST['fSubmit_n_x'] ) ) {
		$button									= _(">");			
	} elseif( isset( $_POST['fSubmit_l_x'] ) ) {
		$button									= _(">>");
	} elseif( isset( $_POST['fSubmit_new_x'] ) ) {
		$button									= _("New repository");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_new'] ) ) {
		$button									= _("New repository");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} else {
		$button									= "undef";
	}
 	
 	if( $button == _("New repository") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: workOnRepo.php?task=new" );
 		exit;
 		
 	} elseif( $button == _("Back") ) {
 		
 		db_disconnect( $dbh );
 		header( "Location: main.php" );
 		exit;
 		
 	} elseif( $button == _("<<") ) {
		
		$_SESSION['svn_sessid']['repocounter']		= 0;
		$tRepos										= getRepos( 0, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountRepos( $dbh );
		$tPrevDisabled								= "disabled";
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _("<") ) {
		
		$_SESSION['svn_sessid']['repocounter']--;
		if( $_SESSION['svn_sessid']['repocounter'] < 0 ) {
			
			$_SESSION['svn_sessid']['repocounter']	= 0;
			$tPrevDisabled							= "disabled";
			
		} elseif( $_SESSION['svn_sessid']['repocounter'] == 0 ) {
			
			$tPrevDisabled							= "disabled";
			
		}
		
		$start										= $_SESSION['svn_sessid']['repocounter'] * $CONF['page_size'];
		$tRepos										= getRepos( $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountRepos( $dbh );
	
		if( $tCountRecords <= $CONF['page_size'] ) {
		
			$tNextDisabled 							= "disabled";
		
		}
		
	} elseif( $button == _(">") ) {
		
		$_SESSION['svn_sessid']['repocounter']++;
		$start										= $_SESSION['svn_sessid']['repocounter'] * $CONF['page_size'];
		$tRepos										= getRepos( $start, $CONF['page_size'], $dbh );
		$tCountRecords								= getCountRepos( $dbh );
		$tRemainingRecords							= $tCountRecords - $start - $CONF['page_size'];
		
		if( $tRemainingRecords <= 0 ) {
			
			$tNextDisabled							= "disabled";
			
		}
		
	} elseif( $button == _(">>") ) {
		
		$count										= getCountRepos( $dbh );
		$rest   									= $count % $CONF['page_size'];
		if( $rest != 0 ) {
			
			$start									= $count - $rest + 1;
			$_SESSION['svn_sessid']['repocounter'] 	= floor($count / $CONF['page_size'] );
			
		} else {
			
			$start									= $count - $CONF['page_size'] - 1;
			$_SESSION['svn_sessid']['repocounter'] 	= floor($count / $CONF['page_size'] ) - 1;
			
		}
		
		
		$tRepos										= getRepos( $start, $CONF['page_size'], $dbh );
		$tNextDisabled								= "disabled";
				
	} else {
		
		$tMessage									= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
		
	}
 	
   	$template		= "list_repos.tpl";
   	$header			= "repos";
   	$subheader		= "repos";
   	$menu			= "repos";
   
   	include ("./templates/framework.tpl");
 
 	db_disconnect( $dbh );
}
?>
