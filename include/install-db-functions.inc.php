<?php

//
//
//
function dropMySQLDatabaseTables($dbh) {

    global $DBTABLES;
    
    $error = 0;
    $tMessage = "";
    
    foreach( $DBTABLES as $dbtable) {
        
        if ($error == 0) {
            
            $query = "DROP TABLE IF EXISTS `" . $dbtable . "`";
            db_query_install($query, $dbh);
        }
    }
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

//
//
//
function createHelpTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `help` (`id` int(10) unsigned NOT NULL auto_increment, `topic` varchar(255) NOT NULL, `headline_en` varchar(255) NOT NULL, `headline_de` varchar(255) NOT NULL, `helptext_de` longtext NOT NULL, `helptext_en` longtext NOT NULL, PRIMARY KEY  (`id`), KEY `idx_topic` (`topic`), FULLTEXT KEY `helptext_de` (`helptext_de`), FULLTEXT KEY `helptext_en` (`helptext_en`)) ENGINE=MyISAM  DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of help texts';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createLogTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `log` (`id` int(10) unsigned NOT NULL auto_increment, `logtimestamp` varchar(14) NOT NULL DEFAULT '00000000000000', `username` varchar(255) NOT NULL, `ipaddress` varchar(15) NOT NULL, `logmessage` longtext NOT NULL, PRIMARY KEY  (`id`), KEY `idx_timestamp` (`logtimestamp`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of log messages';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createPreferencesTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `preferences` (`id` int(10) unsigned NOT NULL auto_increment, `user_id` int(10) NOT NULL, `page_size` int(4) NOT NULL, `user_sort_fields` varchar(255) NOT NULL, `user_sort_order` varchar(255) NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL, `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL, PRIMARY KEY  (`id`), KEY `idx_userid` (`user_id`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of user preferences';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createRightsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `rights` (`id` int(10) unsigned NOT NULL auto_increment, `right_name` varchar(255) NOT NULL, `description_en` varchar(255) NOT NULL, `description_de` varchar(255) NOT NULL, `allowed_action` enum('none','read','edit','delete') NOT NULL default 'none', `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of rights to grant to users';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSessionsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `sessions` (`session_id` varchar(255) NOT NULL, `session_expires` int(10) unsigned NOT NULL default '0', `session_data` text, PRIMARY KEY  (`session_id`), KEY `idx_expires` (`session_expires`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation;";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnAccessRightsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svn_access_rights` (`id` int(10) unsigned NOT NULL auto_increment, `project_id` int(10) NOT NULL, `user_id` int(10) NOT NULL DEFAULT '0', `group_id` int(10) NOT NULL DEFAULT '0', `path` longtext NOT NULL, `valid_from` varchar(14) NOT NULL COMMENT 'JHJJMMTT', `valid_until` varchar(14) NOT NULL COMMENT 'JHJJMMTT', `access_right` enum('none','read','write') NOT NULL default 'none', `recursive` enum('yes','no') NOT NULL default 'yes', `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `idx_projectid` (`project_id`), KEY `idx_userid` (`user_id`), KEY `idx_groupid` (`group_id`), KEY `idx_path` (`path`(512)), KEY `idx_deleted` (`deleted`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of user or group access rights';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createWorkinfoTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `workinfo` (`id` int(10) unsigned NOT NULL auto_increment, `usertimestamp` timestamp NOT NULL default CURRENT_TIMESTAMP, `action` varchar(255) NOT NULL, `status` varchar(255) NOT NULL, `type` varchar(255) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='table of workinfo';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvngroupsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svngroups` (`id` int(10) unsigned NOT NULL auto_increment, `groupname` varchar(255) NOT NULL, `description` varchar(255) NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `groupname` (`groupname`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of svn user groups';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnusersTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svnusers` (`id` int(10) unsigned NOT NULL auto_increment, `userid` varchar(255) NOT NULL, `name` varchar(255) NOT NULL, `givenname` varchar(255) NOT NULL, `password` varchar(255) NOT NULL default '', `passwordexpires` tinyint(1) NOT NULL default '1', `locked` tinyint(1) NOT NULL default '0', `emailaddress` varchar(255) NOT NULL default '', `admin` char(1) NOT NULL default 'n', `user_mode` varchar(10) NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', `password_modified` varchar(14) NOT NULL DEFAULT '00000000000000', `superadmin` tinyint(1) NOT NULL default '0', `securityquestion` varchar(255) default '', `securityanswer` varchar(255) default '', `custom1` varchar(255) default '', `custom2` varchar(255) default '', `custom3` varchar(255) default '', PRIMARY KEY  (`id`), UNIQUE KEY `idx_userid` (`userid`,`deleted`), KEY `idx_mode` (`locked`), KEY `idx_passwordexpires` (`passwordexpires`), KEY `idx_deleted` (`deleted`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of all known users';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnGroupsResponsibleTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE `svn_groups_responsible` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(10) unsigned NOT NULL, `group_id` int(10) unsigned NOT NULL, `allowed` enum('none','read','edit','delete') NOT NULL DEFAULT 'none', `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY (`id`), KEY `idx_projectid_userid_groupid` (`user_id`,`group_id`), KEY `idx_deleted` (`deleted`)) ENGINE=InnoDB  DEFAULT CHARSET=$charset COLLATE=$collation;";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnProjectsMailinglistsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svn_projects_mailinglists` ( `id` int(10) unsigned NOT NULL auto_increment, `project_id` int(10) unsigned NOT NULL, `mailinglisten_id` int(10) unsigned NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `moduleid` (`project_id`,`mailinglisten_id`), KEY `mailinglistenid` (`mailinglisten_id`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of modules and mailinglist relations';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnProjectsresponsibleTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svn_projects_responsible` (`id` int(10) unsigned NOT NULL auto_increment, `project_id` int(10) NOT NULL, `user_id` int(10) NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `idx_projectid` (`project_id`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of project responsible users';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnUsersGroupsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svn_users_groups` ( `id` int(10) NOT NULL auto_increment, `user_id` int(10) unsigned NOT NULL, `group_id` int(10) unsigned NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `idx_groupid` (`group_id`), KEY `idx_userid` (`user_id`), KEY `idx_deleted` (`deleted`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of user group relations';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnMailinglistsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svnmailinglists` (`id` int(10) unsigned NOT NULL auto_increment, `mailinglist` varchar(255) NOT NULL, `emailaddress` varchar(255) NOT NULL, `description` mediumtext NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of available svn mailing lists';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnPasswordResetTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svnpasswordreset` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `unixtime` int(11) NOT NULL, `username` varchar(255) NOT NULL, `token` varchar(255) NOT NULL, `idstr` varchar(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=$charset COLLATE=$collation;";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnReposTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svnrepos` (`id` int(10) unsigned NOT NULL auto_increment, `reponame` varchar(255) NOT NULL, `repopath` varchar(255) NOT NULL, `repouser` varchar(255) NOT NULL, `repopassword` varchar(255) NOT NULL, `different_auth_files` tinyint(1) NOT NULL DEFAULT '0', `auth_user_file` varchar(255) NOT NULL, `svn_access_file` varchar(255) NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `idx_deleted` (`deleted`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of svn repositories';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnProjectsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `svnprojects` (`id` int(10) unsigned NOT NULL auto_increment, `repo_id` int(10) unsigned NOT NULL, `svnmodule` varchar(255) NOT NULL, `modulepath` varchar(255) NOT NULL, `description` varchar(255) NOT NULL, `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ',	 PRIMARY KEY  (`id`), KEY `idx_repoid` (`repo_id`), KEY `idx_deleted` (`deleted`) ) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of svn modules';";
    db_query_install($query, $dbh);
    
}

//
//
//
function createUserRightsTableMySQL($dbh, $charset, $collation) {

    $query = "CREATE TABLE IF NOT EXISTS `users_rights` (`id` int(10) unsigned NOT NULL auto_increment, `user_id` int(10) NOT NULL, `right_id` int(10) NOT NULL, `allowed` enum('none','read','add','edit','delete') NOT NULL default 'none', `created` varchar(14) NOT NULL DEFAULT '00000000000000', `created_user` varchar(255) NOT NULL DEFAULT ' ', `modified` varchar(14) NOT NULL DEFAULT '00000000000000', `modified_user` varchar(255) NOT NULL DEFAULT ' ', `deleted` varchar(14) NOT NULL DEFAULT '00000000000000', `deleted_user` varchar(255) NOT NULL DEFAULT ' ', PRIMARY KEY  (`id`), KEY `idx_user_id` (`user_id`), KEY `idx_right_id` (`right_id`)) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of granted user rights';";
    db_query_install($query, $dbh);
    
}

//
//
//
function loadMySQLDbData($dbh) {

    db_ta(BEGIN, $dbh);
    
    $error = 0;
    $tMessage = "";
    $dbnow = db_now();
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('User admin', 'Administer users', 'Benutzer verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Group admin', 'Administer groups', 'Gruppen verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Project admin', 'Administer projects', 'Projekte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Repository admin', 'Administer repositories', 'Repositories verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Access rights admin', 'Administer repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
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
?>