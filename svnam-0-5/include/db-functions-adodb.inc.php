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
 
if (ereg ("db-functions-adodb.inc.php", $_SERVER['PHP_SELF'])) {
   
   header ("Location: login.php");
   exit;
   
}

$installBase								= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

if ( file_exists ( realpath ( "./include/adodb5/adodb.inc.php" ) ) ) {

	include_once ("./include/adodb5/adodb-exceptions.inc.php");
	include_once ("./include/adodb5/adodb.inc.php");
		
} elseif( file_exists ( realpath ( "../include/adodb5/adodb.inc.php" ) ) ) {
	
	include_once ("../include/adodb5/adodb-exceptions.inc.php");
	include_once ("../include/adodb5/adodb.inc.php");
	
} elseif( file_exists ( "$installBase/include/adodb5/adodb.inc.php" ) ) {
	
	include_once ("$installBase/include/adodb5/adodb-exceptions.inc.php");
	include_once ("$installBase/include/adodb5/adodb.inc.php");
	
} else {
	
	die( "can't find adodb.inc.php! Check your installation!\n" );
}



 
$DEBUG_TEXT = "\n
<p />\n
Please check the documentation and website for more information.\n
";




//
// db_connect
// Action: Makes a connection to the database if it doesn't exist
// Call: db_connect ()
//
function db_connect () {
   
   	global $CONF;
   	global $DEBUG_TEXT;
   
   	$link = "";
   
   	if( isset($CONF['database_charset']) ) {
   		$charset 									= $CONF['database_charset'];
   	} else {
   		$charset 									= "latin1";
   	}
   
   	if( isset($CONF['database_collation']) ) {
   		$collation 									= $CONF['database_collation'];
   	} else {
   		$collation 									= "latin1_german1_ci";
   	}
   
   	$nameset  										= "SET NAMES '$charset' COLLATE '$collation'";
   

	try {
   		
   		$link 										= &ADONewConnection($CONF['database_type']); 
   		$link->Pconnect($CONF['database_host'], $CONF['database_user'], $CONF['database_password'], $CONF['database_name'] );
   		$link->SetFetchMode(ADODB_FETCH_ASSOC);
   		
   		if ($CONF['database_type'] == "mysql") {
   			$link->Execute($nameset);
   		}
   		#$link->debug								= true;
   		
	} catch( exception $e ) {
		
		$_SESSION['svn_sessid']['dberror']			= $e->msg;
      	$_SESSION['svn_sessid']['dbquery']			= "Database connect";
      	$_SESSION['svn_sessid']['dbfunction']		= "db_connect";
         
		if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  	    $location								= "database_error.php";
	    } else {
	  	    $location								= "../database_error.php";
	  	}
	  	
	 	header( "location: $location");
	 	exit;
		
	}

	return $link;
   
}



//
// db_connect_install
// Action: Makes a connection to the database if it doesn't exist
// Call: db_connect (string dbhost, string dbuser, string dbpassword, string dbname)
//
function db_connect_install ($dbhost, $dbuser, $dbpassword, $dbname, $charset, $collation, $dbtype="", $test="no") {
   
   global $CONF;
   global $DEBUG_TEXT;
   
   	$link 											= "";
   	$nameset 										= "SET NAMES '$charset' COLLATE '$collation'";
	$dbtype											= ($dbtype == "") ? "mysql" : $dbtype;
	
	try {
		#error_log( "connect to $dbtype" );
		$link 										= &ADONewConnection($dbtype); 
		if( $dbtype == "oci8" ) {
			$link->Connect($dbname, $dbuser, $dbpassword );	
		} else {
   			$link->Connect($dbhost, $dbuser, $dbpassword, $dbname);
		}
   		$link->SetFetchMode(ADODB_FETCH_ASSOC);
   		
   		if ($dbtype == "mysql") {
   			$link->Execute($nameset);
   		}
   		
	} catch( exception $e ) {
		
		if( $test == "no" ) {
			
			$tDbError								= $e->msg;
	      	$tDbQuery								= "Connect: Unable to connect to database: Make sure that you have set the correct database type in the config.inc.php file and username and password are corect also!";
		 	
		  	if ( file_exists ( realpath ( "database_error_install.php" ) ) ) {
		  		$location							= "database_error_install.php";
		  	} else {
		  		$location							= "../database_error_install.php";
		  	}
		  
		  	header( "location: $location?dberror=$tDbError&dbquery=$tDbQuery");
		  	exit;
		  	
		} else {
			
			error_log( "db connect test error: ".$e->msg );
			return array( 'ret' => false, 'error' => $e->msg );
		}
	  
	}

	return $link;
   
}



