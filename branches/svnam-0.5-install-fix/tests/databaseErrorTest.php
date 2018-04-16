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
final class DatabaseErrorTest extends PHPUnit_Framework_TestCase {

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