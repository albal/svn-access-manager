<?php

/**
 * help with lost passwords
 *
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
 *           
 *            SVN Access Manager - a subversion access rights management tool
 *            Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *           
 *            This program is free software; you can redistribute it and/or modify
 *            it under the terms of the GNU General Public License as published by
 *            the Free Software Foundation; either version 2 of the License, or
 *            (at your option) any later version.
 *           
 *            This program is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU General Public License for more details.
 *           
 *            You should have received a copy of the GNU General Public License
 *            along with this program; if not, write to the Free Software
 *            Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *           
 *
 */

/*
 *
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
require ("$installBase/include/functions.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session_lpw("n");
$dbh = db_connect();
$_SESSION[SVNLPW][HELPTOPIC] = "lostpassword";
$schema = db_determine_schema();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if (isset($_SESSION[SVNLPW]['error'])) {
        $tMessage = $_SESSION[SVNLPW]['error'];
    }
    else {
        $tMessage = "";
    }
    
    $tUsername = $SESSID_USERNAME;
    $tEmailaddress = isset($_SESSION[SVNLPW][EMAILADDRESS]) ? $_SESSION[SVNLPW][EMAILADDRESS] : "";
    
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
        
        if (! isset($_SESSION[SVNLPW])) {
            $_SESSION[SVNLPW] = array();
        }
        $_SESSION[SVNLPW]['username'] = $tUsername;
        $_SESSION[SVNLPW][EMAILADDRESS] = $tEmailaddress;
        
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
