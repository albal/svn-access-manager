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
final class CreateAuthFilesTest extends PHPUnit_Framework_TestCase {

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