//
// db_disconnect
// Action: close connection to database
// Call: db_disconnect (resource link);
//
function db_disconnect ($link) {
   
   	global $CONF;
   	global $DEBUG_TEXT;
   
   	try {
   		
   		$link->Close();
   		
   	} catch( exception $e ) {
   		
   	}
         
}



//
// db_query
// Action: Sends a query to the database and returns query result and number of rows
// Call: db_query (string query, resource link)
//
function db_query ($query, $link, $limit=-1, $offset=-1) {
   
   global $CONF;
   global $DEBUG_TEXT;
   
   $result 										= "";
   $number_rows 								= "";
   $query										= trim( $query );
   $error										= 0;
   
   // database prefix workaround
	if (!empty ($CONF['database_prefix'])) {
  
  		if (eregi ("^SELECT", $query)) {
 			$query 								= substr ($query, 0, 14) . $CONF['database_prefix'] . substr ($query, 14);
  		} else {
     		$query 								= substr ($query, 0, 6) . $CONF['database_prefix'] . substr ($query, 7);
      	}
   	}
   	
	try {
   		
   		if ($CONF['database_type'] != "mysql") {
	   		
	   		if (eregi ("LIMIT", $query)) { 
		        
		        $search							= "/LIMIT (\w+), (\w+)/";
		        $replace 						= "LIMIT \$2 OFFSET \$1";
		        $query 							= preg_replace ($search, $replace, $query); 
	      	}
   		}
   		
   		$link->SetFetchMode(ADODB_FETCH_ASSOC);
   		if( ($limit != -1 ) ) {
   			if( $offset != -1 ) {
   				$result							= $link->SelectLimit( $query, $limit, $offset );
   			} else {
   				$result							= $link->SelectLimit( $query, $limit );
   			}
   			
   		} else {
   			$result								= $link->Execute( $query );
   		}
   		
   		if (eregi ("^SELECT", $query)) {
   			$number_rows						= $result->RecordCount();
   		} else {
   			
   			#error_log( "query: >$query<");
   			$number_rows						= $link->Affected_Rows();
   		}
   		
	} catch( exception $e ) {
		
		error_log( "ERROR: ",print_r($e, true));
		
		$_SESSION['svn_sessid']['dberror']		= $e->msg;
      	$_SESSION['svn_sessid']['dbquery']		= $query;
      	$_SESSION['svn_sessid']['dbfunction']	= "db_query";
	 	db_ta ("ROLLBACK", $link);
	 	db_disconnect( $link );
	 	
	 	error_log( "DB-Error: ".$_SESSION['svn_sessid']['dberror'] );
	 	error_log( "DB-Query: ".$_SESSION['svn_sessid']['dbquery'] );
	 	
	 	if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  		$location							= "database_error.php";
	  	} else {
	  		$location							= "../database_error.php";
	    }
	    
	    $error									= 1;
	  
	  	#error_log( "jumping to $location" );
	 	header( "Location: $location");
	 	exit;
	}

   	$return = array (
    	"result" => $result,
      	"rows" => $number_rows
   	);
   
   	return $return;
}




//
// db_query_install
// Action: Sends a query to the database and returns query result and number of rows
// Call: db_query_install (string query, resource link)
//
function db_query_install ($query, $link, $limit=-1, $offset=-1) {
   
   	global $CONF;
   	global $DEBUG_TEXT;
   
   	$result 									= "";
   	$number_rows 								= "";
   	$query										= trim( $query );

	// database prefix workaround
	if (!empty ($CONF['database_prefix'])) {
  
  		if (eregi ("^SELECT", $query)) {
 			$query 								= substr ($query, 0, 14) . $CONF['database_prefix'] . substr ($query, 14);
  		} else {
     		$query 								= substr ($query, 0, 6) . $CONF['database_prefix'] . substr ($query, 7);
      	}
   	}
   	
	try {
   		
   		if ($CONF['database_type'] != "mysql") {
	   		
	   		if (eregi ("LIMIT", $query)) { 
		        $search							= "/LIMIT (\w+), (\w+)/";
		        $replace 						= "LIMIT \$2 OFFSET \$1";
		        $query 							= preg_replace ($search, $replace, $query); 
	      	}
   		}
   		
   		$link->SetFetchMode(ADODB_FETCH_ASSOC);
   		if( ($limit != -1 ) ) {
   			if( $offset != -1 ) {
   				$result							= $link->SelectLimit( $query, $limit, $offset );
   			} else {
   				$result							= $link->SelectLimit( $query, $limit );
   			}
   		} else {
   			$result								= $link->Execute( $query );
   		}
   		if (eregi ("^SELECT", $query)) {
   			$number_rows						= $result->RecordCount();
   		} else {
   			$number_rows						= $link->Affected_rows();
   		}
   		
	} catch( exception $e ) {
		
		$tDbError								= urlencode($e->msg);
    	$tDbQuery								= $query;
    	
      	error_log( "DB Error: $tDbError" );
      	error_log( "DB Query: $query" );
	 	
	 	if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  		$location							= "database_error_install.php";
	  	} else {
	  		$location							= "../database_error_install.php";
	    }
	  
	 	header( "location: ".$location."?dbquery=$tDbQuery&dberror=$tDbError&dbfunction=db_query_install");
	 	exit;
	}


   	$return = array (
    	"result" => $result,
      	"rows" => $number_rows
   	);
   
   	return $return;

}





