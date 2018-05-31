<?php

/**
 * SVN Access manager Installer
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
 * Template File: install.tpl
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
require ("../include/variables.inc.php");
require ("../include/constants.inc.php");
require ("../include/db-functions-adodb.inc.php");
require ("../include/install-db-functions.inc.php");
require ("../include/install-db-functions-pg.inc.php");
require ("../include/install-db-functions-oracle.inc.php");
require ("../include/functions.inc.php");

/**
 *
 * @global array $WEBCHARSETS
 */
$WEBCHARSETS = array(
        'ISO_8859-1',
        'ISO_8859-2',
        'ISO_8859-3',
        'ISO_8859-4',
        'ISO_8859-5',
        'ISO_8859-6',
        'ISO_8859-6-E',
        'ISO_8859-6-I',
        'ISO_8859-7',
        'ISO_8859-8',
        'ISO_8859-8-E',
        'ISO_8859-8-I',
        'ISO_8859-9',
        'ISO_8859-supp',
        'ISO-8859-10',
        'ISO-8859-13',
        'ISO-8859-14',
        'ISO-8859-15',
        'ISO-8859-16',
        'UTF-7',
        'UTF-8',
        'UTF-16',
        'UTF-16BE',
        'UTF-16LE',
        'UTF-32',
        'UTF-32BE',
        'UTF-32LE',
        'ISO-8859-1-Windows-3.0-Latin-1',
        'ISO-8859-1-Windows-3.1-Latin-1',
        'ISO-8858-2-Windows-Latin-2',
        'ISO-8859-9-Windows-Latin-5',
        'Adobe-Standard-Encoding',
        'Adobe-Symbol-Encoding',
        'Amiga-1251',
        'ANSI_X3.110-1983',
        'ANSI_X3.4-1968',
        'ASMO_449',
        'Big5',
        'Big5-HKSCS',
        'BOCU-1',
        'BRF',
        'BS_4730',
        'BS_viewdata',
        'CESU-8',
        'CP50220',
        'CP51932',
        'CSA_Z243.4-1985-1',
        'CSA_Z243.4-1985-2',
        'CSA_Z243.4-1985-gr',
        'CSN_369103',
        'DEC-MCS',
        'DIN_66003',
        'dk-us',
        'DS_2089',
        'EBCDIC-AT-DE',
        'EBCDIC-AT-DE-A',
        'EBCDIC-CA-FR',
        'EBCDIC-DK-NO',
        'EBCDIC-DK-NO-A',
        'EBCDIC-ES',
        'EBCDIC-ES-A',
        'EBCDIC-ES-S',
        'EBCDIC-FI-SE',
        'EBCDIC-FI-SE-A',
        'EBCDIC-FR',
        'EBCDIC-IT',
        'EBCDIC-PT',
        'EBCDIC-UK',
        'EBCDIC-US',
        'ECMA-cyrillic',
        'ES',
        'ES2',
        'EUC-KR',
        'Extended_UNIX_Code_Fixed_Width_for_Japanese',
        'Extended_UNIX_Code_Packed_Format_for_Japanese',
        'GB18030',
        'GB_1988-80',
        'GB2312',
        'GB_2312-80',
        'GBK',
        'GOST_19768-74',
        'greek7',
        'greek7-old',
        'greek-ccitt',
        'HP-DeskTop',
        'HP-Legal',
        'HP-Math8',
        'HP-Pi-font',
        'hp-roman8',
        'HZ-GB-2312',
        'IBM00858',
        'IBM00924',
        'IBM01140',
        'IBM01141',
        'IBM01142',
        'IBM01143',
        'IBM01144',
        'IBM01145',
        'IBM01146',
        'IBM01147',
        'IBM01148',
        'IBM01149',
        'IBM037',
        'IBM038',
        'IBM1026',
        'IBM1047',
        'IBM273',
        'IBM274',
        'IBM275',
        'IBM277',
        'IBM278',
        'IBM280',
        'IBM281',
        'IBM284',
        'IBM285',
        'IBM290',
        'IBM297',
        'IBM420',
        'IBM423',
        'IBM424',
        'IBM437',
        'IBM500',
        'IBM775',
        'IBM850',
        'IBM851',
        'IBM852',
        'IBM855',
        'IBM857',
        'IBM860',
        'IBM861',
        'IBM862',
        'IBM863',
        'IBM864',
        'IBM865',
        'IBM866',
        'IBM868',
        'IBM869',
        'IBM870',
        'IBM871',
        'IBM880',
        'IBM891',
        'IBM903',
        'IBM904',
        'IBM905',
        'IBM918',
        'IBM-Symbols',
        'IBM-Thai',
        'IEC_P27-1',
        'INIS',
        'INIS-8',
        'INIS-cyrillic',
        'INVARIANT',
        'ISO_10367-box',
        'ISO-10646-J-1',
        'ISO-10646-UCS-2',
        'ISO-10646-UCS-4',
        'ISO-10646-UCS-Basic',
        'ISO-10646-Unicode-Latin1',
        'ISO-10646-UTF-1',
        'ISO-11548-1',
        'ISO-2022-CN',
        'ISO-2022-CN-EXT',
        'ISO-2022-JP',
        'ISO-2022-JP-2',
        'ISO-2022-KR',
        'ISO_2033-1983',
        'ISO_5427',
        'ISO_5427',
        'ISO_5428',
        'ISO_646.basic',
        'ISO_646.irv',
        'ISO_6937-2-25',
        'ISO_6937-2-add',
        'iso-ir-90',
        'ISO-Unicode-IBM-1261',
        'ISO-Unicode-IBM-1264',
        'ISO-Unicode-IBM-1265',
        'ISO-Unicode-IBM-1268',
        'ISO-Unicode-IBM-1276',
        'IT',
        'JIS_C6220-1969-jp',
        'JIS_C6220-1969-ro',
        'JIS_C6226-1978',
        'JIS_C6226-1983',
        'JIS_C6229-1984-a',
        'JIS_C6229-1984-b',
        'JIS_C6229-1984-b-add',
        'JIS_C6229-1984-hand',
        'JIS_C6229-1984-hand-add',
        'JIS_C6229-1984-kana',
        'JIS_Encoding',
        'JIS_X0201',
        'JIS_X0212-1990',
        'JUS_I.B1.002',
        'JUS_I.B1.003-mac',
        'JUS_I.B1.003-serb',
        'KOI7-switched',
        'KOI8-R',
        'KOI8-U',
        'KS_C_5601-1987',
        'KSC5636',
        'KZ-1048',
        'latin-greek',
        'Latin-greek-1',
        'latin-lap',
        'macintosh',
        'Microsoft-Publishing',
        'MNEM',
        'MNEMONIC',
        'MSZ_7795.3',
        'NATS-DANO',
        'NATS-DANO-ADD',
        'NATS-SEFI',
        'NATS-SEFI-ADD',
        'NC_NC00-10',
        'NF_Z_62-010',
        'NF_Z_62-010_(1973)',
        'NS_4551-1',
        'NS_4551-2',
        'OSD_EBCDIC_DF03_IRV',
        'OSD_EBCDIC_DF04_1',
        'OSD_EBCDIC_DF04_15',
        'PC8-Danish-Norwegian',
        'PC8-Turkish',
        'PT',
        'PT2',
        'PTCP154',
        'SCSU',
        'SEN_850200_B',
        'SEN_850200_C',
        'Shift_JIS',
        'T.101-G2',
        'T.61-7bit',
        'T.61-8bit',
        'TIS-620',
        'TSCII',
        'UNICODE-1-1',
        'UNICODE-1-1-UTF-7',
        'UNKNOWN-8BIT',
        'us-dk',
        'Ventura-International',
        'Ventura-Math',
        'Ventura-US',
        'videotex-suppl',
        'VIQR',
        'VISCII',
        'windows-1250',
        'windows-1251',
        'windows-1252',
        'windows-1253',
        'windows-1254',
        'windows-1255',
        'windows-1256',
        'windows-1257',
        'windows-1258',
        'Windows-31J',
        'windows-874'
);

/**
 *
 * @global array $DBTABLES
 */
$DBTABLES = array(
        'help',
        'log',
        'preferences',
        'rights',
        'workinfo',
        'sessions',
        'svngroups',
        'svnmailinglists',
        'svnpasswordreset',
        'svnprojects',
        'svnrepos',
        'svnusers',
        'svn_access_rights',
        'svn_groups_responsible',
        'svn_projects_mailinglists',
        'svn_projects_responsible',
        'svn_users_groups',
        'users_rights',
        'messages'
);

/**
 * create PostgreSQL database tables
 *
 * @param resource $dbh
 * @param string $charset
 * @param string $schema
 * @param string $tablespace
 * @param string $dbuser
 * @return integer[]|string[]
 */
