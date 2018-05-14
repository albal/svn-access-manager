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
final class TemplatesTest extends PHPUnit_Framework_TestCase {

    public function test_template_general() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        include_once ('output.inc.php');
        include_once ('variables.inc.php');
        require_once ('HTML5Validate.php');
        
        $template = "general.tpl";
        $header = GENERAL;
        $subheader = GENERAL;
        $menu = GENERAL;
        
        $tUserid = 'admin';
        $tGivenname = 'Tom';
        $tName = 'Tester';
        $tEmail = 'tom.tester@example.com';
        $tSecurityQuestion = 'Security question';
        $tAnswer = 'answer';
        $tPwModified = '02.02.2018';
        $tPasswordExpires = '31.12.2018';
        $tLocked = 'no';
        $tGroups = array();
        $tProjects = array();
        $tAccessRights = array();
        $tCustom1 = '+49 69 12345678';
        $tCustom2 = '+49 170 2746535';
        $tCustom3 = 'IT Development';
        $tMessage = 'Nothing to say ;)';
        $tBuildInfo = '2018-01-02 11:11:11 4711';
        
        ob_start();
        $_SESSION['SVNSESSID']['username'] = 'admin';
        $_SESSION['SVNSESSID']['admin'] = 'n';
        include './templates/framework.tpl';
        $output = ob_get_clean();
        
        $this->assertContains('<form name="general" method="post">', $output);
        $this->assertContains('+49 69 12345678', $output);
        $this->assertContains('+49 170 2746535', $output);
        $this->assertContains('IT Development', $output);
        
    }
    
}

?>