// db_assoc
// Action: Returns a row from a table
// Call: db_assoc(int result)
//
function db_assoc ($result) {
   
   	global $CONF;
   
   	try {
		$row										= $result->FetchRow();
		if( $row === false ) {
			$row									= "";
		} else {
			
			$newrow									= array();
			
			foreach( $row as $key => $value ) {
				$key								= strtolower($key);
				$newrow[$key]						= $value;	
			}
			$row									= $newrow;
		}
		
   	} catch( exception $e ) {
   		
   		$row										= "";
   		
   	}
   	return $row;
}




//
// db_log
// Action: Logs actions from admin
// Call: db_delete (string username, string domain, string action, string data, resource link)
//
function db_log ( $username, $data, $link="" ) {
   
   	global $CONF;
	
	$schema												= db_determine_schema();
    
   	$REMOTE_ADDR 										= $_SERVER['REMOTE_ADDR'];
   	
   	try {
   		
   		if( ! $link ) {
   			$link										= db_connect();
   		}
   		
   		$dbnow											= db_now();
   		$query											= "INSERT INTO ".$schema."log (logtimestamp, username, ipaddress, logmessage) " .
      						  						  	   "VALUES ('$dbnow', '$username', '$REMOTE_ADDR', '$data')";
      	#error_log( "logging: $query" );
      	$link->Execute( $query );
      	return true;
      						  
   	} catch( exception $e ) {
   		
   		$errormsg										= $e->msg;
   		
   		error_log( "Database error during log write process" );
   		error_log( "DB query: $query" );
   		error_log( "DB error messge: $errormsg" );
   		
   		return false;
   	}
}




//
// db_ta
// Action: transactions
// Call: db_ta (string action, resource link)
//
function db_ta ($action,$link) {
   
	global $CONF;
   	global $DEBUG_TEXT;
   
	if ($CONF['database_innodb'] == 'YES') {
		
		try {
			
			if( strtoupper( $action ) == "BEGIN" ) {
				
				$link->StartTrans();
				
			} elseif( strtoupper( $action) == "COMMIT" ) {
				
				$link->CompleteTrans();
				
			} elseif( strtoupper( $action ) == "ROLLBACK" ) {
				
				$link->FailTrans();
				
			} else {
				
				$_SESSION['svn_sessid']['dberror']		= sprintf( _("Invalid transaction type %s"), $action );
		      	$_SESSION['svn_sessid']['dbquery']		= $action;
		      	$_SESSION['svn_sessid']['dbfunction']	= "db_ta";
			 	db_disconnect( $link );
			 	
			 	error_log( "DB-Error: ".$_SESSION['svn_sessid']['dberror'] );
			 	error_log( "DB-Query: ".$_SESSION['svn_sessid']['dbquery'] );
			 	
			 	if ( file_exists ( realpath ( "database_error.php" ) ) ) {
			  		$location							= "database_error.php";
			  	} else {
			  		$location							= "../database_error.php";
			    }
			  
			 	header( "location: $location");
			 	exit;
		 	
			}
			
		} catch( exception $e ) {
			
			$_SESSION['svn_sessid']['dberror']		= $e->msg;
	      	$_SESSION['svn_sessid']['dbquery']		= $action;
	      	$_SESSION['svn_sessid']['dbfunction']	= "db_ta";
		 	db_disconnect( $link );
		 	
		 	error_log( "DB-Error: ".$_SESSION['svn_sessid']['dberror'] );
		 	error_log( "DB-Query: ".$_SESSION['svn_sessid']['dbquery'] );
		 	
		 	if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  		$location							= "database_error.php";
		  	} else {
		  		$location							= "../database_error.php";
		    }
		  
		 	header( "location: $location");
		 	exit;
			 	
		}
		
	}
		
   return true;
   
}



