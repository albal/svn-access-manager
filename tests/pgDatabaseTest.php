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
 * $LastChangedDate: 2018-01-24 13:32:49 +0100 (Wed, 24 Jan 2018) $
 * $LastChangedBy: kriegeth $
 *
 * $Id: databaseTest.php 775 2018-01-24 12:32:49Z kriegeth $
 *
 */
final class PgDatabaseTest extends PHPUnit_Extensions_Database_TestCase {
    public $fixtures = array();
    private $conn = null;
    
    /**
     *
     * @var array
     */
    private $databaseTables = array(
            'help',
            'log',
            'preferences',
            'rights',
            'workinfo',
            'sessions',
            'svngroups',
            'svnprojects',
            'svnusers',
            'svn_access_rights',
            'svn_groups_responsible',
            'svn_projects_mailinglists',
            'svn_projects_responsible',
            'svn_users_groups',
            'svnmailinglists',
            'svnpasswordreset',            
            'svnrepos',           
            'users_rights'          
    );

    public function __construct() {

        //echo "constructor of pgDatabaseTest\n";
    
    }

    private function _get_include_contents($filename) {

        if (is_file($filename)) {
            ob_start();
            include $filename;
            return ob_get_clean();
        }
        return false;
    
    }

    public function getConnection() {

        //echo "get connection for postgres called\n";
        if ($this->conn === null) {
            try {
                // $pdo = new PDO($GLOBALS['PG_DB_DSN'], $GLOBALS['PG_DB_USER'], $GLOBALS['PG_DB_PASSWD']);
                $pdo = new PDO($GLOBALS['PG_DB_DSN']);
                $this->conn = $this->createDefaultDBConnection($pdo, $GLOBALS['PG_DB_DBNAME']);
            }
            catch ( PDOException $e ) {
                $err = $e->getMessage();
                echo "DB connect error: " . $err . "\n";
            }
        }
        return $this->conn;
    
    }

    /**
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function old_getDataSet() {
        
        //echo "get data set for postgres called\n";
        try {
            $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet(';');
            foreach( $this->databaseTables as $table) {
                
                //echo "importing table " . $table . "\n";
                $dataSet->addTable($table, "./tests/files/pg/" . $table . ".csv");
            }
        }
        catch ( PDOException $e ) {
            $err = $e->getMessage();
            echo "CSV import error: " . $err . "\n";
        }
        
        return $dataSet;
    
    }
    
    public function getDataSet() {
        
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
        
        $dbh = db_connect_test($GLOBALS['PG_DB_TYPE'], $GLOBALS['PG_DB_HOST'], $GLOBALS['PG_DB_USER'], '4711', $GLOBALS['PG_DB_DBNAME']);
        
        $this->assertNull($dbh);
    
    }

}
?>