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

final class FunctionsTest extends PHPUnit_Framework_TestCase {

    private function _execute(array $params = array()) {

        $_GET = $params;
        ob_start();
        include 'functions.php';
        return ob_get_clean();
    
    }

    public function testcheck_string() {

        include_once 'functions.inc.php';
        $this->assertEquals(true, check_string("AGBVFG5654rtref"));
        $this->assertEquals(false, check_string("AGBVFG565\><4rtref"));
    
    }

    public function testcheck_date() {

        include_once 'functions.inc.php';
        
        $this->assertTrue(check_date('12', '12', '2017'));
        $this->assertFalse(check_date('30', '02', '2017'));
        $this->assertFalse(check_date('3a', '03', '2017'));
        $this->assertFalse(check_date('28', 'a3', '2017'));
        $this->assertFalse(check_date('24', '03', '2a17'));
    
    }

    public function testsplitdate() {

        include_once 'functions.inc.php';
        
        $this->assertEquals('11.12.2017', splitdate('20171211'));
    
    }

    public function testGetPhpVersion() {

        $this->assertGreaterThan('50', getPhpVersion());
    
    }

    public function testSplitDateTime() {

        list($date, $time ) = splitDateTime('20160302101112');
        $this->assertEquals('02.03.2016', $date);
        $this->assertEquals('10:11:12', $time);
    
    }

    public function testSplitValiddate() {

        include_once 'functions.inc.php';
        
        $this->assertEquals('11.12.2017', splitValidDate('20171211'));
    
    }

    public function testMkUnixTimestampFromDateTime() {

        $this->assertEquals(1456909872, mkUnixTimestampFromDateTime('20160302101112'));
    
    }

    public function testEncodeHeader() {

        $this->assertEquals('Huijuijui', encode_header('Huijuijui', 'iso-8859-15'));
    
    }

    public function testNoMagicQuotes() {

        $this->assertEquals('SELECT * FROM `eurokurse` WHERE kurs like \%;', no_magic_quotes('SELECT * FROM `eurokurse` WHERE kurs like \%;'));
    
    }

    public function test_getLocale() {

        include_once 'functions.inc.php';
        
        $this->assertEquals('en_US', get_locale());
    
    }

    public function test_initialize_i18n() {

        include_once 'functions.inc.php';
        
        $this->assertNull(initialize_i18n());
    
    }

    public function test_checkLanguage() {

        include_once 'functions.inc.php';
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de_DE';
        $this->assertEquals('de', check_language());
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en_US';
        $this->assertEquals('en', check_language());
    
    }

    public function test_getDateJhjjmmtt() {

        $this->assertEquals(date('Ymd'), getDateJhjjmmtt());
    
    }

    public function test_no_magic_quotes() {

        $this->assertEquals('SELECT * FROM test;', no_magic_quotes('SELECT * FROM test;'));
    
    }

    public function test_digestcrypt() {

        $this->assertEquals('81d477272475b5bcf5fe5659c7b1d05d', digestcrypt('admin', 'huibuh', '1234567890'));
    
    }

    public function test_checkPasswordPolicy() {

        $this->assertEquals(1, checkPasswordPolicy('Start!1234567_', 'y'));
        $this->assertEquals(0, checkPasswordPolicy('123jhy456jfhg7', 'y'));
        $this->assertEquals(0, checkPasswordPolicy('Start!123', 'y'));
        
        $this->assertEquals(1, checkPasswordPolicy('Start!12', 'n'));
        $this->assertEquals(0, checkPasswordPolicy('123jhy45', 'n'));
        $this->assertEquals(0, checkPasswordPolicy('Start!', 'n'));
    
    }

    public function test_check_email() {

        $this->assertTrue(check_email('q@q.com'));
        $this->assertFalse(check_email('q@q.c;om'));
    
    }

    public function test_generate_password() {

        $pw = generate_password();
        $len = strlen($pw);
        $this->assertEquals(8, $len);
    
    }

    public function test_generatePassword() {

        $pw = generatePassword('y');
        $len = strlen($pw);
        $this->assertEquals(14, $len);
        
        $pw = generatePassword('n');
        $len = strlen($pw);
        $this->assertEquals(8, $len);
    
    }

}

?>