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
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF['page_size'] = $preferences['page_size'];
$rightAllowed = db_check_acl($SESSID_USERNAME, "Reports", $dbh);
$_SESSION[SVNSESSID]['helptopic'] = "repaccessrights";

if ($rightAllowed == "none") {
    
    db_log($SESSID_USERNAME, "tried to use rep_access_rights without permission", $dbh);
    db_disconnect($dbh);
    header("Location: nopermission.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $lang = check_language();
    
    if ($lang == "de") {
        
        $tDate = "TT.MM.JJJJ";
        $tDate = date("d") . "." . date("m") . "." . date("Y");
        $tDateFormat = "dd-mm-yy";
        $tLocale = "de";
    }
    else {
        
        $tDate = "MM/DD/YYYY";
        $tDate = date("m") . "/" . date("d") . "/" . date("Y");
        $tDateFormat = "mm-dd-yy";
        $tLocale = "en";
    }
    
    $template = "getDateForAccessRights.tpl";
    $header = REPORTS;
    $subheader = REPORTS;
    $menu = REPORTS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $error = 0;
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif ((isset($_POST['fSubmit_date_x'])) || (isset($_POST['fSubmit_date']))) {
        $button = _("Create report");
    }
    else {
        $button = "undef";
    }
    
    if ($button == _("Create report")) {
        
        $tDate = isset($_POST['fDate']) ? db_escape_string($_POST['fDate']) : "";
        // error_log( $tDate );
        $_SESSION[SVNSESSID]['date'] = $tDate;
        $lang = check_language();
        
        if (($lang == "de") or (substr($tDate, 2, 1) == ".")) {
            
            $day = substr($tDate, 0, 2);
            $month = substr($tDate, 3, 2);
            $year = substr($tDate, 6, 4);
        }
        else {
            
            $day = substr($tDate, 3, 2);
            $month = substr($tDate, 0, 2);
            $year = substr($tDate, 6, 4);
        }
        
        // error_log( "day = $day, month = $month, year = $year" );
        if (! check_date($day, $month, $year)) {
            
            $tMessage = sprintf(_("Not a valid date: %s (%s-%s-%s)"), $tDate, $day, $month, $year);
            $error = 1;
            
            if ($lang == "de") {
                
                $tDateFormat = "dd-mm-yy";
                $tLocale = "de";
            }
            else {
                
                $tDateFormat = "mm-dd-yy";
                $tLocale = "en";
            }
            
            $template = "getDateForAccessRights.tpl";
            $header = REPORTS;
            $subheader = REPORTS;
            $menu = REPORTS;
            
            include ("$installBase/templates/framework.tpl");
            
            db_disconnect($dbh);
            exit();
        }
        else {
            
            $valid = $year . $month . $day;
            $_SESSION[SVNSESSID]['valid'] = $valid;
            $_SESSION[SVNSESSID]['rightcounter'] = 0;
            $tAccessRights = db_getAccessRightsList($_SESSION[SVNSESSID]['valid'], 0, - 1, $dbh);
            $tCountRecords = db_getCountAccessRightsList($_SESSION[SVNSESSID]['valid'], $dbh);
            $tPrevDisabled = "disabled";
            
            if ($tCountRecords <= $CONF['page_size']) {
                
                $tNextDisabled = "disabled";
            }
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
    }
    
    $template = "rep_access_rights.tpl";
    $header = REPORTS;
    $subheader = REPORTS;
    $menu = REPORTS;
    
    include ("$installBase/templates/framework.tpl");
    
    db_disconnect($dbh);
}

?>
