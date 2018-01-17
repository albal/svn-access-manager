<?php

final class MyDatabaseTest extends PHPUnit_Extensions_Database_TestCase {
    public $fixtures = array();
    private $conn = null;

    private function _get_include_contents($filename) {

        if (is_file($filename)) {
            ob_start();
            include $filename;
            return ob_get_clean();
        }
        return false;
    
    }

    public function getConnection() {
        
        // echo "get connection called\n";
        if ($this->conn === null) {
            try {
                $pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
                $this->conn = $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
            }
            catch ( PDOException $e ) {
                echo $e->getMessage();
            }
        }
        return $this->conn;
    
    }

    /**
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        
        // echo "get data set called\n";
        return $this->createMySQLXMLDataSet('./tests/files/fixture.xml');
    
    }

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
        
        foreach( $rowCounts as $table => $count) {
            $this->assertGreaterThanOrEqual($count, $this->getConnection()->getRowCount($table), "Pre-Condition");
        }
    
    }

    public function test_wrong_db_login() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], '4711', $GLOBALS['DB_DBNAME']);
        
        $this->assertNull($dbh);
    
    }

    public function testDatabaseFunctions() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        
        $date = $GLOBALS['DB_TEST_DATE'];
        $dbh = db_connect_test($GLOBALS['DB_TYPE'], $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_DBNAME']);
        
        db_log('test', 'test entry', $dbh);
        
        $this->assertEquals('admin', db_getUseridById(1, $dbh));
        $this->assertEquals(1, db_getIdByUserid('admin', $dbh));
        $this->assertEquals('write', db_getUserRightByUserid('admin', $dbh));
        
        $this->assertEquals('write', db_getGroupRightByGroupid(1, $dbh));
        
        $this->assertEquals('Test1', db_getRepoById(1, $dbh));
        $this->assertFalse(db_getRepoById(10, $dbh));
        $this->assertEquals(1, db_getRepoByName('Test1', $dbh));
        $this->assertFalse(db_getRepoByName('Test3', $dbh));
        
        $this->assertEquals('Test1', db_getProjectById(1, $dbh));
        $this->assertFalse(db_getProjectById(10, $dbh));
        
        $this->assertEquals('Tester', db_getGroupById(1, $dbh));
        $this->assertFalse(db_getGroupById(10, $dbh));
        
        $this->assertEquals('User admin', db_getRightName(1, $dbh));
        $this->assertEquals('undefined', db_getRightName(100, $dbh));
        
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
        
        $tDataArray = db_getGroupsForUser(2, $dbh);
        $tData = $tDataArray[0];       
        $this->assertArrayHasKey('description', $tData);
        $this->assertArrayHasKey('groupname', $tData);
        $this->assertEquals('Tester', $tData['groupname']);
        $this->assertEquals('Group for testers', $tData['description']);
        
        $tDataArray = db_getProjectResponsibleForUser(2, $dbh);
        $tData = $tDataArray[0];
        $this->assertEquals(1, count($tDataArray));
        $this->assertArrayHasKey('svnmodule', $tData);
        $this->assertArrayHasKey('reponame', $tData);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('Test1', $tData['reponame']);
        
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
              
        $tDataArray = db_getProjects(0, 5, $dbh);
        $tData = $tDataArray[0];
        $this->assertEquals('1', $tData['id']);
        $this->assertEquals('Test1', $tData['svnmodule']);
        $this->assertEquals('/', $tData['modulepath']);
        $this->assertEquals('Test1', $tData['reponame']);
        
        $this->assertEquals(2, db_getCountProjects($dbh));
        
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
        
        $CONF['page_size'] = 30;
        $CONF['user_sort_fields'] = 'name';
        $CONF['user_sort_order'] = 'ASC';
        
        $tData = db_get_preferences('admin', $dbh);
        $this->assertEquals(50, $tData['page_size']);
        $this->assertEquals('name,givenname',$tData['user_sort_fields']);
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

}
?>