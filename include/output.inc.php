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

function outputHeader() {

    $tUsername = isset($_SESSION[SVNSESSID][USERNAME]) ? $_SESSION[SVNSESSID][USERNAME] : "undefined";
    
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

    $area = strtolower($area);
    
    switch ($area) {
        case "groups" :
            print "<img src='./images/group.png' border='0' />  " . _("Groups");
            break;
        
        case "main" :
            print "<img src='./images/welcome.png' border='0' /> " . sprintf(_("Welcome %s"), $_SESSION[SVNSESSID]['givenname'] . " " . $_SESSION[SVNSESSID]['name']);
            break;
        
        case "users" :
            print "<img src='./images/user.png' border='0' />  " . _("Users");
            break;
        
        case "password" :
            print "<img src='./images/password.png' border='0' />  " . _("Password");
            break;
        
        case "password_policy" :
            print "<img src='./images/password.png' border='0' />  " . _("Password policy");
            break;
        
        case "general" :
            print "<img src='./images/personal.png' border='0' />  " . _("General");
            break;
        
        case "noadmin" :
            print "<img src='./images/service.png' border='0' />  " . _("Access denied");
            break;
        
        case "dberror" :
            print "<img src='./images/service.png' border='0' />  " . _("Database error");
            break;
        
        case "nopermission" :
            print "<img src='./images/password.png' border='0' />  " . _("Permission denied");
            break;
        
        case "projects" :
            print "<img src='./images/project.png' border='0' />  " . _("Projects");
            break;
        
        case "repos" :
            print "<img src='./images/service.png' border='0' />  " . _("Repositories");
            break;
        
        case "access" :
            print "<img src='./images/password.png' border='0' />  " . _("Access rights");
            break;
        
        case "reports" :
            print "<img src='./images/reports.png' border='0' />  " . _("Reports");
            break;
        
        case "preferences" :
            print "<img src='./images/macros.png' border='0' />  " . _("Preferences");
            break;
        
        case "search" :
            print "<img src='./images/search_large.png' border='0' />  " . _("Search");
            break;
        
        default :
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
        print "\t\t\t\t<ul class='leftmenu'>\n";
    }
    
    if ($rightUserAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_users.php\">" . _("Users") . "</a></li>\n";
        
        if (isset($CONF[USE_LDAP]) && (strtoupper($CONF[USE_LDAP]) == "YES")) {
            
            print "\t\t\t\t\t<li class='leftmenu'><a href=\"bulk_add_ldap_users.php\">" . _("Bulk add LDAP users") . "</a></li>\n";
        }
    }
    
    if ($rightGroupAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_groups.php\">" . _("Groups") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_group_admins.php\">" . _("Group administrators") . "</a></li>\n";
    }
    elseif (count($tGroupsAllowed) > 0) {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_groups.php\">" . _("Groups") . "</a></li>\n";
    }
    
    if ($rightRepositoryAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_repos.php\">" . _("Repositories") . "</a></li>\n";
    }
    
    if ($rightProjectAdmin != "none") {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_projects.php\">" . _("Projects") . "</a></li>\n";
    }
    
    if (($rightAccessRightAdmin != "none") || ($tAdmin == "p") || ($tAdmin == "y")) {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"list_access_rights.php\">" . _("Repository access rights") . "</a></li>\n";
    }
    
    if ($rightCreateFiles != "none") {
        
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"createAccessFiles.php\">" . _("Create access files") . "</a></li>\n";
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
        print "\t\t\t\t<ul class='leftmenu'>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"rep_access_rights.php\">" . _("Repository access rights") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"rep_log.php\">" . _("Log") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"rep_locked_users.php\">" . _("Locked users") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"rep_granted_user_rights.php\">" . _("Granted user rights") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"rep_show_user.php\">" . _("Show user") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'><a href=\"rep_show_group.php\">" . _("Show group") . "</a></li>\n";
        print "\t\t\t\t\t<li class='leftmenu'>&nbsp;</li>\n";
        print "\t\t\t\t</ul>\n";
    }
    
}

function outputMenu() {

    global $CONF;
    global $_SESSION;
    
    print "\t\t\t\t<h3>" . _("My account") . "</h3>";
    print "\t\t\t\t<ul class='leftmenu'>\n";
    print "\t\t\t\t\t<li class='leftmenu'><a href='general.php'>" . _("General") . "</a></li>\n";
    print "\t\t\t\t\t<li class='leftmenu'><a href='password.php'>" . _("Password") . "</a></li>\n";
    print "\t\t\t\t\t<li class='leftmenu'><a href='password_policy.php'>" . _("Password policy") . "</a></li>\n";
    print "\t\t\t\t\t<li class='leftmenu'><a href='preferences.php'>" . _("Preferences") . "</a></li>\n";
    print "\t\t\t\t</ul>\n";
    
    $dbh = db_connect();
    
    $tUsername = isset($_SESSION[SVNSESSID][USERNAME]) ? $_SESSION[SVNSESSID][USERNAME] : 'undefined';
    $tAdmin = isset($_SESSION[SVNSESSID][ADMIN]) ? $_SESSION[SVNSESSID][ADMIN] : 'n';
    
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
    
}

