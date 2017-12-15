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
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();

if (file_exists(realpath("./templates/framework.tpl"))) {
    $location = "./templates/framework.tpl";
}
else {
    $location = "../templates/framework.tpl";
}

$tQuery = $_SESSION['svn_sessid']['dbquery'];
$tDbError = $_SESSION['svn_sessid']['dberror'];
$tDbFunction = isset($_SESSION['svn_sessid']) ? $_SESSION['svn_sessid'] : "not set";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $template = "database_error.tpl";
    $header = "dberror";
    $subheader = "dberror";
    $menu = "dberror";
    
    include ($location);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $template = "database_error.tpl";
    $header = "dberror";
    $subheader = "dberror";
    $menu = "dberror";
    
    include ($location);
}
?>
