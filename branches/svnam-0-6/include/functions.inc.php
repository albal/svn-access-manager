<?php
/**
 *
 * @filesource
 */
/**
 *
 * Functions to make work easier.
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
error_reporting(E_NOTICE | E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
ini_set('log_errors', 'On');
ini_set('html_errors', 'Off');

if (preg_match("/functions.inc.php/", $_SERVER['PHP_SELF'])) {
    
    header("Location: login.php");
    exit();
}

/**
 * get php version in two digit format like "53"
 *
 * @source
 * @return string
 */
function getPhpVersion() {

    $version = explode(".", PHP_VERSION);
    
    return ($version[0] . $version[1]);
    
}

/**
 * check if running in php client
 *
 * @source
 * @return boolean
 */
function runInCli() {

    return (php_sapi_name() == "cli");
    
}

/**
 * inialize gettext
 *
 * @source
 * @return void
 */
function initialize_i18n() {

    $locale = get_locale();
    
    if (! preg_match('/_/', $locale)) {
        $locale = $locale . "_" . strtoupper($locale);
    }
    
    putenv("LANG=$locale");
    putenv("LC_ALL=$locale");
    setlocale(LC_ALL, 0);
    setlocale(LC_ALL, $locale);
    
    /**
     * Path "./locale/de/LC_MESSAGES/messages.mo" handled in else branch
     */
    if (file_exists(realpath("../locale/de/LC_MESSAGES/messages.mo"))) {
        $localepath = "../locale";
    }
    else {
        $localepath = "./locale";
    }
    
    bindtextdomain("messages", $localepath);
    textdomain("messages");
    bind_textdomain_codeset(MESSAGES, 'UTF-8');
    
}

/**
 * Check if a session already exists, if not redirect to login.php
 *
 * @source
 * @return string
 */
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

/**
 * Check if a session already exists, if not redirect to login.php
 *
 * @source
 * @param string $redirect
 * @return string
 */
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

/**
 * Check if a session already exists, if not redirect to login.php
 *
 * @source
 * @return array
 */
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

/**
 * create a verify string for email verification
 *
 * @source
 * @return string The string to be used for verification
 *        
 */
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

/**
 * checks if a password is expired and takes the user to the password change mask
 *
 * @return void
 */
function check_password_expired() {

    if ((isset($_SESSION[SVNSESSID]['password_expired'])) && ($_SESSION[SVNSESSID]['password_expired'] == 1)) {
        
        header("Location: password.php");
        exit();
    }
    
}

/**
 * checks what language the browser uses
 *
 * @return string The language string
 */
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

/**
 * Get the locale from the accepted languages, checks what language the browser uses
 *
 *
 * @return string
 *
 */
