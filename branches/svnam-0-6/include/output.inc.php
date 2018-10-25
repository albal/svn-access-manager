<?php

/**
 * Output functions to write out menues and other stuff.
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
 * @license GPL v2
 *         
 *          SVN Access Manager - a subversion access rights management tool
 *          Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *         
 *          This program is free software; you can redistribute it and/or modify
 *          it under the terms of the GNU General Public License as published by
 *          the Free Software Foundation; either version 2 of the License, or
 *          (at your option) any later version.
 *         
 *          This program is distributed in the hope that it will be useful,
 *          but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *          GNU General Public License for more details.
 *         
 *          You should have received a copy of the GNU General Public License
 *          along with this program; if not, write to the Free Software
 *          Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *         
 *
 *
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
 * check if called directly and redirect to loghin page
 */
if (preg_match("/output.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

/**
 * Write admin menu to navigation bar.
 *
 * @param string $tAdmin
 * @param string $rightUserAdmin
 * @param string $rightGroupAdmin
 * @param string $rightProjectAdmin
 * @param string $rightRepositoryAdmin
 * @param string $rightAccessRightAdmin
 * @param string $tGroupsAllowed
 * @param string $rightCreateFiles
 *
 */
function outputAdminMenu($tAdmin, $rightUserAdmin, $rightGroupAdmin, $rightProjectAdmin, $rightRepositoryAdmin, $rightAccessRightAdmin, $tGroupsAllowed, $rightCreateFiles) {

    global $CONF;
    global $_SESSION;

    if ($tAdmin == "p" || $rightUserAdmin != "none" || $rightGroupAdmin != "none" || $rightProjectAdmin != "none" || $rightRepositoryAdmin != "none" || $rightAccessRightAdmin != "none" || count($tGroupsAllowed) > 0 || $rightCreateFiles != "none") {

        /**
         * dropdown Administration
         */
        print '<li class="dropdown">';
        print '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . _("Administration") . ' <span class="caret"></span></a>';
        print '<ul class="dropdown-menu">';
    }
    
    if ($rightUserAdmin != "none") {
        
        print '<li><a href="list_users.php">' . _("Users") . '</a></li>';
        
        if (isset($CONF[USE_LDAP]) && (strtoupper($CONF[USE_LDAP]) == "YES")) {
            
            print '<li><a href="bulk_add_ldap_users.php">' . _("Bulk add LDAP users") . '</a></li>';
        }
    }
    
    if ($rightGroupAdmin != "none") {
        
        print '<li><a href="list_groups.php">' . _("Groups") . '</a></li>';
        print '<li><a href="list_group_admins.php">' . _("Group administrators") . '</a></li>';
    }
    elseif (count($tGroupsAllowed) > 0) {
        
        print '<li><a href="list_groups.php">' . _("Groups") . '</a></li>';
    }
    
    if ($rightRepositoryAdmin != "none") {
        
        print '<li><a href="list_repos.php">' . _("Repositories") . '</a></li>';
    }
    
    if ($rightProjectAdmin != "none") {
        
        print '<li><a href="list_projects.php">' . _("Projects") . '</a></li>';
    }
    
    if (($rightAccessRightAdmin != "none") || ($tAdmin == "p") || ($tAdmin == "y")) {
        
        print '<li><a href="list_access_rights.php">' . _("Repository access rights") . '</a></li>';
    }
    
    if ($rightCreateFiles != "none") {
        
        print '<li><a href="createAccessFiles.php">' . _("Create access files") . '</a></li>';
    }
    
    if ($rightUserAdmin != "none") {
        
        print '<li role="separator" class="divider"></li>';
        print '<li><a href="list_messages.php">' . _("Messages") . '</a></li>';
    }
    
    if (($tAdmin == "p") || ($rightUserAdmin != "none") || ($rightGroupAdmin != "none") || ($rightProjectAdmin != "none") || ($rightRepositoryAdmin != "none") || ($rightAccessRightAdmin != "none") || (count($tGroupsAllowed) > 0) || ($rightCreateFiles != "none")) {
        
        print '</ul>';
        print '</li>';
    /**
     * end dropdown Administration
     */
    }
    
}

/**
 * Write reports menu to navigation bar.
 *
 * @param string $rightReports
 *
 */
function outputReportsMenu($rightReports) {

    global $CONF;
    global $_SESSION;
    
    if ($rightReports != "none") {
        
        /**
         * dropdown Reports
         */
        print '<li class="dropdown">';
        print '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . _("Reports") . ' <span class="caret"></span></a>';
        print '<ul class="dropdown-menu">';
        
        print '<li><a href="rep_access_rights.php">' . _("Repository access rights") . '</a></li>';
        print '<li><a href="rep_log.php">' . _("Log") . '</a></li>';
        print '<li><a href="rep_locked_users.php">' . _("Locked users") . '</a></li>';
        print '<li><a href="rep_granted_user_rights.php">' . _("Granted user rights") . '</a></li>';
        print '<li><a href="rep_show_user.php">' . _("Show user") . '</a></li>';
        print '<li><a href="rep_show_group.php">' . _("Show group") . '</a></li>';
        
        print '</ul>';
        print '</li>';
    /**
     * end dropdown Reports
     */
    }
    
}

/**
 * Write navigation bar menu to webpage.
 */
function outputMenu() {

    global $CONF;
    global $_SESSION;
    
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
    
    print '<!-- Fixed navbar -->';
    print '<nav class="navbar navbar-default navbar-fixed-top">';
    print '<div class="container">';
    print '<div class="navbar-header">';
    print '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">';
    print '<span class="sr-only">Toggle navigation</span>';
    print '<span class="icon-bar"></span>';
    print '<span class="icon-bar"></span>';
    print '<span class="icon-bar"></span>';
    print '</button>';
    print '<a class="navbar-brand" href="main.php">SVN Access Manager</a>';
    print '</div>';
    print '<div id="navbar" class="navbar-collapse collapse">';
    print '<ul class="nav navbar-nav">';
    
    /**
     * dropdown my account
     */
    print '<li class="dropdown">';
    print '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . _("My account") . ' <span class="caret"></span></a>';
    print '<ul class="dropdown-menu">';
    print '<li><a href="general.php">' . _("General") . '</a></li>';
    print '<li><a href="password.php">' . _("Password") . '</a></li>';
    print '<li><a href="preferences.php">' . _("Preferences") . '</a></li>';
    print '</ul>';
    print '</li>';
    /**
     * end dropdown my account
     */
    
    outputAdminMenu($tAdmin, $rightUserAdmin, $rightGroupAdmin, $rightProjectAdmin, $rightRepositoryAdmin, $rightAccessRightAdmin, $tGroupsAllowed, $rightCreateFiles);
    outputReportsMenu($rightReports);
    
    print '</ul>';
    
    print '<ul class="nav navbar-nav navbar-right">';
    print '<li><a href="help.php" id="help"><span class="glyphicon glyphicon-question-sign"></span> ' . _("Help") . '</a></li>';
    print '<li><a target="_blank" href="https://www.svn-access-manager.org/using-svn-access-manager/"><span class="glyphicon glyphicon-info-sign"></span> ' . _("Documentation") . '</a></li>';
    print '<li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> ' . _("Logout") . '</a></li>';
    print '</ul>';
    print '</div><!--/.nav-collapse -->';
    print '</div>';
    print '</nav>';
    
}

/**
 * Write a lamguage dropdown to navigation bar.
 */
function outputLanguageMenu() {

    print '<li>';
    print '<div class="bfh-selectbox bfh-languages" data-language="en_US" data-available="en_US,de_DE" data-flags="true">';
    print '<input type="hidden" value="" name="fLanguage">';
    print '<a class="bfh-selectbox-toggle" role="button" data-toggle="bfh-selectbox" href="#">';
    print '<span class="bfh-selectbox-option input-medium" data-option=""></span>';
    print '<b class="caret"></b>';
    print '</a>';
    print '<div class="bfh-selectbox-options">';
    print '<div role="listbox">';
    print '<ul role="option">';
    print '</ul>';
    print '</div>';
    print '</div>';
    print '</div>';
    print '</li>';
    
}

/**
 * Write custom fields to webpage.
 */
function outputCustomFields() {

    global $CONF;
    
    if (isset($CONF[CUSTOM_COLUMN1])) {
        print "\t\t\t\t\t\t<th>\n";
        print "\t\t\t\t\t\t\t" . _($CONF[CUSTOM_COLUMN1]);
        print "\t\t\t\t\t\t</th>\n";
    }
    if (isset($CONF[CUSTOM_COLUMN2])) {
        print "\t\t\t\t\t\t<th>\n";
        print "\t\t\t\t\t\t\t" . _($CONF[CUSTOM_COLUMN2]);
        print "\t\t\t\t\t\t</th>\n";
    }
    if (isset($CONF[CUSTOM_COLUMN3])) {
        print "\t\t\t\t\t\t<th>\n";
        print "\t\t\t\t\t\t\t" . _($CONF[CUSTOM_COLUMN3]);
        print "\t\t\t\t\t\t</th>\n";
    }
    
}

/**
 * Write a table with all users to webpage.
 *
 * @param string $tUsers
 * @param string $rightAllowed
 */
function outputUsers($tUsers, $rightAllowed) {

    foreach( $tUsers as $entry) {
        
        global $CONF;
        
        list($date, $time ) = splitDateTimeI18n($entry['password_modified']);
        $pwChanged = $date . " " . $time;
        $locked = $entry['locked'] == 0 ? _("no") : _("yes");
        $expires = $entry['passwordexpires'] == 0 ? _("no") : _("yes");
        $admin = ((strtolower($entry[ADMIN]) == "y") || $entry[ADMIN] == '1') ? _('yes') : _('no');
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

/**
 * Write a table with all repositories to the webpage.
 *
 * @param string $tRepos
 * @param string $rightAllowed
 */
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

/**
 * Write a table with all projects to the webpage.
 *
 * @param string $tProjects
 * @param string $rightAllowed
 */
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

/**
 * Write a table with all messages to the webpage.
 *
 * @param array $tUserMessages
 * @param string $rightAllowed
 */
function outputMessages($tUserMessages, $rightAllowed) {

    global $CONF;
    
    foreach( $tUserMessages as $entry) {
        
        if (($rightAllowed == EDIT) || ($rightAllowed == DELETE)) {
            $url = htmlentities("workOnMessage.php?id=" . $entry['id'] . "&task=change");
            $edit = "<a href=\"$url\" title=\"" . _("Change") . "\" alt=\"" . _("Change") . "\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
        }
        else {
            $edit = "";
        }
        
        if ($rightAllowed == DELETE) {
            $url = htmlentities("deleteMessage.php?id=" . $entry['id'] . "&task=delete");
            $delete = "<a href=\"$url\" title=\"" . _("Delete") . "\" alt=\"" . _("Delete") . "\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
        }
        else {
            $delete = "";
        }
        $action = $edit . "     " . $delete;
        
        print "\t\t\t\t\t<tr>\n";
        print "\t\t\t\t\t\t<td>" . $entry['validfrom_date'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['validuntil_date'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $entry['message'] . "</td>\n";
        print "\t\t\t\t\t\t<td>" . $action . "</td>\n";
        print "\t\t\t\t\t</tr>\n";
    }
    
}

/**
 * Write a table with all groups to the webpage.
 *
 * @param string $tGroups
 * @param string $tGroupsAllowed
 * @param string $rightAllowed
 */
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

/**
 * Write a table with all group administrators to the webpage.
 *
 * @param string $tGroups
 * @param string $rightAllowed
 */
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

/**
 * Write a table with all access rights to the webpacge
 *
 * @param string $tAccessRights
 * @param string $rightAllowed
 */
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

/**
 * Write a message to the webpage
 *
 * @param string $tMessage
 * @param string $type
 *            valid types: success, info, warning, danger
 */
function outputMessage($tMessage, $type = '') {

    if (empty($tMessage)) {
        
        if ((isset($_SESSION[SVNSESSID][ERRORMSG])) && (! empty($_SESSION[SVNSESSID][ERRORMSG]))) {
            $tMessage = $_SESSION[SVNSESSID][ERRORMSG];
            $type = (isset($_SESSION[SVNSESSID][ERRORTYPE])) ? $_SESSION[SVNSESSID][ERRORTYPE] : 'info';
            
            print "<div class=\"alert alert-" . $type . "\">" . $tMessage . "</div>";
            
            $_SESSION[SVNSESSID][ERRORMSG] = '';
            $_SESSION[SVNSESSID][ERRORTYPE] = '';
        }
    }
    else {
        
        if (empty($type)) {
            $type = 'info';
        }
        print "<div class=\"alert alert-" . $type . "\">" . $tMessage . "</div>";
    }
    
}

/**
 * Write a span to signal a message
 *
 * @param string $type
 *            valid types: ok, warn, error or empty
 * @return string
 */
function outputResponseSpan($type = '') {

    switch ($type) {
        case 'ok' :
            $type = 'ok';
            break;
        case 'warn' :
            $type = 'warning-sign';
            break;
        case 'error' :
            $type = 'remove';
            break;
        default :
            $type = '';
    }
    
    return (empty($type) ? '' : '<span class="glyphicon glyphicon-' . $type . ' form-control-feedback"></span>');
    
}

/**
 * return css classes for message
 *
 * @param string $type
 *            valid types: ok, warn, error or empty
 * @return string
 */
function outputResponseClasses($type = '') {

    switch ($type) {
        case 'ok' :
            $type = 'has-success has-feedback';
            break;
        case 'warn' :
            $type = 'has-warning has-feedback';
            break;
        case 'error' :
            $type = 'has-error has-feedback';
            break;
        default :
            $type = '';
    }
    
    return ($type);
    
}

/**
 * output configuration data
 */
function outputConfig() {

    global $CONF;
    
    print '<div><h3>' . _("Configuration") . '</h3></div>';
    print '<div>';
    print '<div>';
    print '<label>' . _("Database type") . ':</label>';
    print '<div>';
    print '<p>' . $CONF['database_type'] . '</p>';
    print '</div>';
    print '</div>';
    print '<div>';
    print '<label>' . _("Database host") . ':</label>';
    print '<div>';
    print '<p>' . $CONF['database_host'] . '</p>';
    print '</div>';
    print '</div>';
    print '<div>';
    print '<label>' . _("Database user") . ':</label>';
    print '<div>';
    print '<p>' . $CONF['database_user'] . '</p>';
    print '</div>';
    print '</div>';
    print '<div>';
    print '<label>' . _("Database name") . ':</label>';
    print '<div>';
    print '<p>' . $CONF['database_name'] . '</p>';
    print '</div>';
    print '</div>';
    print '</div>';
    
}

/**
 * output statistics data
 *
 * @param array $tStats
 */
function outputStatistics($tStats) {

    print '<div><h3>' . _("Statistics") . '</h3></div>';
    print '<div>';
    print '<div>';
    print '<label>' . _("Users active / locked / deleted") . '</label>';
    print '<div>';
    print '<p>' . $tStats['user_active'] . ' / ' . $tStats['user_locked'] . ' / ' . $tStats['user_deleted'] . '</p>';
    print '</div>';
    print '</div>';
    print '<div>';
    print '<label>' . _("Groups active / deleted") . '</label>';
    print '<div>';
    print '<p>' . $tStats['group_active'] . ' / ' . $tStats['group_deleted'] . '</p>';
    print '</div>';
    print '</div>';
    print '<div>';
    print '<label>' . _("Projects active / deleted") . '</label>';
    print '<div>';
    print '<p>' . $tStats['project_active'] . ' / ' . $tStats['project_deleted'] . '</p>';
    print '</div>';
    print '</div>';
    print '<div>';
    print '<label>' . _("Repositories active / deleted") . '</label>';
    print '<div>';
    print '<p>' . $tStats['repo_active'] . ' / ' . $tStats['repo_deleted'] . '</p>';
    print '</div>';
    print '</div>';
    print '</div>';
    
}
?>
