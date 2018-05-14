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
error_reporting(E_NOTICE | E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
ini_set('log_errors', 'On');
ini_set('html_errors', 'Off');

if (preg_match("/functions.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

//
// getPhpVersion
// Action: get php version in two dgit format like "53"
// Call: getPhpVersion
//
function getPhpVersion() {

    $version = explode(".", PHP_VERSION);
    
    return ($version[0] . $version[1]);
    
}

//
// runInCli
// Action: check if runninh in php client
// Call: runInCli
//
function runInCli() {

    return (php_sapi_name() == "cli");
    
}

//
// initalize_i18n
// Action: inialize gettext
// Call: initialize_i18n()
//
function initialize_i18n() {

    $locale = get_locale();
    
    if (! preg_match('/_/', $locale)) {
        
        $locale = $locale . "_" . strtoupper($locale);
    }
    
    putenv("LANG=$locale");
    putenv("LC_ALL=$locale");
    setlocale(LC_ALL, 0);
    setlocale(LC_ALL, $locale);
    
    // Path "./locale/de/LC_MESSAGES/messages.mo" handled in else branch
    if (file_exists(realpath("../locale/de/LC_MESSAGES/messages.mo"))) {
        
        $localepath = "../locale";
    }
    else {
        
        $localepath = "./locale";
    }
    
    bindtextdomain(MESSAGES, $localepath);
    textdomain(MESSAGES);
    
    bind_textdomain_codeset(MESSAGES, 'UTF-8');
    
}

//
// check_session
// Action: Check if a session already exists, if not redirect to login.php
// Call: check_session ()
//
function check_session() {

    global $CONF;
    
    $s = new Session();
    session_start();
    
    if (! isset($_SESSION[SVNSESSID])) {
        
        header("Location: login.php");
        exit();
    }
    
    $SESSID_USERNAME = $_SESSION[SVNSESSID][USERNAME];
    
    if ((isset($CONF['ldap_bind_use_login_data']) && ($CONF['ldap_bind_use_login_data'] == 1)) && (isset($CONF['ldap_bind_dn_suffix']))) {
        
        $CONF['bind_dn'] = $_SESSION[SVNSESSID][USERNAME] . $CONF['ldap_bind_dn_suffix'];
        $CONF['bind_pw'] = $_SESSION[SVNSESSID]['password'];
    }
    
    return $SESSID_USERNAME;
    
}

//
// check_session_lpw
// Action: Check if a session already exists, if not redirect to login.php
// Call: check_session ()
//
function check_session_lpw($redirect = "y") {

    $s = new Session();
    @session_start();
    
    if (! isset($_SESSION[SVNLPW])) {
        
        $SESSID_USERNAME = "";
        
        if ($redirect == "y") {
            header("Location: lostpassword.php");
            exit();
        }
    }
    else {
        
        if (isset($_SESSION[SVNLPW][USERNAME])) {
            $SESSID_USERNAME = $_SESSION[SVNLPW][USERNAME];
        }
        else {
            $SESSID_USERNAME = "";
        }
    }
    
    return $SESSID_USERNAME;
    
}

//
// check_session_status
// Action: Check if a session already exists, if not redirect to login.php
// Call: check_session_status ()
//
function check_session_status() {

    $ret = 0;
    $SESSID_USERNAME = "";
    @session_start();
    
    if (! isset($_SESSION[SVNSESSID])) {
        
        $ret = 0;
    }
    else {
        
        $ret = 1;
        $SESSID_USERNAME = $_SESSION[SVNSESSID][USERNAME];
    }
    
    return array(
            $ret,
            $SESSID_USERNAME
    );
    
}

//
// create_verify_string
// Action: create a verify string for email verification
// Call: create_verify_string
//
function create_verify_string() {

    $validchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
    $verifyString = "";
    for($i = 0; $i < 32; $i ++) {
        $char = chr(mt_rand(0, 255));
        while ( strpos($validchars, $char) == 0 ) {
            $char = chr(mt_rand(0, 255));
        }
        $verifyString .= $char;
    }
    
    return $verifyString;
    
}

//
// check_password_expired
// Action: checks if a password is expired and akes the user to the password change mask
// Call: check_password_expired
//
function check_password_expired() {

    if ((isset($_SESSION[SVNSESSID]['password_expired'])) && ($_SESSION[SVNSESSID]['password_expired'] == 1)) {
        
        header("Location: password.php");
        exit();
    }
    
}

//
// check_language
// Action: checks what language the browser uses
// Call: check_language
//
function check_language() {

    global $CONF;
    $supported_languages = array(
            'de',
            'en'
    );
    $accept_languages = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
    $lang_array = preg_split('/(\s*,\s*)/', $accept_languages);
    
    if (is_array($lang_array)) {
        
        $lang_first = strtolower((trim(strval($lang_array[0]))));
        $lang_first = substr($lang_first, 0, 2);
        
        if (in_array($lang_first, $supported_languages)) {
            
            $lang = $lang_first;
        }
        else {
            
            $lang = $CONF['default_language'];
        }
    }
    else {
        
        $lang = $CONF['default_language'];
    }
    
    return $lang;
    
}

//
// check_language
// Action: checks what language the browser uses
// Call: check_language
//
function get_locale() {

    global $CONF;
    
    if (isset($CONF)) {
        $supported_languages = $CONF['supported_languages'];
    }
    else {
        $supported_languages = array(
                'de',
                'de_DE',
                'en',
                'en_US'
        );
    }
    
    $lang_array = preg_split('/(\s*,\s*)/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    
    if (is_array($lang_array)) {
        
        $lang_first = strtolower((trim(strval($lang_array[0]))));
        $lang_parts = explode('-', $lang_array[0]);
        $count = count($lang_parts);
        if ($count == 2) {
            
            $lang_parts[1] = strtoupper($lang_parts[1]);
            $lang_first = implode("_", $lang_parts);
        }
        
        if (in_array($lang_first, $supported_languages)) {
            
            $lang = $lang_first;
        }
        else {
            
            $lang = $CONF['default_locale'];
        }
    }
    else {
        
        $lang = $CONF['default_locale'];
    }
    
    return $lang;
    
}

//
// check_string
// Action: checks if a string is valid and returns TRUE is this is the case.
// Call: check_string (string var)
//
function check_string($var) {

    return (preg_match('/^([A-Za-z0-9 ]+)+$/', $var));
    
}

//
// check_email
// Action: Checks if email is valid and returns TRUE if this is the case.
// Call: check_email (string email)
//
function check_email($email) {

    if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    else {
        return false;
    }
    
}

//
// getDateJhjjmmtt
// Action: retrieve date in format YYYYMMTT
// Call getDateJhjjmmtt
//
function getDateJhjjmmtt() {

    $date = getdate();
    $year = $date['year'];
    $mon = $date['mon'];
    $day = $date['mday'];
    
    if ($mon < "10") {
        $mon = "0" . $mon;
    }
    if ($day < "10") {
        $day = "0" . $day;
    }
    
    return ($year . $mon . $day);
    
}

//
// splitDate
// Action: convert date from jhjjmmtt to t.mm.jhjj
// Call: splitdate( string date )
//
function splitdate($date) {

    $year = substr($date, 0, 4);
    $mon = substr($date, 4, 2);
    $day = substr($date, 6, 2);
    
    return ($day . "." . $mon . "." . $year);
    
}

//
// check_date
// Action: check a date string if it is a valid date
// Call: check_date (string day, string month, string year)
//
function check_date($day, $month, $year) {

    return (preg_match('/[0-9]{2}/', $day) && preg_match('/[0-9]{2}/', $month) && preg_match('/[0-9]{4}/', $year) && checkdate($month, $day, $year));
    
}

function no_magic_quotes($query) {

    $data = explode("\\\\", $query);
    return (implode("\\", $data));
    
}

//
// generate_password
// Action: Generates a random password
// Call: generate_password ()
//
function generate_password() {

    return (substr(md5(mt_rand()), 0, 8));
    
}

function make_seed() {

    list($usec, $sec ) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
    
}

//
// generatePassword
// Action: Generates a random password
// Call: generatePassword ()
//
function generatePassword($admin) {

    global $CONF;
    
    if (strtolower($admin) == "y") {
        $pwLength = 14;
    }
    else {
        $pwLength = 8;
    }
    
    $password = "";
    
    while ( checkPasswordPolicy($password, strtolower($admin)) == 0 ) {
        
        $password = "";
        
        for($i = 1; $i <= $pwLength; $i ++) {
            
            $group = rand(0, 3);
            mt_srand(make_seed());
            
            switch ($group) {
                case 0 :
                    $index = rand(0, 25);
                    $value = chr($index + 65);
                    break;
                
                case 1 :
                    $index = rand(0, 25);
                    $value = chr($index + 97);
                    break;
                
                case 2 :
                    $value = rand(0, 9);
                    break;
                
                case 3 :
                    $group = rand(0, 2);
                    
                    switch ($group) {
                        case 0 :
                            $index = rand(33, 47);
                            break;
                        
                        case 1 :
                            $index = 60;
                            while ( ($index == 60) || ($index == 62) ) {
                                $index = rand(58, 64);
                            }
                            break;
                        
                        case 2 :
                            $index = rand(91, 96);
                            break;
                    }
                    
                    $value = chr($index);
                    break;
            }
            
            $password .= $value;
        }
    }
    
    return ($password);
    
}

//
// pacrypt
// Action: Encrypts password based on config settings
// Call: pacrypt (string cleartextpassword, optional hashedpassword)
//
function pacrypt($pw, $pw_db = "") {

    global $CONF;
    
    if ($pw_db != "") {
        $crypt = get_passwd_type_salt($pw_db, $salt);
    }
    else {
        $salt = "";
        
        if (isset($CONF[PWCRYPT]) && $CONF[PWCRYPT] != "") {
            $crypt = $CONF[PWCRYPT];
        }
        else {
            $crypt = "crypt";
        }
    }
    
    switch ($crypt) {
        case "sha" : //
            return '{SHA}' . base64_encode(pack('H*', sha1($pw)));
            break;
        case APRMD5 : // The modern Apache version of the MD5 password hash
            return md5crypt($pw, $salt, '$apr1$');
            break;
        case "md5" : // The Unix version of the MD5 password hash
            return md5crypt($pw, $salt, '$1$');
            break;
        case "crypt" :
            // crypt() can choose surprising behavior if the salt for DES-crypt is not provided
            if ($salt == "") {
                $salt = create_salt(2);
            }
            return crypt($pw, $salt);
            break;
        default :
            throw new Exception('Unsupported password hash type: "' . $crypt . '" from hash "' . $pw_db . '"');
    }
    
}

//
// get_passwd_type_salt
// Action: Parse hashed password to determine hash type and existing salt
// Call: get_passwd_type_salt (string hashed_password, string &salt)
//
function get_passwd_type_salt($hpw, &$salt) {

    // Looking first for "$<id>$<salt>$<hash>" pattern
    $split_hash = preg_split('/\$/', $hpw);
    
    if ($split_hash[0] == "" && $split_hash[1] != "") {
        switch ($split_hash[1]) {
            case "apr1" :
                $type = APRMD5;
                break;
            case "1" :
                $type = "md5";
                break;
            default :
                throw new Exception('Unsupported password hash type: ' . '"' . $split_hash[1] . '"');
        }
        $salt = $split_hash[2];
    }
    elseif (substr($hpw, 0, 5) == "{SHA}") {
        $type = "sha";
        $salt = "";
    }
    else {
        $type = "crypt";
        $salt = substr($hpw, 0, 2);
    }
    
    return $type;
    
}

//
// Legal hashed password character set for base 64 representation
//
$ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

//
// md5crypt
// Action: Creates MD5 encrypted password
// Call: md5crypt (string cleartextpassword, string existingsalt, string md5typeidentifier)
//
function md5crypt($pw, $salt = "", $magic = "") {

    if ($magic == "") {
        $magic = '$1$';
    }
    if ($salt == "") {
        $salt = create_salt(8);
    }
    
    $ctx = $pw . $magic . $salt;
    $final = myhex2bin(md5($pw . $salt . $pw));
    
    for($i = strlen($pw); $i > 0; $i -= 16) {
        if ($i > 16) {
            $ctx .= substr($final, 0, 16);
        }
        else {
            $ctx .= substr($final, 0, $i);
        }
    }
    $i = strlen($pw);
    
    while ( $i > 0 ) {
        
        if ($i & 1) {
            $ctx .= chr(0);
        }
        else {
            $ctx .= $pw[0];
        }
        $i = $i >> 1;
    }
    
    $final = myhex2bin(md5($ctx));
    
    for($i = 0; $i < 1000; $i ++) {
        
        $ctx1 = "";
        if ($i & 1) {
            
            $ctx1 .= $pw;
        }
        else {
            
            $ctx1 .= substr($final, 0, 16);
        }
        
        if ($i % 3) {
            $ctx1 .= $salt;
        }
        if ($i % 7) {
            $ctx1 .= $pw;
        }
        if ($i & 1) {
            $ctx1 .= substr($final, 0, 16);
        }
        else {
            $ctx1 .= $pw;
        }
        
        $final = myhex2bin(md5($ctx1));
    }
    
    $passwd = "";
    $passwd .= to64(((ord($final[0]) << 16) | (ord($final[6]) << 8) | (ord($final[12]))), 4);
    $passwd .= to64(((ord($final[1]) << 16) | (ord($final[7]) << 8) | (ord($final[13]))), 4);
    $passwd .= to64(((ord($final[2]) << 16) | (ord($final[8]) << 8) | (ord($final[14]))), 4);
    $passwd .= to64(((ord($final[3]) << 16) | (ord($final[9]) << 8) | (ord($final[15]))), 4);
    $passwd .= to64(((ord($final[4]) << 16) | (ord($final[10]) << 8) | (ord($final[5]))), 4);
    $passwd .= to64(ord($final[11]), 2);
    
    return "$magic$salt\$$passwd";
    
}

function digestcrypt($userid, $realm, $password) {

    $pw = md5($userid . ':' . $realm . ':' . $password);
    
    return ($pw);
    
}

function create_salt($len) {

    global $ITOA64;
    $maxidx = strlen($ITOA64) - 1;
    
    $salt = "";
    for($i = 0; $i < $len; $i ++) {
        $choice = mt_rand(0, $maxidx);
        $salt .= substr($ITOA64, $choice, 1);
    }
    return $salt;
    
}

function myhex2bin($str) {

    $len = strlen($str);
    $nstr = "";
    
    for($i = 0; $i < $len; $i += 2) {
        $num = sscanf(substr($str, $i, 2), "%x");
        $nstr .= chr($num[0]);
    }
    
    return $nstr;
    
}

function to64($v, $n) {

    global $ITOA64;
    $ret = "";
    
    while ( ($n - 1) >= 0 ) {
        $n --;
        $ret .= $ITOA64[$v & 0x3f];
        $v = $v >> 6;
    }
    
    return $ret;
    
}

function checkAdminPasswordPolicy($passwordLength, $groups) {

    global $CONF;
    
    if (isset($CONF['minPasswordlength'])) {
        $minPasswordLength = $CONF['minPasswordlength'];
    }
    else {
        $minPasswordLength = 14;
    }
    if ($passwordLength < $minPasswordLength) {
        
        $retval = 0;
    }
    else {
        
        if (isset($CONF['minPasswordGroups'])) {
            $minPasswordGroups = $CONF['minPasswordGroups'];
        }
        else {
            $minPasswordGroups = 4;
        }
        if (isset($minPasswordGroups)) {
            
            if (($minPasswordGroups < 1) || ($minPasswordGroups > 4)) {
                $minGroups = 4;
            }
            else {
                $minGroups = $minPasswordGroups;
            }
        }
        else {
            $minGroups = 4;
        }
        
        if ($groups < $minGroups) {
            
            $retval = 0;
        }
        else {
            
            $retval = 1;
        }
    }
    
    return ($retval);
    
}

function checkNormalPasswordPolicy($passwordLength, $groups) {

    global $CONF;
    
    if (isset($CONF['minPasswordlengthUser'])) {
        $minPasswordLengthUser = $CONF['minPasswordlengthUser'];
    }
    else {
        $minPasswordLengthUser = 8;
    }
    if ($passwordLength < $minPasswordLengthUser) {
        
        $retval = 0;
    }
    else {
        
        if (isset($CONF[MINPASSWORDGROUPUSER])) {
            
            if (($CONF[MINPASSWORDGROUPUSER] < 1) || ($CONF[MINPASSWORDGROUPUSER] > 4)) {
                $minGroupsUser = 3;
            }
            else {
                $minGroupsUser = $CONF[MINPASSWORDGROUPUSER];
            }
        }
        else {
            $minGroupsUser = 3;
        }
        
        if ($groups < $minGroupsUser) {
            
            $retval = 0;
        }
        else {
            
            $retval = 1;
        }
    }
    
    return ($retval);
    
}

//
// checkPasswordPolicy
// Action: check password against password policy
// call: checkPasswordPolicy
// Return: 1 = password ok, 0 = password not ok
//
function checkPasswordPolicy($password, $admin = "y") {

    global $CONF;
    
    $smallLetters = preg_match('/[a-z]/', $password);
    $capitalLetters = preg_match('/[A-Z]/', $password);
    $numbers = preg_match('/[0-9]/', $password);
    if (isset($CONF['passwordSpecialChars'])) {
        $pattern = '/' . $CONF['passwordSpecialChars'] . '/';
    }
    else {
        $pattern = '/' . '[\!\"\§\$\%\/\(\)=\?\*\+\#\-\_\.\:\,\;\<\>\|\@]' . '/';
    }
    $specialChars = preg_match($pattern, $password);
    $passwordLength = strlen($password);
    $groups = 0;
    
    if ($smallLetters != 0) {
        $groups ++;
    }
    
    if ($capitalLetters != 0) {
        $groups ++;
    }
    
    if ($numbers != 0) {
        $groups ++;
    }
    
    if ($specialChars != 0) {
        $groups ++;
    }
    
    if ($admin == "y") {
        
        $retval = checkAdminPasswordPolicy($passwordLength, $groups);
    }
    else {
        
        $retval = checkNormalPasswordPolicy($passwordLength, $groups);
    }
    
    return $retval;
    
}

//
// splitDateTime
// Action: split a datetime value from mysql to date and time
// Call: splitdateTime (string datetime)
//
function splitDateTime($datetime) {

    $year = substr($datetime, 0, 4);
    $month = substr($datetime, 4, 2);
    $day = substr($datetime, 6, 2);
    $date = $day . "." . $month . "." . $year;
    
    $hour = substr($datetime, 8, 2);
    $min = substr($datetime, 10, 2);
    $sec = substr($datetime, 12, 2);
    $time = $hour . ":" . $min . ":" . $sec;
    
    return array(
            $date,
            $time
    );
    
}

//
// splitValidDate
// Action split valid date
// Call: splitValidDate (string date)
//
function splitValidDate($date) {

    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2);
    
    return ($day . "." . $month . "." . $year);
    
}

//
// mkUnixTimestampFromDateTime
// Action: create a unix timestamp from a datetime field
// Call: mkUnixTimestampFromDateTime(string datetime)
//
function mkUnixTimestampFromDateTime($datetime) {

    $year = substr($datetime, 0, 4);
    $month = substr($datetime, 4, 2);
    $day = substr($datetime, 6, 2);
    $hour = substr($datetime, 8, 2);
    $min = substr($datetime, 10, 2);
    $sec = substr($datetime, 12, 2);
    
    return (mktime($hour, $min, $sec, $month, $day, $year, - 1));
    
}

//
// determineOs
// Action: determine if windows or not
// Call: determineOs()
//
function determineOs() {

    $ret = "undef";
    
    ob_start();
    eval("phpinfo();");
    $info = ob_get_contents();
    ob_end_clean();
    
    foreach( explode("\n", $info) as $line) {
        
        if (strpos($line, "System") !== false) {
            
            $show = trim(str_replace("System", "", strip_tags($line)));
        }
    }
    
    if (preg_match('/WIN/i', $show)) {
        
        $ret = "windows";
    }
    else {
        $ret = "unix";
    }
    
    return ($ret);
    
}

//
// encode_subject
// Action: encode subject of a email
// Call: encode_subject( string $in_str, string $charset )
//
function encode_subject($in_str, $charset) {

    $out_str = $in_str;
    if ($out_str && $charset) {
        
        // define start delimimter, end delimiter and spacer
        $end = "?=";
        $start = "=?" . $charset . "?B?";
        $spacer = $end . "\r\n " . $start;
        
        // determine length of encoded text within chunks
        // and ensure length is even
        $length = 75 - strlen($start) - strlen($end);
        
        /*
         * [EDIT BY danbrown AT php DOT net: The following
         * is a bugfix provided by (gardan AT gmx DOT de)
         * on 31-MAR-2005 with the following note:
         * "This means: $length should not be even,
         * but divisible by 4. The reason is that in
         * base64-encoding 3 8-bit-chars are represented
         * by 4 6-bit-chars. These 4 chars must not be
         * split between two encoded words, according
         * to RFC-2047.
         */
        $length = $length - ($length % 4);
        
        // encode the string and split it into chunks
        // with spacers after each chunk
        $out_str = base64_encode($out_str);
        $out_str = chunk_split($out_str, $length, $spacer);
        
        // remove trailing spacer and
        // add start and end delimiters
        $spacer = preg_quote($spacer);
        $out_str = preg_replace("/" . $spacer . "$/", "", $out_str);
        $out_str = $start . $out_str . $end;
    }
    return $out_str;
    
}

//
// sortLdapUsers
// Action: sort ldap user by a preconfigured field
// Call: sortLdapUsers( string $a, string $b )
//
function sortLdapUsers($a, $b) {

    global $CONF;
    
    $aValue = $a[$CONF['ldap_sort_field']];
    $bValue = $b[$CONF['ldap_sort_field']];
    
    $aValue = strtolower($aValue);
    $bValue = strtolower($bValue);
    
    if (isset($CONF['ldap_sort_order']) && $CONF['ldap_sort_order'] == "DESC") {
        // sort desc
        return $aValue < $bValue;
    }
    else {
        // sort asc
        return $aValue > $bValue;
    }
    
}

//
// rand_name
// Action: Create a safe random name using alphabetic and numeric characters only
//
function rand_name($len = 8) {

    $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $maxidx = strlen($charset) - 1;
    
    $name = "";
    for($i = 0; $i < $len; $i ++) {
        $choice = mt_rand(0, $maxidx);
        $name .= substr($charset, $choice, 1);
    }
    return $name;
    
}

//
// getGrepCommand
// Action: determine grep command
//
function getGrepCommand($tGrepCommand) {

    $greppath = array(
            '/usr/local/bin/grep',
            '/usr/bin/grep',
            '/bin/grep'
    );
    
    for($i = 0; $i < count($greppath); $i ++) {
        if (file_exists($greppath[$i]) && ($tGrepCommand == "")) {
            
            $tGrepCommand = $greppath[$i];
        }
    }
    
    return ($tGrepCommand);
    
}

//
// getSvnadminCommand
// Action: determine svnadmin command
//
function getSvnadminCommand($tSvnadminCommand) {

    $svnadminpath = array(
            '/usr/local/bin/svnadmin',
            '/usr/bin/svnadmin',
            '/bin/svnadmin'
    );
    
    for($i = 0; $i < count($svnadminpath); $i ++) {
        if (file_exists($svnadminpath[$i]) && ($tSvnadminCommand == "")) {
            
            $tSvnadminCommand = $svnadminpath[$i];
        }
    }
    
    return ($tSvnadminCommand);
    
}

//
// getApacheReloadCommand
// Action: assemble apache reload command
//
function getApacheReloadCommand($tViewvcApacheReload) {

    $apachepath = array(
            '/etc/init.d/httpd',
            '/etc/init.d/apache2',
            '/etc/init.d/apache'
    );
    
    for($i = 0; $i < count($apachepath); $i ++) {
        if (file_exists($apachepath[$i]) && ($tViewvcApacheReload == "")) {
            
            $tViewvcApacheReload = "sudo " . $apachepath[$i] . " graceful";
        }
    }
    
    return ($tViewvcApacheReload);
    
}

//
// getSvnCommand
// Actopn: determine svn command
//
function getSvnCommand($tSvnCommand) {

    // common locations where to find grep and svn under linux/unix
    $svnpath = array(
            '/usr/local/bin/svn',
            '/usr/bin/svn',
            '/bin/svn'
    );
    
    for($i = 0; $i < count($svnpath); $i ++) {
        if (file_exists($svnpath[$i]) && ($tSvnCommand == "")) {
            
            $tSvnCommand = $svnpath[$i];
        }
    }
    
    return ($tSvnCommand);
    
}

//
// unlinkFile
// Action: delete a file on Windows systems
//
function unlinkFile($os, $filename) {

    if (($os == WINDOWS) && file_exists($filename)) {
        unlink($filename);
    }
    
}

//
// getSlash
// Action: determine slash dependant on OS
//
function getSlash($os) {

    return (($os == WINDOWS) ? "\\" : "/");
    
}

//
// translateRight
// Action: translate right from database value to auth file value
//
function translateRight($right) {

    // Right 'none' handled in else branch
    if ($right == "read") {
        
        $right = "r";
    }
    elseif ($right == "write") {
        
        $right = "rw";
    }
    else {
        
        $right = "";
    }
    
    return ($right);
    
}

//
// Installer setters
//
function setEncryption($tPwEnc) {

    switch ($tPwEnc) {
        case 'sha' :
            $tPwSha = CHECKED;
            $tPwApacheMd5 = "";
            $tPwMd5 = "";
            $tPwCrypt = "";
            $tPwType = "sha";
            break;
        
        case APRMD5 :
            $tPwSha = "";
            $tPwApacheMd5 = CHECKED;
            $tPwMd5 = "";
            $tPwCrypt = "";
            $tPwType = APRMD5;
            break;
        
        case 'md5' :
            $tPwSha = "";
            $tPwApacheMd5 = "";
            $tPwMd5 = CHECKED;
            $tPwCrypt = "";
            $tPwType = "md5";
            break;
        
        default :
            $tPwSha = "";
            $tPwApacheMd5 = "";
            $tPwMd5 = "";
            $tPwCrypt = CHECKED;
            $tPwType = "crypt";
    }
    
    return (array(
            $tPwSha,
            $tPwApacheMd5,
            $tPwMd5,
            $tPwCrypt,
            $tPwType
    ));
    
}

function setUserDefaultAccess($tUserDefaultAccess) {

    if ($tUserDefaultAccess == "read") {
        $tUserDefaultAccessRead = CHECKED;
        $tUserDefaultAccessWrite = "";
    }
    else {
        $tUserDefaultAccessRead = "";
        $tUserDefaultAccessWrite = CHECKED;
    }
    
    return (array(
            $tUserDefaultAccessRead,
            $tUserDefaultAccessWrite
    ));
    
}

function setPasswordExpires($tExpirePassword) {

    if ($tExpirePassword == 1) {
        $tExpirePasswordYes = CHECKED;
        $tExpirePasswordNo = "";
    }
    else {
        $tExpirePasswordYes = "";
        $tExpirePasswordNo = CHECKED;
    }
    
    return (array(
            $tExpirePasswordYes,
            $tExpirePasswordNo
    ));
    
}

function setLogging($tLogging) {

    if ($tLogging == "YES") {
        $tLoggingYes = CHECKED;
        $tLoggingNo = "";
    }
    else {
        $tLoggingYes = "";
        $tLoggingNo = CHECKED;
    }
    
    return (array(
            $tLoggingYes,
            $tLoggingNo
    ));
    
}

function setJavaScript($tJavaScript) {

    if ($tJavaScript == "YES") {
        $tJavaScriptYes = CHECKED;
        $tJavaScriptNo = "";
    }
    else {
        $tJavaScriptYes = "";
        $tJavaScriptNo = CHECKED;
    }
    
    return (array(
            $tJavaScriptYes,
            $tJavaScriptNo
    ));
    
}

function setViewvcConfig($tViewvcConfig) {

    if ($tViewvcConfig == "YES") {
        $tViewvcConfigYes = CHECKED;
        $tViewvcConfigNo = "";
    }
    else {
        $tViewvcConfigYes = "";
        $tViewvcConfigNo = CHECKED;
    }
    
    return (array(
            $tViewvcConfigYes,
            $tViewvcConfigNo
    ));
    
}

function setAnonAccess($tAnonAccess) {

    if ($tAnonAccess == 1) {
        $tAnonAccessYes = CHECKED;
        $tAnonAccessNo = "";
    }
    else {
        $tAnonAccessYes = "";
        $tAnonAccessNo = CHECKED;
    }
    
    return (array(
            $tAnonAccessYes,
            $tAnonAccessNo
    ));
    
}

function setLdapBindUseLoginData($tLdapBindUseLoginData) {

    if ($tLdapBindUseLoginData == 0) {
        $tLdapBindUseLoginDataYes = "";
        $tLdapBindUseLoginDataNo = CHECKED;
    }
    else {
        $tLdapBindUseLoginDataYes = CHECKED;
        $tLdapBindUseLoginDataNo = "";
    }
    
    return (array(
            $tLdapBindUseLoginDataYes,
            $tLdapBindUseLoginDataNo
    ));
    
}

function setLdapUserSort($tLdapUserSort) {

    if ($tLdapUserSort == "ASC") {
        $tLdapUserSortAsc = CHECKED;
        $tLdapUserSortDesc = "";
    }
    else {
        $tLdapUserSortAsc = "";
        $tLdapUserSortDesc = CHECKED;
    }
    
    return (array(
            $tLdapUserSortAsc,
            $tLdapUserSortDesc
    ));
    
}

function setPathSortOrder($tPathSortOrder) {

    if ($tPathSortOrder == "ASC") {
        $tPathSortOrderAsc = CHECKED;
        $tPathSortOrderDesc = "";
    }
    else {
        $tPathSortOrderAsc = "";
        $tPathSortOrderDesc = CHECKED;
    }
    
    return (array(
            $tPathSortOrderAsc,
            $tPathSortOrderDesc
    ));
    
}

function setPerRepoFiles($tPerRepoFiles) {

    if ($tPerRepoFiles == "YES") {
        $tPerRepoFilesYes = CHECKED;
        $tPerRepoFilesNo = "";
    }
    else {
        $tPerRepoFilesYes = "";
        $tPerRepoFilesNo = CHECKED;
    }
    
    return (array(
            $tPerRepoFilesYes,
            $tPerRepoFilesNo
    ));
    
}

function setAccessControlLevel($tAccessControlLevel) {

    if ($tAccessControlLevel == "dirs") {
        $tAccessControlLevelDirs = CHECKED;
        $tAccessControlLevelFiles = "";
    }
    else {
        $tAccessControlLevelDirs = "";
        $tAccessControlLevelFiles = CHECKED;
    }
    
    return (array(
            $tAccessControlLevelDirs,
            $tAccessControlLevelFiles
    ));
    
}

function setDatabaseValues($tDatabase) {

    switch ($tDatabase) {
        case 'mysql' :
            $tDatabaseMySQL = CHECKED;
            $tDatabaseMySQLi = "";
            $tDatabasePostgreSQL = "";
            $tDatabaseOracle = "";
            break;
        
        case 'mysqli' :
            $tDatabaseMySQL = "";
            $tDatabaseMySQLi = CHECKED;
            $tDatabasePostgreSQL = "";
            $tDatabaseOracle = "";
            break;
        
        case 'postgres8' :
            $tDatabaseMySQL = "";
            $tDatabaseMySQLi = "";
            $tDatabasePostgreSQL = CHECKED;
            $tDatabaseOracle = "";
            break;
        
        case 'oci8' :
            $tDatabaseMySQL = "";
            $tDatabaseMySQLi = "";
            $tDatabasePostgreSQL = "";
            $tDatabaseOracle = CHECKED;
            break;
        
        default :
            $tDatabaseMySQL = "";
            $tDatabaseMySQLi = "";
            $tDatabasePostgreSQL = "";
            $tDatabaseOracle = "";
    }
    
    return (array(
            $tDatabaseMySQL,
            $tDatabaseMySQLi,
            $tDatabasePostgreSQL,
            $tDatabaseOracle
    ));
    
}

function setUseSvnAccessFile($tUseSvnAccessFile) {

    if ($tUseSvnAccessFile == "YES") {
        $tUseSvnAccessFileYes = CHECKED;
        $tUseSvnAccessFileNo = "";
    }
    else {
        $tUseSvnAccessFileYes = "";
        $tUseSvnAccessFileNo = CHECKED;
    }
    
    return (array(
            $tUseSvnAccessFileYes,
            $tUseSvnAccessFileNo
    ));
    
}

function setUseAuthUserFile($tUseAuthUserFile) {

    if ($tUseAuthUserFile == "YES") {
        $tUseAuthUserFileYes = CHECKED;
        $tUseAuthUserFileNo = "";
    }
    else {
        $tUseAuthUserFileYes = "";
        $tUseAuthUserFileNo = CHECKED;
    }
    
    return (array(
            $tUseAuthUserFileYes,
            $tUseAuthUserFileNo
    ));
    
}

function setLdapprotocol($tLdapProtocol) {

    if ($tLdapProtocol == "3") {
        $tLdap3 = CHECKED;
        $tLdap2 = "";
    }
    else {
        $tLdap3 = "";
        $tLdap2 = CHECKED;
    }
    
    return (array(
            $tLdap2,
            $tLdap3
    ));
    
}

function setUseLdap($tUseLdap) {

    if ($tUseLdap == "YES") {
        $tUseLdapYes = CHECKED;
        $tUseLdapNo = "";
    }
    else {
        $tUseLdapYes = "";
        $tUseLdapNo = CHECKED;
    }
    
    return (array(
            $tUseLdapYes,
            $tUseLdapNo
    ));
    
}

function setSessionIndatabase($tSessionInDatabase) {

    if ($tSessionInDatabase == "YES") {
        $tSessionInDatabaseYes = CHECKED;
        $tSessionInDatabaseNo = "";
    }
    else {
        $tSessionInDatabaseYes = "";
        $tSessionInDatabaseNo = CHECKED;
    }
    
    return (array(
            $tSessionInDatabaseYes,
            $tSessionInDatabaseNo
    ));
    
}

function setDropDatabaseTables($tDropDatabaseTables) {

    if ($tDropDatabaseTables == "YES") {
        $tDropDatabaseTablesYes = CHECKED;
        $tDropDatabaseTablesNo = "";
    }
    else {
        $tDropDatabaseTablesYes = "";
        $tDropDatabaseTablesNo = CHECKED;
    }
    
    return (array(
            $tDropDatabaseTablesYes,
            $tDropDatabaseTablesNo
    ));
    
}

function setCreateDatabaseTables($tCreateDatabaseTables) {

    if ($tCreateDatabaseTables == "YES") {
        $tCreateDatabaseTablesYes = CHECKED;
        $tCreateDatabaseTablesNo = "";
    }
    else {
        $tCreateDatabaseTablesYes = "";
        $tCreateDatabaseTablesNo = CHECKED;
    }
    
    return (array(
            $tCreateDatabaseTablesYes,
            $tCreateDatabaseTablesNo
    ));
    
}

function setDatabaseCharset($tDatabase) {

    if ((strtoupper($tDatabase) == 'MYSQL') || (strtoupper($tDatabase) == 'MYSQLI')) {
        $tDatabaseCharsetDefault = "latin1";
        $tDatabaseCollationDefault = "latin1_german1_ci";
    }
    else {
        $tDatabaseCharsetDefault = "";
        $tDatabaseCollationDefault = "";
    }
    
    return (array(
            $tDatabaseCharsetDefault,
            $tDatabaseCollationDefault
    ));
    
}

function getLoggingFromSession() {

    return (isset($_SESSION[SVN_INST]['logging']) ? $_SESSION[SVN_INST]['logging'] : "YES");
    
}

function getJavaScriptFromSession() {

    return (isset($_SESSION[SVN_INST]['javaScript']) ? $_SESSION[SVN_INST]['javaScript'] : "YES");
    
}

function getPageSizeFromSession() {

    return (isset($_SESSION[SVN_INST]['pageSize']) ? $_SESSION[SVN_INST]['pageSize'] : "30");
    
}

function getMinAdminPwSizeFromSession() {

    return (isset($_SESSION[SVN_INST]['minAdminPwSize']) ? $_SESSION[SVN_INST]['minAdminPwSize'] : "14");
    
}

function getMinUserPwSizeFromSession() {

    return (isset($_SESSION[SVN_INST]['minUserPwSize']) ? $_SESSION[SVN_INST]['minUserPwSize'] : "8");
    
}

function getExpirePasswordFromSession() {

    return (isset($_SESSION[SVN_INST]['expirePassword']) ? $_SESSION[SVN_INST]['expirePassword'] : 1);
    
}

function getPwEncFromSession() {

    return (isset($_SESSION[SVN_INST]['pwEnc']) ? $_SESSION[SVN_INST]['pwEnc'] : "md5");
    
}

function getUserDefaultAccessFromSession() {

    return (isset($_SESSION[SVN_INST]['userDefaultAccess']) ? $_SESSION[SVN_INST]['userDefaultAccess'] : "read");
    
}

function getCustom1FromSession() {

    return (isset($_SESSION[SVN_INST]['custom1']) ? $_SESSION[SVN_INST]['custom1'] : "");
    
}

function getCustom2FromSession() {

    return (isset($_SESSION[SVN_INST]['custom2']) ? $_SESSION[SVN_INST]['custom2'] : "");
    
}

function getCustom3FromSession() {

    return (isset($_SESSION[SVN_INST]['custom3']) ? $_SESSION[SVN_INST]['custom3'] : "");
    
}

function getAuthUserFileFromSession() {

    return (isset($_SESSION[SVN_INST]['authUserFile']) ? $_SESSION[SVN_INST]['authUserFile'] : "");
    
}

function getSvnAccessFileFromSession() {

    return (isset($_SESSION[SVN_INST]['svnAccessFile']) ? $_SESSION[SVN_INST]['svnAccessFile'] : "");
    
}

?>
