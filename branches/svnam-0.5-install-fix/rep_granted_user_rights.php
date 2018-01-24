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
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Reports", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "repgranteduserrights";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use rep_granted_user_rights without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

// error_log( "page_size = ".$CONF['page_size'] );

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tGrantedRights = db_getGrantedRights(0, - 1, $dbh);
    $tCountRecords = db_getCountGrantedRights($dbh);
    $tPrevDisabled = "disabled";
    $_SESSION[SVNSESSID]['rightcounter'] = 0;
    
    if ($tCountRecords <= $CONF['page_size']) {
        
        $tNextDisabled = "disabled";
    }
    
    $template = "rep_granted_user_rights.tpl";
    $header = "reports";
    $subheader = "reports";
    $menu = "reports";
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    
    $template = "rep_granted_user_rights.tpl";
    $header = "reports";
    $subheader = "reports";
    $menu = "reports";
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}
?>
