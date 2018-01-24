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

if (file_exists(realpath("./templates/framework.tpl"))) {
    $location = "./templates/framework.tpl";
}
else {
    $location = "../templates/framework.tpl";
}

$tQuery = $_SESSION[SVNSESSID]['dbquery'];
$tDbError = $_SESSION[SVNSESSID]['dberror'];
$tDbFunction = isset($_SESSION[SVNSESSID]['dbfunction']) ? $_SESSION[SVNSESSID]['dbfunction'] : "not set";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $template = "database_error.tpl";
    $header = DBERROR;
    $subheader = DBERROR;
    $menu = DBERROR;
    
    include ($location);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $template = "database_error.tpl";
    $header = DBERROR;
    $subheader = DBERROR;
    $menu = DBERROR;
    
    include ($location);
}
?>
