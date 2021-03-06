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
 
if (preg_match ("/db-functions-adodb\.inc\.php/", $_SERVER['PHP_SELF'])) {
   
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
  
  		if (preg_match ("/^SELECT/i", $query)) {
 			$query 								= substr ($query, 0, 14) . $CONF['database_prefix'] . substr ($query, 14);
  		} else {
     		$query 								= substr ($query, 0, 6) . $CONF['database_prefix'] . substr ($query, 7);
      	}
   	}
   	
	try {
   		
   		if ($CONF['database_type'] != "mysql") {
	   		
	   		if (preg_match ("/LIMIT/i", $query)) { 
		        
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
   		
   		if (preg_match ("/^SELECT/i", $query)) {
   			$number_rows						= $result->RecordCount();
   		} else {
   			
   			#error_log( "query: >$query<");
   			$number_rows						= $link->Affected_Rows();
   		}
   		
	} catch( exception $e ) {
		
		#error_log( "ERROR: ".print_r($e, true));
		
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
  
  		if (preg_match ("/^SELECT/i", $query)) {
 			$query 								= substr ($query, 0, 14) . $CONF['database_prefix'] . substr ($query, 14);
  		} else {
     		$query 								= substr ($query, 0, 6) . $CONF['database_prefix'] . substr ($query, 7);
      	}
   	}
   	
	try {
   		
   		if ($CONF['database_type'] != "mysql") {
	   		
	   		if (preg_match ("/LIMIT/i", $query)) { 
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
   		if (preg_match("/^SELECT/i", $query)) {
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
	
	$date					= date('YmdHis');
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
// db_getGroupRightByGroupid 
// Action: check a group and return the lowest privilege of an user
// Call: db_getGroupRightByGroupid (string groupid, resource link)
//
function db_getGroupRightByGroupid($groupid, $link) {
	
	global $CONF;
	
	$mode			= "";
	$schema			= db_determine_schema();
	$result			= db_query( "SELECT svnusers.user_mode ".
								"  FROM svnusers, svngroups, svn_users_groups ".
								" WHERE (svngroups.id = $groupid) ".
								"   AND (svn_users_groups.group_id = svngroups.id) ".
								"   AND (svn_users_groups.user_id = svnusers.id) ".
								"   AND (svnusers.deleted = '00000000000000') ".
								"   AND (svngroups.deleted = '00000000000000') ".
								"   AND (svn_users_groups.deleted = '00000000000000')", $link);
	while( $row = db_assoc( $result['result'] ) ) {
		
		if((strtolower($row['user_mode']) == "write") and ($mode == "") ) {
			$mode	= strtolower($row['user_mode']);
		} elseif(strtolower($row['user_mode']) == "read") {
			$mode	= strtolower($row['user_mode']);
		}
		
	}
	
	return $mode;
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
// db_getRepoByName
// Action: get repository by name
// Call: db_getRepobyId (string reponame, ressource link)
//
function db_getRepoByName ($reponame, $link) {
	
	global $CONF;
	
	$schema			= db_determine_schema();
    
	$result			= db_query( "SELECT * " .
								"  FROM ".$schema."svnrepos " .
								" WHERE (reponame = '$reponame') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row		= db_assoc( $result['result'] );
		$id			= $row['id'];
		
		return $id;
	
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
// db_getRightName
// Action: get name for a right
// Call: db_getRightName(string id, resource link)
//
function db_getRightName( $id, $link ) {

	global $CONF;
	
	$schema						= db_determine_schema();
	
	$query						= "SELECT right_name ".
								  "  FROM rights ".
								  " WHERE (id = $id) ".
								  "   AND (deleted = '00000000000000')";
	$result						= db_query( $query, $link );
	if( $result['rows'] == 1 ) {
		$row					= db_assoc( $result['result'] );
		return( $row['right_name'] );
	} else {
		return( "undefined" );
	}
	
}



//
// db_getRightData
// Action: get data for access right
// Call: db_getRightData(string id, resource link)
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
// db_check_global_admin
// Action: check if an user is an global admin
// Call: db_check_global_admin( string username, resource link )
//
function db_check_global_admin( $username, $link ) {

	global $CONF;
	
	$schema							= db_determine_schema();
	$ret							= false;
	$query							= "SELECT superadmin ".
									  "  FROM ".$schema."svnusers ".
									  " WHERE (deleted = '00000000000000') ".
									  "   AND (userid = '$username')";
	$result							= db_query( $query, $link );
	if( $result['rows'] > 0 ) {
		$row    					= db_assoc( $result['result'] );
		$ret 						= strtolower($row['superadmin']) == 1 ? true : false;
		return( $ret );
	} else {
		return false;
	}
}



//
// db_check_global_admin_by_id
// Action: check if an user is an global admin
// Call: db_check_global_admin_by_id( string id, resource link )
//
function db_check_global_admin_by_id( $id, $link ) {

	global $CONF;
	
	$schema							= db_determine_schema();
	$ret							= false;
	$query							= "SELECT superadmin ".
									  "  FROM ".$schema."svnusers ".
									  " WHERE (deleted = '00000000000000') ".
									  "   AND (id = $id)";
	$result							= db_query( $query, $link );
	if( $result['rows'] > 0 ) {
		$row    					= db_assoc( $result['result'] );
		$ret						= strtolower($row['superadmin']) == 1 ? true : false;
		return( $ret );
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
  	
  		if( empty($link) ) {
      			$newConnection					= 1;
      			$link							= db_connect();
      	} else {
      			$newConnection					= 0;
      	}
      		
  		$escaped_string							= $link->qstr( $string, get_magic_quotes_gpc() );
  		$escaped_string							= preg_replace( '/^\'/', "", $escaped_string );
  		$escaped_string							= preg_replace( '/\'$/', "", $escaped_string );
  		
  		if( $newConnection == 1 ) {
  			db_disconnect( $link );
  		}
   
	}

   	return $escaped_string;
}



//
// ldap_check_user_exists
// Action: check if an user exists in ldap directory
// Call: ldap_check_user_exists(string userid)
//
function ldap_check_user_exists($userid) {
	
	global $CONF;
	global $LDAP_CONNECT_OPTIONS; 
	
	$ret										= 0;
	
	if( isset( $CONF['ldap_protocol'] ) ) {
		$protocol								= $CONF['ldap_protocol'];
	} else {
		$protocol								= "2";
	}
	
	error_log( "using ldap protocol version $protocol" );
	$LDAP_CONNECT_OPTIONS = Array(
         Array ("OPTION_NAME"=>LDAP_OPT_DEREF, "OPTION_VALUE" => 2),
         Array ("OPTION_NAME"=>LDAP_OPT_SIZELIMIT,"OPTION_VALUE" => 1000),
         Array ("OPTION_NAME"=>LDAP_OPT_TIMELIMIT,"OPTION_VALUE" => 30),
         Array ("OPTION_NAME"=>LDAP_OPT_PROTOCOL_VERSION,"OPTION_VALUE" => $protocol),
         Array ("OPTION_NAME"=>LDAP_OPT_ERROR_NUMBER,"OPTION_VALUE" => 13),
         Array ("OPTION_NAME"=>LDAP_OPT_REFERRALS,"OPTION_VALUE" => FALSE),
         Array ("OPTION_NAME"=>LDAP_OPT_RESTART,"OPTION_VALUE" => FALSE)
	);
	
	try {
		$ldap										= &NewADOConnection( 'ldap' );
		#error_log( $CONF['ldap_server'].",".$CONF['bind_dn'].",".$CONF['bind_pw'].",".$CONF['user_dn'] );
		$ldap->Connect( $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn'] );
		$ldapOpen									= 1;
		
	} catch( exception $e ) {
		
		$_SESSION['svn_sessid']['dberror']			= $e->msg;
      	$_SESSION['svn_sessid']['dbquery']			= sprintf("Database connect: %s - %s - %s - %s", $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn']);
      	$_SESSION['svn_sessid']['dbfunction']		= sprintf("db_connect: %s - %s - %s - %s", $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn']);
         
		if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  	    $location								= "database_error.php";
	    } else {
	  	    $location								= "../database_error.php";
	  	}
	  	
	 	header( "location: $location");
	 	exit;
	 	
	}
	
	try {
		$filter									= "(&(".$CONF['user_filter_attr']."=$userid)(objectclass=".$CONF['user_objectclass']."))";
		$ldap->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs										= $ldap->Execute( $filter );
		if( $rs ) {
			if( $rs->RecordCount() > 0 ) {
				$ret							= 1;
			} else {
				$ret							= 0;
			}
		} else {
			$ret								= 0;
		}
		
	} catch( exception $e ) {
		
		error_log( "Error: ".$e->msg );
		$ret									= 0;
	 	
	}
	
	if( $ldapOpen == 1 ) {
		$ldap->Close();
	}
	
	return( $ret );
	
} 



//
// get_ldap_users
// Action: get available users from ldap
// Call: et_ldap_users()
//
function get_ldap_users() {
	
	global $CONF;
	global $LDAP_CONNECT_OPTIONS; 
	
	$tUsers										= array();
	
	$additionalFilter							= isset( $CONF['additional_user_filter'] ) ? $CONF['additional_user_filter'] : "";
	
	if( isset( $CONF['ldap_protocol'] ) ) {
		$protocol								= $CONF['ldap_protocol'];
	} else {
		$protocol								= "2";
	}
	
	$LDAP_CONNECT_OPTIONS = Array(
         Array ("OPTION_NAME"=>LDAP_OPT_DEREF, "OPTION_VALUE" => 2),
         Array ("OPTION_NAME"=>LDAP_OPT_SIZELIMIT,"OPTION_VALUE" => 1000),
         Array ("OPTION_NAME"=>LDAP_OPT_TIMELIMIT,"OPTION_VALUE" => 30),
         Array ("OPTION_NAME"=>LDAP_OPT_PROTOCOL_VERSION,"OPTION_VALUE" => $protocol),
         Array ("OPTION_NAME"=>LDAP_OPT_ERROR_NUMBER,"OPTION_VALUE" => 13),
         Array ("OPTION_NAME"=>LDAP_OPT_REFERRALS,"OPTION_VALUE" => FALSE),
         Array ("OPTION_NAME"=>LDAP_OPT_RESTART,"OPTION_VALUE" => FALSE)
	);
	
	try {
		$ldap										= &NewADOConnection( 'ldap' );
		#error_log( $CONF['ldap_server'].",".$CONF['bind_dn'].",".$CONF['bind_pw'].",".$CONF['user_dn'] );
		$ldap->Connect( $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn'] );
		$ldapOpen									= 1;
		
	} catch( exception $e ) {
		
		$_SESSION['svn_sessid']['dberror']			= $e->msg;
      	$_SESSION['svn_sessid']['dbquery']			= sprintf("Database connect: %s - %s - %s - %s", $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn']);
      	$_SESSION['svn_sessid']['dbfunction']		= sprintf("db_connect: %s - %s - %s - %s", $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn']);
         
		if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  	    $location								= "database_error.php";
	    } else {
	  	    $location								= "../database_error.php";
	  	}
	  	
	 	header( "location: $location");
	 	exit;
	 	
	}
	
	if( $additionalFilter != "" ) {
		$filter										= "(&(objectclass=".$CONF['user_objectclass'].")".$additionalFilter.")";
	} else {
		$filter										= "(objectclass=".$CONF['user_objectclass'].")";
	}
	
	try {
		$ldap->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs										= $ldap->Execute( $filter );
		if( $rs ) {
			
			while( $arr = $rs->FetchRow() ) {
				
				$entry							= array();
				
				if( isset($CONF['attr_mapping']['uid']) ) {		
					if(isset($arr[$CONF['attr_mapping']['uid']])) {
						$entry['uid']			= $arr[$CONF['attr_mapping']['uid']];
					} else {
						$entry['uid']			= "";
					}			
				} else {
					if(isset($arr['uid'])) {
						$entry['uid']			= $arr['uid'];
					} else {
						$entry['uid']			= "";
					}	
				}
				
				if( isset($CONF['attr_mapping']['name']) ) {	
					if(isset($arr[$CONF['attr_mapping']['name']])) {
						$entry['name']			= $arr[$CONF['attr_mapping']['name']];
					} else {
						$entry['name']			= "";
					}			
				} else {
					if(isset($arr['sn'])) {
						$entry['name']			= $arr['sn'];
					} else {
						$entry['name']			= "";
					}	
				}
				
				if( isset($CONF['attr_mapping']['givenName']) ) {
					if(isset($arr[$CONF['attr_mapping']['givenName']])) {			
						$entry['givenname']		= $arr[$CONF['attr_mapping']['givenName']];	
					} else {
						$entry['givenname']		= "";
					}
				} else {
					if(isset($arr['givenName'])) {			
						$entry['givenname']		= $arr['givenName'];	
					} else {
						$entry['givenname']		= "";
					}	
				}
				
				if( isset($CONF['attr_mapping']['mail']) ) {				
					$attr						= $CONF['attr_mapping']['mail'];	
				} else {
					$attr						= 'mail';	
				}
				
				$entry['emailaddress']			= isset( $arr[$attr] ) ? $arr[$attr] : "";
				
				if (isset($CONF['ldap_uservalues_encode']) && $CONF['ldap_uservalues_encode']) {
                	$entry['name'] 				= htmlentities($entry['name']);
                	$entry['givenname'] 		= htmlentities($entry['givenname']);
        		}
				
				$tUsers[]						= $entry;
			}
		}
		
	} catch( exception $e ) {
		
		$_SESSION['svn_sessid']['dberror']			= $e->msg;
      	$_SESSION['svn_sessid']['dbquery']			= $filter;
      	$_SESSION['svn_sessid']['dbfunction']		= "get_ldap_user";
         
		if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  	    $location								= "database_error.php";
	    } else {
	  	    $location								= "../database_error.php";
	  	}
	  	
	 	header( "location: $location");
	 	exit;
	 	
	}
	
	if( $ldapOpen == 1 ) {
		$ldap->Close();
	}
	
	usort($tUsers, "sortLdapUsers");
	
	return( $tUsers );
}



//
// check_ldap_password
// Action: check password against ldap
// Call: check_ldap_password(string userid, string password)
//
function check_ldap_password( $userid, $password ) {
	
	global $CONF;
	global $LDAP_CONNECT_OPTIONS; 
	
	$ret										= 0;
	
	if( isset( $CONF['ldap_protocol'] ) ) {
		$protocol								= $CONF['ldap_protocol'];
	} else {
		$protocol								= "2";
	}
	
	$LDAP_CONNECT_OPTIONS = array(
         array ("OPTION_NAME"=>LDAP_OPT_DEREF, "OPTION_VALUE" => 2),
         array ("OPTION_NAME"=>LDAP_OPT_SIZELIMIT,"OPTION_VALUE" => 1000),
         array ("OPTION_NAME"=>LDAP_OPT_TIMELIMIT,"OPTION_VALUE" => 30),
         array ("OPTION_NAME"=>LDAP_OPT_PROTOCOL_VERSION,"OPTION_VALUE" => $protocol),
         array ("OPTION_NAME"=>LDAP_OPT_ERROR_NUMBER,"OPTION_VALUE" => 13),
         array ("OPTION_NAME"=>LDAP_OPT_REFERRALS,"OPTION_VALUE" => FALSE),
         array ("OPTION_NAME"=>LDAP_OPT_RESTART,"OPTION_VALUE" => FALSE)
	);
	
	error_log("check_ldap_password");
	
	try {
		$ldap										= NewADOConnection( 'ldap' );
		#error_log( $CONF['ldap_server'].",".$CONF['bind_dn'].",".$CONF['bind_pw'].",".$CONF['user_dn'] );
		$ldap->Connect( $CONF['ldap_server'], $CONF['bind_dn'], $CONF['bind_pw'], $CONF['user_dn'] );
		$ldapOpen									= 1;
		#error_log("ldap open");
		
	} catch( exception $e ) {
		
		#error_log( "exception during connect" );
		$_SESSION['svn_sessid']['dberror']			= $e->msg;
	    $_SESSION['svn_sessid']['dbquery']			= sprintf("Database connect: %s - %s - %s - %s", $CONF['ldap_server'], $CONF['bind_dn'], 'xxxxxxxx', $CONF['user_dn']);
	    $_SESSION['svn_sessid']['dbfunction']		= sprintf("db_connect: %s - %s - %s - %s", $CONF['ldap_server'], $CONF['bind_dn'], 'xxxxxxxx', $CONF['user_dn']);
	      	
	    $tErrorMessage								= strtolower( $_SESSION['svn_sessid']['dberror'] );
		
      	if( isset($CONF['ldap_bind_use_login_data']) 	and 
      	    ($CONF['ldap_bind_use_login_data'] == 1) 	and 
      	    strpos( $tErrorMessage, "invalid") 			and 
      	    strpos( $tErrorMessage, "credentials") 
      	) {
      		$ldapOpen								= 0;
      		$ret									= 0;
      		#error_log( "check reset" );
      		
      	} else {
         
			if ( file_exists ( realpath ( "database_error.php" ) ) ) {
		  	    $location							= "database_error.php";
		    } else {
		  	    $location							= "../database_error.php";
		  	}
		  	
		  	$ret										= -1;
		  	return( $ret );
		 	
      	}
	}
	
	try {
		$filter									= "(&(".$CONF['user_filter_attr']."=$userid)(objectclass=".$CONF['user_objectclass']."))";
		$ldap->SetFetchMode(ADODB_FETCH_ASSOC);
		error_log("filter = $filter");
		$rs										= $ldap->Execute( $filter );
		if( $rs ) {
			if( $rs->RecordCount() == 1 ) {
				
				$arr							= $rs->FetchRow();
				$dn								= $arr['dn'];
				#error_log( "dn = $dn" );
				$ldapUser						= &NewADOConnection( 'ldap' );
				$ldapUser->Connect( $CONF['ldap_server'], $dn, $password, $CONF['user_dn'] );
				$ret							= 1;
				$ldapUser->Close();
				
			} else {
				$ret							= 0;
				error_log( "mehrere treffer" );
			}
			
		} else {
			$ret								= 0;
			error_log("filter keine treffer");
		}
		
	} catch( exception $e ) {
		
		error_log( "Error: ".$e->msg );
		$ret									= 0;
	 	
	}
	
	if( $ldapOpen == 1 ) {
		$ldap->Close();
	}
	
	return( $ret );
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
