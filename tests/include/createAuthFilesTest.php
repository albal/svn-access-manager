<?php

/*
 *
 * $Id: createAuthFilesTest.php 549 2018-01-17 09:55:50Z kriegeth $
 *
 */
final class CreateAuthFilesTest extends PHPUnit_Framework_TestCase {

    private function _execute(array $params = array()) {

        $_GET = $params;
        ob_start();
        include 'functions.php';
        return ob_get_clean();
    
    }

    public function test_createAuthUserFile() {

        include_once ('createAuthFiles.php');
        
        $dbh = db_connect_test('mysql', $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $ret = createAuthUserFile($dbh);
        
        $this->assertEquals(0, $ret['error']);
        $this->assertFileEquals('/tmp/svnpasswd', 'tests/files/svnpasswd');
        
        db_disconnect($dbh);
    
    }

    public function test_createAuthUserFilePerRepo() {

        include_once ('createAuthFiles.php');
        
        $dbh = db_connect_test('mysql', $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $ret = createAuthUserFilePerRepo($dbh);
        
        $this->assertEquals(0, $ret['error']);
        $this->assertFileEquals('/tmp/svn-passwd.Test1', 'tests/files/svn-passwd.Test1');
        $this->assertFileEquals('/tmp/svn-passwd.Test2', 'tests/files/svn-passwd.Test2');
        
        db_disconnect($dbh);
    
    }

    public function test_createAccessFile() {

        include_once ('createAuthFiles.php');
        
        $dbh = db_connect_test('mysql', $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $ret = createAccessFile($dbh);
        
        $this->assertEquals(0, $ret['error']);
        $this->assertFileEquals('/tmp/svnaccess', 'tests/files/svnaccess');
        
        db_disconnect($dbh);
    
    }

    public function test_createAccessFilPerRepo() {

        include_once ('createAuthFiles.php');
        
        $dbh = db_connect_test('mysql', $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $ret = createAccessFilePerRepo($dbh);
        
        $this->assertEquals(0, $ret['error']);
        $this->assertFileEquals('/tmp/svn-access.Test1', 'tests/files/svn-access.Test1');
        $this->assertFileEquals('/tmp/svn-access.Test2', 'tests/files/svn-access.Test2');
        
        db_disconnect($dbh);
    
    }

    public function test_create_files_without_write_permission() {

        include_once ('createAuthFiles.php');
        
        global $CONF;
        
        $CONF['SVNAccessFile'] = '/var/svnaccess';
        $CONF['AuthUserFile'] = '/var/svnpasswd';
        $CONF['ViewvcConf'] = '/var/viewvc-apache.conf';
        $CONF['ViewvcGroups'] = '/var/viewvc-groups';
        
        $dbh = db_connect_test('mysql', $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $ret = createAuthUserFile($dbh);
        $this->assertEquals(2, $ret['error']);
        
        $ret = createAuthUserFilePerRepo($dbh);
        $this->assertEquals(2, $ret['error']);
        
        $ret = createAccessFile($dbh);
        $this->assertEquals(1, $ret['error']);
        
        $ret = createAccessFilePerRepo($dbh);
        $this->assertEquals(1, $ret['error']);
        
        db_disconnect($dbh);
    
    }

}
?>
