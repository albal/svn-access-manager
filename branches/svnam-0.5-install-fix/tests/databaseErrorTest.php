<?php

/*
 *
 * $Id$
 *
 */
final class DatabaseErrorTest extends PHPUnit_Framework_TestCase {

    private function _execute(array $params = array()) {

        $_GET = $params;
        ob_start();
        include 'functions.php';
        return ob_get_clean();
    
    }

    public function test_database_error_install() {

        $_GET['dbquery'] = 'SELECT * FROM test;';
        $_GET['dberror'] = 'Test error';
        $_GET['dbfunction'] = 'Test function';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $tMessage = 'nothing to say';
        
        ob_start();
        include ('database_error_install.php');
        $output = ob_get_clean();
        
        $this->assertContains('SELECT * FROM test;', $output);
        $this->assertContains('Test error', $output);
        $this->assertContains('Test function', $output);
    
    }

}
?>