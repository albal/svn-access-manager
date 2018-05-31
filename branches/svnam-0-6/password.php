<?php

/**
 * set a new password
 *
 * @author Thomas Krieger
 * @copyright 2008-2018 Thomas Krieger. All rights reserved.
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
include('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require("$installBase/include/variables.inc.php");
include_once("$installBase/include/constants.inc.php");
require("$installBase/include/functions.inc.php");
require("$installBase/include/output.inc.php");
require("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$dbh = db_connect();
$SESSID_USERNAME = check_session();
$_SESSION[SVNSESSID]['helptopic'] = PASSWORD;
$schema = db_determine_schema();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    $tCurrentError = '';
    $tPasswordError = '';
    $tPassword2Error = '';
    $header = PASSWORD;
    $subheader = PASSWORD;
    $menu = PASSWORD;
    $template = "password.tpl";

    include("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    } elseif ((isset($_POST['fSubmit_ok_x'])) || (isset($_POST['fSubmit_ok']))) {
        $button = _("Change password");
    } elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Cancel");
    } else {
        $button = "undef";
    }

    if ($button == _("Cancel")) {
        db_disconnect($dbh);
        header("Location: main.php");
        exit();
    } elseif ($button == _("Change password")) {
        $error = 0;
        $tCurrentError = 'ok';
        $tPasswordError = 'ok';
        $tPassword2Error = 'ok';
        $fUser = $SESSID_USERNAME;
        $fPassword_current = db_escape_string($_POST['fPassword_current']);
        $fPassword = db_escape_string($_POST['fPassword']);
        $fPassword2 = db_escape_string($_POST['fPassword2']);

        $result = db_query("SELECT * FROM svnusers WHERE userid = '$fUser'", $dbh);

        if ($result['rows'] == 1) {

            $row = db_assoc($result['result']);
            $checked_password = addslashes(pacrypt($fPassword_current, $row['password']));
            $result = db_query("SELECT * " . "  FROM " . $schema . "svnusers " . " WHERE userid = '$fUser' " . "   AND password = '$checked_password'", $dbh);

            if ($result['rows'] != 1) {

                $error = 1;
                $tMessage = _("Current password not entered!");
                $tMessageType = DANGER;
                $tCurrentError = ERROR;
            } else {

                $row = db_assoc($result['result']);
                $isAdmin = $row['admin'];
            }
        } else {

            $error = 1;
            $tMessage = _("User doesn't exist!");
            $tMessageType = DANGER;
        }

        if (empty($fPassword) || ($fPassword != $fPassword2)) {

            $error = 1;
            $tMessage = _("New passwords do not match!");
            $tMessageType = DANGER;
            $tPasswordError = ERROR;
            $tPassword2Error = ERROR;
        } elseif ($fPassword == $fPassword_current) {

            $error = 1;
            $tMessage = _("New password can not be the same as the current password!");
            $tMessageType = DANGER;
            $tPasswordError = ERROR;
            $tPassword2Error = ERROR;
        }

        if (($error == 0) && (checkPasswordPolicy($fPassword, $isAdmin) == 0)) {

            $tMessage = _("Password not strong enough!");
            $tMessageType = DANGER;
            $tPasswordError = ERROR;
            $tPassword2Error = ERROR;
            $error = 1;
        }

        if ($error != 1) {

            db_ta("BEGIN", $dbh);

            $password = db_escape_string(pacrypt($fPassword), $dbh);
            $moddate = getDateJhjjmmtt();
            $dbnow = db_now();
            $result = db_query("UPDATE " . $schema . "svnusers " . "   SET password = '$password', " . "       password_modified = '$dbnow' " . " WHERE userid = '$fUser'", $dbh);

            if ($result['rows'] == 1) {

                db_log($_SESSION[SVNSESSID]['username'], "password changed", $dbh);

                $tMessage = _("Password changed successfully");
                $tMessageType = SUCCESS;
                $_SESSION[SVNSESSID][ERRORMSG] = $tMessage;
                $_SESSION[SVNSESSID][ERRORTYPE] = $tMessageType;

                db_ta("COMMIT", $dbh);

                $_SESSION[SVNSESSID]['password_expired'] = 0;

                db_disconnect($dbh);
                header("Location: main.php");
                exit();
            } else {

                $tMessage = _("Password change failed due to database error!");
                $tMessageType = DANGER;

                db_ta("ROLLBACK", $dbh);
            }
        }
    }

    $header = PASSWORD;
    $subheader = PASSWORD;
    $menu = PASSWORD;
    $template = "password.tpl";

    include("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
