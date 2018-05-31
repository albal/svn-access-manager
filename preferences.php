<?php

/**
 * setup preferences
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
include ('load_config.php');

$installBase = isset($CONF[INSTALLBASE]) ? $CONF[INSTALLBASE] : "";

require ("$installBase/include/variables.inc.php");
include_once ("$installBase/include/constants.inc.php");
require ("$installBase/include/functions.inc.php");
require ("$installBase/include/output.inc.php");
require ("$installBase/include/db-functions-adodb.inc.php");

initialize_i18n();

$SESSID_USERNAME = check_session();
check_password_expired();
$dbh = db_connect();
$preferences = db_get_preferences($SESSID_USERNAME, $dbh);
$CONF[PAGESIZE] = $preferences[PAGESIZE];
$CONF[TOOLTIP_SHOW] = $preferences[TOOLTIP_SHOW];
$CONF[TOOLTIP_HIDE] = $preferences[TOOLTIP_HIDE];
$userid = db_getIdByUserid($SESSID_USERNAME, $dbh);
$_SESSION[SVNSESSID]['helptopic'] = PREFERENCES;
$schema = db_determine_schema();
$tRecordsPerPage = array(
        '10' => '10',
        '25' => '25',
        '50' => '50',
        '-1' => 'All'
);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $tReadonly = "";
    $tPageSizeError = '';
    $tTooltipShowError = '';
    $tTooltipHideError = '';
    
    $query = "SELECT * " . "  FROM " . $schema . "preferences " . " WHERE (user_id = $userid) " . "   AND (deleted = '00000000000000')";
    $result = db_query($query, $dbh);
    
    if ($result['rows'] == 0) {
        
        $tPageSize = $CONF[PAGESIZE];
        $tSortField = $CONF[USER_SORT_FIELDS];
        $tSortOrder = $CONF[USER_SORT_ORDER];
        $tTooltipShow = $CONF[TOOLTIP_SHOW];
        $tTooltipHide = $CONF[TOOLTIP_HIDE];
    }
    else {
        
        $row = db_assoc($result['result']);
        $tPageSize = $row[PAGESIZE];
        $tSortField = $row[USER_SORT_FIELDS];
        $tSortOrder = $row[USER_SORT_ORDER];
        $tTooltipShow = $row[TOOLTIP_SHOW];
        $tTooltipHide = $row[TOOLTIP_HIDE];
        
        if ($tSortField == "") {
            
            $tSortField = $CONF[USER_SORT_FIELDS];
            $tSortOrder = $CONF[USER_SORT_ORDER];
        }
    }
    
    // sort order asc handled in else branch
    if ($tSortOrder == "DESC") {
        $tDesc = CHECKED;
        $tAsc = "";
    }
    else {
        $tAsc = CHECKED;
        $tDesc = "";
    }
    
    // sort prefende name,givenname is handled in else branch
    if ($tSortField == "userid") {
        $tUserid = CHECKED;
        $tName = "";
    }
    else {
        $tName = CHECKED;
        $tUserid = "";
    }
    
    $header = PREFERENCES;
    $subheader = PREFERENCES;
    $menu = PREFERENCES;
    $template = "preferences.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    if (isset($_POST['fSubmit'])) {
        $button = db_escape_string($_POST['fSubmit']);
    }
    elseif (isset($_POST['fSubmit_f_x'])) {
        $button = _("<<");
    }
    elseif (isset($_POST['fSubmit_p_x'])) {
        $button = _("<");
    }
    elseif (isset($_POST['fSubmit_n_x'])) {
        $button = _(">");
    }
    elseif (isset($_POST['fSubmit_l_x'])) {
        $button = _(">>");
    }
    elseif ((isset($_POST['fSubmit_ok_x'])) || (isset($_POST['fSubmit_ok']))) {
        $button = _("Submit");
    }
    elseif ((isset($_POST['fSubmit_back_x'])) || (isset($_POST['fSubmit_back']))) {
        $button = _("Back");
    }
    else {
        $button = "undef";
    }
    
    $tPageSize = isset($_POST['fPageSize']) ? db_escape_string($_POST['fPageSize']) : "";
    $tSortField = isset($_POST['fSortField']) ? db_escape_string($_POST['fSortField']) : "";
    $tSortOrder = isset($_POST['fSortOrder']) ? db_escape_string($_POST['fSortOrder']) : "";
    $tTooltipShow = isset($_POST['fTooltipShow']) ? db_escape_string($_POST['fTooltipShow']) : "";
    $tTooltipHide = isset($_POST['fTooltipHide']) ? db_escape_string($_POST['fTooltipHide']) : "";
    
    if ($button == _("Back")) {
        
        db_disconnect($dbh);
        header("Location: main.php");
        exit();
    }
    elseif ($button == _("Submit")) {
        
        $error = 0;
        $tPageSizeError = 'ok';
        $tTooltipShowError = 'ok';
        $tTooltipHideError = 'ok';
        
        if ($tPageSize == "") {
            
            $error = 1;
            $tMessage = _("Records per page must be filled in!");
            $tMessageType = DANGER;
            $tPageSizeError = ERROR;
        }
        elseif (! is_numeric($tPageSize)) {
            
            $error = 1;
            $tMessage = _("Records per page must contain digits only!");
            $tMessageType = DANGER;
            $tPageSizeError = ERROR;
        }
        
        if ($tTooltipShow == "") {
            
            $error = 1;
            $tMessage = _("Tooltip milliseconds for show up must be filled in!");
            $tMessageType = DANGER;
            $tTooltipShowError = ERROR;
        }
        elseif (! is_numeric($tTooltipShow)) {
            
            $error = 1;
            $tMessage = _("Tooltip show up milliseconds must be numeric!");
            $tMessageType = DAMGER;
            $tTooltipShowError = ERROR;
        }
        
        if ($tTooltipHide == "") {
            
            $error = 1;
            $tMessage = _("Tooltip milliseconds for vanish up must be filled in!");
            $tMessageType = DANGER;
            $tTooltipHideError = ERROR;
        }
        elseif (! is_numeric($tTooltipHide)) {
            
            $error = 1;
            $tMessage = _("Tooltip vanish milliseconds must be numeric!");
            $tMessageType = DAMGER;
            $tTooltipHideError = ERROR;
        }
        
        if ($error == 0) {
            
            db_ta('BEGIN', $dbh);
            db_log($SESSID_USERNAME, 'changed preferences', $dbh);
            
            $query = "SELECT * " . "  FROM " . $schema . "preferences " . " WHERE (user_id = $userid) " . "   AND (deleted = '00000000000000')";
            $result = db_query($query, $dbh);
            
            if ($result['rows'] == 0) {
                
                $dbnow = db_now();
                $query = "INSERT INTO " . $schema . "preferences (user_id, page_size, user_sort_fields, user_sort_order, tooltip_show, tooltip_hide, created, created_user) " . "     VALUES ($userid, $tPageSize, '$tSortField', '$tSortOrder', $tTooltipShow, $tTooltipHide, '$dbnow', '$SESSID_USERNAME')";
            }
            else {
                
                $dbnow = db_now();
                $query = "UPDATE " . $schema . "preferences " . "   SET page_size = $tPageSize, " . "       user_sort_fields = '$tSortField', " . "       user_sort_order = '$tSortOrder', " . "       tooltip_show = $tTooltipShow, " . "       tooltip_hide = $tTooltipHide, " . "       modified = '$dbnow', " . "       modified_user = '$SESSID_USERNAME' " . " WHERE (user_id = $userid) " . "   AND (deleted = '00000000000000')";
            }
            
            $result = db_query($query, $dbh);
            
            if ($result['rows'] == 1) {
                
                db_ta('COMMIT', $dbh);
                $tMessage = _("Preferences changed successfully");
                $tMessageType = SUCCESS;
            }
            else {
                
                db_ta('ROLLBACK', $dbh);
                $tMessages = _("Preferences not changed due to database error");
                $tMessageType = DANGER;
            }
        }
    }
    else {
        
        $tMessage = sprintf(_("Invalid button %s, anyone tampered arround with?"), $button);
        $tMessageType = DANGER;
    }
    
    // sort orswr ASC is handled in else brnach
    if ($tSortOrder == "DESC") {
        $tDesc = CHECKED;
        $tAsc = "";
    }
    else {
        $tAsc = CHECKED;
        $tDesc = "";
    }
    
    // sort preference name,givennme is handled in else branch
    if ($tSortField == "userid") {
        $tUserid = CHECKED;
        $tName = "";
    }
    else {
        $tName = CHECKED;
        $tUserid = "";
    }
    
    $header = PREFERENCES;
    $subheader = PREFERENCES;
    $menu = PREFERENCES;
    $template = "preferences.tpl";
    
    include ("$installBase/templates/framework.tpl");
}

db_disconnect($dbh);
?>
