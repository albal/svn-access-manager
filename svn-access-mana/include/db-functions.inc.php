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
 
if (ereg ("db-functions.inc.php", $_SERVER['PHP_SELF'])) {
   
   header ("Location: login.php");
   exit;
   
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

   if ($CONF['database_type'] == "mysql") {
      
      if (function_exists ("mysql_connect")) {
         
         $link 	 = @mysql_connect ($CONF['database_host'], $CONF['database_user'], $CONF['database_password']) or die ("<p />DEBUG INFORMATION:<br />Connect: " .  mysql_error () . "$DEBUG_TEXT");
         $succes = @mysql_select_db ($CONF['database_name'], $link) or die ("<p />DEBUG INFORMATION:<br />MySQL Select Database: " .  mysql_error () . "$DEBUG_TEXT");
         
      } else {
         
          $_SESSION['svn_sessid']['dberror']		= mysql_errno().": ".mysql_error();
      	  $_SESSION['svn_sessid']['dbquery']		= "MySQL 3.x / 4.0 functions not available!<br />database_type = 'mysql' in config.inc.php, are you using a different database?";
	 	  db_ta ("ROLLBACK", $link);
	 	  db_disconnect( $link );
	 	
	 	  header( "location: database_error.php");
	 	  exit;
         
      }
   }

   if ($CONF['database_type'] == "mysqli")  {
      if (function_exists ("mysqli_connect")) {
         $link 		= @mysqli_connect ($CONF['database_host'], $CONF['database_user'], $CONF['database_password']) or die ("<p />DEBUG INFORMATION:<br />Connect: " .  mysqli_connect_error () . "$DEBUG_TEXT");
         $succes 	= @mysqli_select_db ($link, $CONF['database_name']) or die ("<p />DEBUG INFORMATION:<br />MySQLi Select Database: " .  mysqli_error () . "$DEBUG_TEXT");
      } else {
         print "<p />DEBUG INFORMATION:<br />MySQL 4.1 functions not available!<br />database_type = 'mysqli' in config.inc.php, are you using a different database? $DEBUG_TEXT";
         die;
      }
   }

   if ($CONF['database_type'] == "pgsql") {
      if (function_exists ("pg_connect")) {
         $connect_string = "host=" . $CONF['database_host'] . " dbname=" . $CONF['database_name'] . " user=" . $CONF['database_user'] . " password=" . $CONF['database_password'];
         $link = @pg_connect ($connect_string) or die ("<p />DEBUG INFORMATION:<br />Connect: " .  pg_last_error () . "$DEBUG_TEXT");
      } else {
         print "<p />DEBUG INFORMATION:<br />PostgreSQL functions not available!<br />database_type = 'pgsql' in config.inc.php, are you using a different database? $DEBUG_TEXT";
         die;
      }
   }

   if ($link)
   {
      return $link;
   } else {
   	
	  $_SESSION['svn_sessid']['dberror']		= mysql_errno().": ".mysql_error();
      $_SESSION['svn_sessid']['dbquery']		= "Connect: Unable to connect to database: Make sure that you have set the correct database type in the config.inc.php file";
	  db_ta ("ROLLBACK", $link);
	  db_disconnect( $link );
	 	
	  header( "location: database_error.php");
	  exit;
   }
}



//
// db_connect_install
// Action: Makes a connection to the database if it doesn't exist
// Call: db_connect (string dbhost, string dbuser, string dbpassword, string dbname)
//
function db_connect_install ($dbhost, $dbuser, $dbpassword, $dbname) {
   
   global $CONF;
   global $DEBUG_TEXT;
   $link = "";

   if ($CONF['database_type'] == "mysql") {
      
      if (function_exists ("mysql_connect")) {
         
         $link 	 = @mysql_connect ($dbhost, $dbuser, $dbpassword);
         $succes = @mysql_select_db ($dbname, $link);
         
      } else {
         
          $_SESSION['svn_sessid']['dberror']		= mysql_errno().": ".mysql_error();
      	  $_SESSION['svn_sessid']['dbquery']		= "MySQL 3.x / 4.0 functions not available!<br />database_type = 'mysql' in config.inc.php, are you using a different database?";
	 	  db_ta ("ROLLBACK", $link);
	 	  db_disconnect( $link );
	 	
	 	  header( "location: database_error.php");
	 	  exit;
         
      }
   }

   if ($CONF['database_type'] == "mysqli")  {
      if (function_exists ("mysqli_connect")) {
         $link 		= @mysqli_connect ($dbhost, $dbuser, $dbpassword) or die ("<p />DEBUG INFORMATION:<br />Connect: " .  mysqli_connect_error () . "$DEBUG_TEXT");
         $succes 	= @mysqli_select_db ($link, $dbname) or die ("<p />DEBUG INFORMATION:<br />MySQLi Select Database: " .  mysqli_error () . "$DEBUG_TEXT");
      } else {
         print "<p />DEBUG INFORMATION:<br />MySQL 4.1 functions not available!<br />database_type = 'mysqli' in config.inc.php, are you using a different database? $DEBUG_TEXT";
         die;
      }
   }


   if ($link)
   {
      return $link;
   } else {
   	
	  $_SESSION['svn_sessid']['dberror']		= mysql_errno().": ".mysql_error();
      $_SESSION['svn_sessid']['dbquery']		= "Connect: Unable to connect to database: Make sure that you have set the correct database type in the config.inc.php file";
	  db_ta ("ROLLBACK", $link);
	  db_disconnect( $link );
	 	
	  if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  	$location								= "database_error.php";
	  } else {
	  	$location								= "../database_error.php";
	  }
	  
	  header( "location: $location");
	  exit;
   }
}



