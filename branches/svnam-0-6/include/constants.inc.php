<?php

/**
 * constants definitions
 *
 * @author Thomas krieger
 * @copyright 2018 Thomas Krieger. All rights ewsserved.
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
 * @filesource
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

define_constant('PROJECTS', 'projects');
define_constant('GROUPS', 'groups');
define_constant('USERS', 'users');
define_constant('ACCESS', 'access');
define_constant('PROJECT', 'project');

define_constant('DBERROR', 'dberror');
define_constant('DBQUERY', 'dbquery');
define_constant('DBFUNCTION', 'dbfunction');
define_constant('RESULT', 'result');
define_constant('NOTSET', 'not set');

define_constant('BACK', 'back');
define_constant('DELETE', 'delete');
define_constant('REPOS', 'repos');
define_constant('GENERAL', 'general');
define_constant('PREFERENCES', 'preferences');
define_constant('REPORTS', 'reports');
define_constant('SEARCH', 'search');

define_constant('ERROR', 'error');
define_constant('ERRORMSG', 'errormsg');
define_constant('ERRORLIST', 'errorlist');
define_constant('ALERT', 'alert');
define_constant('DANGER', 'danger');
define_constant('SUCCESS', 'success');
define_constant('WARNING', 'warning');

define_constant('SVNSESSID', 'SVNSESSID');
define_constant('PROJECT_ID', 'project_id');
define_constant('PROJECTID', 'projectid');
define_constant('REPO_ID', 'repo_id');
define_constant('REPOID', 'repoid');
define_constant('USER_ID', 'user_id');
define_constant('USERID', 'userid');
define_constant('GROUP_ID', 'group_id');
define_constant('GROUPID', 'groupid');
define_constant('RIGHTID', 'rightid');
define_constant('GROUPNAME', 'groupname');
define_constant('GROUPDESCR', 'groupdescr');
define_constant('USERNAME', 'username');
define_constant('SVNMODULE', 'svnmodule');
define_constant('REPONAME', 'reponame');
define_constant('REPOPATH', 'repopath');
define_constant('REPOUSER', 'repouser');
define_constant('REPOPASSWORD', 'repopassword');
define_constant('USER_MODE', 'user_mode');
define_constant('RIGHT_NAME', 'right_name');
define_constant('ALLOWED', 'allowed');
define_constant('TASK', 'task');
define_constant('SVNGROUPS', 'svngroups');
define_constant('SVNUSERS', 'svnusers');
define_constant('MODULEPATH', 'modulepath');
define_constant('PATHCNT', 'pathcnt');

define_constant('PAGESIZE', 'page_size');
define_constant('ACCESSBY', 'access_by');
define_constant('ACCESS_RIGHT', 'access_right');
define_constant('USER_SORT_FIELDS', 'user_sort_fields');
define_constant('USER_SORT_ORDER', 'user_sort_order');

define_constant('GIVENNAME', 'givenname');
define_constant('BULKADDLIST', 'bulkaddlist');
define_constant('EMAILADDRESS', 'emailaddress');

define_constant('HELPTOPIC', 'helptopic');
define_constant('HEADLINE', 'headline');
define_constant('HELPTEXT', 'helptext');

define_constant('SEPARATEFILESPERREPO', 'separateFilesPerRepo');
define_constant('AUTHUSERFILE', 'AuthUserFile');
define_constant('CREATEAUTHUSERFILE', 'createauthuserfile');
define_constant('CREATEACCESSFILE', 'createaccessfile');
define_constant('SVNACCESSFILE', 'SVNAccessFile');
define_constant('REPOPATHSORTORDER', 'repoPathSortOrder');
define_constant('CREATEVIEWVCCONF', 'createviewvcconf');
define_constant('VIEWVCCONF', 'ViewvcConf');
define_constant('VIEWVCGROUPS', 'ViewvcGroups');
define_constant('VIEWVCLOCATION', 'ViewvcLocation');
define_constant('WRITEANONYMOUSACCESSRIGHTS', 'write_anonymous_access_rights');

define_constant('DATABASE_CHARSET', 'database_charset');
define_constant('DATABASECHARSET', 'databaseCharset');
define_constant('DATABASE_COLLATION', 'database_collation');
define_constant('DATABASECOLLATION', 'databaseCollation');
define_constant('DATABASE_TYPE', 'database_type');
define_constant('DATABASE_PASSWORD', 'database_password');
define_constant('DATABASEPASSWORD', 'databasePassword');
define_constant('DATABASE_USER', 'database_user');
define_constant('DATABASEUSER', 'databaseUser');
define_constant('DATABASE_HOST', 'database_host');
define_constant('DATABASEHOST', 'databaseHost');
define_constant('DATABASE_NAME', 'database_name');
define_constant('DATABASENAME', 'databaseName');
define_constant('DATABASE_SCHEMA', 'database_schema');
define_constant('DATABASESCHEMA', 'databaseSchema');
define_constant('DATABASE_PREFIX', 'database_prefix');
define_constant('DATABASE_TABLESPACE', 'database_tablespace');
define_constant('DATABASETABLESPACE', 'databaseTablespace');
define_constant('MYSQLI', 'mysqli');
define_constant('MYSQL', 'mysql');
define_constant('ROLLBACK', 'ROLLBACK');
define_constant('BEGIN', 'BEGIN');
define_constant('COMMIT', 'COMMIT');

define_constant('LDAP_PROTOCOL', 'ldap_protocol');
define_constant('BIND_DN', 'bind_dn');
define_constant('USER_DN', 'user_dn');
define_constant('LDAP_SERVER', 'ldap_server');
define_constant('BIND_PW', 'bind_pw');
define_constant('USER_OBJECTCLASS', 'user_objectclass');
define_constant('ATTR_MAPPING', 'attr_mapping');
define_constant('USE_LDAP', 'use_ldap');
define_constant('CUSTOM_COLUMN1', 'custom_column1');
define_constant('CUSTOM_COLUMN2', 'custom_column2');
define_constant('CUSTOM_COLUMN3', 'custom_column3');
define_constant('ADMIN', 'admin');
define_constant('EDIT', 'edit');
define_constant('DELETE', 'delete');

define_constant('MESSAGES', 'messages');
define_constant('SVNLPW', 'svn_lpw');
define_constant('PWCRYPT', 'pwcrypt');
define_constant('MINPASSWORDGROUPUSER', 'minPasswordGroupsUser');
define_constant('DESCRIPTION', 'description');

define_constant('WINDOWS', 'windows');

define_constant('SVN_INST', 'svn_inst');
define_constant('SVNADMIN_COMMAND', 'svnadmin_command');
define_constant('CHECKED', 'checked');
define_constant('MEMBERS', 'members');
define_constant('ADD', 'add');
define_constant('NEW', 'new');
define_constant('RELIST', 'relist');
define_constant('PASSWORD', 'password');
define_constant('ADMIN_EMAIL', 'admin_email');
define_constant('PASSWORD_EXPIRES', 'password_expires');
define_constant('WRITE', 'write');
define_constant('CHANGE', 'change');
define_constant('SEPARATEFILESPERREPO', 'separateFilesPerRepo');
define_constant('NOPERMISSION', 'nopermission');
define_constant('SECURITYQUESTION', 'securityquestion');
define_constant('PASSWORD_POLICY', 'password_policy');
define_constant('SELECTED', 'selected');
define_constant('ALLOWED_ACTION', 'allowed_action');
define_constant('SEARCHRESULT', 'searchresult');
define_constant('RIGHTSGRANTED', 'rightsgranted');
define_constant('PASSWORDEXPIRES', 'passwordexpires');
define_constant('LOCKED', 'locked');
define_constant('ADMINSTER', 'adminster');
define_constant('APRMD5', 'apr-md5');

?>