//
// db_getUseridById
// Action: get userid from database table svnusers with id
// Call: db_getUseridById (string id, resource link)
//
function db_getUseridById ($id, $link) {
	
	global $CONF;
	
	$schema					= db_determine_schema();
    
	$result = db_query( "SELECT userid FROM ".$schema."svnusers WHERE id = $id", $link);
	if( $result['rows'] == 1 ) {
		
		$row				= db_assoc( $result['result'] );
		
		return $row['userid'];
		
	} else {
		
		return false;
		
	}
	
}



//
// db_getIdByUserid
// Action: get id from database table svnusers with userid
// Call: db_getIdByUserid (string userid, resource link)
//
function db_getIdByUserid ($userid, $link) {
	
	global $CONF;
	
	$schema					= db_determine_schema();
    
	$result = db_query( "SELECT id " .
						"  FROM ".$schema."svnusers " .
						" WHERE (userid = '$userid') " .
						"   AND (deleted = '00000000000000')", $link);
	if( $result['rows'] == 1 ) {
		
		$row				= db_assoc( $result['result'] );
		
		return $row['id'];
		
	} else {
		
		return false;
		
	}
	
}



//
// db_now
// Action: get a 14 digit timestamp in format jjjjmmddhhmmss
// Call: db_now()
//
function db_now() {
	
	$date					= date('YmdGis');
	return $date;
}


			
//
// db_last_insert_id
// Action: get last inserted id in a table
// Call: db_get_last_insert_id($table, $column, $link)
//
function db_get_last_insert_id($table, $column, $link, $schema="") {
	
	global $CONF;
	
	if( $schema == "" ) {
		$schema						= isset( $CONF['database_schema'] ) ? $CONF['database_schema'] : "";
	}
	 
	if( $id = $link->Insert_Id() ) {
		
	} else {
		
		try {
			#error_log( "database = ". $link->databaseType);
			#error_log( "schema = $schema" );
			
			if( $link->databaseType == "oci8" ) {
				$query				= "SELECT $schema.$table"."_SEQ.currval AS id FROM dual";
			} else {
				$query				= "SELECT CURRVAL(pg_get_serial_sequence('$schema.$table','$column')) AS id";
			}
			$result					= db_query( $query, $link );
			$row					= db_assoc( $result['result'] );
			$id						= $row['id'];
			
		} catch( exception $e ) {
			
			$id						= false;
			
		}
	}
	
	return $id;
}



//
// db_getUserRightByUserid
// Action: get global user right by userid
// Call: db_getUserRightByUserid (string userid, ressource link)
//
function db_getUserRightByUserid ($userid, $link) {
	
	global $CONF;
	
	$schema			= db_determine_schema();
    
	$result			= db_query( "SELECT * " .
								"  FROM ".$schema."svnusers " .
								" WHERE (userid = '$userid') " .
								"   AND (deleted = '00000000000000')", $link);
	if( $result['rows'] == 1 ) {
		
		$row		= db_assoc( $result['result'] );
		$mode		= strtolower( $row['user_mode'] );
		
		return $mode;
	
	} else {
		
		return false;
		
	}		
	
}



//
// db_getRepoById
// Action: get repository by id
// Call: db_getRepobyId (string id, ressource link)
//
function db_getRepoById ($id, $link) {
	
	global $CONF;
	
	$schema			= db_determine_schema();
    
	$result			= db_query( "SELECT * " .
								"  FROM ".$schema."svnrepos " .
								" WHERE (id = '$id') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row		= db_assoc( $result['result'] );
		$reponame	= $row['reponame'];
		
		return $reponame;
	
	} else {
		
		return false;
		
	}		
	
}



//
// db_getProjectById
// Action: get project by id
// Call: db_getProjectById (string id, ressource link)
//
function db_getProjectById ($id, $link) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
    
	$result				= db_query( "SELECT * " .
								"  FROM ".$schema."svnprojects " .
								" WHERE (id = '$id') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row			= db_assoc( $result['result'] );
		$projectname	= $row['svnmodule'];
		
		return $projectname;
	
	} else {
		
		return false;
		
	}		
	
}



//
// db_getGroupById
// Action: get group by id
// Call: db_getGroupById (string id, ressource link)
//
function db_getGroupById ($id, $link) {
	
	global $CONF;
	
	$schema				= db_determine_schema();
    
	$result				= db_query( "SELECT * " .
								"  FROM ".$schema."svngroups " .
								" WHERE (id = '$id') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row			= db_assoc( $result['result'] );
		$groupname		= $row['groupname'];
		
		return $groupname;
	
	} else {
		
		return false;
		
	}		
	
}




