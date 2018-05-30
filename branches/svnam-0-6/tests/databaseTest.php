<?php

/**
 *
 * Test MariDB database functionality
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
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
 *
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
/**
 * Class for tests of MariaDB functionality
 */
final class MyDatabaseTest extends PHPUnit_Extensions_Database_TestCase {
    /**
     * fixtures for db load
     */
    public $fixtures = array();
    /**
     * database connection
     */
    private $conn = null;

    /**
     * constructor
     */
    public function __construct() {

        
    }

    /**
     * get a database connection
     *
     * @return resource
     */
    public function getConnection() {

        if ($this->conn === null) {
            try {
                $pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
                $this->conn = $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
            }
            catch ( PDOException $e ) {
                $msg = $e->getMessage();
                echo "Error in MySQL DB connect: " . $msg . "\n";
            }
        }
        return $this->conn;
        
    }

    /**
     * load database data
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {

        return $this->createMySQLXMLDataSet('./tests/files/fixture.xml');
        
    }

    /**
     * database login
     *
     * @return resource
     */
    public function databaseLogin() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        return db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
    }

    /**
     * check if row count in tables is correct
     */
    public function testRowCounts() {

        $rowCounts = array();
        $rowCounts['help'] = 39;
        $rowCounts['log'] = 34;
        $rowCounts['preferences'] = 0;
        $rowCounts['rights'] = 7;
        $rowCounts['sessions'] = 1;
        $rowCounts['svngroups'] = 1;
        $rowCounts['svnmailinglists'] = 0;
        $rowCounts['svnpasswordreset'] = 0;
        $rowCounts['svnprojects'] = 2;
        $rowCounts['svnrepos'] = 2;
        $rowCounts['svnusers'] = 3;
        $rowCounts['svn_access_rights'] = 3;
        $rowCounts['svn_groups_responsible'] = 0;
        $rowCounts['svn_projects_mailinglists'] = 0;
        $rowCounts['svn_projects_responsible'] = 2;
        $rowCounts['svn_users_groups'] = 2;
        $rowCounts['users_rights'] = 21;
        $rowCounts['workinfo'] = 3;
        
        foreach( $rowCounts as $table => $count ) {
            $this->assertGreaterThanOrEqual($count, $this->getConnection()->getRowCount($table), "Pre-Condition");
        }
        
    }

    /**
     * test database functionality
     */
    public function testDatabaseFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $date = $GLOBALS['DB_TEST_DATE'];
        $dbh = $this->databaseLogin();
        
        $this->assertTrue(db_check_global_admin('admin', $dbh));
        $this->assertFalse(db_check_global_admin('test1', $dbh));
        $this->assertFalse(db_check_global_admin('test9', $dbh));
        
        $this->assertTrue(db_check_global_admin_by_id(1, $dbh));
        $this->assertFalse(db_check_global_admin_by_id(2, $dbh));
        $this->assertFalse(db_check_global_admin_by_id(9, $dbh));
        
        $this->assertEquals('delete', db_check_acl('admin', 'User admin', $dbh));
        $this->assertEquals('delete', db_check_acl('admin', 'Group admin', $dbh));
        $this->assertEquals('none', db_check_acl('test1', 'Group admin', $dbh));
        
        $tDataArray = db_check_group_acl('test1', $dbh);
        $this->assertEquals(0, count($tDataArray));
        
        $tDataArray = db_check_group_acl('test2', $dbh);
        $this->assertEquals(0, count($tDataArray));
        
        $tDataArray = db_check_group_acl('admin', $dbh);
        $this->assertEquals(0, count($tDataArray));
        
        $CONF[PAGESIZE] = 30;
        $CONF['user_sort_fields'] = 'name';
        $CONF['user_sort_order'] = 'ASC';
        
        $tData = db_get_preferences('admin', $dbh);
        $this->assertEquals(50, $tData[PAGESIZE]);
        $this->assertEquals('name,givenname', $tData['user_sort_fields']);
        $this->assertEquals('ASC', $tData['user_sort_order']);
        
        $this->assertEquals('SELECT * FROM test;', db_escape_string("SELECT * FROM test;", $dbh));
        
        $this->assertEquals('', db_determine_schema());
        
        $this->assertTrue(db_set_semaphore('test', 'test', $dbh));
        $this->assertFalse(db_get_semaphore('test1', 'test', $dbh));
        $this->assertTrue(db_get_semaphore('test', 'test', $dbh));
        $this->assertFalse(db_unset_semaphore('test1', 'test', $dbh));
        $this->assertTrue(db_unset_semaphore('test', 'test', $dbh));
        
        db_disconnect($dbh);
        
    }

    /**
     * test database functions regarding users
     */
    public function testDatabaseUserFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $this->assertEquals('admin', db_getUseridById(1, $dbh));
        $this->assertEquals(1, db_getIdByUserid('admin', $dbh));
        $this->assertEquals('write', db_getUserRightByUserid('admin', $dbh));
        
        $tDataArray = db_getUsers(0, 5, $dbh);
        $cnt = count($tDataArray);
        $userEntry = db_getUserData(1, $dbh);
        $this->assertEquals('admin', $userEntry['userid']);
        $this->assertEquals('Admin', $userEntry['name']);
        $this->assertEquals('Secret', $userEntry['givenname']);
        
        $tDataArray = db_getLockedUsers(0, 10, $dbh);
        $cntArray = count($tDataArray);
        $cnt = db_getCountLockedUsers($dbh);
        $this->assertEquals(0, $cnt);
        $this->assertEquals($cnt, $cntArray);
        
        db_disconnect($dbh);
        
    }

    /**
     * test database functions regarding groups
     */
    public function testDatabaseGroupFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $tDataArray = db_getGroupsForUser(2, $dbh);
        $tData = $tDataArray[0];
        $this->assertArrayHasKey('description', $tData);
        $this->assertArrayHasKey('groupname', $tData);
        $this->assertEquals('Tester', $tData['groupname']);
        $this->assertEquals('Group for testers', $tData['description']);
        
        $this->assertEquals('write', db_getGroupRightByGroupid(1, $dbh));
        
        $this->assertEquals('Tester', db_getGroupById(1, $dbh));
        $this->assertFalse(db_getGroupById(10, $dbh));
        
        $tDataArray = db_getGroupList(0, 10, $dbh);
        $cnt = count($tDataArray);
        $tData = $tDataArray[0];
        $this->assertEquals('Tester', $tData['groupname']);
        $this->assertEquals('Group for testers', $tData['description']);
        $this->assertEquals(1, $cnt);
        
        $tDataArray = db_getGroups(0, 5, $dbh);
        $this->assertEquals(0, count($tDataArray));
        
        $this->assertEquals(0, db_getCountGroups($dbh));
        
        $tGroupsAllowed = array();
        $tDataArray = db_getGroupsAllowed(0, 5, 0, $tGroupsAllowed, $dbh);
        $tData = $tDataArray[0];
        $this->assertEquals('1', $tData['id']);
        $this->assertEquals('Tester', $tData['groupname']);
        $this->assertEquals('Group for testers', $tData['description']);
        $this->assertEquals(1, db_getCountGroupsAllowed(0, $tGroupsAllowed, $dbh));
        
        $tGroups = db_getGroupsForUser(2, $dbh);
        $tDataArray = db_getAccessRightsForUser(2, $tGroups, $dbh);
        $tData = $tDataArray[0];
        $this->assertArrayHasKey('svnmodule', $tData);
        $this->assertArrayHasKey('modulepath', $tData);
        $this->assertArrayHasKey('reponame', $tData);
        $this->assertArrayHasKey('path', $tData);
        $this->assertArrayHasKey('user_id', $tData);
        $this->assertArrayHasKey('group_id', $tData);
        $this->assertArrayHasKey('access_by', $tData);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        $this->assertEquals('Test1', $tData['reponame']);
        $this->assertEquals('/', $tData['path']);
        $this->assertEquals('0', $tData['user_id']);
        $this->assertEquals('1', $tData['group_id']);
        $this->assertEquals('write', $tData['access_right']);
        $this->assertEquals('1', $tData['repo_id']);
        $this->assertEquals('group id', $tData['access_by']);
        
        $tData = $tDataArray[1];
        $this->assertArrayHasKey('svnmodule', $tData);
        $this->assertArrayHasKey('modulepath', $tData);
        $this->assertArrayHasKey('reponame', $tData);
        $this->assertArrayHasKey('path', $tData);
        $this->assertArrayHasKey('user_id', $tData);
        $this->assertArrayHasKey('group_id', $tData);
        $this->assertArrayHasKey('access_by', $tData);
        $this->assertEquals('Test2', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        $this->assertEquals('Test2', $tData['reponame']);
        $this->assertEquals('/', $tData['path']);
        $this->assertEquals('0', $tData['user_id']);
        $this->assertEquals('1', $tData['group_id']);
        $this->assertEquals('write', $tData['access_right']);
        $this->assertEquals('2', $tData['repo_id']);
        $this->assertEquals('group id', $tData['access_by']);
        
        $groupEntry = db_getGroupData(1, $dbh);
        $this->assertEquals('Tester', $groupEntry['groupname']);
        $this->assertEquals('Group for testers', $groupEntry['description']);
        
        $tDataArray = db_getUsersForGroup(1, $dbh);
        $this->assertEquals(2, count($tDataArray));
        
        $tData = $tDataArray[0];
        $this->assertEquals('test1', $tData['userid']);
        $this->assertEquals('Tester1', $tData['name']);
        $this->assertEquals('Tester1', $tData['givenname']);
        
        $tDataArray = db_getGroupAdminsForGroup(1, $dbh);
        $this->assertEquals(0, count($tDataArray));
        
        db_disconnect($dbh);
        
    }

    /**
     * test database functions regarding projects
     */
    public function testDatabaseProjectFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $this->assertEquals('Test1', db_getProjectById(1, $dbh));
        $this->assertFalse(db_getProjectById(10, $dbh));
        
        $tDataArray = db_getProjects(0, 5, $dbh);
        $tData = $tDataArray[0];
        $this->assertEquals('1', $tData['id']);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        $this->assertEquals('Test1', $tData['reponame']);
        
        $this->assertEquals(2, db_getCountProjects($dbh));
        
        $tDataArray = db_getProjectResponsibleForUser(2, $dbh);
        $tData = $tDataArray[0];
        $this->assertEquals(1, count($tDataArray));
        $this->assertArrayHasKey('svnmodule', $tData);
        $this->assertArrayHasKey('reponame', $tData);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('Test1', $tData['reponame']);
        
        db_disconnect($dbh);
        
    }

    /**
     * test database functions regarding repositories
     */
    public function testDatabaseRepoFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $this->assertEquals('Test1', db_getRepoById(1, $dbh));
        $this->assertFalse(db_getRepoById(10, $dbh));
        $this->assertEquals(1, db_getRepoByName('Test1', $dbh));
        $this->assertFalse(db_getRepoByName('Test3', $dbh));
        
        db_disconnect($dbh);
        
    }

    /**
     * test database functions regarding logging
     */
    public function testDatabaseLogFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        db_log('test', 'test entry', $dbh);
        
        $tDataArray = db_getLog(0, 5, $dbh);
        $tData = $tDataArray[0];
        $cntArray = count($tDataArray);
        $cnt = db_getCountLog($dbh);
        $this->assertEquals(35, $cnt);
        $this->assertEquals(5, $cntArray);
        $this->assertEquals('test', $tData['username']);
        $this->assertEquals('127.0.0.1', $tData['ipaddress']);
        $this->assertEquals('test entry', $tData['logmessage']);
        
        db_disconnect($dbh);
        
    }

    /**
     * test database functions regarding access rights
     */
    public function testDatabaseAccessRightsFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $this->assertEquals('User admin', db_getRightName(1, $dbh));
        $this->assertEquals('undefined', db_getRightName(100, $dbh));
        
        $tDataArray = db_getAccessRights('Tester1', 0, 5, $dbh);
        $tData = $tDataArray[0];
        $this->assertArrayHasKey('svnmodule', $tData);
        $this->assertArrayHasKey('modulepath', $tData);
        $this->assertArrayHasKey('repopath', $tData);
        $this->assertEquals('1', $tData['id']);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        $this->assertEquals('Test1', $tData['reponame']);
        $this->assertEquals('00000000', $tData['valid_from']);
        $this->assertEquals('99999999', $tData['valid_until']);
        $this->assertEquals('/', $tData['path']);
        $this->assertEquals('write', $tData['access_right']);
        $this->assertEquals('yes', $tData['recursive']);
        $this->assertEquals('0', $tData['user_id']);
        $this->assertEquals('1', $tData['group_id']);
        $this->assertEquals('file:///svn/repos/test1', $tData['repopath']);
        $this->assertEquals('Tester', $tData['groupname']);
        $this->assertEquals('', $tData['username']);
        
        $tDataArray = db_getAccessRights('test1', 0, 5, $dbh);
        $tData = $tDataArray[0];
        $this->assertArrayHasKey('svnmodule', $tData);
        $this->assertArrayHasKey('modulepath', $tData);
        $this->assertArrayHasKey('repopath', $tData);
        $this->assertEquals('1', $tData['id']);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        $this->assertEquals('Test1', $tData['reponame']);
        $this->assertEquals('00000000', $tData['valid_from']);
        $this->assertEquals('99999999', $tData['valid_until']);
        $this->assertEquals('/', $tData['path']);
        $this->assertEquals('write', $tData['access_right']);
        $this->assertEquals('yes', $tData['recursive']);
        $this->assertEquals('0', $tData['user_id']);
        $this->assertEquals('1', $tData['group_id']);
        $this->assertEquals('file:///svn/repos/test1', $tData['repopath']);
        $this->assertEquals('Tester', $tData['groupname']);
        $this->assertEquals('', $tData['username']);
        
        $this->assertEquals(3, db_getCountAccessRights('Tester1', $dbh));
        $this->assertEquals(2, db_getCountAccessRights('test1', $dbh));
        
        $this->assertFalse(db_getRightData(100, $dbh));
        $tData = db_getRightData(1, $dbh);
        $this->assertArrayHasKey('project_id', $tData);
        $this->assertArrayHasKey('group_id', $tData);
        $this->assertArrayHasKey('user_id', $tData);
        $this->assertArrayHasKey('repo_id', $tData);
        $this->assertArrayHasKey('access_right', $tData);
        $this->assertEquals(1, $tData['project_id']);
        $this->assertEquals(1, $tData['group_id']);
        $this->assertEquals(0, $tData['user_id']);
        $this->assertEquals(1, $tData['repo_id']);
        $this->assertEquals('write', $tData['access_right']);
        
        $tDataArray = db_getAccessRightsForGroup(1, $dbh);
        $this->assertEquals(2, count($tDataArray));
        
        $tDataArray = db_getGrantedRights(0, 5, $dbh);
        $cnt = db_getCountGrantedRights($dbh);
        $this->assertEquals(3, $cnt);
        $tData = $tDataArray[0];
        $this->assertEquals('admin', $tData['userid']);
        $this->assertEquals('Secret Admin', $tData['name']);
        
        $tDataArray = db_getAccessRightsList($GLOBALS['DB_TEST_DATE'], 0, 5, $dbh);
        $cnt = db_getCountAccessRightsList($GLOBALS['DB_TEST_DATE'], $dbh);
        $this->assertEquals(3, $cnt);
        $tData = $tDataArray[0];
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        
        db_disconnect($dbh);
        
    }

    /**
     * test wrong login
     */
    public function test_wrong_db_login() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], '4711', $GLOBALS['DB_DBNAME']);
        
        $this->assertNull($dbh);
        
    }

    /**
     * test main template functionality
     */
    public function test_template_main() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        include_once ('output.inc.php');
        include_once ('variables.inc.php');
        require_once ('HTML5Validate.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        $tMessage = 'Nothing to report';
        $tMessageType = 'info';
        $_SESSION[SVNSESSID]['username'] = 'admin';
        $_SESSION[SVNSESSID]['admin'] = 'y';
        $tUserMessages = array();
        $tUsername = isset($_SESSION[SVNSESSID]['username']) ? $_SESSION[SVNSESSID]['username'] : 'undefined';
        $tAdmin = isset($_SESSION[SVNSESSID]['admin']) ? $_SESSION[SVNSESSID]['admin'] : 'n';
        $rightUserAdmin = db_check_acl($tUsername, 'User admin', $dbh);
        $rightGroupAdmin = db_check_acl($tUsername, 'Group admin', $dbh);
        $rightProjectAdmin = db_check_acl($tUsername, 'Project admin', $dbh);
        $rightRepositoryAdmin = db_check_acl($tUsername, 'Repository admin', $dbh);
        $rightAccessRightAdmin = db_check_acl($tUsername, 'Access rights admin', $dbh);
        $rightCreateFiles = db_check_acl($tUsername, 'Create files', $dbh);
        $rightReports = db_check_acl($tUsername, 'Reports', $dbh);
        $tGroupsAllowed = db_check_group_acl($tUsername, $dbh);
        $tUserMessages = db_getMessagesShort($dbh);
        $tStats = db_getStatistics($dbh);
        
        $CONF = array();
        $CONF[PAGESIZE] = 30;
        $CONF[TOOLTIP_SHOW] = 700;
        $CONF[TOOLTIP_HIDE] = 300;
        $CONF['database_type'] = 'mysqli';
        $CONF['database_host'] = 'localhost';
        $CONF['database_user'] = 'svnam';
        $CONF['database_name'] = 'svnam';
        
        ob_start();
        include './templates/main.tpl';
        $output = ob_get_clean();
        
        $this->assertContains('General functions', $output);
        
        $_SESSION[SVNSESSID]['username'] = 'admin';
        $_SESSION[SVNSESSID]['admin'] = 'y';
        
        ob_start();
        include './templates/main.tpl';
        $output = ob_get_clean();
        
        $this->assertContains('Administration', $output);
        $this->assertContains('Reports', $output);
        
        db_disconnect($dbh);
        
    }
    
}
?>