//
// db_disconnect
// Action: close connection to database
// Call: db_disconnect (resource link);
//
function db_disconnect ($link) {
   
   global $CONF;
   global $DEBUG_TEXT;
   
   if ($CONF['database_type'] == "mysql") 	mysql_close ($link);
   if ($CONF['database_type'] == "mysqli") 	mysqli_close ($link);
   if ($CONF['database_type'] == "pgsql") 	pg_close ($link);      
}



//
// db_query
// Action: Sends a query to the database and returns query result and number of rows
// Call: db_query (string query, resource link)
//
function db_query ($query, $link) {
   
   global $CONF;
   global $DEBUG_TEXT;
   
   $result 			= "";
   $number_rows 	= "";

   // database prefix workaround
   if (!empty ($CONF['database_prefix'])) {
      
      if (eregi ("^SELECT", $query)) {
         $query = substr ($query, 0, 14) . $CONF['database_prefix'] . substr ($query, 14);
      } else {
         $query = substr ($query, 0, 6) . $CONF['database_prefix'] . substr ($query, 7);
      }
   }
   
   if ($CONF['database_type'] == "mysql") {
      if(! $result = @mysql_query ($query, $link)) { 
      	
      	$_SESSION['svn_sessid']['dberror']		= mysql_errno().": ".mysql_error();
      	$_SESSION['svn_sessid']['dbquery']		= $query;
	 	db_ta ("ROLLBACK", $link);
	 	db_disconnect( $link );
	 	
	 	if ( file_exists ( realpath ( "database_error.php" ) ) ) {
	  		$location								= "database_error.php";
	  	} else {
	  		$location								= "../database_error.php";
	    }
	  
	 	header( "location: $location");
	 	exit;
	 	 
	 	#die ("Uups");
      }
   }
   
   if ($CONF['database_type'] == "mysqli") $result = @mysqli_query ($link, $query) or die ("<p />DEBUG INFORMATION:<br />Invalid query: " . mysqli_error() . "$DEBUG_TEXT");
   if ($CONF['database_type'] == "pgsql") {
      if (eregi ("LIMIT", $query)) { 
         $search = "/LIMIT (\w+), (\w+)/";
         $replace = "LIMIT \$2 OFFSET \$1";
         $query = preg_replace ($search, $replace, $query); 
      }
      
      $result = @pg_query ($link, $query) or die ("<p />DEBUG INFORMATION:<br />Invalid query: " . pg_last_error() . "$DEBUG_TEXT");
   } 

   if (eregi ("^SELECT", $query)) {
      // if $query was a SELECT statement check the number of rows with [database_type]_num_rows ().
      if ($CONF['database_type'] == "mysql") 	$number_rows = mysql_num_rows ($result);
      if ($CONF['database_type'] == "mysqli") 	$number_rows = mysqli_num_rows ($result);      
      if ($CONF['database_type'] == "pgsql") 	$number_rows = pg_num_rows ($result);
   } else {
      // if $query was something else, UPDATE, DELETE or INSERT check the number of rows with
      // [database_type]_affected_rows ().
      if ($CONF['database_type'] == "mysql") 	$number_rows = mysql_affected_rows ($link);
      if ($CONF['database_type'] == "mysqli") 	$number_rows = mysqli_affected_rows ($link);
      if ($CONF['database_type'] == "pgsql") 	$number_rows = pg_affected_rows ($result);      
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
function db_query_install ($query, $link) {
   
   global $CONF;
   global $DEBUG_TEXT;
   
   $result 			= "";
   $number_rows 	= "";

   // database prefix workaround
   if (!empty ($CONF['database_prefix'])) {
      
      if (eregi ("^SELECT", $query)) {
         $query = substr ($query, 0, 14) . $CONF['database_prefix'] . substr ($query, 14);
      } else {
         $query = substr ($query, 0, 6) . $CONF['database_prefix'] . substr ($query, 7);
      }
   }
   
   if ($CONF['database_type'] == "mysql") {
      if(! $result = @mysql_query ($query, $link)) { 
      	
      	die ("<p />DEBUG INFORMATION:<br />Query: $query<br />" .  mysql_error () . "$DEBUG_TEXT");
	 	 
	 	#die ("Uups");
      }
   }
   
   if ($CONF['database_type'] == "mysqli") $result = @mysqli_query ($link, $query) or die ("<p />DEBUG INFORMATION:<br />Invalid query: " . mysqli_error() . "$DEBUG_TEXT");
   if ($CONF['database_type'] == "pgsql") {
      if (eregi ("LIMIT", $query)) { 
         $search = "/LIMIT (\w+), (\w+)/";
         $replace = "LIMIT \$2 OFFSET \$1";
         $query = preg_replace ($search, $replace, $query); 
      }
      
      $result = @pg_query ($link, $query) or die ("<p />DEBUG INFORMATION:<br />Invalid query: " . pg_last_error() . "$DEBUG_TEXT");
   } 

   if (eregi ("^SELECT", $query)) {
      // if $query was a SELECT statement check the number of rows with [database_type]_num_rows ().
      if ($CONF['database_type'] == "mysql") 	$number_rows = mysql_num_rows ($result);
      if ($CONF['database_type'] == "mysqli") 	$number_rows = mysqli_num_rows ($result);      
      if ($CONF['database_type'] == "pgsql") 	$number_rows = pg_num_rows ($result);
   } else {
      // if $query was something else, UPDATE, DELETE or INSERT check the number of rows with
      // [database_type]_affected_rows ().
      if ($CONF['database_type'] == "mysql") 	$number_rows = mysql_affected_rows ($link);
      if ($CONF['database_type'] == "mysqli") 	$number_rows = mysqli_affected_rows ($link);
      if ($CONF['database_type'] == "pgsql") 	$number_rows = pg_affected_rows ($result);      
   }

   $return = array (
      "result" => $result,
      "rows" => $number_rows
   );
   return $return;
}



// db_row
// Action: Returns a row from a table
// Call: db_row (int result)
//
function db_row ($result) {
   
   global $CONF;
   $row = "";
   if ($CONF['database_type'] == "mysql") 	$row = mysql_fetch_row ($result);
   if ($CONF['database_type'] == "mysqli") 	$row = mysqli_fetch_row ($result);
   if ($CONF['database_type'] == "pgsql") 	$row = pg_fetch_row ($result);
   return $row;
}



// db_array
// Action: Returns a row from a table
// Call: db_array (int result)
//
function db_array ($result) {
  
   global $CONF;
   $row = "";
   if ($CONF['database_type'] == "mysql") 	$row = mysql_fetch_array ($result);
   if ($CONF['database_type'] == "mysqli") 	$row = mysqli_fetch_array ($result);
   if ($CONF['database_type'] == "pgsql") 	$row = pg_fetch_array ($result);   
   return $row;
}



// db_assoc
// Action: Returns a row from a table
// Call: db_assoc(int result)
//
function db_assoc ($result) {
   
   global $CONF;
   $row = "";
   
   if ($CONF['database_type'] == "mysql") 	$row = mysql_fetch_assoc ($result);
   if ($CONF['database_type'] == "mysqli") 	$row = mysqli_fetch_assoc ($result);
   if ($CONF['database_type'] == "pgsql") 	$row = pg_fetch_assoc ($result);   
   
   return $row;
}



//
// db_delete
// Action: Deletes a row from a specified table
// Call: db_delete (string table, string where, string delete, resource link)
//
function db_delete($table,$where,$delete,$link) {
   
   $result 			= db_query ("DELETE FROM $table WHERE $where='$delete'",$link);
   if ($result['rows'] >= 1)    {
      return $result['rows'];
   } else {
      return true;
   }
}



//
// db_log
// Action: Logs actions from admin
// Call: db_delete (string username, string domain, string action, string data, resource link)
//
function db_log ( $username, $data, $link="" ) {
   
   global $CONF;
   
   if( ! $link ) {
   		$link				= db_connect();
   }
   $REMOTE_ADDR 			= $_SERVER['REMOTE_ADDR'];
   
   if( $CONF['logging'] == 'YES' ) {
      
      $query				= "INSERT INTO log (timestamp, username, ipaddress, logmessage) " .
      						  "VALUES (NOW(), '$username', '$REMOTE_ADDR', '$data')";
      $result 				= db_query ($query, $link);
      
      if ($result['rows'] != 1) {
      
         return false;
         
      } else {
         
         return true;
         
      }
   }
}



//
// db_error
// Action: get last db error code
// Call: db_error (resource link)
//
function db_error($link) {
	
	global $CONF;
	
	$error 		= false;
	
	if( $CONF['database_type'] == "mysql" ) 	$error = @mysql_errno( $link ).": ".mysql_error( $link ); 
	if( $CONF['database_type'] == "mysqli" )	$error = @mysqli_errno( $link).": ".mysqli_error( $link );
	
	return $error;
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
	if ($CONF['database_type'] == "mysql") 	$result = @mysql_query ($action, $link) or die ("<p />DEBUG INFORMATION:<br />Invalid query($action): " . mysql_error() . "$DEBUG_TEXT");
	if ($CONF['database_type'] == "mysqli") $result = @mysqli_query ($link, $action) or die ("<p />DEBUG INFORMATION:<br />Invalid query: " . mysqli_error() . "$DEBUG_TEXT");
	if ($CONF['database_type'] == "pgsql") 	$result = @pg_query ($link, $action) or die ("<p />DEBUG INFORMATION:<br />Invalid query: " . pg_last_error() . "$DEBUG_TEXT");
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
	
	$result = db_query( "SELECT userid FROM svnusers WHERE id = $id", $link);
	if( $result['rows'] == 1 ) {
		
		$row				= db_array( $result['result'] );
		
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
	
	$result = db_query( "SELECT id " .
						"  FROM svnusers " .
						" WHERE (userid = '$userid') " .
						"   AND (deleted = '0000-00-00 00:00:00')", $link);
	if( $result['rows'] == 1 ) {
		
		$row				= db_array( $result['result'] );
		
		return $row['id'];
		
	} else {
		
		return false;
		
	}
	
}



//
// db_getUserRightByUserid
// Action: get global user right by userid
// Call: db_getUserRightByUserid (string userid, ressource link)
//
function db_getUserRightByUserid ($userid, $link) {
	
	global $CONF;
	
	$result			= db_query( "SELECT * " .
								"  FROM svnusers " .
								" WHERE (userid = '$userid') " .
								"   AND (deleted = '0000-00-00 00:00:00')", $link);
	if( $result['rows'] == 1 ) {
		
		$row		= db_array( $result['result'] );
		$mode		= strtolower( $row['mode'] );
		
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
	
	$result			= db_query( "SELECT * " .
								"  FROM svnrepos " .
								" WHERE (id = '$id') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row		= db_array( $result['result'] );
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
	
	$result				= db_query( "SELECT * " .
								"  FROM svnprojects " .
								" WHERE (id = '$id') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row			= db_array( $result['result'] );
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
	
	$result				= db_query( "SELECT * " .
								"  FROM svngroups " .
								" WHERE (id = '$id') ", $link);
	if( $result['rows'] == 1 ) {
		
		$row			= db_array( $result['result'] );
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
	
	$query						= "SELECT project_id, group_id, user_id, path, access_right " .
								  "  FROM svn_access_rights " .
								  " WHERE id = $id";
	$result						= db_query( $query, $link );
	
	if( $result['rows'] == 1 ) {
		
		$ret					= array();
		$row					= db_array( $result['result'] );
		$ret['project_id']		= $row['project_id'];
		$ret['user_id']			= $row['user_id'];
		$ret['group_id']		= $row['group_id'];
		$ret['path']			= $row['path'];
		$ret['access_right']	= $row['access_right'];
		
		$query					= "SELECT * " .
								  "  FROM svnprojects " .
								  " WHERE id = ".$row['project_id'];
		$result					= db_query( $query, $link );
		if( $result['rows'] == 1 ) {
			
			$row				= db_array( $result['result'] );
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
// Call: db_check_acl( string username, string action, ressource dbh )
//
function db_check_acl( $username, $action, $dbh ) {

	$query 							= "SELECT users_rights.allowed " .
									  "  FROM svnusers, rights, users_rights " .
									  " WHERE (svnusers.id = users_rights.user_id) " .
									  "   AND (rights.id = users_rights.right_id) " .
									  "   AND (svnusers.deleted = '0000-00-00 00:00:00') " .
									  "   AND (users_rights.deleted = '0000-00-00 00:00:00') " .
									  "   AND (svnusers.userid = '$username') " .
									  "   AND (rights.right_name = '$action')";

	$result 						= db_query( $query, $dbh );
	
	if( $result['rows'] > 0 ) {
		
		$row    					= db_array( $result['result'] );
		$right 						= $row['allowed'];
		
	} else {
	
		$right = "none";
		
	}

	return $right;
}



//
// db_get_preference
// Action: load user's preferences
// Call: db_get_preferences(int userid, resource link)
//
function db_get_preferences($userid, $link) {

	global $CONF;
	
	$id								= db_getIdByUserid( $userid, $link );
	$query							= "SELECT * " .
									  "  FROM preferences " .
									  " WHERE user_id = $id";
	$result							= db_query( $query, $link );
	
	if( $result['rows'] == 1 ) {
		
		$row						= db_array( $result['result'] );
		$page_size					= $row['page_size'];
		$preferences				= array();
		$preferences['page_size']	= $page_size;
		
	} else {
		
		$preferences['page_size']	= $CONF['page_size'];
		
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
	
	$query							= "SELECT * " .
									  "  FROM semaphores " .
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
	
	if( db_get_semaphore( $action, $type, $link ) ) {
		
		return false;
		
	} else {
		
		$query						= "INSERT INTO semaphores (action, status, type) " .
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
	
	if( db_get_semaphore( $action, $type, $link ) ) {
		
		$query							= "UPDATE semaphores " .
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
       	
       	$db_user 				= $CONF['database_user'];
    	$db_pass 				= $CONF['database_password'];
    	$db_host 				= $CONF['database_host'];
    	$db_name				= $CONF['database_name'];
    	
        if (self::$_sess_db = mysql_connect($db_host, $db_user, $db_pass)) {
            return mysql_select_db($db_name, self::$_sess_db);
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
    	
        #return mysql_close(self::$_sess_db);
        return true;
    }

    /**
     * Read the session
     * @param int session id
     * @return string string of the sessoin
     */
    public static function read($id) {
        
        if(self::$DEBUG != 0) {
        	db_log( 'gc', 'read executed' );
        }
        
        $id 						= mysql_real_escape_string($id);
        $sql 						= sprintf("SELECT `session_data` FROM `sessions` " .
                       							"WHERE `session` = '%s'", $id);
        if ($result = mysql_query($sql, self::$_sess_db)) {
            
            if (mysql_num_rows($result)) {
                
                $record 			= mysql_fetch_assoc($result);
                return $record['session_data'];
            }
        }
        
        return '';
    }

    /**
     * Write the session
     * @param int session id
     * @param string data of the session
     */
    public static function write($id, $data) {
        
        if(self::$DEBUG != 0) {
        	db_log( 'gc', 'write executed' );
        }
        
        $sql = sprintf("REPLACE INTO `sessions` VALUES('%s', '%s', '%s')",
                       mysql_real_escape_string($id, self::$_sess_db),
                       mysql_real_escape_string(time(), self::$_sess_db),
                       mysql_real_escape_string($data, self::$_sess_db)
                       );
        
        return mysql_query($sql, self::$_sess_db);
    }

    /**
     * Destoroy the session
     * @param int session id
     * @return bool
     */
    public static function destroy($id) {
       
       if(self::$DEBUG != 0) {
       		db_log( 'gc', 'destroy executed' );
       }
       	
        $sql = sprintf("DELETE FROM `sessions` WHERE `session` = '%s'", $id);
        
        return mysql_query($sql, self::$_sess_db);
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
        
        if(self::$DEBUG != 0) {
        	db_log( 'gc', 'gc executed ('.$max.')' );
        }
        
        $sql = sprintf("DELETE FROM `sessions` WHERE `session_expires` < '%s'",
                       mysql_real_escape_string(time() - $max));
        
        return mysql_query($sql, self::$_sess_db);
    }
}

ini_set('session.gc_probability', 50);
ini_set('session.gc_divisor', 50);
ini_set('session.save_handler', 'user');
ini_set('session.gc_maxlifetime', '1800');

if( isset( $CONF) and ($CONF['session_in_db'] == "YES") ) {
	
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
