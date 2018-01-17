<?php

/*
 *
 * $Id: outputTest.php 549 2018-01-17 09:55:50Z kriegeth $
 *
 */
final class OutputTest extends PHPUnit_Framework_TestCase {

    private function _execute(array $params = array()) {

        $_GET = $params;
        ob_start();
        include 'functions.php';
        return ob_get_clean();
    
    }

    public function test_outputHeader() {

       
        $_SESSION[SVNSESSID]['username'] = 'admin';
        include_once 'output.inc.php';
        
        $this->expectOutputString('<ul class=\'topmenu\'><li class=\'topmenu\'><a href=\'main.php\' alt=\'Home\'><img src=\'./images/gohome.png\' border=\'0\' /> Main menu</a></li><li class=\'topmenu\'><a href=\'logout.php\' alt=\'Logout\'><img src=\'./images/stop.png\' border=\'0\' />Logoff</a></li><li class=\'topmenu\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li><li><a href=\'help.php\' alt=\'help\' id=\'help\' target=\'_blank\'><img src=\'./images/help.png\' border=\'0\' />Help</a></li><li class=\'topmenu\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li><li><a href=\'doc/html/index.html#use\' alt=\'Documentation\' id=\'doc\' target=\'_blank\'><img src=\'./images/help.png\' border=\'0\' />Documentation</a></li></ul><div align=\'right\'><p>&nbsp;</p>Logged in as: admin</div>');
        outputHeader('anlage');
    
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

    
        /*
         * include_once 'output.inc.php';
         *
         * $this->expectOutputString(' <ul class=\'leftMenu\'>
         * <li class=\'leftMenu\'><a href=\'anlage.php\'>Anlage</a></li>
         * <li class=\'leftMenu\'><a href=\'wertentwicklung.php\'>Wertentwicklung</a></li>
         * <li class=\'leftMenu\'><a href=\'kurse.php\'>Kurse</a></li>
         * </ul>
         * ');
         * outputMenu('anlage');
         */
    }

    public function test_self_protect() {

        $_SERVER['PHP_SELF'] = 'output.inc.php';
        $_SESSION[SVNSESSID]['username'] = 'admin';
        
        include_once 'output.inc.php';
    
    }

}
?>