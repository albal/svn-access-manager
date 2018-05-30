<?php

/**
 * constants definitions
 *
 * @author Thomas krieger
 * @copyright 2008-2018 Thomas Krieger. All rights ewsserved.
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
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */

/**
 * Define a constant if not yet done.
 *
 * @param string $constant
 * @param string $value
 */
function define_constant($constant, $value) {

    if (! defined($constant)) {
        define($constant, $value);
    }
    
}

/**
 * check if called directly and redirect to login page.
 */
if (preg_match("/constants.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

define_constant('INSTALLBASE', 'install_base');
define_constant('SVNSESSID', 'SVNSESSID');
define_constant('SVN_INST', 'svn_inst');

define('PROJECTS', 'projects');
define('GROUPS', 'groups');
define('USERS', 'users');
define('ACCESS', 'access');
define('PROJECT', 'project');
define('RIGHT', 'right');

define('DBERROR', 'dberror');
define('DBQUERY', 'dbquery');
define('DBFUNCTION', 'dbfunction');
define('RESULT', 'result');
define('NOTSET', 'not set');

define('BACK', 'back');
define('DELETE', 'delete');
define('REPOS', 'repos');
define('GENERAL', 'general');
define('PREFERENCES', 'preferences');
define('REPORTS', 'reports');
define('SEARCH', 'search');

define('ERROR', 'error');
define('ERRORMSG', 'errormsg');
define('ERRORLIST', 'errorlist');
define('ERRORTYPE', 'errortype');
define('ALERT', 'alert');
define('DANGER', 'danger');
define('SUCCESS', 'success');
define('WARNING', 'warning');

define('PROJECT_ID', 'project_id');
define('PROJECTID', 'projectid');
define('REPO_ID', 'repo_id');
define('REPOID', 'repoid');
define('USER_ID', 'user_id');
define('USERID', 'userid');
define('GROUP_ID', 'group_id');
define('GROUPID', 'groupid');
define('RIGHTID', 'rightid');
define('GROUPNAME', 'groupname');
define('GROUPDESCR', 'groupdescr');
define('USERNAME', 'username');
define('SVNMODULE', 'svnmodule');
define('REPONAME', 'reponame');
define('REPOPATH', 'repopath');
define('REPOUSER', 'repouser');
define('REPOPASSWORD', 'repopassword');
define('USER_MODE', 'user_mode');
define('RIGHT_NAME', 'right_name');
define('ALLOWED', 'allowed');
define('TASK', 'task');
define('SVNGROUPS', 'svngroups');
define('SVNUSERS', 'svnusers');
define('MODULEPATH', 'modulepath');
define('PATHCNT', 'pathcnt');

define('PAGESIZE', 'page_size');
define('ACCESSBY', 'access_by');
define('ACCESS_RIGHT', 'access_right');
define('USER_SORT_FIELDS', 'user_sort_fields');
define('USER_SORT_ORDER', 'user_sort_order');

define('GIVENNAME', 'givenname');
define('BULKADDLIST', 'bulkaddlist');
define('EMAILADDRESS', 'emailaddress');

define('HELPTOPIC', 'helptopic');
define('HEADLINE', 'headline');
define('HELPTEXT', 'helptext');

define('SEPARATEFILESPERREPO', 'separateFilesPerRepo');
define('AUTHUSERFILE', 'AuthUserFile');
define('CREATEAUTHUSERFILE', 'createauthuserfile');
define('CREATEACCESSFILE', 'createaccessfile');
define('SVNACCESSFILE', 'SVNAccessFile');
define('REPOPATHSORTORDER', 'repoPathSortOrder');
define('CREATEVIEWVCCONF', 'createviewvcconf');
define('VIEWVCCONF', 'ViewvcConf');
define('VIEWVCGROUPS', 'ViewvcGroups');
define('VIEWVCLOCATION', 'ViewvcLocation');
define('WRITEANONYMOUSACCESSRIGHTS', 'write_anonymous_access_rights');

define('DATABASE_CHARSET', 'database_charset');
define('DATABASECHARSET', 'databaseCharset');
define('DATABASE_COLLATION', 'database_collation');
define('DATABASECOLLATION', 'databaseCollation');
define('DATABASE_TYPE', 'database_type');
define('DATABASE_PASSWORD', 'database_password');
define('DATABASEPASSWORD', 'databasePassword');
define('DATABASE_USER', 'database_user');
define('DATABASEUSER', 'databaseUser');
define('DATABASE_HOST', 'database_host');
define('DATABASEHOST', 'databaseHost');
define('DATABASE_NAME', 'database_name');
define('DATABASENAME', 'databaseName');
define('DATABASE_SCHEMA', 'database_schema');
define('DATABASESCHEMA', 'databaseSchema');
define('DATABASE_PREFIX', 'database_prefix');
define('DATABASE_TABLESPACE', 'database_tablespace');
define('DATABASETABLESPACE', 'databaseTablespace');
define('MYSQLI', 'mysqli');
define('MYSQL', 'mysql');
define('ROLLBACK', 'ROLLBACK');
define('BEGIN', 'BEGIN');
define('COMMIT', 'COMMIT');

define('LDAP_PROTOCOL', 'ldap_protocol');
define('BIND_DN', 'bind_dn');
define('USER_DN', 'user_dn');
define('LDAP_SERVER', 'ldap_server');
define('BIND_PW', 'bind_pw');
define('USER_OBJECTCLASS', 'user_objectclass');
define('ATTR_MAPPING', 'attr_mapping');
define('USE_LDAP', 'use_ldap');
define('CUSTOM_COLUMN1', 'custom_column1');
define('CUSTOM_COLUMN2', 'custom_column2');
define('CUSTOM_COLUMN3', 'custom_column3');
define('ADMIN', 'admin');
define('EDIT', 'edit');

define('MESSAGES', 'messages');
define('SVNLPW', 'svn_lpw');
define('PWCRYPT', 'pwcrypt');
define('MINPASSWORDGROUPUSER', 'minPasswordGroupsUser');
define('DESCRIPTION', 'description');

define('WINDOWS', 'windows');
define('SVNADMIN_COMMAND', 'svnadmin_command');
define('CHECKED', 'checked');
define('MEMBERS', 'members');
define('ADD', 'add');
define('NEW', 'new');
define('RELIST', 'relist');
define('PASSWORD', 'password');
define('PASSWORD2', 'password2');
define('ADMIN_EMAIL', 'admin_email');
define('PASSWORD_EXPIRES', 'password_expires');
define('WRITE', 'write');
define('CHANGE', 'change');
define('NOPERMISSION', 'nopermission');
define('SECURITYQUESTION', 'securityquestion');
define('PASSWORD_POLICY', 'password_policy');
define('SELECTED', 'selected');
define('ALLOWED_ACTION', 'allowed_action');
define('SEARCHRESULT', 'searchresult');
define('RIGHTSGRANTED', 'rightsgranted');
define('PASSWORDEXPIRES', 'passwordexpires');
define('LOCKED', 'locked');
define('ADMINSTER', 'adminster');
define('APRMD5', 'apr-md5');
define('LDAP_SORT_FIELD', 'ldap_sort_field');

define('MESSAGEID', 'messageid');
define('TOOLTIP_SHOW', 'tooltip_show');
define('TOOLTIP_HIDE', 'tooltip_hide');

?>


