<?php

/*
 *
 * $Id$
 *
 */
final class CheckSessionTest extends PHPUnit_Framework_TestCase {

    private function _execute(array $params = array()) {

        $_GET = $params;
        ob_start();
        include 'functions.php';
        return ob_get_clean();
    
    }

    public function test_checkSession() {

        include_once ('./checkSession.php');
        
        $this->expectOutputString('0');
    
    }

}
?>