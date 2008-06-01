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
$LastChangedDate: 2008-05-16 18:14:12 +0200 (Fri, 16 May 2008) $
$LastChangedBy: kriegeth $

$Id: config.inc.php 93 2008-05-16 16:14:12Z kriegeth $

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
$CONF['database_host'] 			= '###DBHOST###';
$CONF['database_user'] 			= '###DBUSER###';
$CONF['database_password'] 		= '###DBPASS###';
$CONF['database_name'] 			= '###DBNAME###';
$CONF['database_prefix'] 		= '';
$CONF['database_innodb']		= 'YES';
$CONF['session_in_db']			= '###SESSIONINDB###';

// Site Admin
// Define the Site Admins email address below.
$CONF['admin_email'] 			= '###ADMINEMAIL###';

$CONF['encrypt'] 				= 'system';
$CONF['generate_password'] 		= 'YES';

$CONF['logging']				= '###USELOGGING###';

// Page Size
// Set the number of entries that you would like to see
// in one page.
$CONF['page_size'] 				= '###PAGESIZE###';

$CONF['passwordSpecialChars']	= '[\!\"\§\$\%\/\(\)=\?\*\+\#\-\_\.\:\,\;\<\>\|\@]';
$CONF['passwordSpecialCharsTxt']= '!"§$%/()=?*+#-_.:,;<>|@';
$CONF['minPasswordlength']		= ###MINPWADMIN###;
$CONF['minPasswordlengthUser']	= ###MINPWUSER###;

$CONF['copyright']				= '(C) 2008 Thomas Krieger (tom(at)svn-access-manager(dot)org)';

$CONF['svn_command']			= '###SVNCMD###';
$CONF['grep_command']			= '###GREPCMD###';
$CONF['use_javascript']			= '###USEJS###';

$CONF['SVNAccessFile']			= '###SVNACCESSFILE###';
$CONF['AuthUserFile']			= '###SVNAUTHFILE###';
$CONF['createAccessFile']		= '###CREATEACCESSFILE###';
$CONF['createUserFile']			= '###CREATEAUTHFILE###';
//
// END OF CONFIG FILE
//
?>
