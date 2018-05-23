<?php

/**
 * add a group to a project
 *
 * @author Thomas Krieger
 * @copyright 2018 Thomas Krieger. All rights reserved.
 *           
 *            SVN Access Manager - a subversion access rights management tool
 *            Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>
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
 */
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");

require_once ("$installBase/include/functions.inc.php");
include_once ("$installBase/include/constants.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();
check_password_expired();

$_SESSION['svn_sessid']['helptopic'] = "addgrouptoproject";

/**
 * add a group to a project
 * 
 * @param array $currentMembers
 * @param resource $dbh
 */
function addGroupToProject($currentMembers, $dbh) {

    global $CONF;
    
    $installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";
    $schema = db_determine_schema();
    
    $cs_array = array();
    
    foreach( $currentMembers as $groupid => $name ) {
        
        $cs_array[] = $groupid;
    }
    
    $tGroups = array();
    $query = "SELECT * " . "  FROM " . $schema . "svngroups " . " WHERE (deleted = '00000000000000') " . "ORDER BY groupname ASC";
    $result = db_query($query, $dbh);
    
    while ( $row = db_assoc($result['result']) ) {
        
        $name = $row['groupname'];
        $groupid = $row['id'];
        
        if (! in_array($groupid, $cs_array)) {
            
            $tGroups[$groupid] = $name;
        }
    }
    
    $tMessage = "";
    $header = PROJECT;
    $subheader = PROJECT;
    $menu = PROJECT;
    $template = "addGroupToProject.tpl";
    
    include ("$installBase/templates/framework.tpl");
    
}
?>