function createPgDatabaseTables($dbh, $charset, $schema, $tablespace, $dbuser) {

    $error = 0;
    $tMessage = "";
    
    $query = "SET client_encoding = '$charset'";
    db_query_install($query, $dbh);
    $query = "SET standard_conforming_strings = off";
    db_query_install($query, $dbh);
    $query = "SET check_function_bodies = false";
    db_query_install($query, $dbh);
    $query = "SET client_min_messages = warning";
    db_query_install($query, $dbh);
    $query = "SET escape_string_warning = off";
    db_query_install($query, $dbh);
    if ($schema != "") {
        $query = "SET search_path = '$schema'";
    }
    else {
        $query = "SET search_path = ''";
    }
    db_query_install($query, $dbh);
    $query = "SET default_tablespace = '$tablespace'";
    db_query_install($query, $dbh);
    $query = "SET default_with_oids = false;";
    db_query_install($query, $dbh);
    
    // Table help
    createHelpTablePostgresql($dbh, $schema, $dbuser);
    
    // Table log
    createLogTableProstgresql($dbh, $schema, $dbuser);
    
    // Table preferences
    createPreferencesTableProstgresql($dbh, $schema, $dbuser);
    
    // Table rights
    createRightsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table workinfo
    createWorkinfoTableProstgresql($dbh, $schema, $dbuser);
    
    // Table sessions
    createSessionsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svngroups
    createSvngroupsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svnprojects
    createSvnprojectsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svnusers
    createSvnusersTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svn_access_rights
    createSvnAccessRightsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svn_groups_responsible
    createSvnGroupsResponsibleTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svn_projects_mailinglists
    createSvnProjectsMailinglisteTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svn_projects_responsible
    createSvnProjectsResponsibleTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svn_users_groups
    createSvnUsersGroupsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svnmailinglists
    createSvnmailinglistsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svnpasswordreset
    createSvnpasswordresetTableProstgresql($dbh, $schema, $dbuser);
    
    // Table svnrepos
    createSvnreposTableProstgresql($dbh, $schema, $dbuser);
    
    // Table users_rights
    createUserrightsTableProstgresql($dbh, $schema, $dbuser);
    
    // Table messages
    createMessagesTableProstgresql($dbh, $schema, $dbuser);
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * create Oracle database tables
 *
 * @param resource $dbh
 * @param string $schema
 * @return integer[]|string[]
 */
function createOracleDatabaseTables($dbh, $schema) {

    $error = 0;
    $tMessage = "";
    
    // Table help
    createHelpTableOracle($dbh, $schema);
    
    // Table log
    createLogTableOracle($dbh, $schema);
    
    // Table preferences
    createPreferencesTableOracle($dbh, $schema);
    
    // Table workinfo
    createWorkinfoTableOracle($dbh, $schema);
    
    // Table rights
    createRightsTableOracle($dbh, $schema);
    
    // Table sessions
    createSessionTableOracle($dbh, $schema);
    
    // Table svngroups
    createSvngroupsTableOracle($dbh, $schema);
    
    // Table svnmailiinglists
    createSvnmailinglistsTableOracle($dbh, $schema);
    
    // Table svnpasswordreset
    createPasswordresetTableOracle($dbh, $schema);
    
    // Table svnrepos
    createSvnreposTableOracle($dbh, $schema);
    
    // Table svnprojects
    createSvnprojectsTableOracle($dbh, $schema);
    
    // Table svnusers
    createSvnusersTableOracle($dbh, $schema);
    
    // Table svn_access_rights
    createSvnAccessrightsTableOracle($dbh, $schema);
    
    // Table svn_groups_responsible
    createSvnGroupsResponsibleTableOracle($dbh, $schema);
    
    // Table svn_projects_mailinglists
    createSvnProjectsMailinglistsTableOracle($dbh, $schema);
    
    // Table svn_projects_responsible
    createSvnProjectsresponsibleTableOracle($dbh, $schema);
    
    // Table users_rights
    createUserRightsTableOracle($dbh, $schema);
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * create MySQL database tables
 *
 * @param resource $dbh
 * @param string $charset
 * @param string $collation
 * @return integer[]|string[]
 */
function createMySQLDatabaseTables($dbh, $charset, $collation) {

    $error = 0;
    $tMessage = "";
    
    createHelpTableMySQL($dbh, $charset, $collation);
    createLogTableMySQL($dbh, $charset, $collation);
    createPreferencesTableMySQL($dbh, $charset, $collation);
    createRightsTableMySQL($dbh, $charset, $collation);
    createSessionsTableMySQL($dbh, $charset, $collation);
    createSvnAccessRightsTableMySQL($dbh, $charset, $collation);
    createSvnProjectsMailinglistsTableMySQL($dbh, $charset, $collation);
    createSvnProjectsresponsibleTableMySQL($dbh, $charset, $collation);
    createSvnUsersGroupsTableMySQL($dbh, $charset, $collation);
    createSvngroupsTableMySQL($dbh, $charset, $collation);
    createSvnMailinglistsTableMySQL($dbh, $charset, $collation);
    createSvnprojectsTableMySQL($dbh, $charset, $collation);
    createSvnProjectsTableMySQL($dbh, $charset, $collation);
    createSvnReposTableMySQL($dbh, $charset, $collation);
    createSvnusersTableMySQL($dbh, $charset, $collation);
    createWorkinfoTableMySQL($dbh, $charset, $collation);
    createUserRightsTableMySQL($dbh, $charset, $collation);
    createSvnGroupsResponsibleTableMySQL($dbh, $charset, $collation);
    createSvnPasswordResetTableMySQL($dbh, $charset, $collation);
    createMessagesTableMySQL($dbh, $charset, $collation);
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * create admin user
 *
 * @param string $userid
 * @param string $password
 * @param string $givenname
 * @param string $name
 * @param string $emailaddress
 * @param string $databasetype
 * @param resource $dbh
 * @param string $schema
 * @return integer[]|string[]
 */
function createAdmin($userid, $password, $givenname, $name, $emailaddress, $databasetype, $dbh, $schema) {

    db_ta(BEGIN, $dbh);
    
    $CONF = array();
    $CONF[DATABASE_HOST] = $_SESSION[SVN_INST][DATABASEHOST];
    $CONF[DATABASE_USER] = $_SESSION[SVN_INST][DATABASEUSER];
    $CONF[DATABASE_PASSWORD] = $_SESSION[SVN_INST][DATABASEPASSWORD];
    $CONF[DATABASE_NAME] = $_SESSION[SVN_INST][DATABASENAME];
    $CONF[DATABASE_SCHEMA] = $_SESSION[SVN_INST][DATABASESCHEMA];
    $CONF[DATABASE_TABLESPACE] = $_SESSION[SVN_INST][DATABASETABLESPACE];
    $CONF['pwcrypt'] = $_SESSION[SVN_INST]['pwEnc'];
    
    $error = 0;
    $tMessage = "";
    $pwcrypt = $dbh->qstr(pacrypt($password), get_magic_quotes_gpc());
    $dbnow = db_now();
    if (($databasetype == "oci8") || (substr($databasetype, 0, 8) == "postgres")) {
        $query = "INSERT INTO $schema.svnusers (userid, name, givenname, password, emailaddress, user_mode, admin, created, created_user, password_modified, superadmin) " . "VALUES ('$userid', '$name', '$givenname', $pwcrypt, '$emailaddress', 'write', 'y', '$dbnow', 'install', '$dbnow', 1)";
    }
    else {
        $query = "INSERT INTO svnusers (userid, name, givenname, password, emailaddress, user_mode, admin, created, created_user, password_modified, superadmin) " . "VALUES ('$userid', '$name', '$givenname', $pwcrypt, '$emailaddress', 'write', 'y', '$dbnow', 'install', '$dbnow', 1)";
    }
    $result = db_query_install($query, $dbh);
    $uid = db_get_last_insert_id('svnusers', 'id', $dbh, $_SESSION[SVN_INST][DATABASESCHEMA]);
    db_ta(COMMIT, $dbh);
    
    $query = "SELECT id, allowed_action " . "  FROM rights " . " WHERE deleted = '00000000000000'";
    $result = db_query_install($query, $dbh);
    
    while ( ($error == 0) && ($row = db_assoc($result['result'])) ) {
        
        $allowed = $row['allowed_action'];
        $id = $row['id'];
        $dbnow = db_now();
        if (($databasetype == "oci8") || (substr($databasetype, 0, 8) == "postgres")) {
            $query = "INSERT INTO $schema.users_rights (user_id, right_id, allowed, created, created_user) " . "VALUES ($uid, $id, '$allowed', '$dbnow', 'install')";
        }
        else {
            $query = "INSERT INTO users_rights (user_id, right_id, allowed, created, created_user) " . "VALUES ($uid, $id, '$allowed', '$dbnow', 'install')";
        }
        
        db_query_install($query, $dbh);
    }
    
    if ($error == 0) {
        db_ta(COMMIT, $dbh);
    }
    else {
        db_ta(ROLLBACK, $dbh);
    }
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * get filename for help texts
 *
 * @param string $filename
 * @return string
 */
function getHelptextFilename($filename) {

    if (file_exists(realpath("./$filename"))) {
        
        $filename = "./$filename";
    }
    elseif (file_exists(realpath("../$filename"))) {
        
        $filename = "../$filename";
    }
    else {
        
        $filenamer = '';
    }
    
    return ($filename);
    
}

/**
 * load help texts
 *
 * @param string $database
 * @param string $schema
 * @param resource $dbh
 * @return integer[]|string[]
 */
function loadHelpTexts($database, $schema, $dbh) {

    $error = 0;
    $tMessage = "";
    if ((substr($database, 0, 8) == "postgres") || ($database == "oci8")) {
        $filename = "help_texts_non_mysql.sql";
        $schema = ($schema == "") ? "" : $schema . ".";
    }
    else {
        $filename = "help_texts.sql";
        $schema = "";
    }
    
    $filename = getHelptextFilename($filename);
    if ($filename == '') {
        
        $ret = array();
        $ret[ERROR] = 0;
        $ret[ERRORMSG] = _("No file with help texts found.");
        
        return $ret;
    }
    
    if ($fh_in = @fopen($filename, "r")) {
        
        db_ta("BEGIN", $dbh);
        
        while ( ! feof($fh_in) ) {
            
            $query = fgets($fh_in);
            if ($query != "") {
                
                $query = str_replace(" INTO help ", " INTO " . $schema . "help ", $query);
                $query = preg_replace('/;$/', '', $query);
                db_query_install($query, $dbh);
            }
        }
        
        @fclose($fh_in);
        
        db_ta(COMMIT, $dbh);
    }
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * perform database connect test
 *
 * @return integer[]|string[]
 */
function doDbtest() {

    $tErrors = array();
    $error = 0;
    $CONF[DATABASE_HOST] = $_SESSION[SVN_INST][DATABASEHOST];
    $CONF[DATABASE_USER] = $_SESSION[SVN_INST][DATABASEUSER];
    $CONF[DATABASE_PASSWORD] = $_SESSION[SVN_INST][DATABASEPASSWORD];
    $CONF[DATABASE_NAME] = $_SESSION[SVN_INST][DATABASENAME];
    $CONF[DATABASE_SCHEMA] = $_SESSION[SVN_INST][DATABASESCHEMA];
    $CONF[DATABASE_TABLESPACE] = $_SESSION[SVN_INST][DATABASETABLESPACE];
    
    if (empty($CONF[DATABASE_HOST])) {
        $tErrors[] = _("No database host specified. Connect not possible.");
        $error = 1;
    }
    if (empty($CONF[DATABASE_USER])) {
        $tErrors[] = _("No database user specified. Connect not possible.");
        $error = 1;
    }
    if (empty($CONF[DATABASE_NAME])) {
        $tErrors[] = _("No database name specified. Connect not possible.");
        $error = 1;
    }
    
    if ($error == 0) {
        
        $dbh = db_connect_install($_SESSION[SVN_INST][DATABASEHOST], $_SESSION[SVN_INST][DATABASEUSER], $_SESSION[SVN_INST][DATABASEPASSWORD], $_SESSION[SVN_INST][DATABASENAME], $_SESSION[SVN_INST][DATABASECHARSET], $_SESSION[SVN_INST][DATABASECOLLATION], $_SESSION[SVN_INST]['database'], "yes");
        
        if (is_array($dbh)) {
            $tErrors[] = $dbh[ERROR];
            $error = 1;
        }
        else {
            $tErrors[] = _("Database test ok, connection works");
            $error = 1;
        }
    }
    
    $tDatabaseHost = isset($_SESSION[SVN_INST][DATABASEHOST]) ? $_SESSION[SVN_INST][DATABASEHOST] : "";
    $tDatabaseUser = isset($_SESSION[SVN_INST][DATABASEUSER]) ? $_SESSION[SVN_INST][DATABASEUSER] : "";
    $tDatabasePassword = isset($_SESSION[SVN_INST][DATABASEPASSWORD]) ? $_SESSION[SVN_INST][DATABASEPASSWORD] : "";
    $tDatabaseName = isset($_SESSION[SVN_INST][DATABASENAME]) ? $_SESSION[SVN_INST][DATABASENAME] : "";
    $tDatabaseSchema = isset($_SESSION[SVN_INST][DATABASESCHEMA]) ? $_SESSION[SVN_INST][DATABASESCHEMA] : "";
    $tDatabaseTablespace = isset($_SESSION[SVN_INST][DATABASETABLESPACE]) ? $_SESSION[SVN_INST][DATABASETABLESPACE] : "";
    $tDatabaseCharset = isset($_SESSION[SVN_INST][DATABASECHARSET]) ? $_SESSION[SVN_INST][DATABASECHARSET] : "";
    $tDatabaseCollation = isset($_SESSION[SVN_INST][DATABASECOLLATION]) ? $_SESSION[SVN_INST][DATABASECOLLATION] : "";
    
    $ret = array();
    $ret['page'] = ($error == 0) ? 1 : 7;
    $ret['errors'] = $tErrors;
    return ($ret);
    
}

/**
 * perform a lxap connection test
 *
 * @param string $tLdapProtocol
 * @return number[]|string[]
 */
function ldapConnectTest($tLdapProtocol) {

    $error = 0;
    $tErrors = '';
    
    if ($ldap = @ldap_connect($_SESSION[SVN_INST]['ldapHost'], $_SESSION[SVN_INST]['ldapPort'])) {
        
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, $tLdapProtocol);
        
        if (@ldap_bind($ldap, $_SESSION[SVN_INST]['ldapBinddn'], $_SESSION[SVN_INST]['ldapBindpw'])) {
            
            $tErrors = _("LDAP connection test ok, connection works");
            $error = 1;
        }
        else {
            
            $tErrors = sprintf(_("Can't bind to ldap server: %s"), ldap_error($ldap));
            $error = 1;
        }
        
        @ldap_unbind($ldap);
    }
    else {
        
        $tErrors = _("Can't connect to ldap server, hostname/ip and port are ok?");
        $error = 1;
    }
    
    return (array(
            $error,
            $tErrors
    ));
    
}

/**
 * perform a ldap connection test
 *
 * @return integer[]|string[][]
 */
function doLdapTest() {

    $tErrors = array();
    $error = 0;
    $tPage = 2;
    $tLdapProtocol = isset($_SESSION[SVN_INST]['ldapProtocol']) ? $_SESSION[SVN_INST]['ldapProtocol'] : "3";
    
    list($error, $msg ) = ldapConnectTest($tLdapProtocol);
    if ($error > 0) {
        $tErrors[] = $msg;
    }
    
    $tCreateDatabaseTables = isset($_SESSION[SVN_INST]['createDatabaseTables']) ? $_SESSION[SVN_INST]['createDatabaseTables'] : "";
    $tDropDatabaseTables = isset($_SESSION[SVN_INST]['dropDatabaseTables']) ? $_SESSION[SVN_INST]['dropDatabaseTables'] : "";
    $tDatabase = isset($_SESSION[SVN_INST]['database']) ? $_SESSION[SVN_INST]['database'] : "";
    $tSessionInDatabase = isset($_SESSION[SVN_INST]['sessionInDatabase']) ? $_SESSION[SVN_INST]['sessionInDatabase'] : "";
    $tUseLdap = isset($_SESSION[SVN_INST]['useLdap']) ? $_SESSION[SVN_INST]['useLdap'] : "";
    $tLdapHost = isset($_SESSION[SVN_INST]['ldapHost']) ? $_SESSION[SVN_INST]['ldapHost'] : "";
    $tLdapPort = isset($_SESSION[SVN_INST]['ldapPort']) ? $_SESSION[SVN_INST]['ldapPort'] : "";
    $tLdapProtocol = isset($_SESSION[SVN_INST]['ldapProtocol']) ? $_SESSION[SVN_INST]['ldapProtocol'] : "";
    $tLdapBinddn = isset($_SESSION[SVN_INST]['ldapBinddn']) ? $_SESSION[SVN_INST]['ldapBinddn'] : "";
    $tLdapBindpw = isset($_SESSION[SVN_INST]['ldapBindpw']) ? $_SESSION[SVN_INST]['ldapBindpw'] : "";
    $tLdapUserdn = isset($_SESSION[SVN_INST]['ldapUserdn']) ? $_SESSION[SVN_INST]['ldapUserdn'] : "";
    $tLdapUserFilter = isset($_SESSION[SVN_INST]['ldapUserFilter']) ? $_SESSION[SVN_INST]['ldapUserFilter'] : "";
    $tLdapUserObjectclass = isset($_SESSION[SVN_INST]['ldapUserObjectclass']) ? $_SESSION[SVN_INST]['ldapUserObjectclass'] : "";
    $tLdapUserAdditionalFilter = isset($_SESSION[SVN_INST]['ldapUserAdditionalFilter']) ? $_SESSION[SVN_INST]['ldapUserAdditionalFilter'] : "";
    
    list($tCreateDatabaseTablesYes, $tCreateDatabaseTablesNo ) = setCreateDatabaseTables($tCreateDatabaseTables);
    list($tDropDatabaseTablesYes, $tDropDatabaseTablesNo ) = setDropDatabaseTables($tDropDatabaseTables);
    list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues($tDatabase);
    list($tSessionInDatabaseYes, $tSessionInDatabaseNo ) = setSessionIndatabase($tSessionInDatabase);
    list($tUseLdapYes, $tUseLdapNo ) = setUseLdap($tUseLdap);
    list($tLdap2, $tLdap3 ) = setLdapprotocol($tLdapProtocol);
    
    if ($error == 0) {
        $tPage = 2;
    }
    else {
        $tPage = 7;
    }
    
    $ret = array();
    $ret['page'] = $tPage;
    $ret['errors'] = $tErrors;
    return ($ret);
    
}

/**
 * get configuration variables
 *
 * @param string $tBaseDir
 * @return string[]
 */
function getConfigVariables($tBaseDir) {

    if (determineOs() == "windows") {
        
        $tConfigDir = $tBaseDir . "\config";
        $configpath = $tBaseDir . "\config";
        $configtmpl = $configpath . "\config.inc.php.tpl";
        $confignew = $configpath . "\config.inc.php.new";
        $configfile = $configpath . "\config.inc.php";
    }
    else {
        
        $tConfigDir = "/etc/svn-access-manager";
        $configpath = $tBaseDir . "/config";
        $configtmpl = $configpath . "/config.inc.php.tpl";
        $confignew = $tConfigDir . "/config.inc.php.new";
        $configfile = $tConfigDir . "/config.inc.php";
    }
    
    return (array(
            $tConfigDir,
            $configpath,
            $configtmpl,
            $confignew,
            $configfile
    ));
    
}

/**
 * check if config directory is writable
 *
 * @param string $tConfigDir
 * @return string
 */
function isConfigWritable($tConfigDir) {

    if (is_writable($tConfigDir)) {
        $tConfigWritable = _("writable");
    }
    else {
        $tConfigWritable = _("not writable");
    }
    
    return ($tConfigWritable);
    
}

/**
 * check settings for ldap in PHP session
 *
 * @return integer[]|string[]
 */
function checkSessionValuesLdap() {

    $tErrors = array();
    $error = 0;
    
    if ($_SESSION[SVN_INST]['ldapHost'] == "") {
        
        $tErrors[] = _("LDAP host is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapPort'] == "") {
        
        $tErrors[] = _("LDAP port is missing!");
        $error = 1;
    }
    
    if (($_SESSION[SVN_INST]['ldapProtocol'] != "2") && ($_SESSION[SVN_INST]['ldapProtocol'] != "3")) {
        
        $tErrors[] = sprintf(_("Invalid protocol version %s!"), $_SESSION[SVN_INST]['ldapProtocol']);
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapBinddn'] == "") {
        
        $tErrors[] = _("LDAP bind dn is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapBindpw'] == "") {
        
        $tErrors[] = _("LDAP bind password is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapUserdn'] == "") {
        
        $tErrors[] = _("LDAP user dn is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapUserFilter'] == "") {
        
        $tErrors[] = _("LDAP user filter attribute is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapUserObjectclass'] == "") {
        
        $tErrors[] = _("LDAP user object class is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapAttrUid'] == "") {
        
        $tErrors[] = _("LDAP attribute mapping for uid is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapAttrName'] == "") {
        
        $tErrors[] = _("LDAP attribute mapping for name is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapAttrGivenname'] == "") {
        
        $tErrors[] = _("LDAP attribute mapping for given name is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapAttrMail'] == "") {
        
        $tErrors[] = _("LDAP attribute mapping for mail is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['ldapAttrPassword'] == "") {
        
        $tErrors[] = _("LDAP attribute mapping for user password is missing!");
        $error = 1;
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * check database settings in PHP session
 *
 * @return integer[]|string[]
 */
function checkSessionValuesDatabase() {

    $tErrors = array();
    $error = 0;
    
    if ($_SESSION[SVN_INST][DATABASEHOST] == "") {
        
        $tErrors[] = _("Database host is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST][DATABASEUSER] == "") {
        
        $tErrors[] = _("Database user is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST][DATABASENAME] == "") {
        
        $tErrors[] = _("Database name is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST][DATABASECHARSET] == "") {
        
        $tErrors[] = _("Database charset is missing!");
        $error = 1;
    }
    
    if ((($_SESSION[SVN_INST]['database'] == MYSQL) || ($_SESSION[SVN_INST]['database'] == MYSQLI)) && ($_SESSION[SVN_INST][DATABASECOLLATION] == "")) {
        
        $tErrors[] = _("Database collation is missing!");
        $error = 1;
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * check settings for admin in PHP session
 *
 * @return integer[]|string[]
 */
function checkSessionValuesAdmin() {

    $tErrors = array();
    $error = 0;
    
    if ($_SESSION[SVN_INST]['username'] == "") {
        
        $tErrors[] = _("Administrator username is missing!");
        $error = 1;
    }
    
    if ((($_SESSION[SVN_INST][PASSWORD] == "") || ($_SESSION[SVN_INST][PASSWORD2] == "")) && (strtoupper($_SESSION[SVN_INST]['useLdap']) != "YES")) {
        
        $tErrors[] = _("Administrator password is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST][PASSWORD] != $_SESSION[SVN_INST][PASSWORD2]) {
        
        $tErrors[] = _("Administrator passwords do not match!");
        $error = 1;
    }
    elseif ((checkPasswordPolicy($_SESSION[SVN_INST][PASSWORD], 'y') == 0) && (strtoupper($_SESSION[SVN_INST]['useLdap']) != "YES")) {
        
        $tErrors[] = _("Administrator password is not strong enough!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['name'] == "") {
        
        $tErrors[] = _("Administrator name is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['adminEmail'] == "") {
        
        $tErrors[] = _("Administrator email address is missing!");
        $error = 1;
    }
    elseif (! check_email($_SESSION[SVN_INST]['adminEmail'])) {
        
        $tErrors[] = sprintf(_("Administrator email address %s is not a valid email address!"), $_SESSION[SVN_INST]['adminEmail']);
        $error = 1;
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * check settings for website in PHP session
 *
 * @return integer[]|string[]
 */
function checkSessionValuesWebsite() {

    $tErrors = array();
    $error = 0;
    
    if ($_SESSION[SVN_INST]['websiteUrl'] == "") {
        
        $tErrors[] = _("SVN Access Manager website url is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['websiteCharset'] == "") {
        
        $tErrors[] = _("Website charset is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['lpwMailSender'] == "") {
        
        $tErrors[] = _("Lost password mail sender address is missing!");
        $error = 1;
    }
    elseif (! check_email($_SESSION[SVN_INST]['lpwMailSender'])) {
        
        $tErrors[] = sprintf(_("Lost password mail sender address %s is not a valid email address!"), $_SESSION[SVN_INST]['lpwMailSender']);
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['lpwLinkValid'] == "") {
        
        $tErrors[] = _("Lost password days link valid missing!");
        $error = 1;
    }
    elseif (! is_numeric($_SESSION[SVN_INST]['lpwLinkValid'])) {
        
        $tErrors[] = _("Lost password days link valid must be numeric!");
        $error = 1;
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * check settings for ViewVC in PHP session
 *
 * @return integer[]|String[]
 */
function checkSessionValuesViewvc() {

    $tErrors = array();
    $error = 0;
    
    if ($_SESSION[SVN_INST]['useSvnAccessFile'] == "YES") {
        
        if ($_SESSION[SVN_INST]['svnAccessFile'] == "") {
            
            $tErrors[] = _("SVN Access File is missing!");
            $error = 1;
        }
        
        if ($_SESSION[SVN_INST]['authUserFile'] == "") {
            
            $tErrors[] = _("Auth user file is missing!");
            $error = 1;
        }
    }
    
    if ($_SESSION[SVN_INST]['viewvcConfig'] == "YES") {
        
        if ($_SESSION[SVN_INST]['viewvcConfigDir'] == "") {
            
            $tErrors[] = _("ViewVC configuration directory is missing!");
            $error = 1;
        }
        elseif ($_SESSION[SVN_INST]['viewvcAlias'] == "") {
            
            $tErrors[] = _("ViewVC webserver alias is missing!");
            $error = 1;
        }
        elseif ($_SESSION[SVN_INST]['viewvcRealm'] == "") {
            
            $tErrors[] = _("ViewVC realm is missing!");
            $error = 1;
        }
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * check settings for misc values in PHP session
 *
 * @return integer[]|string[]
 */
function checkSessionValuesMisc() {

    $tErrors = array();
    $error = 0;
    
    if ($_SESSION[SVN_INST]['svnCommand'] == "") {
        
        $tErrors[] = _("SVN command is missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['svnadminCommand'] == "") {
        
        $tErrors[] = _("Svnadmin command missing!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['grepCommand'] == "") {
        
        $tErrors[] = _("Grep command is missinbg!");
        $error = 1;
    }
    
    if ($_SESSION[SVN_INST]['pageSize'] == "") {
        
        $tErrors[] = _("Page size is missing!");
        $error = 1;
    }
    
    if (! is_numeric($_SESSION[SVN_INST]['pageSize'])) {
        
        $tErrors[] = _("Page size is not numeric!");
        $error = 1;
    }
    
    if (! is_numeric($_SESSION[SVN_INST]['minAdminPwSize'])) {
        
        $tErrors[] = _("Minimal administrator password length is not numeric!");
        $error = 1;
    }
    
    if (! is_numeric($_SESSION[SVN_INST]['minUserPwSize'])) {
        
        $tErrors[] = _("Minimal user password length is not numeric!");
        $error = 1;
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * check values in PHP session
 *
 * @return array[]
 */
function checkSessionValues() {

    $tErrors = array();
    $error = 0;
    
    $ret = checkSessionValuesDatabase();
    $error = $ret[ERROR];
    $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    
    if (strtoupper($_SESSION[SVN_INST]['useLdap']) == "YES") {
        
        $ret = checkSessionValuesLdap();
        $error = $ret[ERROR];
        $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    }
    
    $ret = checkSessionValuesWebsite();
    $error = $ret[ERROR];
    $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    
    $ret = checkSessionValuesViewvc();
    $error = $ret[ERROR];
    $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    
    $ret = checkSessionValuesAdmin();
    $error = $ret[ERROR];
    $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    
    $ret = checkSessionValuesMisc();
    $error = $ret[ERROR];
    $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors
    ));
    
}

/**
 * replace tokens in config file
 *
 * @param string $content
 * @param string $viewvcconf
 * @param string $viewvcgroups
 * @param string $preCompatible
 * @param string $installBase
 * @return string
 */
function replaceTokens($content, $viewvcconf, $viewvcgroups, $preCompatible, $installBase) {

    $content = str_replace('###DBTYPE###', $_SESSION[SVN_INST]['database'], $content);
    $content = str_replace('###DBHOST###', $_SESSION[SVN_INST][DATABASEHOST], $content);
    $content = str_replace('###DBUSER###', $_SESSION[SVN_INST][DATABASEUSER], $content);
    $content = str_replace('###DBPASS###', $_SESSION[SVN_INST][DATABASEPASSWORD], $content);
    $content = str_replace('###DBNAME###', $_SESSION[SVN_INST][DATABASENAME], $content);
    $content = str_replace('###DBSCHEMA###', $_SESSION[SVN_INST][DATABASESCHEMA], $content);
    $content = str_replace('###DBTABLESPACE###', $_SESSION[SVN_INST][DATABASETABLESPACE], $content);
    $content = str_replace('###DBCHARSET###', $_SESSION[SVN_INST][DATABASECHARSET], $content);
    $content = str_replace('###DBCOLLATION###', $_SESSION[SVN_INST][DATABASECOLLATION], $content);
    $content = str_replace('###USELOGGING###', $_SESSION[SVN_INST]['logging'], $content);
    $content = str_replace('###PAGESIZE###', $_SESSION[SVN_INST]['pageSize'], $content);
    $content = str_replace('###SVNCMD###', $_SESSION[SVN_INST]['svnCommand'], $content);
    $content = str_replace('###GREPCMD###', $_SESSION[SVN_INST]['grepCommand'], $content);
    $content = str_replace('###USEJS###', 'YES', $content);
    $content = str_replace('###SVNACCESSFILE###', $_SESSION[SVN_INST]['svnAccessFile'], $content);
    $content = str_replace('###ACCESSCONTROLLEVEL###', $_SESSION[SVN_INST]['accessControlLevel'], $content);
    $content = str_replace('###SVNAUTHFILE###', $_SESSION[SVN_INST]['authUserFile'], $content);
    $content = str_replace('###CREATEACCESSFILE###', $_SESSION[SVN_INST]['useSvnAccessFile'], $content);
    $content = str_replace('###CREATEAUTHFILE###', $_SESSION[SVN_INST]['useAuthUserFile'], $content);
    $content = str_replace('###ADMINEMAIL###', $_SESSION[SVN_INST]['adminEmail'], $content);
    $content = str_replace('###MINPWADMIN###', $_SESSION[SVN_INST]['minAdminPwSize'], $content);
    $content = str_replace('###MINPWUSER###', $_SESSION[SVN_INST]['minUserPwSize'], $content);
    $content = str_replace('###SESSIONINDB###', $_SESSION[SVN_INST]['sessionInDatabase'], $content);
    $content = str_replace('###PWCRYPT###', $_SESSION[SVN_INST]['pwEnc'], $content);
    $content = str_replace('###CREATEVIEWVCCONF###', $_SESSION[SVN_INST]['viewvcConfig'], $content);
    $content = str_replace('###VIEWVCCONF###', $viewvcconf, $content);
    $content = str_replace('###VIEWVCGROUPS###', $viewvcgroups, $content);
    $content = str_replace('###VIEWVCLOCATION###', $_SESSION[SVN_INST]['viewvcAlias'], $content);
    $content = str_replace('###VIEWVCAPACHERELOAD###', $_SESSION[SVN_INST]['viewvcApacheReload'], $content);
    $content = str_replace('###VIEWVCREALM###', $_SESSION[SVN_INST]['viewvcRealm'], $content);
    $content = str_replace('###SEPERATEFILESPERREPO###', $_SESSION[SVN_INST]['perRepoFiles'], $content);
    $content = str_replace('###REPOPATHSORTORDER###', $_SESSION[SVN_INST]['pathSortOrder'], $content);
    $content = str_replace('###WRITEANONACCESS###', $_SESSION[SVN_INST]['anonAccess'], $content);
    $content = str_replace('###SVNADMINCMD###', $_SESSION[SVN_INST]['svnadminCommand'], $content);
    $content = str_replace('###WEBSITECHARSET###', $_SESSION[SVN_INST]['websiteCharset'], $content);
    $content = str_replace('###WEBSITEURL###', $_SESSION[SVN_INST]['websiteUrl'], $content);
    $content = str_replace('###LOSTPWSENDER###', $_SESSION[SVN_INST]['lpwMailSender'], $content);
    $content = str_replace('###LOSTPWMAXERROR###', 3, $content);
    $content = str_replace('###LOSTPWLINKVALID###', $_SESSION[SVN_INST]['lpwLinkValid'], $content);
    $content = str_replace('###PRECOMPATIBLE###', $preCompatible, $content);
    $content = str_replace('###INSTALLBASE###', $installBase, $content);
    $content = str_replace('###USELDAP###', $_SESSION[SVN_INST]['useLdap'], $content);
    $content = str_replace('###BINDDN###', $_SESSION[SVN_INST]['ldapBinddn'], $content);
    $content = str_replace('###BINDPW###', $_SESSION[SVN_INST]['ldapBindpw'], $content);
    $content = str_replace('###USERDN###', $_SESSION[SVN_INST]['ldapUserdn'], $content);
    $content = str_replace('###USERFILTERATTR###', $_SESSION[SVN_INST]['ldapUserFilter'], $content);
    $content = str_replace('###USEROBJECTCLASS###', $_SESSION[SVN_INST]['ldapUserObjectclass'], $content);
    $content = str_replace('###USERADDITIONALFILTER###', $_SESSION[SVN_INST]['ldapUserAdditionalFilter'], $content);
    $content = str_replace('###LDAPHOST###', $_SESSION[SVN_INST]['ldapHost'], $content);
    $content = str_replace('###LDAPPORT###', $_SESSION[SVN_INST]['ldapPort'], $content);
    $content = str_replace('###LDAPPROTOCOL###', $_SESSION[SVN_INST]['ldapProtocol'], $content);
    $content = str_replace('###LDAPSORTATTR###', $_SESSION[SVN_INST]['ldapAttrUserSort'], $content);
    $content = str_replace('###LDAPSORTORDER###', $_SESSION[SVN_INST]['ldapUserSort'], $content);
    $content = str_replace('###LDAPBINDUSELOGINDATA###', $_SESSION[SVN_INST]['ldapBindUseLoginData'], $content);
    $content = str_replace('###LDAPBINDDNSUFFIX###', $_SESSION[SVN_INST]['ldapBindDnSuffix'], $content);
    $content = str_replace('###MAPUID###', $_SESSION[SVN_INST]['ldapAttrUid'], $content);
    $content = str_replace('###MAPNAME###', $_SESSION[SVN_INST]['ldapAttrName'], $content);
    $content = str_replace('###MAPGIVENNAME###', $_SESSION[SVN_INST]['ldapAttrGivenname'], $content);
    $content = str_replace('###MAPMAIL###', $_SESSION[SVN_INST]['ldapAttrMail'], $content);
    $content = str_replace('###MAPPASSWORD###', $_SESSION[SVN_INST]['ldapAttrPassword'], $content);
    $content = str_replace('###USERDEFAULTACCESS###', $_SESSION[SVN_INST]['userDefaultAccess'], $content);
    $content = str_replace('###PASSWORDEXPIRES###', $_SESSION[SVN_INST]['passwordExpire'], $content);
    $content = str_replace('###PASSWORDEXPIRESWARN###', $_SESSION[SVN_INST]['passwordExpireWarn'], $content);
    $content = str_replace('###EXPIREPASSWORD###', $_SESSION[SVN_INST]['expirePassword'], $content);
    
    $custom1 = ($_SESSION[SVN_INST]['custom1'] == "") ? "NULL" : "'" . $_SESSION[SVN_INST]['custom1'] . "'";
    $custom2 = ($_SESSION[SVN_INST]['custom2'] == "") ? "NULL" : "'" . $_SESSION[SVN_INST]['custom2'] . "'";
    $custom3 = ($_SESSION[SVN_INST]['custom3'] == "") ? "NULL" : "'" . $_SESSION[SVN_INST]['custom3'] . "'";
    
    $content = str_replace('###CUSTOM1###', $custom1, $content);
    $content = str_replace('###CUSTOM2###', $custom2, $content);
    $content = str_replace('###CUSTOM3###', $custom3, $content);
    
    return ($content);
    
}

/**
 * check svnadmin command
 *
 * @return string
 */
function checkSvnadminCommand() {

    $output = "";
    $retcode = 0;
    $cmd = $_SESSION[SVN_INST]['svnadminCommand'] . " help create";
    exec($cmd, $output, $retcode);
    if ($retcode == 0) {
        
        $treffer = preg_grep('/\-\-pre\-(.*)\-compatible/', $output);
        
        if (count($treffer) > 0) {
            
            foreach( $treffer as $entry) {
                
                $entry = explode(":", $entry);
                $entry = $entry[0];
                $entry = preg_replace('/^\s+/', '', $entry);
                $entry = preg_replace('/\s+$/', '', $entry);
            }
            
            $preCompatible = $entry;
        }
        else {
            $preCompatible = "--pre-1.4-compatible";
        }
    }
    else {
        $preCompatible = "--pre-1.4-compatible";
    }
    
    return ($preCompatible);
    
}

/**
 * get installbase
 *
 * @return string
 */
function getInstallBase() {

    return (isset($_SERVER['SCRIPT_FILENAME']) ? dirname(dirname($_SERVER['SCRIPT_FILENAME'])) : '');
    
}

/**
 * write config file content
 *
 * @param string $confignew
 * @param string $content
 * @return array[][]
 */
function writeConfigContent($confignew, $content) {

    $tMessage = "";
    $error = 0;
    $tErrors = array();
    $tResult = array();
    
    if ($fh_out = @fopen($confignew, "w")) {
        
        if (! @fwrite($fh_out, $content)) {
            
            $tErrors[] = _("Can't write new config.inc.php file!");
            $error = 1;
        }
        
        @fclose($fh_out);
    }
    else {
        
        $tErrors[] = sprintf(_("can't open %s for writing. Please make sure the config directory is writeable for the webserver user!"), $confignew);
        $error = 1;
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors,
            RESULT => $tResult
    ));
    
}

/**
 * write configuration file
 *
 * @param string $configtmpl
 * @param string $confignew
 * @param string $configfile
 * @return array[][]
 */
function doInstallConfigFile($configtmpl, $confignew, $configfile) {

    $error = 0;
    $tErrors = array();
    $tResult = array();
    
    if ($fh_in = @fopen($configtmpl, "r")) {
        
        $viewvcconf = $_SESSION[SVN_INST]['viewvcConfigDir'] . "/viewvc-apache.conf";
        $viewvcgroups = $_SESSION[SVN_INST]['viewvcConfigDir'] . "/viewvc-groups";
        $content = fread($fh_in, filesize($configtmpl));
        @fclose($fh_in);
        
        $preCompatible = checkSvnadminCommand();
        $installBase = getInstallBase();
        $content = replaceTokens($content, $viewvcconf, $viewvcgroups, $preCompatible, $installBase);
        $ret = writeConfigContent($confignew, $content);
        $error = $ret[ERROR];
        $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
        $tResult = array_merge($tResult, $ret[RESULT]);
    }
    else {
        
        $tErrors[] = sprintf(_("can't open config template %s for reading!"), $configtmpl);
        $error = 1;
    }
    
    if ($error == 0) {
        
        if (@copy($confignew, $configfile)) {
            
            if (! @unlink($confignew)) {
                
                if (determineOs() == "windows") {
                    $error = 0;
                }
                else {
                    $error = 1;
                    $tErrors[] = _("Error deleting temporary config file");
                }
            }
            else {
                
                $tResult[] = _("config.inc.php successfully created");
            }
        }
        else {
            
            $error = 1;
            $tErrors[] = sprintf(_("Error copying temporary config file %s to %s!"), $confignew, $configfile);
        }
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors,
            RESULT => $tResult
    ));
    
}

/**
 * install MNySQL dfatabase tables
 *
 * @param resource $dbh
 * @return array[][]
 */
function doInstallDatabaseMySQL($dbh) {

    $error = 0;
    $tErrors = array();
    $tResult = array();
    
    $CONF[DATABASE_HOST] = $_SESSION[SVN_INST][DATABASEHOST];
    $CONF[DATABASE_USER] = $_SESSION[SVN_INST][DATABASEUSER];
    $CONF[DATABASE_PASSWORD] = $_SESSION[SVN_INST][DATABASEPASSWORD];
    $CONF[DATABASE_NAME] = $_SESSION[SVN_INST][DATABASENAME];
    $CONF[DATABASE_SCHEMA] = $_SESSION[SVN_INST][DATABASESCHEMA];
    $CONF[DATABASE_TABLESPACE] = $_SESSION[SVN_INST][DATABASETABLESPACE];
    
    if ($_SESSION[SVN_INST]['dropDatabaseTables'] == "YES") {
        
        $ret = dropMySQLDatabaseTables($dbh);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
            $error = 1;
        }
        else {
            
            $tResult[] = _("Database tables successfully dropped");
        }
    }
    else {
        
        $tResult[] = _("No database tables dropped");
    }
    
    if ($error == 0) {
        
        $ret = createMySQLDatabaseTables($dbh, $_SESSION[SVN_INST][DATABASECHARSET], $_SESSION[SVN_INST][DATABASECOLLATION]);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
        }
        else {
            
            $tResult[] = _("Database tables successfully created");
        }
    }
    
    if ($error == 0) {
        
        $ret = loadMySQLDbData($dbh);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
        }
        else {
            
            $tResult[] = _("Database tables successfully created");
        }
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors,
            RESULT => $tResult
    ));
    
}

/**
 * install postgresql database tables
 *
 * @param resource $dbh
 * @return array[][]
 */
function doInstallDatabasePostgres($dbh) {

    $error = 0;
    $tErrors = array();
    $tResult = array();
    
    $CONF[DATABASE_HOST] = $_SESSION[SVN_INST][DATABASEHOST];
    $CONF[DATABASE_USER] = $_SESSION[SVN_INST][DATABASEUSER];
    $CONF[DATABASE_PASSWORD] = $_SESSION[SVN_INST][DATABASEPASSWORD];
    $CONF[DATABASE_NAME] = $_SESSION[SVN_INST][DATABASENAME];
    $CONF[DATABASE_SCHEMA] = $_SESSION[SVN_INST][DATABASESCHEMA];
    $CONF[DATABASE_TABLESPACE] = $_SESSION[SVN_INST][DATABASETABLESPACE];
    
    if ($_SESSION[SVN_INST]['dropDatabaseTables'] == "YES") {
        
        $ret = dropPostgresDatabaseTables($dbh);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
            $error = 1;
        }
        else {
            
            $tResult[] = _("Database tables successfully dropped");
        }
    }
    else {
        
        $tResult[] = _("No database tables dropped");
    }
    
    if ($error == 0) {
        
        $ret = createPgDatabaseTables($dbh, $_SESSION[SVN_INST][DATABASECHARSET], $_SESSION[SVN_INST][DATABASESCHEMA], $_SESSION[SVN_INST][DATABASETABLESPACE], $_SESSION[SVN_INST][DATABASEUSER]);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
        }
        else {
            
            $tResult[] = _("Database tables successfully created");
        }
    }
    
    if ($error == 0) {
        
        $ret = loadPostgresDbData($dbh, $_SESSION[SVN_INST][DATABASESCHEMA]);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
        }
        else {
            
            $tResult[] = _("Database tables successfully created");
        }
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors,
            RESULT => $tResult
    ));
    
}

/**
 * install oracle database tables
 *
 * @param resource $dbh
 * @param string $schema
 * @return array[][]
 */
function doInstallDatabaseOracle($dbh, $schema) {

    $error = 0;
    $tErrors = array();
    $tResult = array();
    
    $CONF[DATABASE_HOST] = $_SESSION[SVN_INST][DATABASEHOST];
    $CONF[DATABASE_USER] = $_SESSION[SVN_INST][DATABASEUSER];
    $CONF[DATABASE_PASSWORD] = $_SESSION[SVN_INST][DATABASEPASSWORD];
    $CONF[DATABASE_NAME] = $_SESSION[SVN_INST][DATABASENAME];
    $CONF[DATABASE_SCHEMA] = $_SESSION[SVN_INST][DATABASESCHEMA];
    $CONF[DATABASE_TABLESPACE] = $_SESSION[SVN_INST][DATABASETABLESPACE];
    
    if ($_SESSION[SVN_INST]['dropDatabaseTables'] == "YES") {
        
        $ret = dropOracleDatabaseTables($dbh, $schema);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
            $error = 1;
        }
        else {
            
            $tResult[] = _("Database tables successfully dropped");
        }
    }
    else {
        
        $tResult[] = _("No database tables dropped");
    }
    
    if ($error == 0) {
        
        $ret = createOracleDatabaseTables($dbh, $schema);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
        }
        else {
            
            $tResult[] = _("Database tables successfully created");
        }
    }
    
    if ($error == 0) {
        
        $ret = loadOracleDbData($dbh, $schema);
        if ($ret[ERROR] != 0) {
            
            $tErrors[] = $ret[ERRORMSG];
        }
        else {
            
            $tResult[] = _("Database tables successfully created");
        }
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors,
            RESULT => $tResult
    ));
    
}

/**
 * runn database installations
 */
function doInstallDatabase() {

    $error = 0;
    $tErrors = array();
    $tResult = array();
    
    $CONF[DATABASE_HOST] = $_SESSION[SVN_INST][DATABASEHOST];
    $CONF[DATABASE_USER] = $_SESSION[SVN_INST][DATABASEUSER];
    $CONF[DATABASE_PASSWORD] = $_SESSION[SVN_INST][DATABASEPASSWORD];
    $CONF[DATABASE_NAME] = $_SESSION[SVN_INST][DATABASENAME];
    $CONF[DATABASE_SCHEMA] = $_SESSION[SVN_INST][DATABASESCHEMA];
    $CONF[DATABASE_TABLESPACE] = $_SESSION[SVN_INST][DATABASETABLESPACE];
    
    if ($_SESSION[SVN_INST]['createDatabaseTables'] == "YES") {
        
        $dbh = db_connect_install($_SESSION[SVN_INST][DATABASEHOST], $_SESSION[SVN_INST][DATABASEUSER], $_SESSION[SVN_INST][DATABASEPASSWORD], $_SESSION[SVN_INST][DATABASENAME], $_SESSION[SVN_INST][DATABASECHARSET], $_SESSION[SVN_INST][DATABASECOLLATION], $_SESSION[SVN_INST]['database']);
        
        if ((strtoupper($_SESSION[SVN_INST]['database']) == 'MYSQL') || (strtoupper($_SESSION[SVN_INST]['database']) == 'MYSQLI')) {
            
            $ret = doInstallDatabaseMySQL($dbh);
        }
        elseif (strtoupper($_SESSION[SVN_INST]['database']) == "POSTGRES8") {
            
            $ret = doInstallDatabasePostgres($dbh);
        }
        elseif (strtoupper($_SESSION[SVN_INST]['database']) == "OCI8") {
            
            $ret = doInstallDatabaseOracle($dbh, $_SESSION[SVN_INST][DATABASESCHEMA]);
        }
        else {
            
            $error = 1;
            $tError[] = sprintf(_("Unknown database type: %s"), $_SESSION[SVN_INST]['database']);
        }
        
        $error = $ret[ERROR];
        $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
        $tResult = array_merge($tResult, $ret[RESULT]);
        
        if ($error == 0) {
            
            $ret = createAdmin($_SESSION[SVN_INST]['username'], $_SESSION[SVN_INST][PASSWORD], $_SESSION[SVN_INST]['givenname'], $_SESSION[SVN_INST]['name'], $_SESSION[SVN_INST]['adminEmail'], $_SESSION[SVN_INST]['database'], $dbh, $_SESSION[SVN_INST][DATABASESCHEMA]);
            if ($ret[ERROR] != 0) {
                
                $tErrors[] = $ret[ERRORMSG];
            }
            else {
                
                $tResult[] = _("Admin account successfully created");
            }
        }
        
        if ($error == 0) {
            
            $ret = loadHelpTexts($_SESSION[SVN_INST]['database'], $_SESSION[SVN_INST][DATABASESCHEMA], $dbh);
        }
        
        db_disconnect($dbh);
    }
    else {
        
        $tResult[] = _("No database tables created");
    }
    
    return (array(
            ERROR => $error,
            ERRORLIST => $tErrors,
            RESULT => $tResult
    ));
    
}

/**
 * perform installatiomn
 *
 * @return array[]
 */
function doInstall() {

    $error = 0;
    $tErrors = array();
    $tResult = array();
    $tBaseDir = getInstallerConfigDir();
    
    list($tConfigDir, $configpath, $configtmpl, $confignew, $configfile ) = getConfigVariables($tBaseDir);
    
    $tConfigWritable = isConfigWritable($tConfigDir);
    
    if (! is_writable(dirname($configfile))) {
        
        $tErrors[] = sprintf(_("Config directory %s not writable!"), dirname($configfile));
        $error = 1;
    }
    
    if ($error == 0) {
        
        $ret = checkSessionValues();
        $error = $ret[ERROR];
        $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
    }
    
    if ($error == 0) {
        
        $ret = doInstallConfigFile($configtmpl, $confignew, $configfile);
        $error = $ret[ERROR];
        $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
        $tResult = array_merge($tResult, $ret[RESULT]);
    }
    
    if ($error == 0) {
        
        $ret = doInstallDatabase();
        $error = $ret[ERROR];
        $tErrors = array_merge($tErrors, $ret[ERRORLIST]);
        $tResult = array_merge($tResult, $ret[RESULT]);
    }
    
    if ($error == 0) {
        
        $CONF = array();
        $CONF['copyright'] = '(C) 2008, 2009, 2010, .. 2018 Thomas Krieger (tom(at)svn-access-manager(dot)org)';
        $tAuthUserFile = getAuthUserFileFromSession();
        $tSvnAccessFile = getSvnAccessFileFromSession();
        
        include ("../templates/installresult.tpl");
        exit();
    }
    else {
        
        $tLogging = getLoggingFromSession();
        $tJavaScript = getJavaScriptFromSession();
        $tPageSize = getPageSizeFromSession();
        $tMinAdminPwSize = getMinAdminPwSizeFromSession();
        $tMinUserPwSize = getMinUserPwSizeFromSession();
        $tExpirePassword = getExpirePasswordFromSession();
        $tPwEnc = getPwEncFromSession();
        $tUserDefaultAccess = getUserDefaultAccessFromSession();
        $tCustom1 = getCustom1FromSession();
        $tCustom2 = getCustom2FromSession();
        $tCustom3 = getCustom3FromSession();
        
        list($tJavaScriptYes, $tJavaScriptNo ) = setJavaScript($tJavaScript);
        list($tLoggingYes, $tLoggingNo ) = setLogging($tLogging);
        list($tExpirePasswordYes, $tExpirePasswordNo ) = setPasswordExpires($tExpirePassword);
        list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $CONF['pwcrypt'] ) = setEncryption($tPwEnc);
        list($tUserDefaultAccessRead, $tUserDefaultAccessWrite ) = setUserDefaultAccess($tUserDefaultAccess);
    }
    
    $ret = array();
    $ret['page'] = 7;
    $ret['errors'] = $tErrors;
    
    return ($ret);
    
}

/**
 * get installe configuration directory
 *
 * @return string
 */
function getInstallerConfigDir() {

    if (file_exists(realpath("./config/config.inc.php"))) {
        
        $configfile = realpath("./config/config.inc.php");
        $tBaseDir = dirname(dirname($configfile));
    }
    elseif (file_exists(realpath("../config/config.inc.php"))) {
        
        $configfile = realpath("../config/config.inc.php");
        $tBaseDir = dirname(dirname($configfile));
    }
    else {
        
        $configfile = realpath("../config/");
        $tBaseDir = dirname($configfile);
    }
    
    return ($tBaseDir);
    
}

// ----------------------------------------------------------------------------------------------------------------------#
// main section
// ----------------------------------------------------------------------------------------------------------------------#

initialize_i18n();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $s = new Session();
    session_start();
    if (! isset($_SESSION[SVN_INST])) {
        $_SESSION[SVN_INST] = array();
    }
    $_SESSION[SVN_INST]['page'] = "1";
    
    $CONF = array();
    $CONF['database_type'] = "mysql";
    $CONF['database_innodb'] = 'YES';
    $CONF['copyright'] = '(C) 2008, 2009, 2010, 2011, .. 2018 Thomas Krieger (tom(at)svn-access-manager(dot)org)';
    
    $tCreateDatabaseTablesYes = "checked";
    $tCreateDatabaseTablesNo = "";
    $tDropDatabaseTablesYes = "checked";
    $tDropDatabaseTablesNo = "";
    if (function_exists('mysql_connect')) {
        $tDatabaseMySQL = "checked";
        $tDatabaseMySQLi = "";
    }
    else {
        $tDatabaseMySQL = "";
        $tDatabaseMySQLi = "checked";
    }
    $tDatabasePostgreSQL = "";
    $tDatabaseOracle = "";
    $tSessionInDatabaseYes = "checked";
    $tSessionInDatabaseNo = "";
    $tUseLdapYes = "";
    $tUseLdapNo = "checked";
    $tLdapHost = "";
    $tLdapPort = "389";
    $tLdap2 = "";
    $tLdap3 = "checked";
    $tLdapBinddn = "";
    $tLdapBindpw = "";
    $tLdapUserdn = "";
    $tLdapUserFilter = "";
    $tLdapUserObjectclass = "";
    $tLdapUserAdditionalFilter = "";
    $tDatabaseHost = "";
    $tDatabaseUser = "";
    $tDatabasePassword = "";
    $tDatabaseName = "";
    $tDatabaseSchema = "";
    $tDatabaseTablespace = "";
    $tDatabaseCharset = "";
    $tDatabaseCollation = "";
    $tLdapAttrUid = "uid";
    $tLdapAttrName = "sn";
    $tLdapAttrGivenname = "givenName";
    $tLdapAttrMail = "mail";
    $tLdapAttrPassword = "userPassword";
    $tLdapAttrUserSort = "sn";
    $tLdapUserSort = "ASC";
    $tLdapBindUseLoginData = 0;
    $tLdapBindDnSuffix = "";
    $tWebsiteCharset = "";
    $tWebsiteUrl = "";
    $tLpwMailSender = "";
    $tLpwLinkValid = 2;
    $tUsername = "";
    $tPassword = "";
    $tPassword2 = "";
    $tGivenname = "";
    $tName = "";
    $tAdminEmail = "";
    $tUseSvnAccessFile = "";
    $tSvnAccessFile = "/etc/svn/svnaccess";
    $tAccessControlLevel = "dirs";
    $tUseAuthUserFile = "";
    $tAuthUserFile = "/etc/svn/svnpasswd";
    $tSvnCommand = "";
    $tSvnadminCommand = "";
    $tGrepCommand = "";
    $tViewvcConfig = "";
    $tViewvcConfigDir = "/etc/svn";
    $tViewvcAlias = "/viewvc";
    $tViewvcApacheReload = "";
    $tViewvcRealm = "ViewVC";
    $tPerRepoFiles = "";
    $tPathSortOrder = "ASC";
    $tAnonAccess = 0;
    $tLogging = "";
    $tJavaScript = "";
    $tUserDefaultAccess = "";
    $tPageSize = 30;
    $tMinAdminPwSize = 14;
    $tMinUserPwSize = 8;
    $tExpirePassword = 1;
    $tPasswordExpire = 60;
    $tPasswordExpireWarn = 50;
    $tPwEnc = "md5";
    $tCustom1 = "";
    $tCustom2 = "";
    $tCustom3 = "";
    $error = 0;
    $tPage = 0;
    
    $tBaseDir = getInstallerConfigDir();
    
    if (determineOs() == "windows") {
        
        $tConfigDir = $tBaseDir . "\config";
    }
    else {
        
        $tConfigDir = "/etc/svn-access-manager";
    }
    
    clearstatcache();
    if (is_writable($tConfigDir)) {
        $tConfigWritable = _("writable");
    }
    else {
        $tConfigWritable = _("not writable");
    }
    
    $tSvnCommand = getSvnCommand($tSvnCommand);
    $tSvnadminCommand = getSvnadminCommand($tSvnadminCommand);
    $tGrepCommand = getGrepCommand($tGrepCommand);
    $tViewvcApacheReload = getApacheReloadCommand($tViewvcApacheReload);
    
    list($tUseAuthUserFileYes, $tUseAuthUserFileNo ) = setUseAuthUserFile($tUseAuthUserFile);
    list($tUseSvnAccessFileYes, $tUseSvnAccessFileNo ) = setUseSvnAccessFile($tUseSvnAccessFile);
    list($tAccessControlLevelDirs, $tAccessControlLevelFiles ) = setAccessControlLevel($tAccessControlLevel);
    list($tPerRepoFilesYes, $tPerRepoFilesNo ) = setPerRepoFiles($tPerRepoFiles);
    list($tPathSortOrderAsc, $tPathSortOrderDesc ) = setPathSortOrder($tPathSortOrder);
    list($tLdapUserSortAsc, $tLdapUserSortDesc ) = setLdapUserSort($tLdapUserSort);
    list($tLdapBindUseLoginDataYes, $tLdapBindUseLoginDataNo ) = setLdapBindUseLoginData($tLdapBindUseLoginData);
    list($tAnonAccessYes, $tAnonAccessNo ) = setAnonAccess($tAnonAccess);
    list($tViewvcConfigYes, $tViewvcConfigNo ) = setViewvcConfig($tViewvcConfig);
    list($tJavaScriptYes, $tJavaScriptNo ) = setJavaScript($tJavaScript);
    list($tLoggingYes, $tLoggingNo ) = setLogging($tLogging);
    list($tExpirePasswordYes, $tExpirePasswordNo ) = setPasswordExpires($tExpirePassword);
    list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $PwType ) = setEncryption($tPwEnc);
    list($tUserDefaultAccessRead, $tUserDefaultAccessWrite ) = setUserDefaultAccess($tUserDefaultAccess);
    
    $tErrors = array();
    
    include ("../templates/install.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $s = new Session();
    session_start();
    if (! isset($_SESSION[SVN_INST])) {
        $_SESSION[SVN_INST] = array();
        $_SESSION[SVN_INST]['page'] = "1";
    }
    
    $tResult = array();
    $tErrors = array();
    $CONF = array();
    $CONF['database_innodb'] = 'YES';
    $CONF['copyright'] = '(C) 2008, 2009, 2010 Thomas Krieger (tom(at)svn-access-manager(dot)org)';
    
    if (file_exists(realpath("./config/config.inc.php"))) {
        
        $configfile = realpath("./config/config.inc.php");
        $tBaseDir = dirname(dirname($configfile));
    }
    elseif (file_exists(realpath("../config/config.inc.php"))) {
        
        $configfile = realpath("../config/config.inc.php");
        $tBaseDir = dirname(dirname($configfile));
    }
    else {
        
        $configfile = realpath("../config/");
        $tBaseDir = dirname($configfile);
    }
    
    if (determineOs() == "windows") {
        
        $tConfigDir = $tBaseDir . "\config";
    }
    else {
        
        $tConfigDir = "/etc/svn-access-manager";
    }
    
    clearstatcache();
    if (is_writable($tConfigDir)) {
        $tConfigWritable = _("writable");
    }
    else {
        $tConfigWritable = _("not writable");
    }
    
    $tCreateDatabaseTables = isset($_POST['fCreateDatabaseTables']) ? ($_POST['fCreateDatabaseTables']) : "";
    $tDropDatabaseTables = isset($_POST['fDropDatabaseTables']) ? ($_POST['fDropDatabaseTables']) : "";
    $tDatabase = isset($_POST['fDatabase']) ? ($_POST['fDatabase']) : "";
    $tSessionInDatabase = isset($_POST['fSessionInDatabase']) ? ($_POST['fSessionInDatabase']) : "";
    $tUseLdap = isset($_POST['fUseLdap']) ? ($_POST['fUseLdap']) : "";
    $tLdapHost = isset($_POST['fLdapHost']) ? ($_POST['fLdapHost']) : "";
    $tLdapPort = isset($_POST['fLdapPort']) ? ($_POST['fLdapPort']) : "389";
    $tLdapProtocol = isset($_POST['fLdapProtocol']) ? ($_POST['fLdapProtocol']) : "3";
    $tLdapBinddn = isset($_POST['fLdapBinddn']) ? ($_POST['fLdapBinddn']) : "";
    $tLdapBindpw = isset($_POST['fLdapBindpw']) ? ($_POST['fLdapBindpw']) : "";
    $tLdapUserdn = isset($_POST['fLdapUserdn']) ? ($_POST['fLdapUserdn']) : "";
    $tLdapUserFilter = isset($_POST['fLdapUserFilter']) ? ($_POST['fLdapUserFilter']) : "";
    $tLdapUserObjectclass = isset($_POST['fLdapUserObjectclass']) ? ($_POST['fLdapUserObjectclass']) : "";
    $tLdapUserAdditionalFilter = isset($_POST['fLdapUserAdditionalFilter']) ? ($_POST['fLdapUserAdditionalFilter']) : "";
    $tDatabaseHost = isset($_POST['fDatabaseHost']) ? ($_POST['fDatabaseHost']) : "";
    $tDatabaseUser = isset($_POST['fDatabaseUser']) ? ($_POST['fDatabaseUser']) : "";
    $tDatabasePassword = isset($_POST['fDatabasePassword']) ? ($_POST['fDatabasePassword']) : "";
    $tDatabaseName = isset($_POST['fDatabaseName']) ? ($_POST['fDatabaseName']) : "";
    $tDatabaseSchema = isset($_POST['fDatabaseSchema']) ? ($_POST['fDatabaseSchema']) : "";
    $tDatabaseTablespace = isset($_POST['fDatabaseTablespace']) ? ($_POST['fDatabaseTablespace']) : "";
    $tDatabaseCharset = isset($_POST['fDatabaseCharset']) ? ($_POST['fDatabaseCharset']) : "";
    $tDatabaseCollation = isset($_POST['fDatabaseCollation']) ? ($_POST['fDatabaseCollation']) : "";
    $tLdapAttrUid = isset($_POST['fLdapAttrUid']) ? ($_POST['fLdapAttrUid']) : "";
    $tLdapAttrName = isset($_POST['fLdapAttrName']) ? ($_POST['fLdapAttrName']) : "";
    $tLdapAttrGivenname = isset($_POST['fLdapAttrGivenname']) ? ($_POST['fLdapAttrGivenname']) : "";
    $tLdapAttrMail = isset($_POST['fLdapAttrMail']) ? ($_POST['fLdapAttrMail']) : "";
    $tLdapAttrPassword = isset($_POST['fLdapAttrPassword']) ? ($_POST['fLdapAttrPassword']) : "";
    $tLdapAttrUserSort = isset($_POST['fLdapAttrUserSort']) ? ($_POST['fLdapAttrUserSort']) : "";
    $tLdapUserSort = isset($_POST['fLdapUserSort']) ? ($_POST['fLdapUserSort']) : "ASC";
    $tLdapBindUseLoginData = isset($_POST['fLdapBindUseLoginData']) ? ($_POST['fLdapBindUseLoginData']) : 0;
    $tLdapBindDnSuffix = isset($_POST['fLdapBindDnSuffix']) ? ($_POST['fLdapBindDnSuffix']) : "";
    $tWebsiteUrl = isset($_POST['fWebsiteUrl']) ? ($_POST['fWebsiteUrl']) : "";
    $tWebsiteCharset = isset($_POST['fWebsiteCharset']) ? ($_POST['fWebsiteCharset']) : "";
    $tLpwMailSender = isset($_POST['fLpwMailSender']) ? ($_POST['fLpwMailSender']) : "";
    $tLpwLinkValid = isset($_POST['fLpwLinkValid']) ? ($_POST['fLpwLinkValid']) : "";
    $tUsername = isset($_POST['fUsername']) ? ($_POST['fUsername']) : "";
    $tPassword = isset($_POST['fPassword']) ? ($_POST['fPassword']) : "";
    $tPassword2 = isset($_POST['fPassword2']) ? ($_POST['fPassword2']) : "";
    $tGivenname = isset($_POST['fGivenname']) ? ($_POST['fGivenname']) : "";
    $tName = isset($_POST['fName']) ? ($_POST['fName']) : "";
    $tAdminEmail = isset($_POST['fAdminEmail']) ? ($_POST['fAdminEmail']) : "";
    $tUseSvnAccessFile = isset($_POST['fUseSvnAccessFile']) ? ($_POST['fUseSvnAccessFile']) : "";
    $tSvnAccessFile = isset($_POST['fSvnAccessFile']) ? ($_POST['fSvnAccessFile']) : "";
    $tAccessControlLevel = isset($_POST['fAccessControlLevel']) ? ($_POST['fAccessControlLevel']) : "";
    $tUseAuthUserFile = isset($_POST['fUseAuthUserFile']) ? ($_POST['fUseAuthUserFile']) : "";
    $tAuthUserFile = isset($_POST['fAuthUserFile']) ? ($_POST['fAuthUserFile']) : "";
    $tSvnCommand = isset($_POST['fSvnCommand']) ? ($_POST['fSvnCommand']) : "";
    $tSvnadminCommand = isset($_POST['fSvnadminCommand']) ? ($_POST['fSvnadminCommand']) : "";
    $tGrepCommand = isset($_POST['fGrepCommand']) ? ($_POST['fGrepCommand']) : "";
    $tViewvcConfig = isset($_POST['fViewvcConfig']) ? ($_POST['fViewvcConfig']) : "";
    $tViewvcConfigDir = isset($_POST['fViewvcConfigDir']) ? ($_POST['fViewvcConfigDir']) : "";
    $tViewvcAlias = isset($_POST['fViewvcAlias']) ? ($_POST['fViewvcAlias']) : "";
    $tViewvcApacheReload = isset($_POST['fViewvcApacheReload']) ? ($_POST['fViewvcApacheReload']) : "";
    $tViewvcRealm = isset($_POST['fViewvcRealm']) ? ($_POST['fViewvcRealm']) : "";
    $tPerRepoFiles = isset($_POST['fPerRepoFiles']) ? ($_POST['fPerRepoFiles']) : "";
    $tPathSortOrder = isset($_POST['fPathSortOrder']) ? ($_POST['fPathSortOrder']) : "ASC";
    $tAnonAccess = isset($_POST['fAnonAccess']) ? ($_POST['fAnonAccess']) : 0;
    $tLogging = isset($_POST['fLogging']) ? ($_POST['fLogging']) : "";
    $tJavaScript = isset($_POST['fJavaScript']) ? ($_POST['fJavaScript']) : "";
    $tPageSize = isset($_POST['fPageSize']) ? ($_POST['fPageSize']) : 30;
    $tMinAdminPwSize = isset($_POST['fMinAdminPwSize']) ? ($_POST['fMinAdminPwSize']) : 14;
    $tMinUserPwSize = isset($_POST['fMinUserPwSize']) ? ($_POST['fMinUserPwSize']) : 8;
    $tPasswordExpire = isset($_POST['fPasswordExpire']) ? ($_POST['fPasswordExpire']) : 60;
    $tPasswordExpireWarn = isset($_POST['fPasswordExpireWarn']) ? ($_POST['fPasswordExpireWarn']) : 50;
    $tExpirePassword = isset($_POST['fExpirePassword']) ? ($_POST['fExpirePassword']) : 1;
    $tPwEnc = isset($_POST['fPwEnc']) ? ($_POST['fPwEnc']) : "";
    $tUserDefaultAccess = isset($_POST['fUserDefaultAccess']) ? ($_POST['fUserDefaultAccess']) : "";
    $tCustom1 = isset($_POST['fCustom1']) ? ($_POST['fCustom1']) : "";
    $tCustom2 = isset($_POST['fCustom2']) ? ($_POST['fCustom2']) : "";
    $tCustom3 = isset($_POST['fCustom3']) ? ($_POST['fCustom3']) : "";
    $error = 0;
    
    $_SESSION[SVN_INST]['createDatabaseTables'] = $tCreateDatabaseTables;
    $_SESSION[SVN_INST]['dropDatabaseTables'] = $tDropDatabaseTables;
    $_SESSION[SVN_INST]['database'] = $tDatabase;
    $_SESSION[SVN_INST]['sessionInDatabase'] = $tSessionInDatabase;
    $_SESSION[SVN_INST]['useLdap'] = $tUseLdap;
    $_SESSION[SVN_INST]['ldapHost'] = $tLdapHost;
    $_SESSION[SVN_INST]['ldapPort'] = $tLdapPort;
    $_SESSION[SVN_INST]['ldapProtocol'] = $tLdapProtocol;
    $_SESSION[SVN_INST]['ldapBinddn'] = $tLdapBinddn;
    $_SESSION[SVN_INST]['ldapBindpw'] = $tLdapBindpw;
    $_SESSION[SVN_INST]['ldapUserdn'] = $tLdapUserdn;
    $_SESSION[SVN_INST]['ldapUserFilter'] = $tLdapUserFilter;
    $_SESSION[SVN_INST]['ldapUserObjectclass'] = $tLdapUserObjectclass;
    $_SESSION[SVN_INST]['ldapUserAdditionalFilter'] = $tLdapUserAdditionalFilter;
    $_SESSION[SVN_INST][DATABASEHOST] = $tDatabaseHost;
    $_SESSION[SVN_INST][DATABASEUSER] = $tDatabaseUser;
    $_SESSION[SVN_INST][DATABASEPASSWORD] = $tDatabasePassword;
    $_SESSION[SVN_INST][DATABASENAME] = $tDatabaseName;
    $_SESSION[SVN_INST][DATABASESCHEMA] = $tDatabaseSchema;
    $_SESSION[SVN_INST][DATABASETABLESPACE] = $tDatabaseTablespace;
    $_SESSION[SVN_INST][DATABASECHARSET] = $tDatabaseCharset;
    $_SESSION[SVN_INST][DATABASECOLLATION] = $tDatabaseCollation;
    $_SESSION[SVN_INST]['ldapAttrUid'] = $tLdapAttrUid;
    $_SESSION[SVN_INST]['ldapAttrName'] = $tLdapAttrName;
    $_SESSION[SVN_INST]['ldapAttrGivenname'] = $tLdapAttrGivenname;
    $_SESSION[SVN_INST]['ldapAttrMail'] = $tLdapAttrMail;
    $_SESSION[SVN_INST]['ldapAttrPassword'] = $tLdapAttrPassword;
    $_SESSION[SVN_INST]['ldapAttrUserSort'] = $tLdapAttrUserSort;
    $_SESSION[SVN_INST]['ldapUserSort'] = $tLdapUserSort;
    $_SESSION[SVN_INST]['ldapBindUseLoginData'] = $tLdapBindUseLoginData;
    $_SESSION[SVN_INST]['ldapBindDnSuffix'] = $tLdapBindDnSuffix;
    $_SESSION[SVN_INST]['websiteUrl'] = $tWebsiteUrl;
    $_SESSION[SVN_INST]['websiteCharset'] = $tWebsiteCharset;
    $_SESSION[SVN_INST]['lpwMailSender'] = $tLpwMailSender;
    $_SESSION[SVN_INST]['lpwLinkValid'] = $tLpwLinkValid;
    $_SESSION[SVN_INST]['username'] = $tUsername;
    $_SESSION[SVN_INST][PASSWORD] = $tPassword;
    $_SESSION[SVN_INST][PASSWORD2] = $tPassword2;
    $_SESSION[SVN_INST]['givenname'] = $tGivenname;
    $_SESSION[SVN_INST]['name'] = $tName;
    $_SESSION[SVN_INST]['adminEmail'] = $tAdminEmail;
    $_SESSION[SVN_INST]['useSvnAccessFile'] = $tUseSvnAccessFile;
    $_SESSION[SVN_INST]['svnAccessFile'] = $tSvnAccessFile;
    $_SESSION[SVN_INST]['accessControlLevel'] = $tAccessControlLevel;
    $_SESSION[SVN_INST]['useAuthUserFile'] = $tUseAuthUserFile;
    $_SESSION[SVN_INST]['authUserFile'] = $tAuthUserFile;
    $_SESSION[SVN_INST]['svnCommand'] = $tSvnCommand;
    $_SESSION[SVN_INST]['svnadminCommand'] = $tSvnadminCommand;
    $_SESSION[SVN_INST]['grepCommand'] = $tGrepCommand;
    $_SESSION[SVN_INST]['viewvcConfig'] = $tViewvcConfig;
    $_SESSION[SVN_INST]['viewvcConfigDir'] = $tViewvcConfigDir;
    $_SESSION[SVN_INST]['viewvcAlias'] = $tViewvcAlias;
    $_SESSION[SVN_INST]['viewvcApacheReload'] = $tViewvcApacheReload;
    $_SESSION[SVN_INST]['viewvcRealm'] = $tViewvcRealm;
    $_SESSION[SVN_INST]['perRepoFiles'] = $tPerRepoFiles;
    $_SESSION[SVN_INST]['pathSortOrder'] = $tPathSortOrder;
    $_SESSION[SVN_INST]['anonAccess'] = $tAnonAccess;
    $_SESSION[SVN_INST]['logging'] = $tLogging;
    $_SESSION[SVN_INST]['javaScript'] = $tJavaScript;
    $_SESSION[SVN_INST]['pageSize'] = $tPageSize;
    $_SESSION[SVN_INST]['minAdminPwSize'] = $tMinAdminPwSize;
    $_SESSION[SVN_INST]['minUserPwSize'] = $tMinUserPwSize;
    $_SESSION[SVN_INST]['passwordExpire'] = $tPasswordExpire;
    $_SESSION[SVN_INST]['passwordExpireWarn'] = $tPasswordExpireWarn;
    $_SESSION[SVN_INST]['expirePassword'] = $tExpirePassword;
    $_SESSION[SVN_INST]['pwEnc'] = $tPwEnc;
    $_SESSION[SVN_INST]['userDefaultAccess'] = $tUserDefaultAccess;
    $_SESSION[SVN_INST]['custom1'] = $tCustom1;
    $_SESSION[SVN_INST]['custom2'] = $tCustom2;
    $_SESSION[SVN_INST]['custom3'] = $tCustom3;
    
    if (isset($_POST['fSubmit_install']) || isset($_POST['fSubmit_install_x'])) {
        
        $error = 0;
        $CONF['database_type'] = $_SESSION[SVN_INST]['database'];
        
        //
        // check fields
        //
        
        list($tDatabaseCharsetDefault, $tDatabaseCollationDefault ) = setDatabaseCharset($tDatabase);
        
        if ($tDatabaseHost == "") {
            
            $tErrors[] = _("Database host is missing!");
            $error = 1;
        }
        
        if ($tDatabaseUser == "") {
            
            $tErrors[] = _("Database user is missing!");
            $error = 1;
        }
        
        if ($tDatabaseName == "") {
            
            $tErrors[] = _("Database name is missing!");
            $error = 1;
        }
        
        if ($tDatabaseCharset == "") {
            
            $tErrors[] = _("Database charset is missing!");
            $error = 1;
        }
        
        if ((($tDatabase == MYSQL) || ($tDatabase == MYSQLI)) && ($tDatabaseCollation == "")) {
            
            $tErrors[] = _("Database collation is missing!");
            $error = 1;
        }
        
        if (strtoupper($tUseLdap) == "YES") {
            
            if ($tLdapHost == "") {
                
                $tErrors[] = _("LDAP host is missing!");
                $error = 1;
            }
            
            if ($tLdapPort == "") {
                
                $tErrors[] = _("LDAP port is missing!");
                $error = 1;
            }
            
            if (($tLdapProtocol != "2") && ($tLdapProtocol != "3")) {
                
                $tErrors[] = sprintf(_("Invalid protocol version %s!"), $tLdapProtocol);
                $error = 1;
            }
            
            if ($tLdapBinddn == "") {
                
                $tErrors[] = _("LDAP bind dn is missing!");
                $error = 1;
            }
            
            if ($tLdapBindpw == "") {
                
                $tErrors[] = _("LDAP bind password is missing!");
                $error = 1;
            }
            
            if ($tLdapUserdn == "") {
                
                $tErrors[] = _("LDAP user dn is missing!");
                $error = 1;
            }
            
            if ($tLdapUserFilter == "") {
                
                $tErrors[] = _("LDAP user filter attribute is missing!");
                $error = 1;
            }
            
            if ($tLdapUserObjectclass == "") {
                
                $tErrors[] = _("LDAP user object class is missing!");
                $error = 1;
            }
            
            if ($tLdapAttrUid == "") {
                
                $tErrors[] = _("LDAP attribute mapping for uid is missing!");
                $error = 1;
            }
            
            if ($tLdapAttrName == "") {
                
                $tErrors[] = _("LDAP attribute mapping for name is missing!");
                $error = 1;
            }
            
            if ($tLdapAttrGivenname == "") {
                
                $tErrors[] = _("LDAP attribute mapping for given name is missing!");
                $error = 1;
            }
            
            if ($tLdapAttrMail == "") {
                
                $tErrors[] = _("LDAP attribute mapping for mail is missing!");
                $error = 1;
            }
            
            if ($tLdapAttrPassword == "") {
                
                $tErrors[] = _("LDAP attribute mapping for user password is missing!");
                $error = 1;
            }
            
            if ($tLdapAttrUserSort == "") {
                
                $tErrors[] = _("LDAP attribute for sorting users missing!");
                $error = 1;
            }
            
            if (($tLdapUserSort != "ASC") && ($tLdapUserSort != "DESC")) {
                
                $tErrors[] = sprintf(_("LDAP user sort order is missing or invalid: %s"), $tLdapUserSort);
            }
            
            if (($tLdapBindUseLoginData != 0) && ($tLdapBindUseLoginData != 1)) {
                
                $tErrors[] = sprintf(_("LDAP bind uses login data is missing or invalid: %s"), $tLdapBindUseLoginData);
            }
            
            if (($tLdapBindUseLoginData == 1) && ($tLdapBindDnSuffix == "")) {
                
                $tErrors[] = _("LDAP Bind Dn Suffix is missing!");
            }
        }
        
        if ($tWebsiteUrl == "") {
            
            $tErrors[] = _("SVN Access Manger Website URL is missing!");
            $error = 1;
        }
        
        if ($tWebsiteCharset == "") {
            
            $tErrors[] = _("Website charset is missing!");
            $error = 1;
        }
        
        if ($tLpwMailSender == "") {
            
            $tErrors[] = _("Lost password mail sender address is missing!");
            $error = 1;
        }
        elseif (! check_email($tLpwMailSender)) {
            
            $tErrors[] = sprintf(_("Lost password mail sender address %s is not a valid email address!"), $tLpwMailSender);
            $error = 1;
        }
        
        if ($tLpwLinkValid == "") {
            
            $tErrors[] = _("Lost password days link valid missing!");
            $error = 1;
        }
        elseif (! is_numeric($tLpwLinkValid)) {
            
            $tErrors[] = _("Lost password days link valid must be numeric!");
            $error = 1;
        }
        
        if ($tUsername == "") {
            
            $tErrors[] = _("Administrator username is missing!");
            $error = 1;
        }
        
        if ((($tPassword == "") || ($tPassword2 == "")) && (strtoupper($tUseLdap) != "YES")) {
            
            $tErrors[] = _("Administrator password is missing!");
            $error = 1;
        }
        elseif ($tPassword != $tPassword2) {
            
            $tErrors[] = _("Administrator passwords do not match!");
            $error = 1;
        }
        elseif ((checkPasswordPolicy($tPassword, 'y') == 0) && (strtoupper($tUseLdap) != "YES")) {
            
            $tErrors[] = _("Administrator password is not strong enough!");
            $error = 1;
        }
        
        if ($tName == "") {
            
            $tErrors[] = _("Administrator name is missing!");
            $error = 1;
        }
        
        if ($tAdminEmail == "") {
            
            $tErrors[] = _("Administrator email address is missing!");
            $error = 1;
        }
        elseif (! check_email($tAdminEmail)) {
            
            $tErrors[] = sprintf(_("Administrator email address %s is not a valid email address!"), $tAdminEmail);
            $error = 1;
        }
        
        if ($tViewvcConfig == "YES") {
            
            if ($tViewvcConfigDir == "") {
                
                $tErrors[] = _("ViewVC configuration directory is missing!");
                $error = 1;
            }
            
            if ($tViewvcAlias == "") {
                
                $tErrors[] = _("ViewVC webserver alias is missing!");
                $error = 1;
            }
            
            if ($tViewvcRealm == "") {
                
                $tErrors[] = _("ViewVC realm is missing!");
                $error = 1;
            }
        }
        
        if ($tSvnCommand == "") {
            
            $tErrors[] = _("SVN command is missing!");
            $error = 1;
        }
        
        if ($tSvnadminCommand == "") {
            
            $tErrors[] = _("Svnadmin command missing!");
            $error = 1;
        }
        
        if ($tGrepCommand == "") {
            
            $tErrors[] = _("Grep command is missinbg!");
            $error = 1;
        }
        
        if ($tPageSize == "") {
            
            $tErrors[] = _("Page size is missing!");
            $error = 1;
        }
        
        if (! is_numeric($tPageSize)) {
            
            $tErrors[] = _("Page size is not numeric!");
            $error = 1;
        }
        
        if (! is_numeric($tMinAdminPwSize)) {
            
            $tErrors[] = _("Minimal administrator password length is not numeric!");
            $error = 1;
        }
        
        if (! is_numeric($tMinUserPwSize)) {
            
            $tErrors[] = _("Minimal user password length is not numeric!");
            $error = 1;
        }
        
        if (! is_numeric($tPasswordExpire)) {
            
            $tErrors[] = _("Password expire days not numeric!");
            $error = 1;
        }
        
        if (! is_numeric($tPasswordExpireWarn)) {
            
            $tErrors[] = _("Password expire warn days not numeric!");
            $error = 1;
        }
        
        if ($tPasswordExpireWarn >= $tPasswordExpire) {
            
            $tErrors[] = _("Password expire days must not be smaller or equal than password expire warn days!");
            $error = 1;
        }
        
        //
        // install process
        //
        if ($error == 0) {
            
            $ret = doInstall();
            $tPage = $ret['page'];
            $tErrors = $ret['errors'];
        }
        else {
            
            $tPage = 7;
        }
    }
    elseif (isset($_POST['fSubmit_testdb']) || isset($_POST['fSubmit_testdb_x'])) {
        
        $ret = doDbtest();
        $tPage = $ret['page'];
        $tErrors = $ret['errors'];
    }
    elseif (isset($_POST['fSubmit_testldap']) || isset($_POST['fSubmit_testldap_x'])) {
        
        $error = 0;
        
        if ($_SESSION[SVN_INST]['useLdap'] == "YES") {
            
            $ret = doLdapTest();
            $tPage = $ret['page'];
            $tErrors = $ret['errors'];
        }
        else {
            
            $tErrors[] = _("Testing LDAP connection doesn't make sense if you do not use LDAP!");
            $tPage = 7;
        }
    }
    
    $tDatabaseHost = isset($_SESSION[SVN_INST][DATABASEHOST]) ? $_SESSION[SVN_INST][DATABASEHOST] : "";
    $tDatabaseUser = isset($_SESSION[SVN_INST][DATABASEUSER]) ? $_SESSION[SVN_INST][DATABASEUSER] : "";
    $tDatabasePassword = isset($_SESSION[SVN_INST][DATABASEPASSWORD]) ? $_SESSION[SVN_INST][DATABASEPASSWORD] : "";
    $tDatabaseName = isset($_SESSION[SVN_INST][DATABASENAME]) ? $_SESSION[SVN_INST][DATABASENAME] : "";
    $tDatabaseSchema = isset($_SESSION[SVN_INST][DATABASESCHEMA]) ? $_SESSION[SVN_INST][DATABASESCHEMA] : "";
    $tDatabaseTablespace = isset($_SESSION[SVN_INST][DATABASETABLESPACE]) ? $_SESSION[SVN_INST][DATABASETABLESPACE] : "";
    $tDatabaseCharset = isset($_SESSION[SVN_INST][DATABASECHARSET]) ? $_SESSION[SVN_INST][DATABASECHARSET] : $tDatabaseCharsetDefault;
    $tDatabaseCollation = isset($_SESSION[SVN_INST][DATABASECOLLATION]) ? $_SESSION[SVN_INST][DATABASECOLLATION] : $tDatabaseCollationDefault;
    $tLdapAttrUid = isset($_SESSION[SVN_INST]['ldapAttrUid']) ? $_SESSION[SVN_INST]['ldapAttrUid'] : "uid";
    $tLdapAttrName = isset($_SESSION[SVN_INST]['ldapAttrName']) ? $_SESSION[SVN_INST]['ldapAttrName'] : "sn";
    $tLdapAttrGivenname = isset($_SESSION[SVN_INST]['ldapAttrGivenname']) ? $_SESSION[SVN_INST]['ldapAttrGivenname'] : "givenName";
    $tLdapAttrMail = isset($_SESSION[SVN_INST]['ldapAttrMail']) ? $_SESSION[SVN_INST]['ldapAttrMail'] : "mail";
    $tLdapAttrPassword = isset($_SESSION[SVN_INST]['ldapAttrPassword']) ? $_SESSION[SVN_INST]['ldapAttrPassword'] : "userPassword";
    $tLdapAttrUserSort = isset($_SESSION[SVN_INST]['ldapAttrUserSort']) ? $_SESSION[SVN_INST]['ldapAttrUserSort'] : "sn";
    $tLdapUserSort = isset($_SESSION[SVN_INST]['ldapUserSort']) ? $_SESSION[SVN_INST]['ldapUserSort'] : "ASC";
    $tLdapBindUseLoginData = isset($_SESSION[SVN_INST]['ldapBindUseLoginData']) ? $_SESSION[SVN_INST]['ldapBindUseLoginData'] : 0;
    $tLdapBindDnSuffix = isset($_SESSION[SVN_INST]['ldapBindDnSuffix']) ? $_SESSION[SVN_INST]['ldapBindDnSuffix'] : "";
    $tWebisteUrl = isset($_SESSION[SVN_INST]['webisteUrl']) ? $_SESSION[SVN_INST]['websiteUrl'] : "";
    $tWebsiteCharset = isset($_SESSION[SVN_INST]['websiteCharset']) ? $_SESSION[SVN_INST]['websiteCharset'] : "iso8859-15";
    $tLpwMailSender = isset($_SESSION[SVN_INST]['lpwMailSender']) ? $_SESSION[SVN_INST]['lpwMailSender'] : "";
    $tLpwLinkValid = isset($_SESSION[SVN_INST]['lpwLinkValid']) ? $_SESSION[SVN_INST]['lpwLinkValid'] : "";
    $tUsername = isset($_SESSION[SVN_INST]['username']) ? $_SESSION[SVN_INST]['username'] : "";
    $tPassword = isset($_SESSION[SVN_INST][PASSWORD]) ? $_SESSION[SVN_INST][PASSWORD] : "";
    $tPassword2 = isset($_SESSION[SVN_INST][PASSWORD2]) ? $_SESSION[SVN_INST][PASSWORD2] : "";
    $tGivenname = isset($_SESSION[SVN_INST]['givenname']) ? $_SESSION[SVN_INST]['givenname'] : "";
    $tName = isset($_SESSION[SVN_INST]['name']) ? $_SESSION[SVN_INST]['name'] : "";
    $tAdminEmail = isset($_SESSION[SVN_INST]['adminEmail']) ? $_SESSION[SVN_INST]['adminEmail'] : "";
    $tUseSvnAccessFile = isset($_SESSION[SVN_INST]['useSvnAccessFile']) ? $_SESSION[SVN_INST]['useSvnAccessFile'] : "";
    $tSvnAccessFile = isset($_SESSION[SVN_INST]['svnAccessFile']) ? $_SESSION[SVN_INST]['svnAccessFile'] : "";
    $tAccessControlLevel = isset($_SESSION[SVN_INST]['accessControlLevel']) ? $_SESSION[SVN_INST]['accessControlLevel'] : "dirs";
    $tUseAuthUserFile = isset($_SESSION[SVN_INST]['useAuthUserFile']) ? $_SESSION[SVN_INST]['useAuthUserFile'] : "";
    $tAuthUserFile = isset($_SESSION[SVN_INST]['authUserFile']) ? $_SESSION[SVN_INST]['authUserFile'] : "";
    $tSvnCommand = isset($_SESSION[SVN_INST]['svnCommand']) ? $_SESSION[SVN_INST]['svnCommand'] : "";
    $tSvnadminCommand = isset($_SESSION[SVN_INST]['svnadminCommand']) ? $_SESSION[SVN_INST]['svnadminCommand'] : "";
    $tGrepCommand = isset($_SESSION[SVN_INST]['grepCommand']) ? $_SESSION[SVN_INST]['grepCommand'] : "";
    $tViewvcConfig = isset($_SESSION[SVN_INST]['viewvcConfig']) ? $_SESSION[SVN_INST]['viewvcConfig'] : "";
    $tViewvcConfigDir = isset($_SESSION[SVN_INST]['viewvcConfigDir']) ? $_SESSION[SVN_INST]['viewvcConfigDir'] : "";
    $tViewvcAlias = isset($_SESSION[SVN_INST]['viewvcAlias']) ? $_SESSION[SVN_INST]['viewvcAlias'] : "/viewvc";
    $tViewvcApacheReload = isset($_SESSION[SVN_INST]['viewvcApacheReload']) ? $_SESSION[SVN_INST]['viewvcApacheReload'] : "";
    $tViewvcRealm = isset($_SESSION[SVN_INST]['viewvcRealm']) ? $_SESSION[SVN_INST]['viewvcRealm'] : "ViewVC Access Control";
    $tPerRepoFiles = isset($_SESSION[SVN_INST]['perRepoFiles']) ? $_SESSION[SVN_INST]['perRepoFiles'] : "";
    $tPathSortOrder = isset($_SESSION[SVN_INST]['psthSortOrder']) ? $_SESSION[SVN_INST]['pathSortOrder'] : "ASC";
    $tAnonAccess = isset($_SESSION[SVN_INST]['anonAccess']) ? $_SESSION[SVN_INST]['anonAccess'] : 0;
    $tSvnCommand = getSvnCommand($tSvnCommand);
    $tSvnadminCommand = getSvnadminCommand($tSvnadminCommand);
    $tGrepCommand = getGrepCommand($tGrepCommand);
    $tViewvcApacheReload = getApacheReloadCommand($tViewvcApacheReload);
    $tLogging = isset($_SESSION[SVN_INST]['logging']) ? $_SESSION[SVN_INST]['logging'] : "YES";
    $tJavaScript = isset($_SESSION[SVN_INST]['javaScript']) ? $_SESSION[SVN_INST]['javaScript'] : "YES";
    $tPageSize = isset($_SESSION[SVN_INST]['pageSize']) ? $_SESSION[SVN_INST]['pageSize'] : "30";
    $tMinAdminPwSize = isset($_SESSION[SVN_INST]['minAdminPwSize']) ? $_SESSION[SVN_INST]['minAdminPwSize'] : "14";
    $tMinUserPwSize = isset($_SESSION[SVN_INST]['minUserPwSize']) ? $_SESSION[SVN_INST]['minUserPwSize'] : "8";
    $tPasswordExpire = isset($_SESSION[SVN_INST]['passwordExpire']) ? $_SESSION[SVN_INST]['passwordExpire'] : 60;
    $tPasswordExpireWarn = isset($_SESSION[SVN_INST]['passwordExpireWarn']) ? $_SESSION[SVN_INST]['passwordExpireWarn'] : 50;
    $tExpirePassword = isset($_SESSION[SVN_INST]['expirePassword']) ? $_SESSION[SVN_INST]['expirePassword'] : 1;
    $tPwEnc = isset($_SESSION[SVN_INST]['pwEnc']) ? $_SESSION[SVN_INST]['pwEnc'] : "md5";
    $tUserDefaultAccess = isset($_SESSION[SVN_INST]['userDefaultAccess']) ? $_SESSION[SVN_INST]['userDefaultAccess'] : "read";
    $tCustom1 = isset($_SESSION[SVN_INST]['custom1']) ? $_SESSION[SVN_INST]['custom1'] : "";
    $tCustom2 = isset($_SESSION[SVN_INST]['custom2']) ? $_SESSION[SVN_INST]['custom2'] : "";
    $tCustom3 = isset($_SESSION[SVN_INST]['custom3']) ? $_SESSION[SVN_INST]['custom3'] : "";
    
    //
    // initialize fields
    //
    
    list($tDatabaseCharsetDefault, $tDatabaseCollationDefault ) = setDatabaseCharset($tDatabase);
    list($tCreateDatabaseTablesYes, $tCreateDatabaseTablesNo ) = setCreateDatabaseTables($tCreateDatabaseTables);
    list($tDropDatabaseTablesYes, $tDropDatabaseTablesNo ) = setDropDatabaseTables($tDropDatabaseTables);
    list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues($tDatabase);
    list($tSessionInDatabaseYes, $tSessionInDatabaseNo ) = setSessionIndatabase($tSessionInDatabase);
    list($tUseLdapYes, $tUseLdapNo ) = setUseLdap($tUseLdap);
    list($tLdap2, $tLdap3 ) = setLdapprotocol($tLdapProtocol);
    list($tUseAuthUserFileYes, $tUseAuthUserFileNo ) = setUseAuthUserFile($tUseAuthUserFile);
    list($tUseSvnAccessFileYes, $tUseSvnAccessFileNo ) = setUseSvnAccessFile($tUseSvnAccessFile);
    list($tAccessControlLevelDirs, $tAccessControlLevelFiles ) = setAccessControlLevel($tAccessControlLevel);
    list($tPerRepoFilesYes, $tPerRepoFilesNo ) = setPerRepoFiles($tPerRepoFiles);
    list($tPathSortOrderAsc, $tPathSortOrderDesc ) = setPathSortOrder($tPathSortOrder);
    list($tLdapUserSortAsc, $tLdapUserSortDesc ) = setLdapUserSort($tLdapUserSort);
    list($tLdapBindUseLoginDataYes, $tLdapBindUseLoginDataNo ) = setLdapBindUseLoginData($tLdapBindUseLoginData);
    list($tAnonAccessYes, $tAnonAccessNo ) = setAnonAccess($tAnonAccess);
    list($tViewvcConfigYes, $tViewvcConfigNo ) = setViewvcConfig($tViewvcConfig);
    list($tJavaScriptYes, $tJavaScriptNo ) = setJavaScript($tJavaScript);
    list($tLoggingYes, $tLoggingNo ) = setLogging($tLogging);
    list($tExpirePasswordYes, $tExpirePasswordNo ) = setPasswordExpires($tExpirePassword);
    list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $CONF['pwcrypt'] ) = setEncryption($tPwEnc);
    list($tUserDefaultAccessRead, $tUserDefaultAccessWrite ) = setUserDefaultAccess($tUserDefaultAccess);
    
    include ("../templates/install.tpl");
}

?>
