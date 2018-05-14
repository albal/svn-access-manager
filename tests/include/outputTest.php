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
final class OutputTest extends PHPUnit_Framework_TestCase {

    public function test_outputHeader() {

        $_SESSION[SVNSESSID][USERNAME] = 'admin';
        include_once 'output.inc.php';
        
        $this->expectOutputString('<ul class=\'topmenu\'><li class=\'topmenu\'><a href=\'main.php\' alt=\'Home\'><img src=\'./images/gohome.png\' border=\'0\' /> Main menu</a></li><li class=\'topmenu\'><a href=\'logout.php\' alt=\'Logout\'><img src=\'./images/stop.png\' border=\'0\' />Logoff</a></li><li class=\'topmenu\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li><li><a href=\'help.php\' alt=\'help\' id=\'help\' target=\'_blank\'><img src=\'./images/help.png\' border=\'0\' />Help</a></li><li class=\'topmenu\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li><li><a href=\'doc/html/index.html#use\' alt=\'Documentation\' id=\'doc\' target=\'_blank\'><img src=\'./images/help.png\' border=\'0\' />Documentation</a></li></ul><div align=\'right\'><p>&nbsp;</p>Logged in as: admin</div>');
        outputHeader();
        
    }

    public function test_outputSubHeader() {

        $_SESSION[SVNSESSID]['givenname'] = 'Me';
        $_SESSION[SVNSESSID]['name'] = 'Too';
        include_once 'output.inc.php';
        
        $this->expectOutputString('<img src=\'./images/group.png\' border=\'0\' />  Groups<img src=\'./images/welcome.png\' border=\'0\' /> Welcome Me Too<img src=\'./images/user.png\' border=\'0\' />  Users<img src=\'./images/password.png\' border=\'0\' />  Password<img src=\'./images/password.png\' border=\'0\' />  Password policy<img src=\'./images/personal.png\' border=\'0\' />  General<img src=\'./images/service.png\' border=\'0\' />  Access denied<img src=\'./images/service.png\' border=\'0\' />  Database error<img src=\'./images/password.png\' border=\'0\' />  Permission denied<img src=\'./images/project.png\' border=\'0\' />  Projects<img src=\'./images/service.png\' border=\'0\' />  Repositories<img src=\'./images/password.png\' border=\'0\' />  Access rights<img src=\'./images/reports.png\' border=\'0\' />  Reports<img src=\'./images/macros.png\' border=\'0\' />  Preferences<img src=\'./images/search_large.png\' border=\'0\' />  Searchunknown tag: wrong');
        
        outputSubHeader('groups');
        outputSubHeader('main');
        outputSubHeader('users');
        outputSubHeader('password');
        outputSubHeader('password_policy');
        outputSubHeader('general');
        outputSubHeader('noadmin');
        outputSubHeader('dberror');
        outputSubHeader('nopermission');
        outputSubHeader('projects');
        outputSubHeader('repos');
        outputSubHeader('access');
        outputSubHeader('reports');
        outputSubHeader('preferences');
        outputSubHeader('search');
        outputSubHeader('wrong');
        
    }

    public function test_outputMenu() {

        
    }

    public function test_self_protect() {

        $_SERVER['PHP_SELF'] = 'output.inc.php';
        $_SESSION[SVNSESSID][USERNAME] = 'admin';
        
        include_once 'output.inc.php';
        
    }
    
}
?>