//
// db_getRightData
// Action: get data for access right
// Call: db_getRightdata(string is, resource link)
//
function db_getRightData( $id, $link ) {
	
	global $CONF;
	
	$schema						= db_determine_schema();
    
	$query						= "SELECT project_id, group_id, user_id, path, access_right " .
								  "  FROM ".$schema."svn_access_rights " .
								  " WHERE id = $id";
	$result						= db_query( $query, $link );
	
	if( $result['rows'] == 1 ) {
		
		$ret					= array();
		$row					= db_assoc( $result['result'] );
		$ret['project_id']		= $row['project_id'];
		$ret['user_id']			= $row['user_id'];
		$ret['group_id']		= $row['group_id'];
		$ret['path']			= $row['path'];
		$ret['access_right']	= $row['access_right'];
		
		$query					= "SELECT * " .
								  "  FROM ".$schema."svnprojects " .
								  " WHERE id = ".$row['project_id'];
		$result					= db_query( $query, $link );
		if( $result['rows'] == 1 ) {
			
			$row				= db_assoc( $result['result'] );
			$ret['repo_id']		= $row['repo_id'];
			
		} else {
		
			return false;
			
		}
		
		return $ret;
		
	} else {
		
		return false;
		
	}
}



//
// db_check_acl
// Action: check if user has permission to do something
// Call: db_check_acl( string username, string action, resource dbh )
//
function db_check_acl( $username, $action, $dbh ) {

	global $CONF;
	
	$schema							= db_determine_schema();
    
	$query 							= "SELECT users_rights.allowed " .
									  "  FROM ".$schema."svnusers, ".$schema."rights, ".$schema."users_rights " .
									  " WHERE (svnusers.id = users_rights.user_id) " .
									  "   AND (rights.id = users_rights.right_id) " .
									  "   AND (svnusers.deleted = '00000000000000') " .
									  "   AND (users_rights.deleted = '00000000000000') " .
									  "   AND (svnusers.userid = '$username') " .
									  "   AND (rights.right_name = '$action')";

	$result 						= db_query( $query, $dbh );
	
	if( $result['rows'] > 0 ) {

		$row    					= db_assoc( $result['result'] );
		$right 						= $row['allowed'];
		
		
	} else {
	
		$right 						= "none";
		
	}

	return $right;
}



//
// db_check_group_acl
// Action: check if user is allowed to administer a particular group
// Call: db_check_group_acl( string username, resource dbh )
//
function db_check_group_acl( $username, $dbh ) {
	
	global $CONF;
	
	$schema							= db_determine_schema();
    
	$query							= "SELECT svn_groups_responsible.allowed, svn_groups_responsible.group_id " .
									  "  FROM ".$schema."svn_groups_responsible, ".$schema."svnusers " .
									  " WHERE (svnusers.id = svn_groups_responsible.user_id) " .
									  "   AND (svnusers.userid = '$username') " .
									  "   AND (svn_groups_responsible.deleted = '00000000000000') " .
									  "   AND (svnusers.deleted = '00000000000000')";
	$result							= db_query( $query, $dbh );
	$tAllowedGroups					= array();
	
	if( $result['rows'] > 0 ) {
		
		
		while( $row = db_assoc( $result['result'] ) ) {
			
			$groupid					= $row['group_id'];
			$right 						= $row['allowed'];
			$tAllowedGroups[$groupid]	= $right;
		}
		
	}
	
	return $tAllowedGroups;
}



//
// db_get_preference
// Action: load user's preferences
// Call: db_get_preferences(int userid, resource link)
//
function db_get_preferences($userid, $link) {

	global $CONF;
	
	$schema										= db_determine_schema();
	
	$id											= db_getIdByUserid( $userid, $link );
	$query										= "SELECT * " .
												  "  FROM ".$schema."preferences " .
												  " WHERE user_id = $id";
	$result										= db_query( $query, $link );
	
	if( $result['rows'] == 1 ) {
		
		$row									= db_assoc( $result['result'] );
		$page_size								= $row['page_size'];
		$preferences							= array();
		$preferences['page_size']				= $page_size;
		$preferences['user_sort_fields']		= $row['user_sort_fields'];
		$preferences['user_sort_order']			= $row['user_sort_order'];
		
	} else {
		
		$preferences['page_size']				= $CONF['page_size'];
		$preferences['user_sort_fields']		= $CONF['user_sort_fields'];
		$preferences['user_sort_order']			= $CONF['user_sort_order'];
		
	}
	
	return $preferences;
}



