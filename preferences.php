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

initialize_i18n();

$SESSID_USERNAME 						= check_session ();
check_password_expired();
$dbh 									= db_connect ();
$userid									= db_getIdByUserid ( $SESSID_USERNAME, $dbh );
$_SESSION['svn_sessid']['helptopic']	= "preferences";

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	$tReadonly								= "";
	
   	$query									= "SELECT * " .
   											  "  FROM preferences " .
   											  " WHERE (user_id = $userid) " .
   											  "   AND (deleted = '0000-00-00 00:00:00')";
   	$result									= db_query( $query, $dbh );
   	
   	if( $result['rows'] == 0 ) {
   	
   		$tPageSize							= $CONF['page_size']	;
   		$tSortField							= $CONF['user_sort_fields'];
   		$tSortOrder							= $CONF['user_sort_order'];
   		
   	} else {
   		
   		$row								= db_array( $result['result'] );
   		$tPageSize							= $row['page_size'];
   		$tSortField							= $row['user_sort_fields'];
   		$tSortOrder							= $row['user_sort_order'];
   		
   		if( $tSortField == "" ) {
   			
   			$tSortField						= $CONF['user_sort_fields'];
   			$tSortOrder						= $CONF['user_sort_order'];
   			
   		}
   		
   	}
   	
   	if( $tSortOrder == "ASC" ) {
   		$tAsc								= "checked";
   		$tDesc								= "";
   	} elseif( $tSortOrder == "DESC" ) {
   		$tDesc								= "checked";
   		$tAsc								= "";
   	} else {
   		$tAsc								= "checked";
   		$tDesc								= "";
   	}
   	
   	if( $tSortField == "name,givenname" ) {
   		$tName								= "checked";
   		$tUserid							= "";
   	} elseif( $tSortField == "userid") {
   		$tUserid							= "checked";
   		$tName								= "";
   	} else {
   		$tName								= "checked";
   		$tUserid							= "";
   	}
		
	
	$header									= "preferences";
	$subheader								= "preferences";
	$menu									= "preferences";
	$template								= "preferences.tpl";
	
   	include ("./templates/framework.tpl");

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
	} elseif( isset( $_POST['fSubmit_ok_x'] ) ) {
		$button									= _("Submit");
	} elseif( isset( $_POST['fSubmit_back_x'] ) ) {
		$button									= _("Back" );
	} elseif( isset( $_POST['fSubmit_ok'] ) ) {
		$button									= _("Submit");
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		$button									= _("Back" );
	} else {
		$button									= "undef";
	}
	
   	$tPageSize									= isset( $_POST['fPageSize'] ) 		? escape_string( $_POST['fPageSize'] ) 	: "";
   	$tSortField									= isset( $_POST['fSortField'] ) 	? escape_string( $_POST['fSortField'] )	: "";
   	$tSortOrder									= isset( $_POST['fSortOrder'] )		? escape_string( $_POST['fSortOrder'] )	: "";
   	   	
   	if( $button == _("Back" ) ) {
   	
   		db_disconnect( $dbh );	
   		header( "Location: main.php" );
   		exit;
   		
   	} elseif( $button == _( "Submit" ) ) {
   		
   		$error								= 0;
   		
   		if( $tPageSize == "" ) {
   			
   			$error							= 1;
   			$tMessage						= _("Records per page must be filled in!" );
   			
   		} elseif( ! is_numeric($tPageSize) ) {
   			
   			$error							= 1;
   			$tMessage						= _("Records per page must contain digits only!" );
   			
   		} elseif( $tSortField == "" ) {
   			
   			$error							= 1;
   			$tMessage						= _("Please slect the user sort fields!" );
   			
   		} elseif( $tSortOrder == "" ) {
   			
   			$error							= 1;
   			$tMessage						= -_("Please select to user sort order!" );
   			
   		}
   		
   		if( $error == 0 ) {
   			
   			db_ta( 'BEGIN', $dbh );
   			db_log( $SESSID_USERNAME, 'changed preferences', $dbh );
   			
   			$query							= "SELECT * " .
   											  "  FROM preferences " .
   											  " WHERE (user_id = $userid) " .
   											  "   AND (deleted = '0000-00-00 00:00:00')";
   			$result							= db_query( $query, $dbh );
   			
   			if( $result['rows'] == 0 ) {
   				
   				$query						= "INSERT INTO preferences (user_id, page_size, user_sort_fields, user_sort_order, created, created_user) " .
   											  "     VALUES ($userid, $tPageSize, '$tSortField', '$tSortOrder', now(), '$SESSID_USERNAME')";
   				
   			} else {
   				
   				$query						= "UPDATE preferences " .
   											  "   SET page_size = $tPageSize, " .
   											  "       user_sort_fields = '$tSortField', " .
   											  "       user_sort_order = '$tSortOrder', " .
   											  "       modified = now(), " .
   											  "       modified_user = '$SESSID_USERNAME' " .
   											  " WHERE (user_id = $userid) " .
   											  "   AND (deleted = '0000-00-00 00:00:00')";
   											  
   			}
   			
   			$result							= db_query( $query, $dbh );
   			
   			if( $result['rows'] == 1 ) {
   				
   				db_ta( 'COMMIT', $dbh );
   				$tMessage					= _("Preferences changed successfully");
   				
   			} else {
   				
   				db_ta( 'ROLLBACK', $dbh );
   				$tMessages					= _("Preferences not changed due to database error" );
   				
   			}
   		}
   		
   	} else {
   		
   		$tMessage							= sprintf( _( "Invalid button %s, anyone tampered arround with?" ), $button );
   		
   	}
   	
   	$header									= "preferences";
	$subheader								= "preferences";
	$menu									= "preferences";
	$template								= "preferences.tpl";
	
   	include ("./templates/framework.tpl");
   
}

db_disconnect ( $dbh );
?>
