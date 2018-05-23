<?php

/**
 * test class for load config
 *
 * @author Thomas Krieger
 * @copyright 2018 Thomas Krieger. Allrights freserved
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
 *            @filesource
 */

/*
 *
 * $LastChangedDate: 2018-05-13 23:13:49 +0200 (Sun, 13 May 2018) $
 * $LastChangedBy: kriegeth $
 *
 * $Id: functionsTest.php 1187 2018-05-13 21:13:49Z kriegeth $
 *
 */
/**
 * class for tests of load_config
 */
final class LoadConfigTest extends PHPUnit_Framework_TestCase {

    /**
     * basic tests.
     * Load fiole and check if INSTALLBASE is set.
     */
    public function test_basic() {

        include "load_config.php";
        
        $this->assertTrue(defined('INSTALLBASE'));
        
    }
    
}

?>