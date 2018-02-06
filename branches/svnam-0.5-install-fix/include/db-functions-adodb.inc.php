<?php

/*
 * SVN Access Manager - a subversion access rights management tool
 * Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

/*
 *
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
if (preg_match("/db-functions-adodb\.inc\.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

if (file_exists(realpath("./include/adodb5/adodb.inc.php"))) {
    
    include_once ("./include/adodb5/adodb-exceptions.inc.php");
    include_once ("./include/adodb5/adodb.inc.php");
}
elseif (file_exists(realpath("../include/adodb5/adodb.inc.php"))) {
    
    include_once ("../include/adodb5/adodb-exceptions.inc.php");
    include_once ("../include/adodb5/adodb.inc.php");
}
elseif (file_exists("$installBase/include/adodb5/adodb.inc.php")) {
    
    include_once ("$installBase/include/adodb5/adodb-exceptions.inc.php");
    include_once ("$installBase/include/adodb5/adodb.inc.php");
}
else {
    
    die("can't find adodb.inc.php! Check your installation!\n");
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
function db_connect() {

    global $CONF;
    global $DEBUG_TEXT;
    
    $link = "";
    
    if (isset($CONF[DATABASE_CHARSET])) {
        $charset = $CONF[DATABASE_CHARSET];
    }
    else {
        $charset = "latin1";
    }
    
    if (isset($CONF[DATABASE_COLLATION])) {
        $collation = $CONF[DATABASE_COLLATION];
    }
    else {
        $collation = "latin1_german1_ci";
    }
    
    $nameset = "SET NAMES '$charset' COLLATE '$collation'";
    
    try {
        
        $link = ADONewConnection($CONF[DATABASE_TYPE]);
        $link->Pconnect($CONF[DATABASE_HOST], $CONF[DATABASE_USER], $CONF[DATABASE_PASSWORD], $CONF[DATABASE_NAME]);
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        
        if (($CONF[DATABASE_TYPE] == MYSQL) || ($CONF[DATABASE_TYPE] == MYSQLI)) {
            $link->Execute($nameset);
        }
        // $link->debug = true;
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = "Database connect";
        $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
        
        if (file_exists(realpath("database_error.php"))) {
            $location = "database_error.php";
        }
        else {
            $location = "../database_error.php";
        }
        
        header("location: $location");
        exit();
    }
    
    return $link;

}

//
// db_connect_install
// Action: Makes a connection to the database if it doesn't exist
// Call: db_connect (string dbhost, string dbuser, string dbpassword, string dbname)
//
function db_connect_install($dbhost, $dbuser, $dbpassword, $dbname, $charset, $collation, $dbtype = "", $test = "no") {

    global $CONF;
    global $DEBUG_TEXT;
    
    $link = "";
    $nameset = "SET NAMES '$charset' COLLATE '$collation'";
    $dbtype = ($dbtype == "") ? MYSQL : $dbtype;
    
    try {
        // error_log( "connect to $dbtype" );
        $link = ADONewConnection($dbtype);
        if ($dbtype == "oci8") {
            $link->Connect($dbname, $dbuser, $dbpassword);
        }
        else {
            $link->Connect($dbhost, $dbuser, $dbpassword, $dbname);
        }
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        
        if (($dbtype == MYSQL) || ($dbtype == MYSQLI)) {
            $link->Execute($nameset);
        }
    }
    catch ( exception $e ) {
        
        if ($test == "no") {
            
            $tDbError = $e->msg;
            $tDbQuery = "Connect: Unable to connect to database: Make sure that you have set the correct database type in the config.inc.php file and username and password are corect also!";
            
            if (file_exists(realpath("database_error_install.php"))) {
                $location = "database_error_install.php";
            }
            else {
                $location = "../database_error_install.php";
            }
            
            header("location: $location?dberror=$tDbError&dbquery=$tDbQuery");
            exit();
        }
        else {
            
            error_log("db connect test error: " . $e->msg);
            return array(
                    'ret' => false,
                    'error' => $e->msg
            );
        }
    }
    
    return $link;

}

//
// db_connect_test
// Action: Makes a connection to the database if it doesn't exist
// Call: db_connect ()
//
function db_connect_test($dbtype, $dbhost, $dbuser, $dbpass, $dbname, $charset = 'utf8', $collation = 'utf8_general_ci') {

    global $DEBUG_TEXT;
    
    $link = "";
    
    $nameset = "SET NAMES '$charset' COLLATE '$collation'";
    
    try {
        
        $link = ADONewConnection($dbtype);
        $link->Pconnect($dbhost, $dbuser, $dbpass, $dbname);
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        
        if (($dbtype == MYSQL) || ($dbtype == MYSQLI)) {
            $link->Execute($nameset);
        }
    }
    catch ( exception $e ) {
        
        $tDbError = 'DB connect error';
        $tDbQuery = "Database connect";
        $tDbFunc = "db_connect";
        
        echo "User=" . $dbuser . "\nType=" . $dbtype . "\n" . "dberror=$tDbError dbquery=$tDbQuery dbfunc=$tDbFunc\n";
        
        $link = null;
    }
    
    return $link;

}

//
// db_disconnect
// Action: close connection to database
// Call: db_disconnect (resource link);
//
function db_disconnect($link) {

    global $CONF;
    global $DEBUG_TEXT;
    
    try {
        
        $link->Close();
    }
    catch ( exception $e ) {
    }

}

//
// db_query
// Action: Sends a query to the database and returns query result and number of rows
// Call: db_query (string query, resource link)
//
function db_query($query, $link, $limit = -1, $offset = -1) {

    global $CONF;
    global $DEBUG_TEXT;
    
    $result = "";
    $number_rows = "";
    $query = trim($query);
    $error = 0;
    
    // database prefix workaround
    if (! empty($CONF[DATABASE_PREFIX])) {
        
        if (preg_match("/^SELECT/i", $query)) {
            $query = substr($query, 0, 14) . $CONF[DATABASE_PREFIX] . substr($query, 14);
        }
        else {
            $query = substr($query, 0, 6) . $CONF[DATABASE_PREFIX] . substr($query, 7);
        }
    }
    
    try {
        
        if (($CONF[DATABASE_TYPE] != MYSQL) && ($CONF[DATABASE_TYPE] != MYSQLI)) {
            
            if (preg_match("/LIMIT/i", $query)) {
                
                $search = "/LIMIT (\w+), (\w+)/";
                $replace = "LIMIT \$2 OFFSET \$1";
                $query = preg_replace($search, $replace, $query);
            }
        }
        
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        if (($limit != - 1)) {
            if ($offset != - 1) {
                $result = $link->SelectLimit($query, $limit, $offset);
            }
            else {
                $result = $link->SelectLimit($query, $limit);
            }
        }
        else {
            $result = $link->Execute($query);
        }
        
        if (preg_match("/^SELECT/i", $query)) {
            $number_rows = $result->RecordCount();
        }
        else {
            
            // error_log( "query: >$query<");
            $number_rows = $link->Affected_Rows();
        }
    }
    catch ( exception $e ) {
        
        // error_log( "ERROR: ".print_r($e, true));
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = $query;
        $_SESSION[SVNSESSID][DBFUNCTION] = "db_query";
        db_ta(ROLLBACK, $link);
        db_disconnect($link);
        
        error_log("DB-Error: " . $_SESSION[SVNSESSID][DBERROR]);
        error_log("DB-Query: " . $_SESSION[SVNSESSID][DBQUERY]);
        
        if (file_exists(realpath("database_error.php"))) {
            $location = "database_error.php";
        }
        else {
            $location = "../database_error.php";
        }
        
        $error = 1;
        
        // error_log( "jumping to $location" );
        header("Location: $location");
        exit();
    }
    
    $return = array(
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
function db_query_install($query, $link, $limit = -1, $offset = -1) {

    global $CONF;
    global $DEBUG_TEXT;
    
    $result = "";
    $number_rows = "";
    $query = trim($query);
    
    // database prefix workaround
    if (! empty($CONF[DATABASE_PREFIX])) {
        
        if (preg_match("/^SELECT/i", $query)) {
            $query = substr($query, 0, 14) . $CONF[DATABASE_PREFIX] . substr($query, 14);
        }
        else {
            $query = substr($query, 0, 6) . $CONF[DATABASE_PREFIX] . substr($query, 7);
        }
    }
    
    try {
        
        if (($CONF[DATABASE_TYPE] != MYSQL) && ($CONF[DATABASE_TYPE] != MYSQLI)) {
            
            if (preg_match("/LIMIT/i", $query)) {
                $search = "/LIMIT (\w+), (\w+)/";
                $replace = "LIMIT \$2 OFFSET \$1";
                $query = preg_replace($search, $replace, $query);
            }
        }
        
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        if (($limit != - 1)) {
            if ($offset != - 1) {
                $result = $link->SelectLimit($query, $limit, $offset);
            }
            else {
                $result = $link->SelectLimit($query, $limit);
            }
        }
        else {
            $result = $link->Execute($query);
        }
        if (preg_match("/^SELECT/i", $query)) {
            $number_rows = $result->RecordCount();
        }
        else {
            $number_rows = $link->Affected_rows();
        }
    }
    catch ( exception $e ) {
        
        $tDbError = urlencode($e->msg);
        $tDbQuery = $query;
        
        error_log("DB Error: $tDbError");
        error_log("DB Query: $query");
        
        if (file_exists(realpath("database_error.php"))) {
            $location = "database_error_install.php";
        }
        else {
            $location = "../database_error_install.php";
        }
        
        header("location: " . $location . "?dbquery=$tDbQuery&dberror=$tDbError&dbfunction=db_query_install");
        exit();
    }
    
    $return = array(
            "result" => $result,
            "rows" => $number_rows
    );
    
    return $return;

}

// db_assoc
// Action: Returns a row from a table
// Call: db_assoc(int result)
//
function db_assoc($result) {

    global $CONF;
    
    try {
        $row = $result->FetchRow();
        if ($row === false) {
            $row = "";
        }
        else {
            
            $newrow = array();
            
            foreach( $row as $key => $value) {
                $key = strtolower($key);
                $newrow[$key] = $value;
            }
            $row = $newrow;
        }
    }
    catch ( exception $e ) {
        
        $row = "";
    }
    return $row;

}

//
// db_log
// Action: Logs actions from admin
// Call: db_delete (string username, string domain, string action, string data, resource link)
//
function db_log($username, $data, $link = "") {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    
    try {
        
        if (! $link) {
            $link = db_connect();
        }
        
        $dbnow = db_now();
        $query = "INSERT INTO " . $schema . "log (logtimestamp, username, ipaddress, logmessage) " . "VALUES ('$dbnow', '$username', '$REMOTE_ADDR', '$data')";
        // error_log( "logging: $query" );
        $link->Execute($query);
        return true;
    }
    catch ( exception $e ) {
        
        $errormsg = $e->msg;
        
        error_log("Database error during log write process");
        error_log("DB query: $query");
        error_log("DB error messge: $errormsg");
        
        return false;
    }

}

//
// db_ta
// Action: transactions
// Call: db_ta (string action, resource link)
//
function db_ta($action, $link) {

    global $CONF;
    global $DEBUG_TEXT;
    
    if ($CONF['database_innodb'] == 'YES') {
        
        try {
            
            if (strtoupper($action) == BEGIN) {
                
                $link->StartTrans();
            }
            elseif (strtoupper($action) == COMMIT) {
                
                $link->CompleteTrans();
            }
            elseif (strtoupper($action) == ROLLBACK) {
                
                $link->FailTrans();
            }
            else {
                
                $_SESSION[SVNSESSID][DBERROR] = sprintf(_("Invalid transaction type %s"), $action);
                $_SESSION[SVNSESSID][DBQUERY] = $action;
                $_SESSION[SVNSESSID][DBFUNCTION] = "db_ta";
                db_disconnect($link);
                
                error_log("DB-Error: " . $_SESSION[SVNSESSID][DBERROR]);
                error_log("DB-Query: " . $_SESSION[SVNSESSID][DBQUERY]);
                
                if (file_exists(realpath("database_error.php"))) {
                    $location = "database_error.php";
                }
                else {
                    $location = "../database_error.php";
                }
                
                header("location: $location");
                exit();
            }
        }
        catch ( exception $e ) {
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $action;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_ta";
            db_disconnect($link);
            
            error_log("DB-Error: " . $_SESSION[SVNSESSID][DBERROR]);
            error_log("DB-Query: " . $_SESSION[SVNSESSID][DBQUERY]);
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            header("location: $location");
            exit();
        }
    }
    
    return true;

}

//
// db_getUseridById
// Action: get userid from database table svnusers with id
// Call: db_getUseridById (string id, resource link)
//
function db_getUseridById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT userid FROM " . $schema . "svnusers WHERE id = $id", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row[USERID];
    }
    else {
        
        return false;
    }

}

//
// db_getIdByUserid
// Action: get id from database table svnusers with userid
// Call: db_getIdByUserid (string userid, resource link)
//
function db_getIdByUserid($userid, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT id " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row['id'];
    }
    else {
        
        return false;
    }

}

//
// db_now
// Action: get a 14 digit timestamp in format jjjjmmddhhmmss
// Call: db_now()
//
function db_now() {

    $date = date('YmdHis');
    return $date;

}

//
// db_last_insert_id
// Action: get last inserted id in a table
// Call: db_get_last_insert_id($table, $column, $link)
//
function db_get_last_insert_id($table, $column, $link, $schema = "") {

    global $CONF;
    
    if ($schema == "") {
        $schema = isset($CONF[DATABASE_SCHEMA]) ? $CONF[DATABASE_SCHEMA] : "";
    }
    
    if ($id = $link->Insert_Id()) {
    }
    else {
        
        try {
            // error_log( "database = ". $link->databaseType);
            // error_log( "schema = $schema" );
            
            if ($link->databaseType == "oci8") {
                $query = "SELECT $schema.$table" . "_SEQ.currval AS id FROM dual";
            }
            else {
                $query = "SELECT CURRVAL(pg_get_serial_sequence('$schema.$table','$column')) AS id";
            }
            $result = db_query($query, $link);
            $row = db_assoc($result[RESULT]);
            $id = $row['id'];
        }
        catch ( exception $e ) {
            
            $id = false;
        }
    }
    
    return $id;

}

//
// db_getUserRightByUserid
// Action: get global user right by userid
// Call: db_getUserRightByUserid (string userid, ressource link)
//
function db_getUserRightByUserid($userid, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        $mode = strtolower($row[USER_MODE]);
        
        return $mode;
    }
    else {
        
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
    
    $mode = "";
    $schema = db_determine_schema();
    $result = db_query("SELECT svnusers.user_mode " . "  FROM svnusers, svngroups, svn_users_groups " . " WHERE (svngroups.id = $groupid) " . "   AND (svn_users_groups.group_id = svngroups.id) " . "   AND (svn_users_groups.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_users_groups.deleted = '00000000000000')", $link);
    while ( $row = db_assoc($result[RESULT]) ) {
        
        if ((strtolower($row[USER_MODE]) == "write") and ($mode == "")) {
            $mode = strtolower($row[USER_MODE]);
        }
        elseif (strtolower($row[USER_MODE]) == "read") {
            $mode = strtolower($row[USER_MODE]);
        }
    }
    
    return $mode;

}

//
// db_getRepoById
// Action: get repository by id
// Call: db_getRepobyId (string id, ressource link)
//
function db_getRepoById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (id = '$id') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        $reponame = $row['reponame'];
        
        return $reponame;
    }
    else {
        
        return false;
    }

}

//
// db_getRepoByName
// Action: get repository by name
// Call: db_getRepobyId (string reponame, ressource link)
//
function db_getRepoByName($reponame, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (reponame = '$reponame') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        $id = $row['id'];
        
        return $id;
    }
    else {
        
        return false;
    }

}

//
// db_getProjectById
// Action: get project by id
// Call: db_getProjectById (string id, ressource link)
//
function db_getProjectById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE (id = '$id') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        $projectname = $row['svnmodule'];
        
        return $projectname;
    }
    else {
        
        return false;
    }

}

//
// db_getGroupById
// Action: get group by id
// Call: db_getGroupById (string id, ressource link)
//
function db_getGroupById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = '$id') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        $groupname = $row[GROUPNAME];
        
        return $groupname;
    }
    else {
        
        return false;
    }

}

//
// db_getRightName
// Action: get name for a right
// Call: db_getRightName(string id, resource link)
//
function db_getRightName($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT right_name " . "  FROM rights " . " WHERE (id = $id) " . "   AND (deleted = '00000000000000')";
    $result = db_query($query, $link);
    if ($result['rows'] == 1) {
        $row = db_assoc($result[RESULT]);
        return ($row[RIGHT_NAME]);
    }
    else {
        return ("undefined");
    }

}

//
// db_getRightData
// Action: get data for access right
// Call: db_getRightData(string id, resource link)
//
function db_getRightData($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT project_id, group_id, user_id, path, access_right " . "  FROM " . $schema . "svn_access_rights " . " WHERE id = $id";
    $result = db_query($query, $link);
    
    if ($result['rows'] == 1) {
        
        $ret = array();
        $row = db_assoc($result[RESULT]);
        $ret[PROJECT_ID] = $row[PROJECT_ID];
        $ret[USER_ID] = $row[USER_ID];
        $ret[GROUP_ID] = $row[GROUP_ID];
        $ret['path'] = $row['path'];
        $ret[ACCESS_RIGHT] = $row[ACCESS_RIGHT];
        
        $query = "SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE id = " . $row[PROJECT_ID];
        $result = db_query($query, $link);
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result[RESULT]);
            $ret[REPO_ID] = $row[REPO_ID];
        }
        else {
            
            return false;
        }
        
        return $ret;
    }
    else {
        
        return false;
    }

}

//
// db_getGroupsForUser
// Action: get all groups for an user
// Call: db_getGroupsForUser(string $tUserId, resource $dbh)
//
function db_getGroupsForUser($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tGroups = array();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups, " . $schema . "svn_users_groups " . " WHERE (svn_users_groups.user_id = '$tUserId') " . "   AND (svn_users_groups.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_users_groups.deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tGroups[] = $row;
    }
    
    return ($tGroups);

}

//
// db_getProjectResponsibleForUser
// Action: get projects an user is responsible for
// Call: db_getProjectResponsibleForUser(string $tUserId, resource $dbh)
//
function db_getProjectResponsibleForUser($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tProjects = array();
    $query = "SELECT svnmodule, reponame " . "  FROM " . $schema . "svnprojects, " . $schema . "svn_projects_responsible, " . $schema . "svnrepos " . " WHERE (svn_projects_responsible.user_id = '$tUserId') " . "   AND (svn_projects_responsible.deleted = '00000000000000') " . "   AND (svn_projects_responsible.project_id = svnprojects.id) " . "   AND (svnprojects.deleted = '00000000000000') " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svnrepos.deleted = '00000000000000') " . "ORDER BY svnmodule ASC";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tProjects[] = $row;
    }
    
    return ($tProjects);

}

//
// db_getAccessRightsForUser
// Action: get access rights assigned to an user
// Call: db_getAccessRightsForUser(string $tUserId, array $tGroups, resource $dbh)
//
function db_getAccessRightsForUser($tUserId, $tGroups, $dbh) {

    global $CONF;
    
    if (isset($CONF['repoPathSortOrder'])) {
        $pathSort = $CONF['repoPathSortOrder'];
    }
    else {
        $pathSort = "ASC";
    }
    
    $schema = db_determine_schema();
    $tAccessRights = array();
    $curdate = strftime("%Y%m%d");
    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) ";
    if (count($tGroups) > 0) {
        $query .= "     AND ((svn_access_rights.user_id = $tUserId) ";
        foreach( $tGroups as $entry) {
            $query .= "    OR (svn_access_rights.group_id = " . $entry[GROUP_ID] . ") ";
        }
        $query .= "       ) ";
    }
    else {
        $query .= "     AND (svn_access_rights.user_id = $tUserId) ";
    }
    $query .= "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnrepos.reponame ASC, svnprojects.svnmodule ASC, svn_access_rights.path $pathSort";
    
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        if (($row[USER_ID] != 0) && ($row[GROUP_ID] != 0)) {
            $row[ACCESSBY] = _("user id + group id");
        }
        elseif ($row[GROUP_ID] != 0) {
            $row[ACCESSBY] = _("group id");
        }
        elseif ($row[USER_ID] != 0) {
            $row[ACCESSBY] = _("user id");
        }
        else {
            $row[ACCESSBY] = " ";
        }
        $tAccessRights[] = $row;
    }
    
    return ($tAccessRights);

}

//
// db_getAccessRights
// Action: get access rights of an user
// Call: db_getAccessRights(integer $user_id, integer $start, integer $count, resource $dbh)
//
function db_getAccessRights($user_id, $start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    if ($user_id != - 1) {
        $id = db_getIdByUserid($user_id, $dbh);
        if ($id == false) {
            $tProjectIds = "";
            $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (deleted = '00000000000000')";
        }
        else {
            $tProjectIds = "";
            $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (user_id = $id) " . "   AND (deleted = '00000000000000')";
        }
    }
    else {
        
        $tProjectIds = "";
        $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (deleted = '00000000000000')";
    }
    
    $result = db_query($query, $dbh);
    while ( $row = db_assoc($result[RESULT]) ) {
        
        if ($tProjectIds == "") {
            
            $tProjectIds = $row[PROJECT_ID];
        }
        else {
            
            $tProjectIds = $tProjectIds . "," . $row[PROJECT_ID];
        }
    }
    
    $tAccessRights = array();
    
    if ($tProjectIds != "") {
        
        $query = "SELECT svn_access_rights.id AS id, svnmodule, modulepath, svnrepos." . "       reponame, valid_from, valid_until, path, access_right, recursive," . "       svn_access_rights.user_id, svn_access_rights.group_id, repopath " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.id IN (" . $tProjectIds . "))" . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') " . "ORDER BY LOWER(svnmodule) ASC ";
        $result = db_query($query, $dbh, $count, $start);
        
        while ( $row = db_assoc($result[RESULT]) ) {
            
            $entry = $row;
            $userid = $row['user_id'];
            if (empty($userid)) {
                $userid = 0;
            }
            
            $groupid = $row['group_id'];
            if (empty($groupid)) {
                $groupid = 0;
            }
            
            $entry[GROUPNAME] = "";
            $entry[USERNAME] = "";
            $add = false;
            
            if ($userid != "0") {
                
                $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE id = $userid";
                $resultread = db_query($query, $dbh);
                if ($resultread['rows'] == 1) {
                    
                    $row = db_assoc($resultread[RESULT]);
                    $entry[USERNAME] = $row[USERID];
                }
            }
            
            if ($groupid != "0") {
                
                $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE id = $groupid";
                $resultread = db_query($query, $dbh);
                if ($resultread['rows'] == 1) {
                    
                    $row = db_assoc($resultread[RESULT]);
                    $entry[GROUPNAME] = $row[GROUPNAME];
                    $add = true;
                }
                else {
                    $entry[GROUPNAME] = "unknown";
                }
            }
            
            $tAccessRights[] = $entry;
        }
    }
    
    return $tAccessRights;

}

//
// db_getCountAccessRights
// Action: get count of user's access rights
// Call: db_getCountAccessRights(integer $user_id, resource $dbh)
//
function db_getCountAccessRights($user_id, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    if ($user_id != - 1) {
        $id = db_getIdByUserid($user_id, $dbh);
        if ($id == false) {
            $tProjectIds = "";
            $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (deleted = '00000000000000')";
        }
        else {
            $tProjectIds = "";
            $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (user_id = $id) " . "   AND (deleted = '00000000000000')";
        }
    }
    else {
        
        $tProjectIds = "";
        $query = "SELECT * " . "  FROM " . $schema . "svn_projects_responsible " . " WHERE (deleted = '00000000000000')";
    }
    
    $result = db_query($query, $dbh);
    while ( $row = db_assoc($result[RESULT]) ) {
        
        if ($tProjectIds == "") {
            
            $tProjectIds = $row[PROJECT_ID];
        }
        else {
            
            $tProjectIds = $tProjectIds . "," . $row[PROJECT_ID];
        }
    }
    
    if ($tProjectIds != "") {
        
        $query = "SELECT COUNT(*) AS anz " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.id IN (" . $tProjectIds . "))" . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') ";
        
        $result = db_query($query, $dbh);
        
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result[RESULT]);
            
            return $row['anz'];
        }
        else {
            
            return false;
        }
    }
    else {
        
        return 0;
    }

}

//
// db_getGroupList
// Action: get groups
// Call: db_getGroupList(integer $start, integer $count, resource $dbh)
//
function db_getGroupList($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tGroups = array();
    $query = " SELECT * " . "   FROM " . $schema . "svngroups " . "   WHERE (deleted = '00000000000000') " . "ORDER BY groupname ASC";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tGroups[] = $row;
    }
    
    return $tGroups;

}

//
// db_getGroups
// Action: get groups
// Call: db_getGroups(integer $start, integer $count, resource $dbh)
//
function db_getGroups($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $tGroups = array();
    $query = "SELECT  svnusers.userid, svnusers.name, svnusers.givenname, svngroups.groupname, svngroups.description, svn_groups_responsible.allowed, svn_groups_responsible.id AS id " . "   FROM " . $schema . "svn_groups_responsible, " . $schema . "svngroups, " . $schema . "svnusers " . "   WHERE (svnusers.deleted = '00000000000000') " . "     AND (svngroups.deleted = '00000000000000') " . "     AND (svn_groups_responsible.deleted = '00000000000000') " . "     AND (svnusers.id = svn_groups_responsible.user_id) " . "     AND (svngroups.id = svn_groups_responsible.group_id) " . "ORDER BY groupname ASC";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tGroups[] = $row;
    }
    
    return $tGroups;

}

//
// db_getCountGroups
// Action: get number of groups
// Call: db_getCountGroups(resource $dbh);
//
function db_getCountGroups($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT  COUNT(*) AS anz " . "   FROM " . $schema . "svn_groups_responsible, " . $schema . "svngroups, " . $schema . "svnusers " . "   WHERE (svnusers.deleted = '00000000000000') " . "     AND (svngroups.deleted = '00000000000000') " . "     AND (svn_groups_responsible.deleted = '00000000000000') " . "     AND (svnusers.id = svn_groups_responsible.user_id) " . "     AND (svngroups.id = svn_groups_responsible.group_id)";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row['anz'];
    }
    else {
        
        return false;
    }

}

//
// db_getGroupsAllowed
// Action: get allowed groups
// Call: db_getGroupsAllowed(integer $start, integer $count, integer $groupAdmin, array $tGroupsAllowed, resource $dbh)
//
function db_getGroupsAllowed($start, $count, $groupAdmin, $tGroupsAllowed, $dbh) {

    $schema = db_determine_schema();
    $tGroups = array();
    
    if ($groupAdmin == 1) {
        
        $grouplist = "";
        
        foreach( $tGroupsAllowed as $groupid => $right) {
            
            if ($grouplist == "") {
                $grouplist = "'" . $groupid . "'";
            }
            else {
                $grouplist .= ",'" . $groupid . "'";
            }
        }
        
        $grouplist = "(" . $grouplist . ")";
        
        $query = "SELECT  * " . "   FROM " . $schema . "svngroups " . "   WHERE (deleted = '00000000000000') " . "     AND (id in $grouplist) " . "ORDER BY groupname ASC ";
    }
    else {
        $query = "SELECT  * " . "   FROM " . $schema . "svngroups " . "   WHERE (deleted = '00000000000000') " . "ORDER BY groupname ASC ";
    }
    
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tGroups[] = $row;
    }
    
    return $tGroups;

}

//
// db_getCountGroupsAllowed
// Action: get count of allowed groups
// Call: db_getCountGroupsAllowed(integer $groupAdmin, array $tGroupsAllowed, resource $dbh)
//
function db_getCountGroupsAllowed($groupAdmin, $tGroupsAllowed, $dbh) {

    $schema = db_determine_schema();
    
    if ($groupAdmin == 1) {
        $grouplist = "";
        
        foreach( $tGroupsAllowed as $groupid => $right) {
            
            if ($grouplist == "") {
                $grouplist = "'" . $groupid . "'";
            }
            else {
                $grouplist .= ",'" . $groupid . "'";
            }
        }
        
        $grouplist = "(" . $grouplist . ")";
        
        $query = "SELECT  COUNT(*) AS anz " . "   FROM " . $schema . "svngroups " . "   WHERE (deleted = '00000000000000') " . "     AND (id in $grouplist)";
    }
    else {
        $query = "SELECT  COUNT(*) AS anz " . "   FROM " . $schema . "svngroups " . "   WHERE (deleted = '00000000000000') ";
    }
    
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row['anz'];
    }
    else {
        
        return false;
    }

}

//
// db_getProjects
// Action: get projects from db
// Call: db_getProjects(integer $start, integer $count, resource $dbh)
//
function db_getProjects($start, $count, $dbh) {

    $schema = db_determine_schema();
    $tProjects = array();
    $query = "SELECT   svnprojects.id, svnprojects.svnmodule, svnprojects.modulepath, svnrepos.reponame " . "    FROM " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svnrepos.deleted = '00000000000000') " . "     AND (svnprojects.deleted = '00000000000000') " . "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnmodule ASC ";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tProjects[] = $row;
    }
    
    return $tProjects;

}

//
// db_getCountProjects
// Action: get count of projects
// Call: db_getCountProjects(resource $dbh)
//
function db_getCountProjects($dbh) {

    $schema = db_determine_schema();
    
    $query = "SELECT   COUNT(*) AS anz " . "    FROM " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svnrepos.deleted = '00000000000000') " . "     AND (svnprojects.deleted = '00000000000000') " . "     AND (svnprojects.repo_id = svnrepos.id) ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row['anz'];
    }
    else {
        
        return false;
    }

}

//
// db_getLockedUsers
// Action: get locked users from db
// Call: db_getLockedUsers(integer $start, integer $count, resource $dbh)
//
function db_getLockedUsers($start, $count, $dbh) {

    $schema = db_determine_schema();
    $tLockedUsers = array();
    $query = " SELECT * " . "   FROM " . $schema . "svnusers " . "  WHERE (deleted = '00000000000000') " . "    AND (locked != 0) " . "ORDER BY userid ASC ";
    // " LIMIT $start, $count";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tLockedUsers[] = $row;
    }
    
    return $tLockedUsers;

}

//
// db_getCountLockedUsers
// Action: get count of locked users
// Call: db_getCountLockedUsers(resource $dbh)
//
function db_getCountLockedUsers($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tUsers = array();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "svnusers " . "  WHERE (deleted = '00000000000000') " . "    AND (locked != 0) " . "GROUP BY userid " . "ORDER BY userid";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        $count = $row['anz'];
        
        return $count;
    }
    else {
        
        return false;
    }

}

//
// db_getLog
// Action: get log entries from db
// Call: db_getLog(integer $start, integer $count, resource $dbh)
//
function db_getLog($start, $count, $dbh) {

    $schema = db_determine_schema();
    $tLogmessages = array();
    $query = " SELECT * " . "   FROM " . $schema . "log " . "ORDER BY logtimestamp DESC ";
    // " LIMIT $start, $count";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tLogmessages[] = $row;
    }
    
    return $tLogmessages;

}

//
// db_getCountLog
// Action: get count of log records
// Call: db_getCountLog(resource $dbh)
//
function db_getCountLog($dbh) {

    $schema = db_determine_schema();
    $tUsers = array();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "log ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        $count = $row['anz'];
        
        return $count;
    }
    else {
        
        return false;
    }

}

//
// db_getUsers
// Action: get users from db
// Call: db_getUsers(integer $start, integer $count, resource $dbh)
//
function db_getUsers($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tUsers = array();
    $query = " SELECT * " . "   FROM " . $schema . "svnusers " . "   WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF[USER_SORT_FIELDS] . " " . $CONF[USER_SORT_ORDER];
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tUsers[] = $row;
    }
    
    return $tUsers;

}

//
// db_getUserData
// Action: get data for an user from db
// Call: db_getUserData(integer $tUserId, resource $dbh)
//
function db_getUserData($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = $tUserId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    
    return ($row);

}

//
// db_getGroupData
// Action: get data for a group from db
// Call: db_getGroupData(integer $tGroupId, resource $dbh)
//
function db_getGroupData($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = $tGroupId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    
    return ($row);

}

//
// db_getUsersForGroup
// Action: get all users having a particular group
// Call: db_getUsersForGroup(integer $tGroupId, resource $dbh)
//
function db_getUsersForGroup($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tUsers = array();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers, " . $schema . "svn_users_groups " . " WHERE (svn_users_groups.group_id = '$tGroupId') " . "   AND (svn_users_groups.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_users_groups.deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tUsers[] = $row;
    }
    
    return ($tUsers);

}

//
// db_getGroupAdminsForGroup
// Action: get all admins for a particular group
// Call: db_getGroupAdminsForGroup(integer $tGroupId, resource $dbh)
//
function db_getGroupAdminsForGroup($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tAdmins = array();
    $query = "SELECT svnusers.userid, svnusers.name, svnusers.givenname, svn_groups_responsible.allowed " . "  FROM " . $schema . "svnusers, " . $schema . "svn_groups_responsible, " . $schema . "svngroups " . " WHERE (svn_groups_responsible.group_id = '$tGroupId') " . "   AND (svn_groups_responsible.deleted = '00000000000000') " . "   AND (svn_groups_responsible.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svngroups.id = svn_groups_responsible.group_id) " . "   AND (svngroups.deleted = '00000000000000') " . "ORDER BY userid ASC";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tAdmins[] = $row;
    }
    
    return ($tAdmins);

}

//
// db_getAccessRightsForGroup
// Action: get all admins for a particular group
// Call: db_getAccessRightsForGroup(integer $tGroupId, resource $dbh)
//
function db_getAccessRightsForGroup($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tAccessRights = array();
    $curdate = strftime("%Y%m%d");
    $query = "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " . "    FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svn_access_rights.deleted = '00000000000000') " . "     AND (svn_access_rights.valid_from <= '$curdate') " . "     AND (svn_access_rights.valid_until >= '$curdate') " . "     AND (svn_access_rights.project_id = svnprojects.id) " . "     AND (svn_access_rights.group_id = $tGroupId) " . "     AND (svnprojects.repo_id = svnrepos.id) " . "ORDER BY svnprojects.repo_id ASC, LENGTH(svn_access_rights.path) DESC";
    
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tAccessRights[] = $row;
    }
    
    return ($tAccessRights);

}

//
// db_getGrantedRights
// Action: get all granted rights
// Call: db_getAccessRightsForGroup(integer $start, integer $count, resource $dbh)
//
function db_getGrantedRights($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tGrantedRights = array();
    $query = "  SELECT * " . "    FROM " . $schema . "svnusers " . "   WHERE deleted = '00000000000000' " . "ORDER BY " . $CONF[USER_SORT_FIELDS] . " " . $CONF[USER_SORT_ORDER];
    // " LIMIT $start, $count";
    $result = db_query($query, $dbh, $count, $start);
    $olduserid = "";
    $rights = "";
    $entry = array();
    
    while ( $row = db_assoc($result['result']) ) {
        
        if ($row[GIVENNAME] != "") {
            
            $entry['name'] = $row[GIVENNAME] . " " . $row['name'];
        }
        else {
            
            $entry['name'] = $row['name'];
        }
        
        $entry[USERID] = $row[USERID];
        $entry['locked'] = $row['locked'];
        $id = $row['id'];
        
        $query = "SELECT rights.right_name, users_rights.allowed " . "  FROM " . $schema . "rights, " . $schema . "users_rights " . " WHERE (rights.id = users_rights.right_id) " . "   AND (users_rights.user_id = $id ) " . "   AND (users_rights.deleted = '00000000000000') " . "   AND (rights.deleted = '00000000000000') " . "ORDER BY user_id, right_id";
        $resultrights = db_query($query, $dbh);
        
        while ( $rowrights = db_assoc($resultrights['result']) ) {
            
            if ($rights == "") {
                
                $rights = $rowrights[RIGHT_NAME] . " (" . $rowrights[ALLOWED] . ")";
            }
            else {
                
                $rights = $rights . ", " . $rowrights[RIGHT_NAME] . " (" . $rowrights[ALLOWED] . ")";
            }
        }
        
        $entry['rights'] = $rights;
        $rights = "";
        $tGrantedRights[] = $entry;
        $entry = array();
    }
    
    return $tGrantedRights;

}

//
// db_getCountGrantedRight
// Action: get count of log records
// Call: db_getCountGrantedRight(resource $dbh)
//
function db_getCountGrantedRights($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "svnusers " . "  WHERE (deleted = '00000000000000') " . "ORDER BY " . $CONF[USER_SORT_FIELDS] . " " . $CONF[USER_SORT_ORDER];
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        $count = $row['anz'];
        
        return $count;
    }
    else {
        
        return false;
    }

}

//
// db_getAccessRightsList
// Action: get all valid rights
// Call: db_getAccessRightsList(string $valid, integer $start, integer $count, resource $dbh)
//
function db_getAccessRightsList($valid, $start, $count, $dbh) {

    $schema = db_determine_schema();
    $tAccessRights = array();
    $query = "SELECT svn_access_rights.id, svnmodule, modulepath, svnrepos." . "       reponame, valid_from, valid_until, path, access_right, recursive," . "       svn_access_rights.user_id, svn_access_rights.group_id " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') " . "   AND (valid_from <= '$valid' ) " . "   AND (valid_until >= '$valid') " . "ORDER BY svnrepos.reponame, svn_access_rights.path ";
    // " LIMIT $start, $count";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $entry = $row;
        $userid = $row['user_id'];
        if (empty($userid)) {
            $userid = 0;
        }
        $groupid = $row['group_id'];
        if (empty($groupid)) {
            $groupid = 0;
        }
        $entry[GROUPNAME] = "";
        $entry[USERNAME] = "";
        
        if ($userid != "0") {
            
            $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE id = $userid";
            $resultread = db_query($query, $dbh);
            if ($resultread['rows'] == 1) {
                
                $row = db_assoc($resultread['result']);
                $entry[USERNAME] = $row[USERID];
            }
        }
        
        if ($groupid != "0") {
            
            $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE id = $groupid";
            $resultread = db_query($query, $dbh);
            if ($resultread['rows'] == 1) {
                
                $row = db_assoc($resultread['result']);
                $entry[GROUPNAME] = $row[GROUPNAME];
            }
            else {
                $entry[GROUPNAME] = "unknown";
            }
        }
        
        $tAccessRights[] = $entry;
    }
    
    return $tAccessRights;

}

//
// db_getCountAccessRightsList
// Action: get count of valid rights
// Call: db_getCountAccessRightsList(string $valid, resource $dbh)
//
function db_getCountAccessRightsList($valid, $dbh) {

    $schema = db_determine_schema();
    $tAccessRights = array();
    $query = "SELECT COUNT(*) AS anz " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') " . "   AND (valid_from <= '$valid' ) " . "   AND (valid_until >= '$valid') ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        $count = $row['anz'];
        
        return $count;
    }
    else {
        
        return false;
    }

}

//
// db_check_global_admin
// Action: check if an user is an global admin
// Call: db_check_global_admin( string username, resource link )
//
function db_check_global_admin($username, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    $ret = false;
    $query = "SELECT superadmin " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (userid = '$username')";
    $result = db_query($query, $link);
    if ($result['rows'] > 0) {
        $row = db_assoc($result[RESULT]);
        $ret = strtolower($row['superadmin']) == 1 ? true : false;
        return ($ret);
    }
    else {
        return false;
    }

}

//
// db_check_global_admin_by_id
// Action: check if an user is an global admin
// Call: db_check_global_admin_by_id( string id, resource link )
//
function db_check_global_admin_by_id($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    $ret = false;
    $query = "SELECT superadmin " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (id = $id)";
    $result = db_query($query, $link);
    if ($result['rows'] > 0) {
        $row = db_assoc($result[RESULT]);
        $ret = strtolower($row['superadmin']) == 1 ? true : false;
        return ($ret);
    }
    else {
        return false;
    }

}

//
// db_check_acl
// Action: check if user has permission to do something
// Call: db_check_acl( string username, string action, resource dbh )
//
function db_check_acl($username, $action, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT users_rights.allowed " . "  FROM " . $schema . "svnusers, " . $schema . "rights, " . $schema . "users_rights " . " WHERE (svnusers.id = users_rights.user_id) " . "   AND (rights.id = users_rights.right_id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (users_rights.deleted = '00000000000000') " . "   AND (svnusers.userid = '$username') " . "   AND (rights.right_name = '$action')";
    
    $result = db_query($query, $dbh);
    
    if ($result['rows'] > 0) {
        
        $row = db_assoc($result[RESULT]);
        $right = $row[ALLOWED];
    }
    else {
        
        $right = "none";
    }
    
    return $right;

}

//
// db_check_group_acl
// Action: check if user is allowed to administer a particular group
// Call: db_check_group_acl( string username, resource dbh )
//
function db_check_group_acl($username, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT svn_groups_responsible.allowed, svn_groups_responsible.group_id " . "  FROM " . $schema . "svn_groups_responsible, " . $schema . "svnusers " . " WHERE (svnusers.id = svn_groups_responsible.user_id) " . "   AND (svnusers.userid = '$username') " . "   AND (svn_groups_responsible.deleted = '00000000000000') " . "   AND (svnusers.deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    $tAllowedGroups = array();
    
    if ($result['rows'] > 0) {
        
        while ( $row = db_assoc($result[RESULT]) ) {
            
            $groupid = $row[GROUP_ID];
            $right = $row[ALLOWED];
            $tAllowedGroups[$groupid] = $right;
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
    
    $preferences[PAGESIZE] = $CONF[PAGESIZE];
    $preferences[USER_SORT_FIELDS] = $CONF[USER_SORT_FIELDS];
    $preferences[USER_SORT_ORDER] = $CONF[USER_SORT_ORDER];
    
    $schema = db_determine_schema();
    
    $id = db_getIdByUserid($userid, $link);
    if ($id != false) {
        $query = "SELECT * " . "  FROM " . $schema . "preferences " . " WHERE user_id = $id";
        $result = db_query($query, $link);
        
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result[RESULT]);
            $page_size = $row[PAGESIZE];
            $preferences = array();
            $preferences[PAGESIZE] = $page_size;
            $preferences[USER_SORT_FIELDS] = $row[USER_SORT_FIELDS];
            $preferences[USER_SORT_ORDER] = $row[USER_SORT_ORDER];
        }
    }
    
    return $preferences;

}

//
// db_get_semaphore
// Action: check if semaphore is set,
// returns true if semaphore is set
// Call: db_get_semaphore(string action, string type, resource link)
//
function db_get_semaphore($action, $type, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT * " . "  FROM " . $schema . "workinfo " . " WHERE (action = '$action') " . "   AND (type = '$type') " . "   AND (status = 'open')";
    $result = db_query($query, $link);
    if ($result['rows'] > 0) {
        return true;
    }
    else {
        return false;
    }

}

//
// db_set_semaphore
// Action: set semaphore and check if a semaphore is already open,
// returns false if a semaphore could not be set
// Call: db_set_semaphore(string action, string type, resource link)
//
function db_set_semaphore($action, $type, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    if (db_get_semaphore($action, $type, $link)) {
        
        return false;
    }
    else {
        
        $query = "INSERT INTO " . $schema . "workinfo (action, status, type) " . "     VALUES ('$action', 'open', '$type')";
        
        db_ta(BEGIN, $link);
        $result = db_query($query, $link);
        if ($result['rows'] == 0) {
            
            db_ta('ROLLBACK', $link);
            return false;
        }
        else {
            
            db_ta(COMMIT, $link);
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
    
    $schema = db_determine_schema();
    
    if (db_get_semaphore($action, $type, $link)) {
        
        $query = "UPDATE " . $schema . "workinfo " . "   SET status = 'closed' " . " WHERE (action = '$action') " . "   AND (type = '$type')";
        
        db_ta(BEGIN, $link);
        $result = db_query($query, $link);
        if ($result['rows'] > 0) {
            
            db_ta(COMMIT, $link);
            return true;
        }
        else {
            
            db_ta('ROLLBACK', $link);
            return false;
        }
    }
    else {
        
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
    
    if (substr($CONF[DATABASE_TYPE], 0, 8) == "postgres") {
        $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
    }
    elseif ($CONF[DATABASE_TYPE] == "oci8") {
        $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
    }
    else {
        $schema = "";
    }
    
    // error_log( "db schema: $schema" );
    
    return ($schema);

}

//
// db_escape_string
// Action: Escape a string
// Call: db_escape_string (string string, resource link)
//
function db_escape_string($string, $link = "") {

    global $CONF;
    
    if (is_array($string)) {
        
        return $string;
    }
    else {
        
        if (empty($link)) {
            $newConnection = 1;
            $link = db_connect();
        }
        else {
            $newConnection = 0;
        }
        
        $escaped_string = $link->qstr($string, get_magic_quotes_gpc());
        $escaped_string = preg_replace('/^\'/', "", $escaped_string);
        $escaped_string = preg_replace('/\'$/', "", $escaped_string);
        
        if ($newConnection == 1) {
            db_disconnect($link);
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
    
    $ret = 0;
    
    if (isset($CONF[LDAP_PROTOCOL])) {
        $protocol = $CONF[LDAP_PROTOCOL];
    }
    else {
        $protocol = "2";
    }
    
    // error_log( "using ldap protocol version $protocol" );
    $LDAP_CONNECT_OPTIONS = Array(
            Array(
                    "OPTION_NAME" => LDAP_OPT_DEREF,
                    "OPTION_VALUE" => 2
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_SIZELIMIT,
                    "OPTION_VALUE" => 1000
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_TIMELIMIT,
                    "OPTION_VALUE" => 30
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_PROTOCOL_VERSION,
                    "OPTION_VALUE" => $protocol
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_ERROR_NUMBER,
                    "OPTION_VALUE" => 13
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_REFERRALS,
                    "OPTION_VALUE" => FALSE
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_RESTART,
                    "OPTION_VALUE" => FALSE
            )
    );
    
    try {
        $ldap = &NewADOConnection('ldap');
        // error_log( $CONF[LDAP_SERVER].",".$CONF[BIND_DN].",".$CONF[BIND_PW].",".$CONF[USER_DN] );
        $ldap->Connect($CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $ldapOpen = 1;
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = sprintf("Database connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $_SESSION[SVNSESSID][DBFUNCTION] = sprintf("db_connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        
        if (file_exists(realpath("database_error.php"))) {
            $location = "database_error.php";
        }
        else {
            $location = "../database_error.php";
        }
        
        header("location: $location");
        exit();
    }
    
    try {
        $filter = "(&(" . $CONF['user_filter_attr'] . "=$userid)(objectclass=" . $CONF[USER_OBJECTCLASS] . "))";
        $ldap->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $ldap->Execute($filter);
        if ($rs) {
            if ($rs->RecordCount() > 0) {
                $ret = 1;
            }
            else {
                $ret = 0;
            }
        }
        else {
            $ret = 0;
        }
    }
    catch ( exception $e ) {
        
        error_log("Error: " . $e->msg);
        $ret = 0;
    }
    
    if ($ldapOpen == 1) {
        $ldap->Close();
    }
    
    return ($ret);

}

//
// get_ldap_users
// Action: get available users from ldap
// Call: et_ldap_users()
//
function get_ldap_users() {

    global $CONF;
    global $LDAP_CONNECT_OPTIONS;
    
    $tUsers = array();
    
    $additionalFilter = isset($CONF['additional_user_filter']) ? $CONF['additional_user_filter'] : "";
    
    if (isset($CONF[LDAP_PROTOCOL])) {
        $protocol = $CONF[LDAP_PROTOCOL];
    }
    else {
        $protocol = "2";
    }
    
    $LDAP_CONNECT_OPTIONS = Array(
            Array(
                    "OPTION_NAME" => LDAP_OPT_DEREF,
                    "OPTION_VALUE" => 2
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_SIZELIMIT,
                    "OPTION_VALUE" => 1000
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_TIMELIMIT,
                    "OPTION_VALUE" => 30
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_PROTOCOL_VERSION,
                    "OPTION_VALUE" => $protocol
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_ERROR_NUMBER,
                    "OPTION_VALUE" => 13
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_REFERRALS,
                    "OPTION_VALUE" => FALSE
            ),
            Array(
                    "OPTION_NAME" => LDAP_OPT_RESTART,
                    "OPTION_VALUE" => FALSE
            )
    );
    
    try {
        $ldap = &NewADOConnection('ldap');
        // error_log( $CONF[LDAP_SERVER].",".$CONF[BIND_DN].",".$CONF[BIND_PW].",".$CONF[USER_DN] );
        $ldap->Connect($CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $ldapOpen = 1;
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = sprintf("Database connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $_SESSION[SVNSESSID][DBFUNCTION] = sprintf("db_connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        
        if (file_exists(realpath("database_error.php"))) {
            $location = "database_error.php";
        }
        else {
            $location = "../database_error.php";
        }
        
        header("location: $location");
        exit();
    }
    
    if ($additionalFilter != "") {
        $filter = "(&(objectclass=" . $CONF[USER_OBJECTCLASS] . ")" . $additionalFilter . ")";
    }
    else {
        $filter = "(objectclass=" . $CONF[USER_OBJECTCLASS] . ")";
    }
    
    try {
        $ldap->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $ldap->Execute($filter);
        if ($rs) {
            
            while ( $arr = $rs->FetchRow() ) {
                
                $entry = array();
                
                if (isset($CONF[ATTR_MAPPING]['uid'])) {
                    if (isset($arr[$CONF[ATTR_MAPPING]['uid']])) {
                        $entry['uid'] = $arr[$CONF[ATTR_MAPPING]['uid']];
                    }
                    else {
                        $entry['uid'] = "";
                    }
                }
                else {
                    if (isset($arr['uid'])) {
                        $entry['uid'] = $arr['uid'];
                    }
                    else {
                        $entry['uid'] = "";
                    }
                }
                
                if (isset($CONF[ATTR_MAPPING]['name'])) {
                    if (isset($arr[$CONF[ATTR_MAPPING]['name']])) {
                        $entry['name'] = $arr[$CONF[ATTR_MAPPING]['name']];
                    }
                    else {
                        $entry['name'] = "";
                    }
                }
                else {
                    if (isset($arr['sn'])) {
                        $entry['name'] = $arr['sn'];
                    }
                    else {
                        $entry['name'] = "";
                    }
                }
                
                if (isset($CONF[ATTR_MAPPING]['givenName'])) {
                    if (isset($arr[$CONF[ATTR_MAPPING]['givenName']])) {
                        $entry[GIVENNAME] = $arr[$CONF[ATTR_MAPPING]['givenName']];
                    }
                    else {
                        $entry[GIVENNAME] = "";
                    }
                }
                else {
                    if (isset($arr['givenName'])) {
                        $entry[GIVENNAME] = $arr['givenName'];
                    }
                    else {
                        $entry[GIVENNAME] = "";
                    }
                }
                
                if (isset($CONF[ATTR_MAPPING]['mail'])) {
                    $attr = $CONF[ATTR_MAPPING]['mail'];
                }
                else {
                    $attr = 'mail';
                }
                
                $entry['emailaddress'] = isset($arr[$attr]) ? $arr[$attr] : "";
                
                if (isset($CONF['ldap_uservalues_encode']) && $CONF['ldap_uservalues_encode']) {
                    $entry['name'] = htmlentities($entry['name']);
                    $entry[GIVENNAME] = htmlentities($entry[GIVENNAME]);
                }
                
                $tUsers[] = $entry;
            }
        }
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = $filter;
        $_SESSION[SVNSESSID][DBFUNCTION] = "get_ldap_user";
        
        if (file_exists(realpath("database_error.php"))) {
            $location = "database_error.php";
        }
        else {
            $location = "../database_error.php";
        }
        
        header("location: $location");
        exit();
    }
    
    if ($ldapOpen == 1) {
        $ldap->Close();
    }
    
    usort($tUsers, "sortLdapUsers");
    
    return ($tUsers);

}

//
// check_ldap_password
// Action: check password against ldap
// Call: check_ldap_password(string userid, string password)
//
function check_ldap_password($userid, $password) {

    global $CONF;
    global $LDAP_CONNECT_OPTIONS;
    
    $ret = 0;
    
    if (isset($CONF[LDAP_PROTOCOL])) {
        $protocol = $CONF[LDAP_PROTOCOL];
    }
    else {
        $protocol = "2";
    }
    
    $LDAP_CONNECT_OPTIONS = array(
            array(
                    "OPTION_NAME" => LDAP_OPT_DEREF,
                    "OPTION_VALUE" => 2
            ),
            array(
                    "OPTION_NAME" => LDAP_OPT_SIZELIMIT,
                    "OPTION_VALUE" => 1000
            ),
            array(
                    "OPTION_NAME" => LDAP_OPT_TIMELIMIT,
                    "OPTION_VALUE" => 30
            ),
            array(
                    "OPTION_NAME" => LDAP_OPT_PROTOCOL_VERSION,
                    "OPTION_VALUE" => $protocol
            ),
            array(
                    "OPTION_NAME" => LDAP_OPT_ERROR_NUMBER,
                    "OPTION_VALUE" => 13
            ),
            array(
                    "OPTION_NAME" => LDAP_OPT_REFERRALS,
                    "OPTION_VALUE" => FALSE
            ),
            array(
                    "OPTION_NAME" => LDAP_OPT_RESTART,
                    "OPTION_VALUE" => FALSE
            )
    );
    
    // error_log("check_ldap_password");
    
    try {
        $ldap = NewADOConnection('ldap');
        // error_log( $CONF[LDAP_SERVER].",".$CONF[BIND_DN].",".$CONF[BIND_PW].",".$CONF[USER_DN] );
        $ldap->Connect($CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $ldapOpen = 1;
        // error_log("ldap open");
    }
    catch ( exception $e ) {
        
        // error_log( "exception during connect" );
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = sprintf("Database connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], 'xxxxxxxx', $CONF[USER_DN]);
        $_SESSION[SVNSESSID][DBFUNCTION] = sprintf("db_connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], 'xxxxxxxx', $CONF[USER_DN]);
        
        $tErrorMessage = strtolower($_SESSION[SVNSESSID][DBERROR]);
        
        if (isset($CONF['ldap_bind_use_login_data']) and ($CONF['ldap_bind_use_login_data'] == 1) and strpos($tErrorMessage, "invalid") and strpos($tErrorMessage, "credentials")) {
            $ldapOpen = 0;
            $ret = 0;
            // error_log( "check reset" );
        }
        else {
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            $ret = - 1;
            return ($ret);
        }
    }
    
    try {
        $filter = "(&(" . $CONF['user_filter_attr'] . "=$userid)(objectclass=" . $CONF[USER_OBJECTCLASS] . "))";
        $ldap->SetFetchMode(ADODB_FETCH_ASSOC);
        error_log("filter = $filter");
        $rs = $ldap->Execute($filter);
        if ($rs) {
            if ($rs->RecordCount() == 1) {
                
                $arr = $rs->FetchRow();
                $dn = $arr['dn'];
                // error_log( "dn = $dn" );
                $ldapUser = &NewADOConnection('ldap');
                $ldapUser->Connect($CONF[LDAP_SERVER], $dn, $password, $CONF[USER_DN]);
                $ret = 1;
                $ldapUser->Close();
            }
            else {
                $ret = 0;
                // error_log( "mehrere treffer" );
            }
        }
        else {
            $ret = 0;
            // error_log("filter keine treffer");
        }
    }
    catch ( exception $e ) {
        
        error_log("Error: " . $e->msg);
        $ret = 0;
    }
    
    if ($ldapOpen == 1) {
        $ldap->Close();
    }
    
    return ($ret);

}

//
// session handling
//
class Session {
    /**
     * a database connection resource
     *
     * @var resource
     */
    private static $_sess_db;
    private static $DEBUG = 0;

    /**
     * Open the session
     *
     * @return bool
     */
    public static function open() {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'open executed');
        }
        // error_log("session open");
        $db_user = $CONF[DATABASE_USER];
        $db_pass = $CONF[DATABASE_PASSWORD];
        $db_host = $CONF[DATABASE_HOST];
        $db_name = $CONF[DATABASE_NAME];
        
        if (isset($CONF[DATABASE_CHARSET])) {
            $charset = $CONF[DATABASE_CHARSET];
        }
        else {
            $charset = "latin1";
        }
        
        if (isset($CONF[DATABASE_COLLATION])) {
            $collation = $CONF[DATABASE_COLLATION];
        }
        else {
            $collation = "latin1_german1_ci";
        }
        
        $nameset = "SET NAMES '$charset' COLLATE '$collation'";
        
        try {
            
            self::$_sess_db = ADONewConnection($CONF[DATABASE_TYPE]);
            self::$_sess_db->Pconnect($CONF[DATABASE_HOST], $CONF[DATABASE_USER], $CONF[DATABASE_PASSWORD], $CONF[DATABASE_NAME]);
            self::$_sess_db->SetFetchMode(ADODB_FETCH_ASSOC);
            
            if (($CONF[DATABASE_TYPE] == MYSQL) || ($CONF[DATABASE_TYPE] == MYSQLI)) {
                self::$_sess_db->Execute($nameset);
            }
            
            return true;
        }
        catch ( exception $e ) {
            
            // var_dump($e);
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = "Database connect";
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: Session database connect");
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            header("location: $location");
            exit();
        }
        
        return false;
    
    }

    /**
     * Close the session
     *
     * @return bool
     */
    public static function close() {

        if (self::$DEBUG != 0) {
            db_log('gc', 'close executed');
        }
        
        // error_log( "session closed");
        // return mysql_close(self::$_sess_db);
        
        // try {
        // self::$_sess_db->Close();
        // } catch( exception $e ) {
        //
        // }
        
        return true;
    
    }

    /**
     * Read the session
     *
     * @param
     *            int session id
     * @return string string of the sessoin
     */
    public static function read($id) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'read executed');
        }
        
        if ($CONF[DATABASE_TYPE] == "postgres8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        elseif ($CONF[DATABASE_TYPE] == "oci8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
        $id = self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $sql = sprintf("SELECT session_data FROM " . $schema . "sessions " . "WHERE session_id = %s", $id);
        // error_log( "session read");
        try {
            
            $result = self::$_sess_db->Execute($sql);
            if ($result->RecordCount() > 0) {
                
                $record = $result->FetchRow();
                return isset($record['session_data']) ? $record['session_data'] : $record['SESSION_DATA'];
            }
            
            return '';
        }
        catch ( exception $e ) {
            
            // var_dump($e);
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $sql;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: $sql");
            error_log("DB query: Session read");
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            header("location: $location");
            exit();
        }
    
    }

    /**
     * Write the session
     *
     * @param
     *            int session id
     * @param
     *            string data of the session
     */
    public static function write($id, $data) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'write executed');
        }
        
        if ($CONF[DATABASE_TYPE] == "postgres8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        elseif ($CONF[DATABASE_TYPE] == "oci8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
        $id = self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $time = self::$_sess_db->qstr(time(), get_magic_quotes_gpc());
        $data = self::$_sess_db->qstr($data, get_magic_quotes_gpc());
        // error_log( "session write" );
        try {
            
            $sql = sprintf("SELECT * FROM " . $schema . "sessions WHERE session_id = %s", $id);
            $result = self::$_sess_db->Execute($sql);
            if ($result->RecordCount() > 0) {
                $sql = sprintf("UPDATE " . $schema . "sessions SET session_expires = %s, session_data = %s WHERE session_id = %s", $time, $data, $id);
            }
            else {
                $sql = sprintf("INSERT INTO " . $schema . "sessions (session_id, session_expires, session_data) VALUES(%s, %s, %s)", $id, $time, $data);
            }
            // error_log( "write query: $sql" );
            self::$_sess_db->Execute($sql);
            $error = 0;
        }
        catch ( exception $e ) {
            
            // adodb_backtrace($e->gettrace());
            
            // error_log( "session write exception 1" );
            // error_log( print_r($e, true) );
            // error_log( "session write exception 2" );
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $sql;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: $sql");
            error_log("DB query: Session write to database");
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            header("location: $location");
            exit();
            
            return false;
        }
        
        if ($error == 0) {
            // error_log("session write true" );
            return true;
        }
        else {
            return false;
        }
    
    }

    /**
     * Destoroy the session
     *
     * @param
     *            int session id
     * @return bool
     */
    public static function destroy($id) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'destroy executed');
        }
        
        if ($CONF[DATABASE_TYPE] == "postgres8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        elseif ($CONF[DATABASE_TYPE] == "oci8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
        // error_log( "session destroyed" );
        $id = self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $sql = sprintf("DELETE FROM " . $schema . "sessions WHERE session_id = %s", $id);
        
        try {
            
            self::$_sess_db->Execute($sql);
            return true;
        }
        catch ( exception $e ) {
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $sql;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: $sql");
            error_log("DB query: Session destroy");
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            header("location: $location");
            exit();
            
            return false;
        }
    
    }

    /**
     * Garbage Collector
     *
     * @param
     *            int life time (sec.)
     * @return bool
     * @see session.gc_divisor 100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability 1
     *      @usage execution rate 1/100
     *      (session.gc_probability/session.gc_divisor)
     */
    public static function gc($max) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'gc executed (' . $max . ')');
        }
        
        if ($CONF[DATABASE_TYPE] == "postgres8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        elseif ($CONF[DATABASE_TYPE] == "oci8") {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
        $time = self::$_sess_db->qstr(time() - $max, get_magic_quotes_gpc());
        $sql = sprintf("DELETE FROM " . $schema . "sessions WHERE session_expires < %s", $time);
        try {
            
            self::$_sess_db->Execute($sql);
            
            return true;
        }
        catch ( exception $e ) {
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $sql;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: $sql");
            error_log("DB query: Session gct");
            
            if (file_exists(realpath("database_error.php"))) {
                $location = "database_error.php";
            }
            else {
                $location = "../database_error.php";
            }
            
            header("location: $location");
            exit();
            
            return false;
        }
    
    }

}

if (isset($CONF) and ($CONF['session_in_db'] == "YES")) {
    
    ini_set('session.gc_probability', 50);
    ini_set('session.gc_divisor', 50);
    ini_set('session.save_handler', 'user');
    ini_set('session.gc_maxlifetime', '1800');
    
    session_set_save_handler(array(
            'Session',
            'open'
    ), array(
            'Session',
            'close'
    ), array(
            'Session',
            'read'
    ), array(
            'Session',
            'write'
    ), array(
            'Session',
            'destroy'
    ), array(
            'Session',
            'gc'
    ));
}

session_cache_expire(30);
?>
