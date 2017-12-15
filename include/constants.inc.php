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
 *
 * $Id: output.inc.php 370 2017-12-14 15:54:04Z tom_krieger $
 *
 */
if (preg_match("/constants.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}
define('INSTALLBASE', 'install_base');
define('PROJECTS', 'projects');
define('GROUPS', 'groups');
define('USERS', 'users');
define('ACCESS', 'access');
define('DBERROR', 'dberror');
define('BACK', 'back');
define('DELETE', 'delete');
define('REPOS', 'repos');
define('GENERAL', 'general');
define('ERROR', 'error');
define('ERRORMSG', 'errormsg');
define('PREFERENCES', 'preferences');
define('REPORTS', 'reports');
define('SEARCH', 'search');

?>