//
// db_get_semaphore
// Action: check if semaphore is set, 
//         returns true if semaphore is set
// Call: db_get_semaphore(string action, string type, resource link)
//
function db_get_semaphore($action, $type, $link) {
	
	global $CONF;
	
	$schema							= db_determine_schema();
    
	$query							= "SELECT * " .
									  "  FROM ".$schema."workinfo " .
									  " WHERE (action = '$action') " .
									  "   AND (type = '$type') " .
									  "   AND (status = 'open')";
	$result							= db_query( $query, $link );
	if( $result['rows'] > 0 ) {
		return true;
	} else {
		return false;
	}
	
}




//
// db_set_semaphore
// Action: set semaphore and check if a semaphore is already open, 
//         returns false if a semaphore could not be set
// Call: db_set_semaphore(string action, string type, resource link)
//
function db_set_semaphore($action, $type, $link) {
	
	global $CONF;
	
	$schema							= db_determine_schema();
    
	if( db_get_semaphore( $action, $type, $link ) ) {
		
		return false;
		
	} else {
		
		$query						= "INSERT INTO ".$schema."workinfo (action, status, type) " .
									  "     VALUES ('$action', 'open', '$type')";
									  
		db_ta( 'BEGIN', $link );
		$result						= db_query( $query, $link);
		if( $result['rows'] == 0 ) {
			
			db_ta('ROLLBACK', $link);
			return false;
			
		} else {
			
			db_ta('COMMIT', $link);
			return true;
			
		}
	}
}



//
// db_unset_semaphore
// Action: unset semaphore
// Call: db_unset_semaphore(string action, string type, resource link)
//
function db_unset_semaphore($action, $type, $link) {
	
	global $CONF;
	
	$schema								= db_determine_schema();
    
	if( db_get_semaphore( $action, $type, $link ) ) {
		
		$query							= "UPDATE ".$schema."workinfo " .
										  "   SET status = 'closed' " .
										  " WHERE (action = '$action') " .
										  "   AND (type = '$type')";
										  
		db_ta('BEGIN', $link);
		$result							= db_query( $query, $link );
		if( $result['rows'] > 0 ) {
			
			db_ta('COMMIT', $link );
			return true;
			
		} else {
			
			db_ta('ROLLBACK', $link );
			return false;
			
		}
		
	} else {
		
		return false;
		
	}
}



//
// db_determine_schema
// Action: get schema and return string
// Call: db_determine_schema()
//
function db_determine_schema() {
	
	global $CONF;
	
	if (substr($CONF['database_type'],0, 8) == "postgres" ) {
    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
    } elseif( $CONF['database_type'] == "oci8" ) {
    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
    } else {
    	$schema					= "";
    }
    
    #error_log( "db schema: $schema" );
    
    return( $schema );
}



//
// db_escape_string
// Action: Escape a string
// Call: db_escape_string (string string, resource link)
//
function db_escape_string ($string, $link="") {
   
   	global $CONF;

	if( is_array( $string) ) {
		
		return $string;
		
	} else {
  	
  		if( $link == "" ) {
      			$newConnection					= 1;
      			$link							= db_connect();
      		} else {
      			$newConnection					= 0;
      		}
      		
      		$escaped_string						= $link->qstr( $string, get_magic_quotes_gpc() );
      		$escaped_string						= preg_replace( '/^\'/', "", $escaped_string );
      		$escaped_string						= preg_replace( '/\'$/', "", $escaped_string );
      		
      		if( $newConnection == 1 ) {
      			db_disconnect( $link );
      		}
   
	}

   	return $escaped_string;
}




//
// session handling
//
class Session {
    /**
     * a database connection resource
     * @var resource
     */
	
    private static $_sess_db;
    private static $DEBUG = 0;

