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

/*

File:            config.inc.php.tpl
$LastChangedDate: 2008-05-18 22:53:30 +0200 (Sun, 18 May 2008) $
$LastChangedBy: kriegeth $

$Id: config.inc.php 98 2008-05-18 20:53:30Z kriegeth $

*/


if (ereg ("config.inc.php", $_SERVER['PHP_SELF'])) {
   
   header ("Location: login.php");
   exit;
   
}


// Language config
$CONF['default_language'] 		= 'en';
$CONF['default_locale']			= 'en_US';
$CONF['supported_languages']	= array ('de', 'de_DE', 'en', 'en_US');

// Database Config
$CONF['database_type'] 			= 'mysql';
$CONF['database_host'] 			= 'localhost';
$CONF['database_user'] 			= 'root';
$CONF['database_password'] 		= 'h4ll0dr1';
$CONF['database_name'] 			= 'svnadminnew';
$CONF['database_prefix'] 		= '';
$CONF['database_innodb']		= 'YES';

// Site Admin
// Define the Site Admins email address below.
$CONF['admin_email'] 			= 'tom@tom-krieger.de';

$CONF['encrypt'] 				= 'system';
$CONF['generate_password'] 		= 'YES';

$CONF['logging']				= 'YES';

// Page Size
// Set the number of entries that you would like to see
// in one page.
$CONF['page_size'] 				= '45';

$CONF['passwordSpecialChars']	= '[\!\"\§\$\%\/\(\)=\?\*\+\#\-\_\.\:\,\;\<\>\|\@]';
$CONF['passwordSpecialCharsTxt']= '!"§$%/()=?*+#-_.:,;<>|@';
$CONF['minPasswordlength']		= 14;
$CONF['minPasswordlengthUser']	= 8;

$CONF['copyright']				= '(C) 2008 Thomas Krieger (tom(at)svn-access-manager(dot)org)';

$CONF['svn_command']			= '/usr/bin/svn';
$CONF['grep_command']			= '/bin/grep';
$CONF['use_javascript']			= 'YES';

$CONF['SVNAccessFile']			= '/etc/svn/svn-access';
$CONF['AuthUserFile']			= '/etc/svn/svn-passwd';
$CONF['createAccessFile']		= 'YES';
$CONF['createUserFile']			= 'YES';
//
// END OF CONFIG FILE
//
?>
