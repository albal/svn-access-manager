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
if (preg_match("/output.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

function outputHeader($area) {

    $tUsername = isset($_SESSION[SVNSESSID]['username']) ? $_SESSION[SVNSESSID]['username'] : "undefined";
    
    print "<ul class='topmenu'>";
    print "<li class='topmenu'><a href='main.php' alt='Home'><img src='./images/gohome.png' border='0' /> " . _("Main menu") . "</a></li>";
    print "<li class='topmenu'><a href='logout.php' alt='Logout'><img src='./images/stop.png' border='0' />" . _("Logoff") . "</a></li>";
    print "<li class='topmenu'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>";
    print "<li><a href='help.php' alt='help' id='help' target='_blank'><img src='./images/help.png' border='0' />" . _("Help") . "</a></li>";
    print "<li class='topmenu'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>";
    print "<li><a href='doc/html/index.html#use' alt='" . _("Documentation") . "' id='doc' target='_blank'><img src='./images/help.png' border='0' />" . _("Documentation") . "</a></li>";
    print "</ul>";
    print "<div align='right'><p>&nbsp;</p>" . _("Logged in as") . ": " . $tUsername . "</div>";
    
}

function outputSubHeader($area) {

    if (strtolower($area) == "groups") {
        
        print "<img src='./images/group.png' border='0' />  " . _("Groups");
    }
    elseif (strtolower($area) == "main") {
        
        print "<img src='./images/welcome.png' border='0' /> " . sprintf(_("Welcome %s"), $_SESSION[SVNSESSID]['givenname'] . " " . $_SESSION[SVNSESSID]['name']);
    }
    elseif (strtolower($area) == "users") {
        
        print "<img src='./images/user.png' border='0' />  " . _("Users");
    }
    elseif (strtolower($area) == "password") {
        
        print "<img src='./images/password.png' border='0' />  " . _("Password");
    }
    elseif (strtolower($area) == "password_policy") {
        
        print "<img src='./images/password.png' border='0' />  " . _("Password policy");
    }
    elseif (strtolower($area) == "general") {
        
        print "<img src='./images/personal.png' border='0' />  " . _("General");
    }
    elseif (strtolower($area) == "noadmin") {
        
        print "<img src='./images/service.png' border='0' />  " . _("Access denied");
    }
    elseif (strtolower($area) == "dberror") {
        
        print "<img src='./images/service.png' border='0' />  " . _("Database error");
    }
    elseif (strtolower($area) == "nopermission") {
        
        print "<img src='./images/password.png' border='0' />  " . _("Permission denied");
    }
    elseif (strtolower($area) == "projects") {
        
        print "<img src='./images/project.png' border='0' />  " . _("Projects");
    }
    elseif (strtolower($area) == "repos") {
        
        print "<img src='./images/service.png' border='0' />  " . _("Repositories");
    }
    elseif (strtolower($area) == "access") {
        
        print "<img src='./images/password.png' border='0' />  " . _("Access rights");
    }
    elseif (strtolower($area) == "reports") {
        
        print "<img src='./images/reports.png' border='0' />  " . _("Reports");
    }
    elseif (strtolower($area) == "preferences") {
        
        print "<img src='./images/macros.png' border='0' />  " . _("Preferences");
    }
    elseif (strtolower($area) == "search") {
        
        print "<img src='./images/search_large.png' border='0' />  " . _("Search");
    }
    else {
        
        print "unknown tag: $area";
    }
    
}

function outputAdminMenu($tAdmin, $rightUserAdmin, $rightGroupAdmin, $rightProjectAdmin, $rightRepositoryAdmin, $rightAccessRightAdmin, $tGroupsAllowed, $rightCreateFiles) {

    global $CONF;
    global $_SESSION;
    
    if (($tAdmin == "p") || ($rightUserAdmin != "none") || ($rightGroupAdmin != "none") || ($rightProjectAdmin != "none") || ($rightRepositoryAdmin != "none") || ($rightAccessRightAdmin != "none") || (count($tGroupsAllowed) > 0) || ($rightCreateFiles != "none")) {
        
        print "\t\t\t\t<p>&nbsp;</p>";
        print "\t\t\t\t<p>&nbsp;</p>";
        print "\t\t\t\t<h3>" . _("Administration") . "</h3>\n";
        print "\t\t\t\t<ul class='leftMenu'>\n";
    }
    
    if ($rightUserAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_users.php\">" . _("Users") . "</a></li>\n";
        
        if (isset($CONF['use_ldap']) and (strtoupper($CONF['use_ldap']) == "YES")) {
            
            print "\t\t\t\t\t<li class='leftMenu'><a href=\"bulk_add_ldap_users.php\">" . _("Bulk add LDAP users") . "</a></li>\n";
        }
    }
    
    if ($rightGroupAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_groups.php\">" . _("Groups") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_group_admins.php\">" . _("Group administrators") . "</a></li>\n";
    }
    elseif (count($tGroupsAllowed) > 0) {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_groups.php\">" . _("Groups") . "</a></li>\n";
    }
    
    if ($rightRepositoryAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_repos.php\">" . _("Repositories") . "</a></li>\n";
    }
    
    if ($rightProjectAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_projects.php\">" . _("Projects") . "</a></li>\n";
    }
    
    if (($rightAccessRightAdmin != "none") || ($tAdmin == "p") || ($tAdmin == "y")) {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"list_access_rights.php\">" . _("Repository access rights") . "</a></li>\n";
    }
    
    if ($rightCreateFiles != "none") {
        
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"createAccessFiles.php\">" . _("Create access files") . "</a></li>\n";
    }
    
    if (($tAdmin == "p") || ($rightUserAdmin != "none") || ($rightGroupAdmin != "none") || ($rightProjectAdmin != "none") || ($rightRepositoryAdmin != "none") || ($rightAccessRightAdmin != "none") || (count($tGroupsAllowed) > 0) || ($rightCreateFiles != "none")) {
        
        print "\t\t\t\t</ul>\n";
    }
    
}

function outputReportsMenu($rightReports) {

    global $CONF;
    global $_SESSION;
    
    if ($rightReports != "none") {
        
        print "\t\t\t\t<p>&nbsp;</p>";
        print "\t\t\t\t<p>&nbsp;</p>";
        print "\t\t\t\t<h3>" . _("Reports") . "</h3>\n";
        print "\t\t\t\t<ul class='leftMenu'>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"rep_access_rights.php\">" . _("Repository access rights") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"rep_log.php\">" . _("Log") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"rep_locked_users.php\">" . _("Locked users") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"rep_granted_user_rights.php\">" . _("Granted user rights") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"rep_show_user.php\">" . _("Show user") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'><a href=\"rep_show_group.php\">" . _("Show group") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftMenu'>&nbsp;</li>\n";
        print "\t\t\t\t</ul>\n";
    }
    
}

function outputMenu($area) {

    global $CONF;
    global $_SESSION;
    
    print "\t\t\t\t<h3>" . _("My account") . "</h3>";
    print "\t\t\t\t<ul class='leftMenu'>\n";
    print "\t\t\t\t\t<li class='leftMenu'><a href='general.php'>" . _("General") . "</a></li>\n";
    print "\t\t\t\t\t<li class='leftMenu'><a href='password.php'>" . _("Password") . "</a></li>\n";
    print "\t\t\t\t\t<li class='leftMenu'><a href='password_policy.php'>" . _("Password policy") . "</a></li>\n";
    print "\t\t\t\t\t<li class='leftMenu'><a href='preferences.php'>" . _("Preferences") . "</a></li>\n";
    print "\t\t\t\t</ul>\n";
    
    $dbh = db_connect();
    
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
    
    outputAdminMenu($tAdmin, $rightUserAdmin, $rightGroupAdmin, $rightProjectAdmin, $rightRepositoryAdmin, $rightAccessRightAdmin, $tGroupsAllowed, $rightCreateFiles);
    outputReportsMenu($rightReports);
    
    print "\t\t\t\t<p>&nbsp;</p>\n";
    
    // print "\t</table>\n";
}

function outputFooter($area) {

    
}

?>