    /**
     * Open the session
     * @return bool
     */
    public static function open() {
       
       	global $CONF;
       	
       	if(self::$DEBUG != 0) {
       		db_log( 'gc', 'open executed' );
       	}
       	#error_log("session open");
       	$db_user 				= $CONF['database_user'];
    	$db_pass 				= $CONF['database_password'];
    	$db_host 				= $CONF['database_host'];
    	$db_name				= $CONF['database_name'];
    	
    	if( isset($CONF['database_charset']) ) {
	   		$charset 			= $CONF['database_charset'];
	   	} else {
	   		$charset 			= "latin1";
	   	}
	   
	   	if( isset($CONF['database_collation']) ) {
	   		$collation 			= $CONF['database_collation'];
	   	} else {
	   		$collation 			= "latin1_german1_ci";
	   	}
	   
	   	$nameset  				= "SET NAMES '$charset' COLLATE '$collation'";

		try {
   		
	   		self::$_sess_db								= &ADONewConnection($CONF['database_type']); 
	   		self::$_sess_db->Pconnect($CONF['database_host'], $CONF['database_user'], $CONF['database_password'], $CONF['database_name'] );
	   		self::$_sess_db->SetFetchMode(ADODB_FETCH_ASSOC);
	   		
	   		if ($CONF['database_type'] == "mysql") {
	   			self::$_sess_db->Execute($nameset);
	   		}
	   		
	   		return true;
	   		
		} catch( exception $e ) {
			
			#var_dump($e); 
			
			$_SESSION['svn_sessid']['dberror']			= $e->msg;
	      	$_SESSION['svn_sessid']['dbquery']			= "Database connect";
	      	$_SESSION['svn_sessid']['dbfunction']		= "db_connect";
	      	
	      	error_log( "DB error: ".$e->msg );
	      	error_log( "DB query: Session database connect" );
	         
			if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  	    $location								= "database_error.php";
		    } else {
		  	    $location								= "../database_error.php";
		  	}
		  	
		 	header( "location: $location");
		 	exit;
			
		}
        
