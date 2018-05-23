<?php

/**
 * Tests for output functions
 *
 * lauthor Thomas Krieger
 *
 * @copyright 2018 Thomas Krieger. Allrights reeserved.
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
 * @filesource
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
 * Test class to test output functionality.
 *
 * @author Thomas Krieger
 * @copyright 2018 Thomas Krieger. All rights reserved.
 *           
 */
final class OutputTest extends PHPUnit_Framework_TestCase {

    /**
     * Test protection against direct call.
     */
    public function test_self_protect() {

        $_SERVER['PHP_SELF'] = 'output.inc.php';
        $_SESSION[SVNSESSID][USERNAME] = 'admin';
        
        include_once 'output.inc.php';
        
    }
    
}
?>