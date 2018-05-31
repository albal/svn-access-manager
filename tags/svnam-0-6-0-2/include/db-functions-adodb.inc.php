<?php

/**
 *
 * Functions to make work with databases easier.
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
 *           
 * SVN Access Manager - a subversion access rights management tool
 *             Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *            
 *             This program is free software; you can redistribute it and/or modify
 *             it under the terms of the GNU General Public License as published by
 *             the Free Software Foundation; either version 2 of the License, or
 *             (at your option) any later version.
 *            
 *             This program is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *            
 *             You should have received a copy of the GNU General Public License
 *             along with this program; if not, write to the Free Software
 *             Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *            
 *            
 *             $LastChangedDate$
 *             $LastChangedBy$
 *            
 *             $Id$
 *            
 */

/**
 * check if called directly and redirect to login page
 */
if (preg_match("/db-functions-adodb\.inc\.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

/**
 * set install base and include database classes for adbdb
 */
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

/**
 * debug text
 *
 * @global string $DEBUG_TEXT
 */
$DEBUG_TEXT = "\n
<p />\n
Please check the documentation and website for more information.\n
";

/**
 * get location of database error file
 *
 * @return string
 */
function db_get_database_error_location($install = '') {

    if (empty($install)) {
        if (file_exists(realpath("database_error.php"))) {
            $file = "database_error.php";
        }
        else {
            $file = "../database_error.php";
        }
    }
    else {
        if (file_exists(realpath("database_error.php"))) {
            $file = "database_error_install.php";
        }
        else {
            $file = "../database_error_install.php";
        }
    }
    
    return ($file);
    
}

/**
 * Makes a connection to the database if it doesn't exist
 *
 * @return resource
 */
function db_connect() {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    /**
     *
     * @global string $DEBUG_TEXT
     */
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
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = "Database connect";
        $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
        
        $location = db_get_database_error_location();
        
        header("location: $location");
        exit();
    }
    
    return $link;
    
}

/**
 * Makes a connection to the database if it doesn't exist.
 * Used during installation.
 *
 * @param string $dbhost
 * @param string $dbuser
 * @param string $dbpassword
 * @param string $dbname
 * @param string $charset
 * @param string $collation
 * @param string $dbtype
 * @param string $test
 * @return array
 * @return resource
 */
function db_connect_install($dbhost, $dbuser, $dbpassword, $dbname, $charset, $collation, $dbtype = "", $test = "no") {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    /**
     *
     * @global string $DEBUG_TEXT
     */
    global $DEBUG_TEXT;
    
    $link = "";
    $nameset = "SET NAMES '$charset' COLLATE '$collation'";
    $dbtype = ($dbtype == "") ? MYSQL : $dbtype;
    
    try {
        
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

/**
 * Makes a connection to the database if it doesn't exist.
 * used during unit tests
 *
 * @param string $dbtype
 * @param string $dbhost
 * @param string $dbuser
 * @param string $dbpass
 * @param string $dbname
 * @param string $charset
 * @param string $collation
 * @return resource
 */
function db_connect_test($dbtype, $dbhost, $dbuser, $dbpass, $dbname, $charset = 'utf8', $collation = 'utf8_general_ci') {

    /**
     *
     * @global string $DEBUG_TEXT
     */
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

/**
 * close connection to database
 *
 * @param resource $link
 */
function db_disconnect($link) {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    /**
     *
     * @global string $DEBUG_TEXT
     */
    global $DEBUG_TEXT;
    
    try {
        
        $link->Close();
    }
    catch ( exception $e ) {
        //
    }
    
}

/**
 * Sends a query to the database and returns query result and number of rows
 *
 * @param string $query
 * @param string $link
 * @param string $limit
 * @param string $offset
 * @return string[]
 */
function db_query($query, $link, $limit = -1, $offset = -1) {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    /**
     *
     * @global string $DEBIG_TEXT
     */
    global $DEBUG_TEXT;
    
    $result = "";
    $number_rows = "";
    $query = trim($query);
    
    /**
     * database prefix workaround
     */
    if (! empty($CONF[DATABASE_PREFIX])) {
        
        if (preg_match("/^SELECT/i", $query)) {
            $query = substr($query, 0, 14) . $CONF[DATABASE_PREFIX] . substr($query, 14);
        }
        else {
            $query = substr($query, 0, 6) . $CONF[DATABASE_PREFIX] . substr($query, 7);
        }
    }
    
    try {
        
        if (($CONF[DATABASE_TYPE] != MYSQL) && ($CONF[DATABASE_TYPE] != MYSQLI) && (preg_match("/LIMIT/i", $query))) {
            
            $search = "/LIMIT (\w+), (\w+)/";
            $replace = "LIMIT \$2 OFFSET \$1";
            $query = preg_replace($search, $replace, $query);
        }
        
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        if ($limit != - 1) {
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
            
            $number_rows = $link->Affected_Rows();
        }
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = $query;
        $_SESSION[SVNSESSID][DBFUNCTION] = "db_query";
        db_ta(ROLLBACK, $link);
        db_disconnect($link);
        
        error_log("DB-Error: " . $_SESSION[SVNSESSID][DBERROR]);
        error_log("DB-Query: " . $_SESSION[SVNSESSID][DBQUERY]);
        
        $location = db_get_database_error_location();
        
        header("Location: $location");
        exit();
    }
    
    return array(
            "result" => $result,
            "rows" => $number_rows
    );
    
}

/**
 * Sends a query to the database and returns query result and number of rows.
 * Used during installatiomn.
 *
 * @param string $query
 * @param string $link
 * @param string $limit
 * @param string $offset
 * @return string[]
 */
function db_query_install($query, $link, $limit = -1, $offset = -1) {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    /**
     *
     * @global string $DEBUG_TEXT
     */
    global $DEBUG_TEXT;
    
    $result = "";
    $number_rows = "";
    $query = trim($query);
    
    /**
     * database prefix workaround
     */
    if (! empty($CONF[DATABASE_PREFIX])) {
        
        if (preg_match("/^SELECT/i", $query)) {
            $query = substr($query, 0, 14) . $CONF[DATABASE_PREFIX] . substr($query, 14);
        }
        else {
            $query = substr($query, 0, 6) . $CONF[DATABASE_PREFIX] . substr($query, 7);
        }
    }
    
    try {
        
        if (($CONF[DATABASE_TYPE] != MYSQL) && ($CONF[DATABASE_TYPE] != MYSQLI) && (preg_match("/LIMIT/i", $query))) {
            
            $search = "/LIMIT (\w+), (\w+)/";
            $replace = "LIMIT \$2 OFFSET \$1";
            $query = preg_replace($search, $replace, $query);
        }
        
        $link->SetFetchMode(ADODB_FETCH_ASSOC);
        if ($limit != - 1) {
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
        
        $location = db_get_database_error_location('1');
        
        header("location: " . $location . "?dbquery=$tDbQuery&dberror=$tDbError&dbfunction=db_query_install");
        exit();
    }
    
    return array(
            "result" => $result,
            "rows" => $number_rows
    );
    
}

/**
 * Create associative array from database results.
 * The function returns an empty string in case of an error.Otherwise an array is returned.
 *
 * @param resource $result
 * @return array
 * @return string
 */
function db_assoc($result) {

    /**
     *
     * @global array $CONF
     */
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

/**
 * Logs actions from admin
 *
 * @param string $username
 * @param string $data
 * @param string $link
 * @return boolean
 */
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

/**
 * Handle database trasactions
 *
 * @param string $action
 * @param string $link
 * @return boolean
 */
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
                
                $location = db_get_database_error_location();
                
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
            
            $location = db_get_database_error_location();
            
            header("location: $location");
            exit();
        }
    }
    
    return true;
    
}

/**
 * get userid from database table svnusers with id.
 * The function returns false in case of an error. Otherwise the userid is returned as string.
 *
 * @param integer $id
 * @param resource $link
 * @return string!boolean
 */
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

/**
 * get id from database table svnusers with userid.
 * The function returns false in case of an error. Otherwise the id of the user is returned as integer.
 *
 * @param string $userid
 * @param resource $link
 * @return integer|boolean
 */
function db_getIdByUserid($userid, $link) {

    global $CONF;
    
    $schema = db_determine_schema();

    /** @var array $result */
    $result = db_query("SELECT id " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row['id'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get a 14 digit timestamp in format jjjjmmddhhmmss
 *
 * @return string
 */
function db_now() {

    return date('YmdHis');
    
}

/**
 * get last inserted id in a table
 *
 * @param string $table
 * @param string $column
 * @param resource $link
 * @param string $schema
 * @return integer|boolean
 */
function db_get_last_insert_id($table, $column, $link, $schema = "") {

    /**
     *
     * @global array $CONF
     */
    global $CONF;
    
    if ($schema == "") {
        $schema = isset($CONF[DATABASE_SCHEMA]) ? $CONF[DATABASE_SCHEMA] : "";
    }
    
    if ($id = $link->Insert_Id()) {
        // last insert id from MySQL
    }
    else {
        
        try {
            
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

/**
 * get global user right by userid
 *
 * @param string $userid
 * @param resource $link
 * @return array|boolean
 */
function db_getUserRightByUserid($userid, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$userid') " . "   AND (deleted = '00000000000000')", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return strtolower($row[USER_MODE]);
    }
    else {
        
        return false;
    }
    
}

/**
 * check a group and return the lowest privilege of an user
 *
 * @param string $groupid
 * @param resource $link
 * @return string
 */
function db_getGroupRightByGroupid($groupid, $link) {

    global $CONF;
    
    $mode = "";
    $schema = db_determine_schema();
    $result = db_query("SELECT svnusers.user_mode " . "  FROM " . $schema . "svnusers, " . $schema . "svngroups, " . $schema . "svn_users_groups " . " WHERE (svngroups.id = $groupid) " . "   AND (svn_users_groups.group_id = svngroups.id) " . "   AND (svn_users_groups.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (svn_users_groups.deleted = '00000000000000')", $link);
    while ( $row = db_assoc($result[RESULT]) ) {
        
        if (((strtolower($row[USER_MODE]) == "write") && ($mode == "")) || (strtolower($row[USER_MODE]) == "read")) {
            $mode = strtolower($row[USER_MODE]);
        }
    }
    
    return $mode;
    
}

/**
 * get repository by id
 *
 * @param integer $id
 * @param resource $link
 * @return string|boolean
 */
function db_getRepoById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (id = '$id') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return $row['reponame'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get repository by name
 *
 * @param string $reponame
 * @param resource $link
 * @return integer|boolean
 */
function db_getRepoByName($reponame, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE (reponame = '$reponame') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return $row['id'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get project by id
 *
 * @param integer $id
 * @param resource $link
 * @return string|boolean
 */
function db_getProjectById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnprojects " . " WHERE (id = '$id') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return $row['svnmodule'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get group by id
 *
 * @param integer $id
 * @param resource $link
 * @return string|boolean
 */
function db_getGroupById($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $result = db_query("SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = '$id') ", $link);
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return $row[GROUPNAME];
    }
    else {
        
        return false;
    }
    
}

/**
 * get name for a right
 *
 * @param integer $id
 * @param resource $link
 * @return string
 */
function db_getRightName($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT right_name " . "  FROM " . $schema . "rights " . " WHERE (id = $id) " . "   AND (deleted = '00000000000000')";
    $result = db_query($query, $link);
    if ($result['rows'] == 1) {
        $row = db_assoc($result[RESULT]);
        return ($row[RIGHT_NAME]);
    }
    else {
        return ("undefined");
    }
    
}

/**
 * get data for access right
 *
 * @param integer $id
 * @param resource $link
 * @return boolean|array[]
 */
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

/**
 * get all groups for an user
 *
 * @param string $tUserId
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get projects an user is responsible for
 *
 * @param string $tUserId
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get access rights assigned to an user
 *
 * @param string $tUserId
 * @param string $tGroups
 * @param resource $dbh
 * @return string[]
 */
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

/**
 * get project ids
 *
 * @param string $schema
 * @param integer $user_id
 * @param resource $dbh
 * @return string
 */
function db_get_projectids($schema, $user_id, $dbh) {

    if ($user_id != - 1) {
        $id = db_getIdByUserid($user_id, $dbh);
        if (! $id) {
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
    
    return ($tProjectIds);
    
}

/**
 * retrieve userid from db row
 *
 * @param array $row
 * @return integer
 */
function get_userid($row) {

    $userid = $row['user_id'];
    if (empty($userid)) {
        $userid = 0;
    }
    
    return ($userid);
    
}

/**
 * get group id from db row
 *
 * @param array $row
 * @return integer
 */
function get_groupid($row) {

    $groupid = $row['group_id'];
    if (empty($groupid)) {
        $groupid = 0;
    }
    
    return ($groupid);
    
}

/**
 * het data for a userid
 *
 * @param integer $userid
 * @param string $schema
 * @param resource $dbh
 * @return string
 */
function get_userid_entry($userid, $schema, $dbh) {

    $username = '';
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE id = $userid";
    $resultread = db_query($query, $dbh);
    if ($resultread['rows'] == 1) {
        
        $row = db_assoc($resultread[RESULT]);
        $username = $row[USERID];
    }
    
    return ($username);
    
}

/**
 * get group name for group id
 *
 * @param integer $groupid
 * @param string $schema
 * @param resource $dbh
 * @return string
 */
function get_groupid_entry($groupid, $schema, $dbh) {

    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE id = $groupid";
    $resultread = db_query($query, $dbh);
    if ($resultread['rows'] == 1) {
        
        $row = db_assoc($resultread[RESULT]);
        $groupname = $row[GROUPNAME];
    }
    else {
        $groupname = "unknown";
    }
    
    return ($groupname);
    
}

/**
 * get access rights of an user
 *
 * @param string $user_id
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
function db_getAccessRights($user_id, $start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tProjectIds = db_get_projectids($schema, $user_id, $dbh);
    $tAccessRights = array();
    
    if ($tProjectIds != "") {
        
        $query = "SELECT svn_access_rights.id AS id, svnmodule, modulepath, svnrepos." . "       reponame, valid_from, valid_until, path, access_right, recursive," . "       svn_access_rights.user_id, svn_access_rights.group_id, repopath " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.id IN (" . $tProjectIds . "))" . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') " . "ORDER BY LOWER(svnmodule) ASC ";
        $result = db_query($query, $dbh, $count, $start);
        
        while ( $row = db_assoc($result[RESULT]) ) {
            
            $entry = $row;
            $userid = get_userid($row);
            $groupid = get_groupid($row);
            $entry[GROUPNAME] = "";
            $entry[USERNAME] = "";
            
            if ($userid != "0") {
                
                $entry[USERNAME] = get_userid_entry($userid, $schema, $dbh);
            }
            
            if ($groupid != "0") {
                
                $entry[GROUPNAME] = get_groupid_entry($groupid, $schema, $dbh);
            }
            
            $tAccessRights[] = $entry;
        }
    }
    
    return $tAccessRights;
    
}

/**
 * get count of user's access rights
 *
 * @param integer $user_id
 * @param resource $dbh
 * @return boolean|integer
 */
function db_getCountAccessRights($user_id, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    if ($user_id != - 1) {
        $id = db_getIdByUserid($user_id, $dbh);
        if (! $id) {
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

/**
 * get groups
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get groups
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get number of groups
 *
 * @param resource $dbh
 * @return integer|boolean
 */
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

/**
 * get allowed groups
 *
 * @param integer $start
 * @param integer $count
 * @param string $groupAdmin
 * @param array $tGroupsAllowed
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get count of allowed groups
 *
 * @param string $groupAdmin
 * @param array $tGroupsAllowed
 * @param resource $dbh
 * @return integer|boolean
 */
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

    /** @var array $result */
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get user messages valid for current date
 *
 * @param resource $dbh
 * @return array
 */
function db_getMessagesShort($dbh) {

    $schema = db_determine_schema();
    $tUserMessages = array();
    $date = date('Ymd');
    $query = "SELECT message FROM " . $schema . "messages WHERE ('" . $date . "' >= validfrom) AND ('" . $date . "' <= validuntil) AND (deleted = '00000000000000');";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tUserMessages[] = $row['message'];
    }
    
    return ($tUserMessages);
    
}

/**
 * get projects from db
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get all not deleted messages in full
 *
 * @param resource $dbh
 * @return $tUserMessages[]
 */
function db_getMessages($dbh) {

    $schema = db_determine_schema();
    $tUserMessages = array();
    $query = "SELECT * FROM " . $schema . "messages WHERE deleted = '00000000000000';";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $date = splitValidDate($row['validfrom']);
        $row['validfrom_date'] = $date;
        
        $date = splitValidDate($row['validuntil']);
        $row['validuntil_date'] = $date;
        
        $tUserMessages[] = $row;
    }
    
    return ($tUserMessages);
    
}

/**
 * get count of projects
 *
 * @param resource $dbh
 * @return integer|boolean
 */
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

/**
 * get locked users from db
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
function db_getLockedUsers($start, $count, $dbh) {

    $schema = db_determine_schema();
    $tLockedUsers = array();
    $query = " SELECT * " . "   FROM " . $schema . "svnusers " . "  WHERE (deleted = '00000000000000') " . "    AND (locked != 0) " . "ORDER BY userid ASC ";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tLockedUsers[] = $row;
    }
    
    return $tLockedUsers;
    
}

/**
 * get repos from db
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
function db_getRepos($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tRepos = array();
    $query = "SELECT   * " . "    FROM " . $schema . "svnrepos " . "   WHERE (deleted = '00000000000000') " . "ORDER BY reponame ASC ";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result[RESULT]) ) {
        
        $tRepos[] = $row;
    }
    
    return $tRepos;
    
}

/**
 * get count of repos
 *
 * @param resource $dbh
 * @return integer|boolean
 */
function db_getCountRepos($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT   COUNT(*) AS anz " . "    FROM " . $schema . "svnrepos " . "   WHERE (deleted = '00000000000000') ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result[RESULT]);
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get count of locked users
 *
 * @param resource $dbh
 * @return integer|boolean
 */
function db_getCountLockedUsers($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "svnusers " . "  WHERE (deleted = '00000000000000') " . "    AND (locked != 0) " . "GROUP BY userid " . "ORDER BY userid";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get log entries from db
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
function db_getLog($start, $count, $dbh) {

    $schema = db_determine_schema();
    $tLogmessages = array();
    $query = " SELECT * " . "   FROM " . $schema . "log " . "ORDER BY logtimestamp DESC ";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tLogmessages[] = $row;
    }
    
    return $tLogmessages;
    
}

/**
 * get count of log records
 *
 * @param resource $dbh
 * @return integer|boolean
 */
function db_getCountLog($dbh) {

    $schema = db_determine_schema();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "log ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get users from db
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get data for an user from db
 *
 * @param integer $tUserId
 * @param resource $dbh
 * @return array
 */
function db_getUserData($tUserId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (id = $tUserId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    
    return ($row);
    
}

/**
 *
 * get data for a group from db
 *
 * @param integer $tGroupId
 * @param resource $dbh
 * @return array
 */
function db_getGroupData($tGroupId, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (id = $tGroupId)";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    
    return ($row);
    
}

/**
 * get all users having a particular group
 *
 * @param integer $tGroupId
 * @param resource $dbh
 * @return array
 */
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

/**
 * get all admins for a particular group
 *
 * @param integer $tGroupId
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get all admins for a particular group
 *
 * @param integer $tGroupId
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * get all granted rights
 *
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return string[][]
 */
function db_getGrantedRights($start, $count, $dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tGrantedRights = array();
    $query = "  SELECT * " . "    FROM " . $schema . "svnusers " . "   WHERE deleted = '00000000000000' " . "ORDER BY " . $CONF[USER_SORT_FIELDS] . " " . $CONF[USER_SORT_ORDER];
    $result = db_query($query, $dbh, $count, $start);
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

/**
 * get rights list for all users
 *
 * @param resource $dbh
 * @return $tGrantedRights[]
 */
function db_getGrantedRightsList($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tGrantedRights = array();
    $query = "SELECT rights.right_name, rights.id, users_rights.allowed, svnusers.name, svnusers.givenname, svnusers.locked, svnusers.userid " . "  FROM " . $schema . "rights, " . $schema . "users_rights, " . $schema . "svnusers " . " WHERE (rights.id = users_rights.right_id) " . "   AND (svnusers.id = users_rights.user_id)" . "   AND (users_rights.deleted = '00000000000000') " . "   AND (rights.deleted = '00000000000000') " . "ORDER BY svnusers.userid, rights.id";
    $resultrights = db_query($query, $dbh);
    
    while ( $rowrights = db_assoc($resultrights['result']) ) {
        
        $tGrantedRights[] = $rowrights;
    }
    
    return $tGrantedRights;
    
}

/**
 * get an array with all rights
 *
 * @param resource $dbh
 * @return $tRights[]
 */
function db_getRights($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $tRights = array();
    $query = "SELECT id, right_name FROM " . $schema . "rights WHERE deleted = '00000000000000' ORDER BY id;";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $tRights[] = $row;
    }
    
    return ($tRights);
    
}

/**
 * get count of log records
 *
 * @param resource $dbh
 * @return integer|boolean
 */
function db_getCountGrantedRights($dbh) {

    global $CONF;
    
    $schema = db_determine_schema();
    $query = " SELECT COUNT(*) AS anz " . "   FROM " . $schema . "svnusers " . "  WHERE (deleted = '00000000000000') ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

/**
 * get userid from db record
 *
 * @param array $row
 * @return integer
 */
function db_get_userid_from_record($row) {

    $userid = $row['user_id'];
    if (empty($userid)) {
        $userid = 0;
    }
    
    return $userid;
    
}

/**
 * get groupid from db record
 *
 * @param array $row
 * @return integer
 */
function db_get_groupid_from_record($row) {

    $groupid = $row['group_id'];
    if (empty($groupid)) {
        $groupid = 0;
    }
    
    return $groupid;
    
}

/**
 * get all valid rights
 *
 * @param string $valid
 * @param integer $start
 * @param integer $count
 * @param resource $dbh
 * @return string[]
 */
function db_getAccessRightsList($valid, $start, $count, $dbh) {

    $schema = db_determine_schema();
    $tAccessRights = array();
    $query = "SELECT svn_access_rights.id, svnmodule, modulepath, svnrepos." . "       reponame, valid_from, valid_until, path, access_right, recursive," . "       svn_access_rights.user_id, svn_access_rights.group_id " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') " . "   AND (valid_from <= '$valid' ) " . "   AND (valid_until >= '$valid') " . "ORDER BY svnrepos.reponame, svn_access_rights.path ";
    $result = db_query($query, $dbh, $count, $start);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $entry = $row;
        $userid = db_get_userid_from_record($row);
        $groupid = db_get_groupid_from_record($row);
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

/**
 * get count of valid rights
 *
 * @param string $valid
 * @param resource $dbh
 * @return integer|boolean
 */
function db_getCountAccessRightsList($valid, $dbh) {

    $schema = db_determine_schema();
    $query = "SELECT COUNT(*) AS anz " . "  FROM " . $schema . "svn_access_rights, " . $schema . "svnprojects, " . $schema . "svnrepos " . " WHERE (svnprojects.id = svn_access_rights.project_id) " . "   AND (svnprojects.repo_id = svnrepos.id) " . "   AND (svn_access_rights.deleted = '00000000000000') " . "   AND (valid_from <= '$valid' ) " . "   AND (valid_until >= '$valid') ";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 1) {
        
        $row = db_assoc($result['result']);
        return $row['anz'];
    }
    else {
        
        return false;
    }
    
}

/**
 * check if an user is an global admin
 *
 * @param string $username
 * @param resource $link
 * @return boolean
 */
function db_check_global_admin($username, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    $ret = false;
    $query = "SELECT superadmin " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (userid = '$username')";
    $result = db_query($query, $link);
    if ($result['rows'] > 0) {
        $row = db_assoc($result[RESULT]);
        $ret = (strtolower($row['superadmin']) == 1);
        return ($ret);
    }
    else {
        return false;
    }
    
}

/**
 * check if an user is an global admin
 *
 * @param integer $id
 * @param resource $link
 * @return boolean
 */
function db_check_global_admin_by_id($id, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    $ret = false;
    $query = "SELECT superadmin " . "  FROM " . $schema . "svnusers " . " WHERE (deleted = '00000000000000') " . "   AND (id = $id)";
    $result = db_query($query, $link);
    if ($result['rows'] > 0) {
        $row = db_assoc($result[RESULT]);
        $ret = (strtolower($row['superadmin']) == 1);
        return ($ret);
    }
    else {
        return false;
    }
    
}

/**
 * check if user has permission to do something
 *
 * @param string $username
 * @param string $action
 * @param resource $dbh
 * @return string
 */
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

/**
 * check if user is allowed to administer a particular group
 *
 * @param string $username
 * @param resource $dbh
 * @return array[]
 */
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

/**
 * load user's preferences
 *
 * @param integer $userid
 * @param resource $link
 * @return array[]
 */
function db_get_preferences($userid, $link) {

    global $CONF;
    
    $preferences[PAGESIZE] = $CONF[PAGESIZE];
    $preferences[USER_SORT_FIELDS] = $CONF[USER_SORT_FIELDS];
    $preferences[USER_SORT_ORDER] = $CONF[USER_SORT_ORDER];
    $preferences[TOOLTIP_SHOW] = $CONF[TOOLTIP_SHOW];
    $preferences[TOOLTIP_HIDE] = $CONF[TOOLTIP_HIDE];
    
    $schema = db_determine_schema();
    
    $id = db_getIdByUserid($userid, $link);
    if ($id) {
        $query = "SELECT * " . "  FROM " . $schema . "preferences " . " WHERE user_id = $id";
        $result = db_query($query, $link);
        
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result[RESULT]);
            $page_size = $row[PAGESIZE];
            $preferences = array();
            $preferences[PAGESIZE] = $page_size;
            $preferences[USER_SORT_FIELDS] = $row[USER_SORT_FIELDS];
            $preferences[USER_SORT_ORDER] = $row[USER_SORT_ORDER];
            $preferences[TOOLTIP_SHOW] = $row[TOOLTIP_SHOW];
            $preferences[TOOLTIP_HIDE] = $row[TOOLTIP_HIDE];
        }
    }
    
    return $preferences;
    
}

/**
 * check if semaphore is set, returns true if semaphore is set
 *
 * @param string $action
 * @param string $type
 * @param resource $link
 * @return boolean
 */
function db_get_semaphore($action, $type, $link) {

    global $CONF;
    
    $schema = db_determine_schema();
    
    $query = "SELECT * " . "  FROM " . $schema . "workinfo " . " WHERE (action = '$action') " . "   AND (type = '$type') " . "   AND (status = 'open')";
    $result = db_query($query, $link);
    
    return ($result['rows'] > 0);
    
}

/**
 * set semaphore and check if a semaphore is already open, returns false if a semaphore could not be set
 *
 * @param string $action
 * @param string $type
 * @param resource $link
 * @return boolean
 */
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

/**
 * unset semaphore
 *
 * @param string $action
 * @param string $type
 * @param resource $link
 * @return boolean
 */
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

/**
 * get schema and return string
 *
 * @return string
 */
function db_determine_schema() {

    global $CONF;
    
    if ((substr($CONF[DATABASE_TYPE], 0, 8) == "postgres") || ($CONF[DATABASE_TYPE] == "oci8")) {
        $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
    }
    else {
        $schema = "";
    }
    
    return ($schema);
    
}

/**
 * Escape a string
 *
 * @param string $string
 * @param resource $link
 * @return string
 */
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

/**
 * get a list ob database objects
 *
 * @param string $type
 * @param string $tSearch
 * @param resource $dbh
 * @param string $tParts
 * @return array[][]
 */
function db_get_list($type, $tSearch, $dbh, $tParts = '') {

    $ret = array();
    $tArray = array();
    $schema = db_determine_schema();
    $tErrorClass = '';
    $tMessage = '';
    
    switch ($type) {
        case 'users' :
            $query = "SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE ((userid like '%$tSearch%') " . "    OR  (CONCAT(givenname, ' ', name) like '%$tSearch%') " . "    OR  (CONCAT(name, ' ', givenname) like '%$tSearch%') " . "    OR (name like '%$tSearch%') " . "    OR (givenname like '%$tSearch%')) " . "   AND (deleted = '00000000000000') " . "ORDER BY name ASC , givenname ASC";
            $page = 'workOnUser.php';
            break;
        case 'groups' :
            $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE ((groupname like '%$tSearch%') " . "    OR (description like '%$tSearch%')) " . "   AND (deleted = '00000000000000') " . "ORDER BY groupname ASC";
            $page = "workOnGroup.php";
            break;
        case 'groupadmins' :
            $tParts = explode(" ", $tSearch);
            if (count($tParts) == 1) {
                
                $query = "SELECT * " . "  FROM " . $schema . "svn_groups_responsible," . $schema . "svnusers, " . $schema . "svngroups " . " WHERE (svn_groups_responsible.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000') " . "   AND (svn_groups_responsible.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND ((svnusers.name like '%$tSearch%') " . "    OR  (svnusers.givenname like '%$tSearch%') " . "    OR  (svnusers.userid like '%$tSearch%') " . "    OR  (svngroups.groupname like '%$tSearch%') " . "    OR  (svngroups.description like '%$tSearch%')) " . "ORDER BY svnusers.name ASC, svnusers.givenname ASC";
            }
            else {
                
                $query = "SELECT * " . "  FROM " . $schema . "svn_groups_responsible," . $schema . "svnusers, " . $schema . "svngroups " . " WHERE (svn_groups_responsible.user_id = svnusers.id) " . "   AND (svnusers.deleted = '00000000000000') " . "   AND (svn_groups_responsible.deleted = '00000000000000') " . "   AND (svn_groups_responsible.group_id = svngroups.id) " . "   AND (svngroups.deleted = '00000000000000') " . "   AND (";
                
                $i = 0;
                foreach( $tParts as $entry) {
                    
                    if ($i == 0) {
                        
                        $query = $query . "       (svnusers.name like '%$entry%') ";
                        $i ++;
                    }
                    else {
                        
                        $query = $query . "    OR (svnusers.name like '%$entry%') ";
                    }
                    
                    $query = $query . "    OR  (svnusers.givenname like '%$entry%') ";
                }
                
                $query = $query . "       ) " . "ORDER BY svnusers.name ASC, svnusers.givenname ASC";
            }
            $page = 'workOnGroupAccessRight.php';
            break;
        case 'projects' :
            $query = "SELECT   svnprojects.id, svnprojects.svnmodule, svnprojects.modulepath, svnrepos.reponame " . "    FROM " . $schema . "svnprojects, " . $schema . "svnrepos " . "   WHERE (svnrepos.deleted = '00000000000000') " . "     AND (svnprojects.deleted = '00000000000000') " . "     AND (svnprojects.repo_id = svnrepos.id) " . "     AND (svnprojects.svnmodule like '%$tSearch%') " . "ORDER BY svnmodule ASC ";
            $page = 'workOnProject.php';
            break;
        case 'repos' :
            $query = "SELECT * " . "  FROM " . $schema . "svnrepos " . " WHERE ((repouser like '%$tSearch%') " . "    OR (reponame like '%$tSearch%')) " . "   AND (deleted = '00000000000000') " . "ORDER BY reponame ASC";
            $page = "workOnRepo.php";
            break;
        default :
            break;
    }
    
    if ($tSearch == "") {
        
        $tErrorClass = "error";
        $tMessage = _("No search string given!");
    }
    else {
        
        $result = db_query($query, $dbh);
        while ( $row = db_assoc($result['result']) ) {
            
            $tArray[] = $row;
        }
        
        if (count($tArray) == 0) {
            
            $tErrorClass = "info";
            $tMessage = _("No group found!");
        }
        elseif (count($tArray) == 1) {
            
            $id = $tArray[0]['id'];
            $url = $page . "?id=" . urlencode($id) . "&task=change";
            db_disconnect($dbh);
            header("Location: $url");
            exit();
        }
        else {
            
            db_disconnect($dbh);
            $_SESSION[SVNSESSID]['searchresult'] = $tArray;
            header("Location: searchresult.php");
            exit();
        }
    }
    
    $ret['errorclass'] = $tErrorClass;
    $ret['message'] = $tMessage;
    $ret['result'] = $tArray;
    
    return $ret;
    
}

/**
 * gather statistics
 * 
 * @param resource $dbh
 * @return array[]
 */
function db_getStatistics($dbh) {

    $schema = db_determine_schema();
    $stats = array();
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnusers WHERE (deleted = '00000000000000') AND (locked = 0);";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['user_active'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnusers WHERE (deleted = '00000000000000') AND (locked = 1);";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['user_locked'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnusers WHERE (deleted != '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['user_deleted'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnrepos WHERE (deleted = '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['repo_active'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnrepos WHERE (deleted != '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['repo_deleted'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svngroups WHERE (deleted = '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['group_active'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svngroups WHERE (deleted != '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['group_deleted'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnprojects WHERE (deleted = '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['project_active'] = $row['cnt'];
    
    $query = "SELECT count(*) AS cnt FROM " . $schema . "svnprojects WHERE (deleted != '00000000000000');";
    $result = db_query($query, $dbh);
    $row = db_assoc($result['result']);
    $stats['project_deleted'] = $row['cnt'];
    
    return ($stats);
    
}

/**
 * create array with LDAP connect options
 *
 * @return array[][]
 */
function set_ldap_connect_options() {

    global $CONF;
    
    if (isset($CONF[LDAP_PROTOCOL])) {
        $protocol = $CONF[LDAP_PROTOCOL];
    }
    else {
        $protocol = "2";
    }
    
    return Array(
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
    
}

/**
 * check if an user exists in ldap directory
 *
 * @param string $userid
 * @return integer
 */
function ldap_check_user_exists($userid) {

    global $CONF;
    global $LDAP_CONNECT_OPTIONS;
    
    $ret = 0;
    $LDAP_CONNECT_OPTIONS = set_ldap_connect_options();
    
    try {
        $ldap = NewADOConnection('ldap');
        $ldap->Connect($CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $ldapOpen = 1;
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = sprintf("Database connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $_SESSION[SVNSESSID][DBFUNCTION] = sprintf("db_connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        
        $location = db_get_database_error_location();
        
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

/**
 * get uid from array ldap result
 *
 * @param array $arr
 * @return integer
 */
function get_uid($arr) {

    global $CONF;
    
    if (isset($CONF[ATTR_MAPPING]['uid'])) {
        if (isset($arr[$CONF[ATTR_MAPPING]['uid']])) {
            $uid = $arr[$CONF[ATTR_MAPPING]['uid']];
        }
        else {
            $uid = "";
        }
    }
    else {
        if (isset($arr['uid'])) {
            $uid = $arr['uid'];
        }
        else {
            $uid = "";
        }
    }
    
    return ($uid);
    
}

/**
 * get name from array ldap result
 *
 * @param array $arr
 * @return string
 */
function get_name($arr) {

    global $CONF;
    
    if (isset($CONF[ATTR_MAPPING]['name'])) {
        if (isset($arr[$CONF[ATTR_MAPPING]['name']])) {
            $name = $arr[$CONF[ATTR_MAPPING]['name']];
        }
        else {
            $name = "";
        }
    }
    else {
        if (isset($arr['sn'])) {
            $name = $arr['sn'];
        }
        else {
            $name = "";
        }
    }
    
    return ($name);
    
}

/**
 * get givenname from ldap result array
 *
 * @param array $arr
 * @return string
 */
function get_givenname($arr) {

    global $CONF;
    
    if (isset($CONF[ATTR_MAPPING]['givenName'])) {
        if (isset($arr[$CONF[ATTR_MAPPING]['givenName']])) {
            $givenname = $arr[$CONF[ATTR_MAPPING]['givenName']];
        }
        else {
            $givenname = "";
        }
    }
    else {
        if (isset($arr['givenName'])) {
            $givenname = $arr['givenName'];
        }
        else {
            $givenname = "";
        }
    }
    
    return ($givenname);
    
}

/**
 * get email adress from ldap result array
 *
 * @param array $arr
 * @return string
 */
function get_emailaddress($arr) {

    global $CONF;
    
    if (isset($CONF[ATTR_MAPPING]['mail'])) {
        $attr = $CONF[ATTR_MAPPING]['mail'];
    }
    else {
        $attr = 'mail';
    }
    
    return isset($arr[$attr]) ? $arr[$attr] : "";
    
}

/**
 * get available users from ldap
 *
 * @return array
 */
function get_ldap_users() {

    global $CONF;
    global $LDAP_CONNECT_OPTIONS;
    
    $tUsers = array();
    $additionalFilter = isset($CONF['additional_user_filter']) ? $CONF['additional_user_filter'] : "";
    $LDAP_CONNECT_OPTIONS = set_ldap_connect_options();
    
    try {
        $ldap = NewADOConnection('ldap');
        $ldap->Connect($CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $ldapOpen = 1;
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = sprintf("Database connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $_SESSION[SVNSESSID][DBFUNCTION] = sprintf("db_connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        
        $location = db_get_database_error_location();
        
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
                
                $entry['uid'] = get_uid($arr);
                $entry['name'] = get_name($arr);
                $entry[GIVENNAME] = get_givenname($arr);
                $entry['emailaddress'] = get_emailaddress($arr);
                
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
        
        $location = db_get_database_error_location();
        
        header("location: $location");
        exit();
    }
    
    if ($ldapOpen == 1) {
        $ldap->Close();
    }
    
    usort($tUsers, "sortLdapUsers");
    
    return ($tUsers);
    
}

/**
 * check password against ldap
 *
 * @param string $userid
 * @param string $password
 * @return integer
 */
function check_ldap_password($userid, $password) {

    global $CONF;
    global $LDAP_CONNECT_OPTIONS;
    
    $ret = 0;
    $LDAP_CONNECT_OPTIONS = set_ldap_connect_options();
    
    try {
        $ldap = NewADOConnection('ldap');
        $ldap->Connect($CONF[LDAP_SERVER], $CONF[BIND_DN], $CONF[BIND_PW], $CONF[USER_DN]);
        $ldapOpen = 1;
    }
    catch ( exception $e ) {
        
        $_SESSION[SVNSESSID][DBERROR] = $e->msg;
        $_SESSION[SVNSESSID][DBQUERY] = sprintf("Database connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], 'xxxxxxxx', $CONF[USER_DN]);
        $_SESSION[SVNSESSID][DBFUNCTION] = sprintf("db_connect: %s - %s - %s - %s", $CONF[LDAP_SERVER], $CONF[BIND_DN], 'xxxxxxxx', $CONF[USER_DN]);
        
        $tErrorMessage = strtolower($_SESSION[SVNSESSID][DBERROR]);
        
        if (isset($CONF['ldap_bind_use_login_data']) && ($CONF['ldap_bind_use_login_data'] == 1) && strpos($tErrorMessage, "invalid") && strpos($tErrorMessage, "credentials")) {
            $ldapOpen = 0;
            $ret = 0;
        }
        else {
            
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
                $ldapUser = NewADOConnection('ldap');
                $ldapUser->Connect($CONF[LDAP_SERVER], $dn, $password, $CONF[USER_DN]);
                $ret = 1;
                $ldapUser->Close();
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

/**
 * Session handling in database
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. Allrights reserved.
 *           
 */
class Session {
    /**
     * a database connection resource
     *
     * @var resource
     */
    private static $_sess_db;
    
    /**
     * switch debugging on or off
     *
     * @var integer
     */
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
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = "Database connect";
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: Session database connect");
            
            $location = db_get_database_error_location();
            
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
        
        return true;
        
    }

    /**
     * Read the session
     *
     * @param integer $id
     *            int session id
     * @return string string of the session
     */
    public static function read($id) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'read executed');
        }
        
        if (($CONF[DATABASE_TYPE] == "postgres8") || ($CONF[DATABASE_TYPE] == "oci8")) {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
        $id = self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $sql = sprintf("SELECT session_data FROM " . $schema . "sessions " . "WHERE session_id = %s", $id);
        
        try {
            
            $result = self::$_sess_db->Execute($sql);
            if ($result->RecordCount() > 0) {
                
                $record = $result->FetchRow();
                return isset($record['session_data']) ? $record['session_data'] : $record['SESSION_DATA'];
            }
            
            return '';
        }
        catch ( exception $e ) {
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $sql;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: $sql");
            error_log("DB query: Session read");
            
            $location = db_get_database_error_location();
            
            header("location: $location");
            exit();
        }
        
    }

    /**
     * Write the session
     *
     * @param integer $id
     *            int session id
     * @param string $data
     *            string data of the session
     */
    public static function write($id, $data) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'write executed');
        }
        
        if (($CONF[DATABASE_TYPE] == "postgres8") || ($CONF[DATABASE_TYPE] == "oci8")) {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
        $id = self::$_sess_db->qstr($id, get_magic_quotes_gpc());
        $time = self::$_sess_db->qstr(time(), get_magic_quotes_gpc());
        $data = self::$_sess_db->qstr($data, get_magic_quotes_gpc());
        
        try {
            
            $sql = sprintf("SELECT * FROM " . $schema . "sessions WHERE session_id = %s", $id);
            $result = self::$_sess_db->Execute($sql);
            if ($result->RecordCount() > 0) {
                $sql = sprintf("UPDATE " . $schema . "sessions SET session_expires = %s, session_data = %s WHERE session_id = %s", $time, $data, $id);
            }
            else {
                $sql = sprintf("INSERT INTO " . $schema . "sessions (session_id, session_expires, session_data) VALUES(%s, %s, %s)", $id, $time, $data);
            }
            
            self::$_sess_db->Execute($sql);
            $error = 0;
        }
        catch ( exception $e ) {
            
            $_SESSION[SVNSESSID][DBERROR] = $e->msg;
            $_SESSION[SVNSESSID][DBQUERY] = $sql;
            $_SESSION[SVNSESSID][DBFUNCTION] = "db_connect";
            
            error_log("DB error: " . $e->msg);
            error_log("DB query: $sql");
            error_log("DB query: Session write to database");
            
            $location = db_get_database_error_location();
            
            header("location: $location");
            exit();
            
            return false;
        }
        
        return ($error == 0);
        
    }

    /**
     * Destoroy the session
     *
     * @param integer $id
     *            int session id
     * @return bool
     */
    public static function destroy($id) {

        global $CONF;
        
        if (self::$DEBUG != 0) {
            db_log('gc', 'destroy executed');
        }
        
        if (($CONF[DATABASE_TYPE] == "postgres8") || ($CONF[DATABASE_TYPE] == "oci8")) {
            $schema = ($CONF[DATABASE_SCHEMA] == "") ? "" : $CONF[DATABASE_SCHEMA] . ".";
        }
        else {
            $schema = "";
        }
        
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
            
            $location = db_get_database_error_location();
            
            header("location: $location");
            exit();
            
            return false;
        }
        
    }

    /**
     * Garbage Collector
     *
     * @param integer $max
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
        
        if (($CONF[DATABASE_TYPE] == "postgres8") || ($CONF[DATABASE_TYPE] == "oci8")) {
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
            
            $location = db_get_database_error_location();
            
            header("location: $location");
            exit();
            
            return false;
        }
        
    }
    
}

/**
 * session handler registration
 */
if (isset($CONF) && ($CONF['session_in_db'] == "YES")) {
    
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

/**
 * set session cache expire time
 */
session_cache_expire(30);
?>