		return false;
    }

    /**
     * Close the session
     * @return bool
     */
    public static function close() {
    	
    	if(self::$DEBUG != 0) {
    		db_log( 'gc', 'close executed' );
    	}
    	
    	#error_log( "session closed");
        #return mysql_close(self::$_sess_db);
        
        #try {
        #	self::$_sess_db->Close();
        #} catch( exception $e ) {
        #	
        #}
        
        return true;
    }

    /**
     * Read the session
     * @param int session id
     * @return string string of the sessoin
     */
    public static function read($id) {
        
        global $CONF;
        
        if(self::$DEBUG != 0) {
        	db_log( 'gc', 'read executed' );
        }
        
        if ($CONF['database_type'] == "postgres8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } elseif( $CONF['database_type'] == "oci8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } else {
	    	$schema					= "";
	    }
        
        $id 						= self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $sql 						= sprintf("SELECT session_data FROM ".$schema."sessions " .
                       							"WHERE session_id = %s", $id);
		#error_log( "session read");
		try {
			
			$result 				= self::$_sess_db->Execute($sql);
			if ($result->RecordCount() > 0 ) {
                
                $record 			= $result->FetchRow();
                return isset( $record['session_data'] ) ? $record['session_data'] : $record['SESSION_DATA'];
            }
            
			return '';
			
		} catch( exception $e ) {
			
			#var_dump($e); 
			
			$_SESSION['svn_sessid']['dberror']			= $e->msg;
	      	$_SESSION['svn_sessid']['dbquery']			= $sql;
	      	$_SESSION['svn_sessid']['dbfunction']		= "db_connect";
	      	
	      	error_log( "DB error: ".$e->msg );
	      	error_log( "DB query: $sql" );
	      	error_log( "DB query: Session read" );
	         
			if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  	    $location								= "database_error.php";
		    } else {
		  	    $location								= "../database_error.php";
		  	}
		  	
		 	header( "location: $location");
		 	exit;
		}                    							
       
    }

    /**
     * Write the session
     * @param int session id
     * @param string data of the session
     */
    public static function write($id, $data) {
        
        global $CONF;
        
        if(self::$DEBUG != 0) {
        	db_log( 'gc', 'write executed' );
        }
        
        if ($CONF['database_type'] == "postgres8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } elseif( $CONF['database_type'] == "oci8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } else {
	    	$schema					= "";
	    }
        
        $id								= self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $time							= self::$_sess_db->qstr(time(), get_magic_quotes_gpc());
        $data							= self::$_sess_db->qstr($data, get_magic_quotes_gpc());
        #error_log( "session write" );
        try {
	        
	        $sql						= sprintf("SELECT * FROM ".$schema."sessions WHERE session_id = %s", $id);
	        $result						= self::$_sess_db->Execute($sql);
	       	if( $result->RecordCount() > 0 ) {
	       		$sql					= sprintf("UPDATE ".$schema."sessions SET session_expires = %s, session_data = %s WHERE session_id = %s", $time, $data, $id);	
	       	}  else {
	        	$sql					= sprintf("INSERT INTO ".$schema."sessions (session_id, session_expires, session_data) VALUES(%s, %s, %s)", $id, $time, $data );
	       	}
	       	#error_log( "write query: $sql" );
	        self::$_sess_db->Execute($sql);
        	$error						= 0;
        	
        } catch( exception $e ) {
        	
        	#adodb_backtrace($e->gettrace());
        	
        	#error_log( "session write exception 1" );
        	#error_log( print_r($e, true) );
        	#error_log( "session write exception 2" );
        	
        	$_SESSION['svn_sessid']['dberror']			= $e->msg;
	      	$_SESSION['svn_sessid']['dbquery']			= $sql;
	      	$_SESSION['svn_sessid']['dbfunction']		= "db_connect";
	      	
	      	error_log( "DB error: ".$e->msg );
	      	error_log( "DB query: $sql" );
	      	error_log( "DB query: Session write to database" );
	         
			if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  	    $location								= "database_error.php";
		    } else {
		  	    $location								= "../database_error.php";
		  	}
		  	
		 	header( "location: $location");
		 	exit;
		 	
        	return false;
        }
        
        if( $error == 0 ) {
        	#error_log("session write true" );
        	return true;
        } else {
        	return false;
        }
    }

    /**
     * Destoroy the session
     * @param int session id
     * @return bool
     */
    public static function destroy($id) {
       
        global $CONF;
        
       	if(self::$DEBUG != 0) {
       		db_log( 'gc', 'destroy executed' );
       	}
       	
       	if ($CONF['database_type'] == "postgres8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } elseif( $CONF['database_type'] == "oci8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } else {
	    	$schema					= "";
	    }
	       	
       	#error_log( "session destroyed" );
       	$id								= self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $sql 							= sprintf("DELETE FROM ".$schema."sessions WHERE session_id = %s", $id);
        
        try {
        	
        	self::$_sess_db->Execute($sql);
        	return true;
        	
        } catch( exception $e ) {
        	
        	$_SESSION['svn_sessid']['dberror']			= $e->msg;
	      	$_SESSION['svn_sessid']['dbquery']			= $sql;
	      	$_SESSION['svn_sessid']['dbfunction']		= "db_connect";
	      	
	      	error_log( "DB error: ".$e->msg );
	      	error_log( "DB query: $sql" );
	      	error_log( "DB query: Session destroy" );
	         
			if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  	    $location								= "database_error.php";
		    } else {
		  	    $location								= "../database_error.php";
		  	}
		  	
		 	header( "location: $location");
		 	exit;
		 	
        	return false;
        }
        
    }

    /**
     * Garbage Collector
     * @param int life time (sec.)
     * @return bool
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability    1
     * @usage execution rate 1/100
     *        (session.gc_probability/session.gc_divisor)
     */
    public static function gc($max) {
        
        global $CONF;
        
        if(self::$DEBUG != 0) {
        	db_log( 'gc', 'gc executed ('.$max.')' );
        }
        
        if ($CONF['database_type'] == "postgres8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } elseif( $CONF['database_type'] == "oci8" ) {
	    	$schema					= ($CONF['database_schema'] == "") ? "" : $CONF['database_schema'].".";
	    } else {
	    	$schema					= "";
	    }
        
        $time								= self::$_sess_db->qstr(time() - $max, get_magic_quotes_gpc());
        $sql 								= sprintf("DELETE FROM ".$schema."sessions WHERE session_expires < %s", $time);
        try {
        	
        	self::$_sess_db->Execute($sql);
        	
        	return true;
        	
        } catch( exception $e ) {
        	
        	$_SESSION['svn_sessid']['dberror']			= $e->msg;
	      	$_SESSION['svn_sessid']['dbquery']			= $sql;
	      	$_SESSION['svn_sessid']['dbfunction']		= "db_connect";
	      	
	      	error_log( "DB error: ".$e->msg );
	      	error_log( "DB query: $sql" );
	      	error_log( "DB query: Session gct" );
	         
			if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  	    $location								= "database_error.php";
		    } else {
		  	    $location								= "../database_error.php";
		  	}
		  	
		 	header( "location: $location");
		 	exit;
		 	
        	return false;
        }
        
    }
}

if( isset( $CONF) and ($CONF['session_in_db'] == "YES") ) {
	
	ini_set('session.gc_probability', 50);
	ini_set('session.gc_divisor', 50);
	ini_set('session.save_handler', 'user');
	ini_set('session.gc_maxlifetime', '1800');
	
	session_set_save_handler(array('Session', 'open'),
	                         array('Session', 'close'),
	                         array('Session', 'read'),
	                         array('Session', 'write'),
	                         array('Session', 'destroy'),
	                         array('Session', 'gc')
	                         );
	                         
}

session_cache_expire(30);
?>
