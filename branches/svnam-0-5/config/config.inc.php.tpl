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
$LastChangedDate$
$LastChangedBy$

$Id$

*/


if (preg_match ("/config.inc.php/", $_SERVER['PHP_SELF'])) {
   
   header ("Location: login.php");
   exit;
   
}


$CONF['install_base']			= '###INSTALLBASE###';

// Language config
$CONF['default_language'] 		= 'en';
$CONF['default_locale']			= 'en_US';
$CONF['supported_languages']	= array ('de', 'de_DE', 'en', 'en_US');

// Database Config
$CONF['database_type'] 			= '###DBTYPE###';
$CONF['database_host'] 			= '###DBHOST###';
$CONF['database_user'] 			= '###DBUSER###';
$CONF['database_password'] 		= '###DBPASS###';
$CONF['database_name'] 			= '###DBNAME###';
$CONF['database_prefix'] 		= '';
$CONF['database_innodb']		= 'YES';
$CONF['database_charset']		= '###DBCHARSET###';
$CONF['database_collation']		= '###DBCOLLATION###';
$CONF['database_schema']		= '###DBSCHEMA###';
$CONF['database_tablespace']	= '###DBTABLESPACE###';
$CONF['session_in_db']			= '###SESSIONINDB###';

$CONF['website_charset']		= '###WEBSITECHARSET###';
$CONF['website_url']			= '###WEBSITEURL###';

// Sort order for user
$CONF['user_sort_fields']		= "name,givenname";
$CONF['user_sort_order']		= "ASC";

// Site Admin
// Define the Site Admins email address below.
$CONF['admin_email'] 			= '###ADMINEMAIL###';

$CONF['encrypt'] 				= 'system';
$CONF['generate_password'] 		= 'YES';

// Lost password
$CONF['lostPwSender']			= '###LOSTPWSENDER###';
$CONF['lostPwMaxError']			= ###LOSTPWMAXERROR###;
$CONF['lostPwLinkValid']		= ###LOSTPWLINKVALID###;

$CONF['logging']				= '###USELOGGING###';

// Page Size
// Set the number of entries that you would like to see
// in one page.
$CONF['page_size'] 				= '###PAGESIZE###';

$CONF['passwordSpecialChars']	= '[\!\"\§\$\%\/\(\)=\?\*\+\#\-\_\.\:\,\;\<\>\|\@]';
$CONF['passwordSpecialCharsTxt']= '!"§$%/()=?*+#-_.:,;<>|@';
$CONF['minPasswordlength']		= ###MINPWADMIN###;
$CONF['minPasswordlengthUser']	= ###MINPWUSER###;
// Set passwword complexity. A password must consist of four different goups och charactsers.
// The four groups are locer-case and upper-case characters, special characters and digits
// The two config varaibles $CONF['minPasswordGroups'] and $CONF['minPasswordGroupsUser']
// allow to set how many groups a password must consist of. The maximum is 4, the minimum is 1!
// If the values are out of range, for user 3 is assumed and for admins 4 is assumed
$CONF['minPasswordGroups']		= 4;
$CONF['minPasswordGroupsUser']	= 3;

// password expiry stuff
$CONF['password_expires']		= ###PASSWORDEXPIRES###;
$CONF['password_expires_warn']	= ###PASSWORDEXPIRESWARN###;
// define default value for password expire, allowed values 0 and 1
$CONF['expire_password']		= '###EXPIREPASSWORD###';

# valid values for pwcrypt are md5 or crypt
$CONF['pwcrypt']				= '###PWCRYPT###';

$CONF['copyright']				= '(C) 2008, 2009, 2010 Thomas Krieger (tom(at)svn-access-manager(dot)org)';

$CONF['svn_command']			= '###SVNCMD###';
$CONF['svnadmin_command']		= '###SVNADMINCMD###';
$CONF['repo_compatibility']		= '###PRECOMPATIBLE###';
$CONF['grep_command']			= '###GREPCMD###';
$CONF['use_javascript']			= '###USEJS###';

$CONF['SVNAccessFile']			= '###SVNACCESSFILE###';
$CONF['AuthUserFile']			= '###SVNAUTHFILE###';
$CONF['ViewvcConf']				= '###VIEWVCCONF###';
$CONF['ViewvcGroups']			= '###VIEWVCGROUPS###';
# ViewVC Alias in the Apache Webserver without trailing /
$CONF['ViewvcLocation']			= '###VIEWVCLOCATION###';
$CONF['ViewvcApacheReload']		= '###VIEWVCAPACHERELOAD###';
$CONF['ViewvcRealm']			= '###VIEWVCREALM###';
$CONF['createAccessFile']		= '###CREATEACCESSFILE###';
$CONF['createUserFile']			= '###CREATEAUTHFILE###';
$CONF['createViewvcConf']		= '###CREATEVIEWVCCONF###';
$CONF['separateFilesPerRepo']	= '###SEPERATEFILESPERREPO###';
//Limit access control to directories only, change to files if you want to have
//files listed during access rights management too
//Valid values are "dirs" or "files"
$CONF['accessControl']			= '###ACCESSCONTROLLEVEL###';
// Set users default access right
// valid values are; read, write
$CONF['userDefaultAccess'] 		= '###USERDEFAULTACCESS###';

// SVN access file repository path sort order, vallowed values are ASC or DESC
$CONF['repoPathSortOrder']		= '###REPOPATHSORTORDER###'; 

// Custom Fields
$CONF['column_custom1']			= ###CUSTOM1###;
$CONF['column_custom2']			= ###CUSTOM2###;
$CONF['column_custom3']			= ###CUSTOM3###;

$CONF['mail_password_warn']		= <<<EOM

Dear %s,

your password for SVN Access Manager is about to expire. Please goto %s, log in and change your password.

Please keep in mind that your account will be locked out automatically if your password was not changed. 

Users are locked out if the password was not changed for %s days!

Kind regrads

SVN Access Manager
Administrator

EOM;

$CONF['mail_user_locked']		= <<<EOM

Dear %s,

you account at SVN Access Manager was locked. You did not change your password for %s days.

You can not access the subversion repositories any more. To get access please log into your account at %s and change your password.

Please give about %s minutes until your account is unlocked and you can access the subversion repositories again.

Kind regards

SVN Access Manager
Administrator

EOM;

//LDAP stuff
$CONF['use_ldap']                               = '###USELDAP###';
$CONF['bind_dn']                                = '###BINDDN###';
$CONF['bind_pw']                                = '###BINDPW###';
$CONF['user_dn']                                = '###USERDN###';
$CONF['user_filter_attr']                       = '###USERFILTERATTR###';
$CONF['user_objectclass']                       = '###USEROBJECTCLASS###';
$CONF['additional_user_filter']                 = '###USERADDITIONALFILTER###';
$CONF['ldap_server']                            = '###LDAPHOST###';
$CONF['ldap_port']                              = '###LDAPPORT###';
$CONF['ldap_protocol']                          = '###LDAPPROTOCOL###';
$CONF['attr_mapping']['uid']                    = '###MAPUID###';
$CONF['attr_mapping']['name']                   = '###MAPNAME###';
$CONF['attr_mapping']['givenName']              = '###MAPGIVENNAME###';
$CONF['attr_mapping']['mail']                   = '###MAPMAIL###';
$CONF['attr_mapping']['userPassword']           = '###MAPPASSWORD###';

//
// END OF CONFIG FILE
//
?>