function outputFooter() {

    
}

function outputCustomFields() {

    global $CONF;
    
    if (isset($CONF[CUSTOM_COLUMN1])) {
        print "\t\t\t\t\t\t<th class=\"ui-table-default\">\n";
        print "\t\t\t\t\t\t\t" . _($CONF[CUSTOM_COLUMN1]);
        print "\t\t\t\t\t\t</th>\n";
    }
    if (isset($CONF[CUSTOM_COLUMN2])) {
        print "\t\t\t\t\t\t<th class=\"ui-table-default\">\n";
        print "\t\t\t\t\t\t\t" . _($CONF[CUSTOM_COLUMN2]);
        print "\t\t\t\t\t\t</th>\n";
    }
    if (isset($CONF[CUSTOM_COLUMN3])) {
        print "\t\t\t\t\t\t<th class=\"ui-table-default\">\n";
        print "\t\t\t\t\t\t\t" . _($CONF[CUSTOM_COLUMN3]);
        print "\t\t\t\t\t\t</th>\n";
    }
    
}

function outputUsers($tUsers, $rightAllowed) {

    foreach( $tUsers as $entry) {
        
        global $CONF;
        
        list($date, $time ) = splitDateTime($entry['password_modified']);
        $pwChanged = $date . " " . $time;
        $locked = $entry['locked'] == 0 ? _("no") : _("yes");
        $expires = $entry['passwordexpires'] == 0 ? _("no") : _("yes");
        $admin = $entry[ADMIN] == "n" ? _("no") : _("yes");
        $custom1 = $entry['custom1'];
        $custom2 = $entry['custom2'];
        $custom3 = $entry['custom3'];
        
        if (($rightAllowed == EDIT) || ($rightAllowed == DELETE)) {
            $url = htmlentities("workOnUser.php?id=" . $entry['id'] . "&task=change");
            $edit = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        else {
            $edit = "";
        }
        
        if ($rightAllowed == DELETE) {
            $url = htmlentities("deleteUser.php?id=" . $entry['id'] . "&task=delete");
            $delete = "<a href=\"$url\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        else {
            $delete = "";
        }
        $action = $edit . "     " . $delete;
        
        print "\t\t\t\t\t<tr>\n";
        print "\t\t\t\t\t\t<td>" . $entry['userid'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['name'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['givenname'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['emailaddress'] . "</td>\n";
        if (isset($CONF[CUSTOM_COLUMN1])) {
            print "\t\t\t\t\t\t<td>" . $custom1 . "</td>\n";
        }
        if (isset($CONF[CUSTOM_COLUMN2])) {
            print "\t\t\t\t\t\t<td>" . $custom2 . "</td>\n";
        }
        if (isset($CONF[CUSTOM_COLUMN3])) {
            print "\t\t\t\t\t\t<td>" . $custom3 . "</td>\n";
        }
        print "\t\t\t\t\t\t<td>" . $entry['user_mode'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $locked . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $pwChanged . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $expires . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $admin . "</td>\n";
        if ((isset($CONF[USE_LDAP])) && (strtoupper($CONF[USE_LDAP]) == "YES")) {
            if (isset($entry['ldap'])) {
                $ldap = ($entry['ldap'] == 1) ? _("yes") : _("no");
            }
            else {
                $ldap = _("No");
            }
            print "\t\t\t\t\t\t<td>" . $ldap . "</td>\n";
        }
        print "\t\t\t\t\t\t<td>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
    }
    
}

function outputRepos($tRepos, $rightAllowed) {

    global $CONF;
    
    foreach( $tRepos as $entry) {
        
        if (($rightAllowed == EDIT) || ($rightAllowed == DELETE)) {
            $url = htmlentities("workOnRepo.php?id=" . $entry['id'] . "&task=change");
            $edit = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        else {
            $edit = "";
        }
        
        if ($rightAllowed == DELETE) {
            $url = htmlentities("deleteRepo.php?id=" . $entry['id'] . "&task=delete");
            $delete = "<a href=\"$url\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        else {
            $delete = "";
        }
        $action = $edit . "     " . $delete;
        
        print "\t\t\t\t\t<tr>\n";
        print "\t\t\t\t\t\t<td>" . $entry[REPONAME] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['repopath'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['repouser'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['repopassword'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
    }
    
}

function outputProjects($tProjects, $rightAllowed) {

    global $CONF;
    
    foreach( $tProjects as $entry) {
        
        if (($rightAllowed == EDIT) || ($rightAllowed == DELETE)) {
            $url = htmlentities("workOnProject.php?id=" . $entry['id'] . "&task=change");
            $edit = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        else {
            $edit = "";
        }
        
        if ($rightAllowed == DELETE) {
            $url = htmlentities("deleteProject.php?id=" . $entry['id'] . "&task=delete");
            $delete = "<a href=\"$url\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        else {
            $delete = "";
        }
        $action = $edit . "     " . $delete;
        
        print "\t\t\t\t\t<tr>\n";
        print "\t\t\t\t\t\t<td>" . $entry['svnmodule'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['modulepath'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry[REPONAME] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
    }
    
}

function outputGroups($tGroups, $tGroupsAllowed, $rightAllowed) {

    global $CONF;
    
    foreach( $tGroups as $entry) {
        
        $groupRight = isset($tGroupsAllowed[$entry['id']]) ? $tGroupsAllowed[$entry['id']] : "none";
        
        if (($rightAllowed == EDIT) || ($rightAllowed == DELETE) || ($groupRight == EDIT) || ($groupRight == DELETE)) {
            $url = htmlentities("workOnGroup.php?id=" . $entry['id'] . "&task=change");
            $edit = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        else {
            $edit = "";
        }
        
        if (($rightAllowed == DELETE) || ($groupRight == DELETE)) {
            $url = htmlentities("deleteGroup.php?id=" . $entry['id'] . "&task=delete");
            $delete = "<a href=\"$url\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        else {
            $delete = "";
        }
        $action = $edit . "     " . $delete;
        
        print "\t\t\t\t\t<tr>\n";
        print "\t\t\t\t\t\t<td>" . $entry[GROUPNAME] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['description'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
    }
    
}

function outputGroupAdmin($tGroups, $rightAllowed) {

    global $CONF;
    
    foreach( $tGroups as $entry) {
        
        if (($rightAllowed == EDIT) || ($rightAllowed == DELETE)) {
            $url = htmlentities("workOnGroupAccessRight.php?id=" . $entry['id'] . "&task=change");
            $edit = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        else {
            $edit = "";
        }
        
        if ($rightAllowed == DELETE) {
            $url = htmlentities("deleteGroupAccessRight.php?id=" . $entry['id'] . "&task=delete");
            $delete = "<a href=\"$url\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        else {
            $delete = "";
        }
        $action = $edit . "     " . $delete;
        $admin = $entry['userid'];
        
        print "\t\t\t\t\t<tr>\n";
        print "\t\t\t\t\t\t<td>" . $entry[GROUPNAME] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['description'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $admin . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['allowed'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
    }
    
}

function outputAccessRights($tAccessRights, $rightAllowed) {

    global $CONF;
    
    $i = 0;
    $_SESSION[SVNSESSID]['max_mark'] = 0;
    $_SESSION[SVNSESSID]['mark'] = array();
    
    foreach( $tAccessRights as $entry) {
        
        $validfrom = splitValidDate($entry['valid_from']);
        $validuntil = splitValiddate($entry['valid_until']);
        $field = "fDelete" . $i;
        $action = "";
        
        if ($rightAllowed == EDIT) {
            $url = htmlentities("workOnAccessRight.php?id=" . $entry['id'] . "&task=change");
            $action = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        elseif ($rightAllowed == DELETE) {
            $url = htmlentities("workOnAccessRight.php?id=" . $entry['id'] . "&task=change");
            $action = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=" . htmlentities($entry['id']) . "&task=delete\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        elseif ($_SESSION[SVNSESSID][ADMIN] == "p") {
            $url = htmlentities("workOnAccessRight.php?id=" . $entry['id'] . "&task=change");
            $action = "<a href=\"workOnAccessRight.php?id=" . $entry['id'] . "&task=change\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
            $action = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=" . htmlentities($entry['id']) . "&task=delete\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        
        print "\t\t\t\t\t<tr valign=\"top\">\n";
        print "\t\t\t\t\t\t<td><input type=\"checkbox\" name=\"" . $field . "\" value=\"" . $entry['id'] . "\"/></td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['svnmodule'] . "</td>\n";
        print "\t\t\t\t\t\t<td align=\"center\">" . $entry['access_right'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry[USERNAME] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry[GROUPNAME] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $validfrom . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $validuntil . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry[REPONAME] . ":" . $entry['path'] . "</td>\n";
        print "\t\t\t\t\t\t<td nowrap>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
        
        $_SESSION[SVNSESSID]['mark'][$i] = $entry['id'];
        
        $i ++;
    }
    
    $_SESSION[SVNSESSID]['max_mark'] = $i - 1;
    
}
?>