function get_locale() {

    /**
     *
     * @global $CONF
     */
    global $CONF;
    
    if (isset($CONF) && isset($CONF['supported_languages'])) {
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

/**
 * checks if a string is valid and returns TRUE is this is the case.
 *
 * @param string $var
 * @return boolean True if string meets regex
 *        
 */
function check_string($var) {

    return (preg_match('/^([A-Za-z0-9 ]+)+$/', $var));
    
}

/**
 * Checks if email is valid and returns TRUE if this is the case.
 *
 * @param string $email
 * @return boolean True if email address is valid
 *        
 */
function check_email($email) {

    if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    else {
        return false;
    }
    
}

/**
 * retrieve date in format YYYYMMTT
 *
 * @return string
 */
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

/**
 * convert date from jhjjmmtt to t.mm.jhjj
 *
 * @param string $date
 * @return string
 *
 */
function splitdate($date) {

    $year = substr($date, 0, 4);
    $mon = substr($date, 4, 2);
    $day = substr($date, 6, 2);
    
    return ($day . "." . $mon . "." . $year);
    
}

/**
 * convert a date according to language settings
 *
 * @param string $day
 * @param string $month
 * @param string $year
 * @return string
 */
function convert_date_i18n($day, $month, $year) {

    return ((check_language() == 'de') ? $day . '.' . $month . '.' . $year : $month . '/' . $day . '/' . $year);
    
}

/**
 * Check a date if its numeric and valid
 *
 * @param string $day
 *            The day
 * @param string $month
 *            The month
 * @param string $year
 *            The year must have 4 digits
 *            
 * @return boolean True if date is valid
 * @source
 *
 */
function check_date($day, $month, $year) {

    return (preg_match('/[0-9]{2}/', $day) && preg_match('/[0-9]{2}/', $month) && preg_match('/[0-9]{4}/', $year) && checkdate($month, $day, $year));
    
}

/**
 * remove magic quotes
 *
 * @param string $query
 * @return string
 *
 */
function no_magic_quotes($query) {

    $data = explode("\\\\", $query);
    return (implode("\\", $data));
    
}

/**
 * Generates a random password
 *
 * @return string
 */
function generate_password() {

    return (substr(md5(mt_rand()), 0, 8));
    
}

/**
 * create a seed
 *
 * @return number
 */
function make_seed() {

    list($usec, $sec ) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
    
}

/**
 * Generates a random password
 *
 * @param string $admin
 * @return string The generated password
 *        
 */
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

/**
 * Encrypts password based on config settings
 *
 * @param string $pw
 * @param string $pw_db
 * @throws Exception
 * @return string Encrypted password
 *        
 */
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
        case "sha" :
            /**
             * sha password hash
             */
            return '{SHA}' . base64_encode(pack('H*', sha1($pw)));
            break;
        /**
         * The modern Apache version of the MD5 password hash
         */
        case APRMD5 :
            return md5crypt($pw, $salt, '$apr1$');
            break;
        /**
         * The Unix version of the MD5 password hash
         */
        case "md5" :
            return md5crypt($pw, $salt, '$1$');
            break;
        case "crypt" :
            /**
             * crypt() can choose surprising behavior if the salt for DES-crypt is not provided
             */
            if ($salt == "") {
                $salt = create_salt(2);
            }
            return crypt($pw, $salt);
            break;
        default :
            throw new Exception('Unsupported password hash type: "' . $crypt . '" from hash "' . $pw_db . '"');
    }
    
}

/**
 * Parse hashed password to determine hash type and existing salt
 *
 * @param string $hpw
 * @param string $salt
 * @throws Exception
 * @return string The type of hash
 *        
 */
function get_passwd_type_salt($hpw, &$salt) {

    /**
     * Looking first for "$<id>$<salt>$<hash>" pattern
     */
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

/**
 * Legal hashed password character set for base 64 representation
 *
 * @global string $ITOA64
 */
$ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

/**
 *
 * Creates MD5 encrypted password
 *
 * @param string $pw
 * @param string $salt
 * @param string $magic
 * @return string
 *
 */
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

/**
 * digest crypt
 *
 * @param string $userid
 * @param string $realm
 * @param string $password
 * @return string Encrypted password
 *        
 */
function digestcrypt($userid, $realm, $password) {

    $pw = md5($userid . ':' . $realm . ':' . $password);
    
    return ($pw);
    
}

/**
 * create a salt
 *
 * @param integer $len
 * @return string Created salt
 */
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

/**
 * own hex2bin function
 *
 * @param string $str
 * @return string
 */
function myhex2bin($str) {

    $len = strlen($str);
    $nstr = "";
    
    for($i = 0; $i < $len; $i += 2) {
        $num = sscanf(substr($str, $i, 2), "%x");
        $nstr .= chr($num[0]);
    }
    
    return $nstr;
    
}

/**
 * to64
 *
 * @param string $v
 * @param string $n
 * @return string
 */
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

/**
 * check if an admin password is compliant to the policy
 *
 * @param integer $passwordLength
 * @param integer $groups
 * @return integer Returns 1 if compliant
 *        
 */
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

/**
 * check id´f a normal user password is compliant to the policy
 *
 * @param integer $passwordLength
 * @param integer $groups
 * @return integer Returns 1 if compliant
 *        
 */
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

/**
 * check password against password policy
 *
 * @param string $password
 * @param string $admin
 * @return integer Returns 1 if password is compliant
 */
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

/**
 * split a datetime value from mysql to date and time
 *
 * @param string $datetime
 * @return string[]
 */
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

/**
 *
 * @param string $datetime
 * @return string[]
 */
function splitDateTimeI18n($datetime) {

    $year = substr($datetime, 0, 4);
    $month = substr($datetime, 4, 2);
    $day = substr($datetime, 6, 2);
    $date = convert_date_i18n($day, $month, $year);
    
    $hour = substr($datetime, 8, 2);
    $min = substr($datetime, 10, 2);
    $sec = substr($datetime, 12, 2);
    $time = $hour . ":" . $min . ":" . $sec;
    
    return array(
            $date,
            $time
    );
    
}

/**
 * split valid date
 *
 * @param string $date
 * @return string
 *
 */
function splitValidDate($date) {

    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2);
    
    return ($day . "." . $month . "." . $year);
    
}

