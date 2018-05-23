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
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");
require ("$installBase/include/functions.inc.php");

initialize_i18n();

$dbh = db_connect();
$schema = db_determine_schema();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $id = isset($_GET['id']) ? db_escape_string($_GET['id']) : "";
    $tMessage = "";
    $tToken = "";
    $tPassword1 = "";
    $tPassword2 = "";
    
    include ("$installBase/templates/resetpassword.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    $id = isset($_GET['id']) ? db_escape_string($_GET['id']) : "";
    $tToken = db_escape_string($_POST['fToken']);
    $tPassword1 = db_escape_string($_POST['fPassword1']);
    $tPassword2 = db_escape_string($_POST['fPassword2']);
    
    if (($tPassword1 == "") || ($tPassword2 == "")) {
        
        $tMessage = _("Please fill in the new password twice!");
        $error = 1;
    }
    elseif ($tPassword1 != $tPassword2) {
        
        $tMessage = _("Passwords are different!");
        $error = 1;
    }
    else {
        
        $query = "SELECT * " . "  FROM " . $schema . "svnpasswordreset " . " WHERE (token = '$tToken') " . "   AND (idstr = '$id')";
        $result = db_query($query, $dbh);
        if ($result['rows'] == 1) {
            
            $row = db_assoc($result['result']);
            $username = $row['username'];
            $timestamp = $row['unixtime'];
            $pkey = $row['id'];
            $days = isset($CONF['lostPwLinkValid']) ? $CONF['lostPwLinkValid'] : 2;
            $timestamp = $timestamp + ($days * 86400);
            if (time() > $timestamp) {
                
                $tMessage = _("Invalid data!");
                $error = 1;
            }
            else {
                
                $query = "SELECT admin " . "  FROM " . $schema . "svnusers " . " WHERE (userid = '$username') " . "   AND (deleted = '00000000000000')";
                $result = db_query($query, $dbh);
                if ($result['rows'] > 0) {
                    $row = db_assoc($result['result']);
                    $admin = $row['admin'];
                    if (checkPasswordPolicy($tPassword1, $admin) == 0) {
                        
                        $tMessage = _("Password not strong enough!");
                        $error = 1;
                    }
                    else {
                        $password = db_escape_string(pacrypt($tPassword1), $dbh);
                        $query = "UPDATE " . $schema . "svnusers " . "   SET password = '$password' " . " WHERE (userid = '$username') " . "   AND (deleted = '00000000000000')";
                        
                        db_ta("BEGIN", $dbh);
                        $result = db_query($query, $dbh);
                        if ($result['rows'] > 0) {
                            
                            $query = "DELETE FROM " . $schema . "svnpasswordreset " . "      WHERE id = $pkey";
                            $result = db_query($query, $dbh);
                            if ($result['rows'] >= 0) {
                                
                                db_ta("COMMIT", $dbh);
                                
                                $tMessage = _("Your new password was set successfully!");
                                
                                include ("$installBase/templates/resetpasswordresult.tpl");
                                db_disconnect($dbh);
                                
                                exit();
                            }
                            else {
                                
                                $tMessage = _("Can't update password. Please try again later.");
                                $error = 1;
                                db_ta("ROLLBACK", $dbh);
                            }
                        }
                        else {
                            
                            $tMessage = _("Can't update password. Please try again later.");
                            $error = 1;
                            db_ta("ROLLBACK", $dbh);
                        }
                    }
                }
                else {
                    
                    $tMessage = _("Your user has been deleted meanwhile!");
                    $error = 1;
                }
            }
        }
        else {
            
            $tMessage = _("No valid data!");
            $error = 1;
        }
    }
    
    include ("$installBase/templates/resetpassword.tpl");
}

db_disconnect($dbh);
?>
