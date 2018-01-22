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

/*
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
function define_constant($constant, $value) {

    if (! defined($constant)) {
        define($constant, $value);
    }

}

if (preg_match("/constants.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

define_constant('INSTALLBASE', 'install_base');

define_constant('PROJECTS', 'projects');
define_constant('GROUPS', 'groups');
define_constant('USERS', 'users');
define_constant('ACCESS', 'access');

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

define_constant('SVNSESSID', 'SVNSESSID');
define_constant('PROJECTID', 'project_id');
define_constant('REPOID', 'repo_id');
define_constant('USER_ID', 'user_id');
define_constant('GROUPID', 'group_id');
define_constant('RIGHTID', 'rightid');
define_constant('PROJECTID', 'project_id');
define_constant('GROUPNAME', 'groupname');

define_constant('PAGESIZE', 'page_size');
define_constant('ACCESSBY', 'access_by');
define_constant('ACCESSRIGHT', 'access_right');

define_constant('USERID', 'userid');
define_constant('GIVENNAME', 'givenname');
define_constant('BULKADDLIST', 'bulkaddlist');
define_constant('EMAILADDRESS', 'emailaddress');

?>

