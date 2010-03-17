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


require ("./config/config.inc.php");
require ("./include/db-functions-adodb.inc.php");
require ("./include/functions.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session ();

db_log( $_SESSION['svn_sessid']['username'], "$SESSID_USERNAME logged out" );

session_unset ();
session_destroy ();

header ("Location: login.php");
exit;
?>
