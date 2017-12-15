<?php

/*
 * SVN Access Manager - a subversion access rights management tool
 * Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>
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
 * File: lostpassword.php
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
if (file_exists(realpath("./config/config.inc.php"))) {
    require ("./config/config.inc.php");
}
elseif (file_exists(realpath("../config/config.inc.php"))) {
    require ("../config/config.inc.php");
}
elseif (file_exists("/etc/svn-access-manager/config.inc.php")) {
    require ("/etc/svn-access-manager/config.inc.php");
}
else {
    die("can't load config.inc.php. Please check your installation!\n");
}

$installBase = isset($CONF['install_base']) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
require ("$installBase/include/functions.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session_lpw("n");
$dbh = db_connect();
$_SESSION['svn_lpw']['helptopic'] = "lostpassword";
$schema = db_determine_schema();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if (isset($_SESSION['svn_lpw']['error'])) {
        $tMessage = $_SESSION['svn_lpw']['error'];
    }
    else {
        $tMessage = "";
    }
    
    $tUsername = $SESSID_USERNAME;
    $tEmailaddress = isset($_SESSION['svn_lpw']['emailaddress']) ? $_SESSION['svn_lpw']['emailaddress'] : "";
    
    include ("$installBase/templates/lostpassword.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    $tUsername = db_escape_string($_POST['fUsername']);
    $tEmailaddress = db_escape_string($_POST['fEmailaddress']);
    $result = db_query("SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$tUsername') " . "   AND (emailaddress = '$tEmailaddress') " . "   AND (deleted = '00000000000000')", $dbh);
    
    if ($result['rows'] == 1) {
        
        $s = new Session();
        session_start();
        // session_register("svn_lpw");
        if (! isset($_SESSION['svn_lpw'])) {
            $_SESSION['svn_lpw'] = array();
        }
        $_SESSION['svn_lpw']['username'] = $tUsername;
        $_SESSION['svn_lpw']['emailaddress'] = $tEmailaddress;
        
        db_log($tUsername, "password reset requested for valid username", $dbh);
        db_disconnect($dbh);
        header("Location: securityquestion.php");
        exit();
    }
    else {
        
        $error = 1;
        $tMessage = _('No user with this username and this emailaddress is known');
        db_log($tUsername, "password reset requested for invalid username", $dbh);
    }
    
    include ("$installBase/templates/lostpassword.tpl");
}

db_disconnect($dbh);
?>
