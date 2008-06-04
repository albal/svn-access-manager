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
$LastChangedDate: 2008-06-04 14:25:27 +0200 (Wed, 04 Jun 2008) $
$LastChangedBy: kriegeth $

$Id: config.inc.php.tpl 216 2008-06-04 12:25:27Z kriegeth $

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
$CONF['password_expires']		= 60;
$CONF['password_expires_warn']	= 50;

$CONF['copyright']				= '(C) 2008 Thomas Krieger (tom(at)svn-access-manager(dot)org)';

$CONF['svn_command']			= '###SVNCMD###';
$CONF['grep_command']			= '###GREPCMD###';
$CONF['use_javascript']			= '###USEJS###';

$CONF['SVNAccessFile']			= '###SVNACCESSFILE###';
$CONF['AuthUserFile']			= '###SVNAUTHFILE###';
$CONF['createAccessFile']		= '###CREATEACCESSFILE###';
$CONF['createUserFile']			= '###CREATEAUTHFILE###';

$CONF['mail_password_warn']		= <<<EOM

Dear %s,

your password for SVN Access manager is about to expire. Please goto %s, log in and change your password.

Please keep in mind that your account will be locked out automatically if your password was not changed. 

Users are locked out if the password was not changed for %s days!

Kind regrads

SVN Access Manager
Administrator

EOM;

$CONF['mail_user_locked']		= <<<EOM

Dear %s,

you account at SVN Access manager was locked. You did not change your password for %s days.

You can not access the subversion repositories any more. To get access please log into your account at %s and change your password.

Please give about %s minutes until your account is unlocked and you can access the subversion repositories again.

Kind regards

SVN Access Manager
Administrator

EOM;

//
// END OF CONFIG FILE
//
?>