/**
 * split a date in format yyyymmdd into a bootstrap compatible format
 *
 * @param string $date
 * @return string
 *
 */
function splitDateForBootstrap($date) {

    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2);
    
    return ($year . '-' . $month . '-' . $day);
    
}

/**
 * create a unix timestamp from a datetime field
 *
 * @param string $datetime
 * @return integer
 *
 */
function mkUnixTimestampFromDateTime($datetime) {

    $year = substr($datetime, 0, 4);
    $month = substr($datetime, 4, 2);
    $day = substr($datetime, 6, 2);
    $hour = substr($datetime, 8, 2);
    $min = substr($datetime, 10, 2);
    $sec = substr($datetime, 12, 2);
    
    return (mktime($hour, $min, $sec, $month, $day, $year));
    
}

/**
 * determine if windows or not
 *
 * @return string
 */
function determineOs() {

    $ret = "undef";
    
    ob_start();
    eval("phpinfo();");
    $info = ob_get_contents();
    ob_end_clean();
    
    foreach( explode("\n", $info) as $line ) {
        
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

/**
 * encode subject of a email
 *
 * @param string $in_str
 * @param string $charset
 * @return string The encdoded subject string
 */
function encode_subject($in_str, $charset) {

    $out_str = $in_str;
    if ($out_str && $charset) {
        
        /**
         * define start delimimter, end delimiter and spacer
         */
        $end = "?=";
        $start = "=?" . $charset . "?B?";
        $spacer = $end . "\r\n " . $start;
        
        /**
         * determine length of encoded text within chunks
         * and ensure length is even
         */
        $length = 75 - strlen($start) - strlen($end);
        
        /**
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
        
        /**
         * encode the string and split it into chunks
         * with spacers after each chunk
         */
        $out_str = base64_encode($out_str);
        $out_str = chunk_split($out_str, $length, $spacer);
        
        /**
         * remove trailing spacer and
         * add start and end delimiters
         */
        $spacer = preg_quote($spacer);
        $out_str = preg_replace("/" . $spacer . "$/", "", $out_str);
        $out_str = $start . $out_str . $end;
    }
    return $out_str;
    
}

/**
 * sort ldap user by a preconfigured field.
 * If preconfigured field is not available fall back to uid for sort.
 *
 * @param array $a
 * @param array $b
 * @return boolean
 */
function sortLdapUsers($a, $b) {

    global $CONF;
    
    if (array_key_exists($CONF[LDAP_SORT_FIELD], $a)) {
        $aValue = $a[$CONF[LDAP_SORT_FIELD]];
    }
    else {
        $aValue = $a['uid'];
    }
    
    if (array_key_exists($CONF[LDAP_SORT_FIELD], $b)) {
        $bValue = $b[$CONF[LDAP_SORT_FIELD]];
    }
    else {
        $bValue = $b['uid'];
    }
    
    $aValue = strtolower($aValue);
    $bValue = strtolower($bValue);
    
    if (isset($CONF['ldap_sort_order']) && $CONF['ldap_sort_order'] == "DESC") {
        /**
         * sort descending
         */
        return $aValue < $bValue;
    }
    else {
        /**
         * sort ascending
         */
        return $aValue > $bValue;
    }
    
}

/**
 * Create a safe random name using alphabetic and numeric characters only
 *
 * @param number $len
 * @return string
 */
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

/**
 * determine grep command
 * #
 *
 * @param string $tGrepCommand
 * @return string
 *
 */
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

/**
 * determine svnadmin command
 *
 * @param string $tSvnadminCommand
 * @return string
 */
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

/**
 * assemble apache reload command
 *
 * @param string $tViewvcApacheReload
 * @return string
 */
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

/**
 * determine svn command
 *
 * @param string $tSvnCommand
 * @return string
 */
function getSvnCommand($tSvnCommand) {

    /**
     * common locations where to find grep and svn under linux/unix
     */
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

/**
 * delete a file on Windows systems
 *
 * @param string $os
 * @param string $filename
 */
function unlinkFile($os, $filename) {

    if (($os == WINDOWS) && file_exists($filename)) {
        unlink($filename);
    }
    
}

/**
 * determine slash dependant on OS
 *
 * @param string $os
 * @return string
 */
function getSlash($os) {

    return (($os == WINDOWS) ? "\\" : "/");
    
}

/**
 * ranslate right from database value to auth file value
 *
 * @param string $right
 * @return string
 */
function translateRight($right) {

    /**
     * Right 'none' handled in else branch
     */
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

/**
 * set encryption
 *
 * @param string $tPwEnc
 * @return string[]
 */
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

/**
 * set user default access
 *
 * @param string $tUserDefaultAccess
 * @return string[]
 */
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

/**
 * set password expires
 *
 * @param string $tExpirePassword
 * @return string[]
 */
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

/**
 * set logging
 *
 * @param string $tLogging
 * @return string[]
 */
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

/**
 * set JavaScript usage
 *
 * @param string $tJavaScript
 * @return string[]
 */
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

/**
 * set viewvc config
 *
 * @param string $tViewvcConfig
 * @return string[]
 */
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

/**
 * set anonymous access
 *
 * @param string $tAnonAccess
 * @return string[]
 */
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

/**
 * set if ldap uses login data of user for bind
 *
 * @param string $tLdapBindUseLoginData
 * @return string[]
 */
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

/**
 * set ldap user sort order
 *
 * @param string $tLdapUserSort
 * @return string[]
 */
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

/**
 * set path sort oreder
 *
 * @param string $tPathSortOrder
 * @return string[]
 */
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

/**
 * set per repo files
 *
 * @param string $tPerRepoFiles
 * @return string[]
 */
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

/**
 * set access control level
 *
 * @param string $tAccessControlLevel
 * @return string[]
 */
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

/**
 * set database values dependant on database type.
 * Currently MySQL, PostgreSQL and Oracle are supported.
 *
 * @param string $tDatabase
 * @return string[]
 */
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

/**
 * set usage of svn access file
 *
 * @param string $tUseSvnAccessFile
 * @return string[]
 */
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

/**
 * set usage of user auth file
 *
 * @param string $tUseAuthUserFile
 * @return string[]
 */
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

/**
 * set ldap protocol to use
 *
 * @param string $tLdapProtocol
 * @return string[]
 */
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

/**
 * sewt ldap usage
 *
 * @param string $tUseLdap
 * @return string[]
 */
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

/**
 * set session handling.
 * Session can be in databaayew or in filesystem.
 *
 * @param string $tSessionInDatabase
 * @return string[]
 */
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

/**
 * set if database tables are dropped during installation.
 *
 * @param string $tDropDatabaseTables
 * @return string[]
 */
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

/**
 * set create of database tables during installation
 *
 * @param string $tCreateDatabaseTables
 * @return string[]
 */
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

/**
 * set datav´base characterset
 *
 * @param string $tDatabase
 * @return string[]
 */
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

/**
 * get loggong config from session
 *
 * @return string
 */
function getLoggingFromSession() {

    return (isset($_SESSION[SVN_INST]['logging']) ? $_SESSION[SVN_INST]['logging'] : "YES");
    
}

/**
 * get JavaScript setting dfrom session
 *
 * @return string
 */
function getJavaScriptFromSession() {

    return (isset($_SESSION[SVN_INST]['javaScript']) ? $_SESSION[SVN_INST]['javaScript'] : "YES");
    
}

/**
 * get page size from session
 *
 * @return string
 */
function getPageSizeFromSession() {

    return (isset($_SESSION[SVN_INST]['pageSize']) ? $_SESSION[SVN_INST]['pageSize'] : "30");
    
}

/**
 * get minimal admin password length from session
 *
 * @return string
 */
function getMinAdminPwSizeFromSession() {

    return (isset($_SESSION[SVN_INST]['minAdminPwSize']) ? $_SESSION[SVN_INST]['minAdminPwSize'] : "14");
    
}

/**
 * get minimal user password length from session
 *
 * @return string
 */
function getMinUserPwSizeFromSession() {

    return (isset($_SESSION[SVN_INST]['minUserPwSize']) ? $_SESSION[SVN_INST]['minUserPwSize'] : "8");
    
}

/**
 * get passwoerd expires from session
 *
 * @return number
 */
function getExpirePasswordFromSession() {

    return (isset($_SESSION[SVN_INST]['expirePassword']) ? $_SESSION[SVN_INST]['expirePassword'] : 1);
    
}

/**
 * get password encryption cfrom session
 *
 * @return string
 */
function getPwEncFromSession() {

    return (isset($_SESSION[SVN_INST]['pwEnc']) ? $_SESSION[SVN_INST]['pwEnc'] : "md5");
    
}

/**
 * get default user av´ccess from session
 *
 * @return string
 */
function getUserDefaultAccessFromSession() {

    return (isset($_SESSION[SVN_INST]['userDefaultAccess']) ? $_SESSION[SVN_INST]['userDefaultAccess'] : "read");
    
}

/**
 * get custom field 1 from session
 *
 * @return string
 */
function getCustom1FromSession() {

    return (isset($_SESSION[SVN_INST]['custom1']) ? $_SESSION[SVN_INST]['custom1'] : "");
    
}

/**
 * get custom field 2 from session
 *
 * @return string
 */
function getCustom2FromSession() {

    return (isset($_SESSION[SVN_INST]['custom2']) ? $_SESSION[SVN_INST]['custom2'] : "");
    
}

/**
 * get custom field 3 from session
 *
 * @return string
 */
function getCustom3FromSession() {

    return (isset($_SESSION[SVN_INST]['custom3']) ? $_SESSION[SVN_INST]['custom3'] : "");
    
}

/**
 * get auth user file from session
 *
 * @return string
 */
function getAuthUserFileFromSession() {

    return (isset($_SESSION[SVN_INST]['authUserFile']) ? $_SESSION[SVN_INST]['authUserFile'] : "");
    
}

/**
 * get svn access file from session
 *
 * @return string
 */
function getSvnAccessFileFromSession() {

    return (isset($_SESSION[SVN_INST]['svnAccessFile']) ? $_SESSION[SVN_INST]['svnAccessFile'] : "");
    
}

/**
 * get repository sort order for pathes
 *
 * @return string
 */
function getRepoSortPath() {

    global $CONF;
    
    if (isset($CONF[REPOPATHSORTORDER])) {
        return ($CONF[REPOPATHSORTORDER]);
    }
    else {
        return ("ASC");
    }
    
}

/**
 * get svn access file
 *
 * @param string $svnaccessfile
 * @param string $reponame
 * @return string
 */
function getSvnAccessFile($svnaccessfile, $reponame) {

    global $CONF;
    
    if ($svnaccessfile == "") {
        $svnaccessfile = dirname($CONF[SVNACCESSFILE]) . "/svn-access." . $reponame;
    }
    
    return ($svnaccessfile);
    
}

/**
 * get current preset page size
 * 
 * @return integer
 */
function getCurrentPageSize() {

    global $CONF;

    return( (isset($CONF[PAGESIZE])) ? $CONF[PAGESIZE] : 10);
}

/**
 * translate access rights for report generation
 *
 * @param string $right
 * @return string
 */
function translateAccessRightReport($right) {

    return (($right == 'none') ? '-' : $right);
    
}

/**
 * translate account locked into icon for report or blank
 *
 * @param string $locked
 * @return string
 */
function translateLockReport($locked) {

    if ($locked == 1) {
        $locked = "<img src='./images/locked_16_16.png' width='16' height='16' border='0' alt='" . _("User locked") . "' title='" . _("User locked") . "' />";
    }
    else {
        $locked = "&nbsp;";
    }
    
    return ($locked);
    
}
?>
