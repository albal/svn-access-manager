<?php

final class TemplatesTest extends PHPUnit_Framework_TestCase {

    private function _get_include_contents($filename) {

        $ret = false;
        if (is_file($filename)) {
            ob_start();
            include $filename;
            $ret = ob_get_clean();
        }
        return $ret;
    
    }

    public function test_template_general() {

        require_once ('constants.inc.php');
        require_once ('db-functions-adodb.inc.php');
        require_once ('functions.inc.php');
        include_once ('output.inc.php');
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