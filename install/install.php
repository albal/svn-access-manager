<?php

/*
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*

File:          install.php
Template File: install.tpl

*/

require ("../include/variables.inc.php");
require ("../config/config.inc.php");
require ("../include/db-functions.inc.php");
require ("../include/functions.inc.php");


function dropDatabaseTables( $dbh ) {
	
	$error									= 0;
	$tMessage								= "";
	
	$query									= "DROP TABLE IF EXISTS `log`";
	$result									= db_query_install( $query, $dbh );
	$result									= db_query_install( $query, $dbh );
	if( mysql_errno() != 0 ) {
		
		$error								= 1;
		$tMessage							= sprintf( _("Cannot drop table %s"), "log" );
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `preferences`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "preferences" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `rights`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "rights" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `sessions`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "sessions" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svn_access_rights`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_access_rights" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svn_projects_mailinglists`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_project_mailinglists" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svn_projects_responsible`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_projects_responsible" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svn_users_groups`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_users_groups" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svngroups`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svngroups" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svnmailinglists`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnmailinglists" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svnprojects`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnprojects" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svnrepos`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnrepos" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `svnusers`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnusers" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "DROP TABLE IF EXISTS `users_rights`";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "users_rights" );
		}
	
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
}


function createDatabaseTables( $dbh ) {
	
	$error									= 0;
	$tMessage								= "";
	
	$query									= "CREATE TABLE IF NOT EXISTS `log` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`timestamp` datetime NOT NULL,
  													`username` varchar(255) NOT NULL,
  													`ipaddress` varchar(15) NOT NULL,
  													`logmessage` longtext NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_timestamp` (`timestamp`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of log messages';";
	$result									= db_query_install( $query, $dbh );
	if( mysql_errno() != 0 ) {
		
		$error								= 1;
		$tMessage							= sprintf( _("Cannot create table %s"), "log" );
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `preferences` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`user_id` int(10) NOT NULL,
  													`page_size` int(4) NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_userid` (`user_id`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of user preferences';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprinf( _("Cannot create table %s"), "preferences" );
		}
	
	}

	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `rights` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`right_name` varchar(255) NOT NULL,
  													`description_en` varchar(255) NOT NULL,
  													`description_de` varchar(255) NOT NULL,
  													`allowed_action` enum('none','read','edit','delete') NOT NULL default 'none',
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of rights to grant to users';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprinf( _("Cannot create table %s"), "rights" );
		}
	
	}

	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `sessions` (
  													`session` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  													`session_expires` int(10) unsigned NOT NULL default '0',
  													`session_data` text collate utf8_unicode_ci,
  													PRIMARY KEY  (`session`),
  													KEY `idx_expires` (`session_expires`)
												) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprinf( _("Cannot create table %s"), "sessions" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_access_rights` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`project_id` int(10) NOT NULL,
  													`user_id` int(10) NOT NULL,
  													`group_id` int(10) NOT NULL,
  													`path` longtext NOT NULL,
  													`valid_from` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  													`valid_until` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  													`access_right` set('none','read','write') NOT NULL default 'none',
  													`recursive` enum('yes','no') NOT NULL default 'yes',
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_projectid` (`project_id`),
  													KEY `idx_userid` (`user_id`),
  													KEY `idx_groupid` (`group_id`),
  													KEY `idx_path` (`path`(512)),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of user or group access rights';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_access_rights" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_projects_mailinglists` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`project_id` int(10) unsigned NOT NULL,
  													`mailinglisten_id` int(10) unsigned NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `moduleid` (`project_id`,`mailinglisten_id`),
  													KEY `mailinglistenid` (`mailinglisten_id`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of modules and mailinglist relations';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_projects_mailinglists" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_projects_responsible` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`project_id` int(10) NOT NULL,
  													`user_id` int(10) NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_projectid` (`project_id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of project responsible users';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_projects_responsible" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_users_groups` (
  													`id` int(10) NOT NULL auto_increment,
  													`user_id` int(10) unsigned NOT NULL,
  													`group_id` int(10) unsigned NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_groupid` (`group_id`),
  													KEY `idx_userid` (`user_id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of user group relations';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svn_users_groups" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svngroups` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`groupname` varchar(255) collate latin1_german1_ci NOT NULL,
  													`description` varchar(255) collate latin1_german1_ci NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `groupname` (`groupname`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn user groups';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svngroups" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnmailinglists` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`mailinglist` varchar(255) collate latin1_german1_ci NOT NULL,
  													`emailaddress` varchar(255) collate latin1_german1_ci NOT NULL,
  													`description` mediumtext collate latin1_german1_ci NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													PRIMARY KEY  (`id`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of available svn mailing lists';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnmailinglists" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnprojects` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`repo_id` int(10) unsigned NOT NULL,
  													`svnmodule` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modulepath` varchar(255) collate latin1_german1_ci NOT NULL,
  													`description` varchar(255) collate latin1_german1_ci NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,	
  													PRIMARY KEY  (`id`),
  													KEY `idx_repoid` (`repo_id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn modules';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= spritf( _("Cannot create table %s"), "svnprojects" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnrepos` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`reponame` varchar(255) collate latin1_german1_ci NOT NULL,
  													`repopath` varchar(255) collate latin1_german1_ci NOT NULL,
  													`repouser` varchar(255) collate latin1_german1_ci NOT NULL,
  													`repopassword` varchar(255) collate latin1_german1_ci NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn repositories';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnrepos" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnusers` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`userid` varchar(255) collate latin1_german1_ci NOT NULL,
  													`name` varchar(255) collate latin1_german1_ci NOT NULL,
  													`givenname` varchar(255) collate latin1_german1_ci NOT NULL,
  													`password` varchar(255) collate latin1_german1_ci NOT NULL default '',
  													`passwordexpires` tinyint(1) NOT NULL default '1',
  													`locked` tinyint(1) NOT NULL default '0',
  													`emailaddress` varchar(255) collate latin1_german1_ci NOT NULL default '',
  													`admin` char(1) collate latin1_german1_ci NOT NULL default 'n',
  													`mode` varchar(10) collate latin1_german1_ci NOT NULL,
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  													`password_modified` datetime NOT NULL,
  													PRIMARY KEY  (`id`),
  													UNIQUE KEY `idx_userid` (`userid`),
  													KEY `idx_mode` (`locked`),
  													KEY `idx_passwordexpires` (`passwordexpires`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of all known users';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table %s"), "svnusers" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `users_rights` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`user_id` int(10) NOT NULL,
  													`right_id` int(10) NOT NULL,
  													`allowed` enum('none','read','edit','delete') NOT NULL default 'none',
  													`created` datetime NOT NULL,
  													`created_user` varchar(255) NOT NULL,
  													`modified` datetime NOT NULL,
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` datetime NOT NULL,
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_user_id` (`user_id`),
  													KEY `idx_right_id` (`right_id`)
												) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of granted user rights';";
		$result								= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
		
			$error							= 1;
			$tMessage						= sprintf( _("Cannot create table "), "users_rights" );
		}
	
	}
	
	if( $error == 0 ) {
		
		$query								= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('User admin', 'Adminster users', 'Benutzer verwalten', 'delete', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
		$result								= db_query_install( $query, $dbh );
		if( $result['rows'] != 1 ) {
			$error							= 1;
			$tMessage						= _("Error inserting data into rights table" );
		}
		
		if( $error == 0 ) {
		
			$query							= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('Group admin', 'Adminster groups', 'Gruppen verwalten', 'delete', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
			$result							= db_query_install( $query, $dbh );
			if( $result['rows'] != 1 ) {
				$error						= 1;
				$tMessage					= _("Error inserting data into rights table" );
			}
		
		}
		
		if( $error == 0 ) {
		
			$query							= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('Project admin', 'Adminster projects', 'Projecte verwalten', 'delete', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
			$result							= db_query_install( $query, $dbh );
			if( $result['rows'] != 1 ) {
				$error						= 1;
				$tMessage					= _("Error inserting data into rights table" );
			}
			
		}
		
		if( $error == 0 ) {
		
			$query							= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('Repository admin', 'Adminster repositories', 'Repositories verwalten', 'delete', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
			$result							= db_query_install( $query, $dbh );
			if( $result['rows'] != 1 ) {
				$error						= 1;
				$tMessage					= _("Error inserting data into rights table" );
			}
			
		}
		
		if( $error == 0 ) {			
		
			$query							= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('Access rights admin', 'Adminster repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
			$result							= db_query_install( $query, $dbh );
			if( $result['rows'] != 1 ) {
				$error						= 1;
				$tMessage					= _("Error inserting data into rights table" );
			}
			
		}	
		if( $error == 0 ) {
		
			$query							= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
			$result							= db_query_install( $query, $dbh );
			if( $result['rows'] != 1 ) {
				$error						= 1;
				$tMessage					= _("Error inserting data into rights table" );
			}
			
		}
			
		if( $error == 0 ) {
		
			$query							= "INSERT INTO `rights` (`right_name`, `description_en`, `description_de`, `allowed_action`, `created`, `created_user`, `modified`, `modified_user`, `deleted`, `deleted_user`) " .
											  "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', now(), 'install', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '')";
			$result							= db_query_install( $query, $dbh );
			if( $result['rows'] != 1 ) {
				$error						= 1;
				$tMessage					= _("Error inserting data into rights table" );
			}
			
		}
		
	}
	
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
	
}

function createAdmin( $userid, $password, $givenname, $name, $emailaddress, $dbh ) {
	
	$error									= 0;
	$tMessage								= "";
	$pwcrypt								= mysql_real_escape_string( pacrypt( $password ) );
	$query									= "INSERT INTO svnusers (userid, name, givenname, password, emailaddress, mode, admin, created, created_user, password_modified) " .
											  "VALUES ('$userid', '$name', '$givenname', '$pwcrypt', '$emailaddress', 'write', 'y', now(), 'install', now())";
	$result									= db_query_install( $query, $dbh );
	if( $result['rows'] != 1 ) {
		
		$error								= 1;
		$tMessage							= _("Error creating admin user");
		
	} else {
		
		$uid								= mysql_insert_id( $dbh );
		
	}
	
	$query									= "SELECT id, allowed_action " .
											  "  FROM rights " .
											  " WHERE deleted = '0000-00-00 00:00:00'";
	$result									= db_query_install( $query, $dbh );
	
	while( ($error == 0) and ($row = db_array( $result['result'] )) ) {
		
		$allowed							= $row['allowed_action'];
		$id									= $row['id'];
		
		$query								= "INSERT INTO users_rights (user_id, right_id, allowed, created, created_user) " .
											  "VALUES ($uid, $id, '$allowed', now(), 'install')";
		$resultinsert						= db_query_install( $query, $dbh );
		
		if( $resultinsert['rows'] != 1 ) {
			
			$error							= 1;
			$tMessage						= _("Error inserting user access right for admin" );
			
		}			
		
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
	
}

initialize_i18n();

$dbh 		= db_connect ();
 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$tCreateDatabaseTablesYes				= "checked";
   	$tCreateDatabaseTablesNo				= "";
   	$tDropDatabaseTablesYes					= "checked";
   	$tDropDatabaseTablesNo					= "";
   	$tUseSvnAccessFileYes					= "";
	$tUseSvnAccessFileNo					= "checked";
	$tUseAuthUserFileYes					= "";
	$tUseAuthUserFileNo						= "checked";
	$tLoggingYes							= "checked";
	$tLoggingNo								= "";
   	$tGrepCommand							= "/bin/grep";
   	$tSvnCommand							= "/usr/bin/svn";
   	$tPageSize								= "30";
   	$tJavaScriptYes							= "checked";
   	$tJavaScriptNo							= "";
   
   	include ("../templates/install.tpl");
   
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$tResult								= array();
	
	$tCreateDatabaseTables					= isset( $_POST['fCreateDatabaseTables'] ) 	? escape_string( $_POST['fCreateDatabaseTables'] )	: "";
	$tDropDatabaseTables					= isset( $_POST['fDropDatabaseTables'] ) 	? escape_string( $_POST['fDropDatabaseTables'] )	: "";
	$tDatabaseHost							= isset( $_POST['fDatabaseHost'] )			? escape_string( $_POST['fDatabaseHost'] )			: "";
	$tDatabaseUser							= isset( $_POST['fDatabaseUser'] )			? escape_string( $_POST['fDatabaseUser'] )			: "";
	$tDatabasePassword						= isset( $_POST['fDatabasePassword'] )		? escape_string( $_POST['fDatabasePassword'] )		: "";
	$tDatabaseName							= isset( $_POST['fDatabaseName'] )			? escape_string( $_POST['fDatabaseName'] )			: "";
	$tUsername								= isset( $_POST['fUsername'] ) 				? escape_string( $_POST['fUsername'] )				: "";
	$tPassword								= isset( $_POST['fPassword'] )				? escape_string( $_POST['fPassword'] )				: "";
	$tPassword2								= isset( $_POST['fPassword2'] )				? escape_string( $_POST['fPassword2'] )				: "";
	$tGivenname								= isset( $_POST['fGivenname'] ) 			? escape_string( $_POST['fGivenname'] )				: "";
	$tName									= isset( $_POST['fName'] )					? escape_String( $_POST['fName'] )					: "";
	$tUseSvnAccessFile						= isset( $_POST['fUseSvnAccessFile'] )		? escape_string( $_POST['fUseSvnAccessFile'] )		: "";
	$tSvnAccessFile							= isset( $_POST['fSvnAccessFile'] )			? escape_string( $_POST['fSvnAccessFile'] )			: "";
	$tUseAuthUserFile						= isset( $_POST['fUseAuthUserFile'] )		? escape_String( $_POST['fUseAuthUserFile'] )		: "";
	$tAuthUserFile							= isset( $_POST['fAuthUserFile'] )			? escape_string( $_POST['fAuthUserFile'] )			: "";
	$tSvnCommand							= isset( $_POST['fSvnCommand'] )			? escape_string( $_POST['fSvnCommand'] )			: "";
	$tGrepCommand							= isset( $_POST['fGrepCommand'] )			? escape_string( $_POST['fGrepCommand'] )			: "";
	$tLogging								= isset( $_POST['fLogging'] )				? escape_string( $_POST['fLogging'] )				: "";
	$tJavaScript							= isset( $_POST['fJavaScript'] )			? escape_string( $_POST['fJavaScript'] )			: "";
	$tPageSize								= isset( $_POST['fPageSize'] )				? escape_string( $_POST['fPageSize'] )				: 30;
	$tAdminEmail							= isset( $_POST['fAdminEmail'] )			? escape_string( $_POST['fAdminEmail'] )			: "";
	
	$tMessage								= "";
	$error									= 0;
	
	if ( file_exists ( realpath ( "./config/config.inc.php" ) ) ) {
		
		$configfile							= realpath ( "./config/config.inc.php" );
		
	} elseif( file_exists ( realpath ( "../config/config.inc.php" ) ) ) {
		
		$configfile							= realpath ( "../config/config.inc.php" );
		
	} else {
		
		$configfile							= realpath ( "./config/config.inc.php" );
		
	}
	
	$configpath								= dirname( $configfile );
	$confignew								= $configpath."/config.inc.php.new";
	$configtmpl								= $configpath."/config.inc.php.tpl";
	
	if( $tJavaScript == "YES" ) {
		$tJavaScriptYes						= "checked";
		$tJavaScriptNo						= "";
	} else {
		$tJavaScriptYes						= "";
		$tJavaScriptNo						= "checked";
	}
	
	if( $tLogging == "YES" ) {
		$tLoggingYes						= "checked";
		$tLoggingNo							= "";
	} else {
		$tLoggingYes						= "";
		$tLoggingNo							= "checked";
	}
	
	if( $tUseAuthUserFile == "YES" ) {
		$tUseAuthUserFileYes				= "checked";
		$tUseAuthUSerFileNo					= "";	
	} else {
		$tUseAuthUserFileYes				= "";
		$tUseAuthUserFileNo					= "checked";
	}
	
	if( $tUseSvnAccessFile == "YES" ) {
		$tUseSvnAccessFileYes				= "checked";
		$tUseSvnAccessFileNo				= "";
	} else {
		$tUseSvnAccessFileYes				= "";
		$tUseSvnAccessFileNo				= "checked";
	}
	
	if( $tCreateDatabaseTables == "YES" ) {
		$tCreateDatabaseTablesYes			= "checked";
		$tCreateDatabaseTablesNo			= "";
	} else {
		$tCreateDatabaseTablesYes			= "";
		$tCreateDatabaseTablesNo			= "checked";
	}
	
	if( $tDropDatabaseTables == "YES" ) {
		$tDropDatabaseTablesYes				= "checked";
		$tDropDatabaseTablesNo				= "";
	} else {
		$tDropDatabaseTablesYes				= "";
		$tDropDatabaseTablesNo				= "checked";
	}
	
	if( $error == 0 ) {
		
		
			
		if( $fh_in = @fopen( $configtmpl, "r" ) ) {
			
			$content 					= fread ( $fh_in, filesize ($configtmpl));
			@fclose( $fh_in );
			
			$content 					= str_replace( '###DBHOST###', $tDatabaseHost, $content );
			$content					= str_replace( '###DBUSER###', $tDatabaseUser, $content );
			$content					= str_replace( '###DBPASS###', $tDatabasePassword, $content );
			$content					= str_replace( '###DBNAME###', $tDatabaseName, $content );
			$content					= str_replace( '###USELOGGING###', $tLogging, $content );
			$content					= str_replace( '###PAGESIZE###', $tPageSize, $content );
			$content					= str_replace( '###SVNCMD###', $tSvnCommand, $content );
			$content					= str_replace( '###GREPCMD###', $tGrepCommand, $content );
			$content					= str_replace( '###USEJS###', $tJavaScript, $content );
			$content					= str_replace( '###SVNACCESSFILE###', $tSvnAccessFile, $content );
			$content					= str_replace( '###SVNAUTHFILE###', $tAuthUserFile, $content );
			$content					= str_replace( '###CREATEACCESSFILE###', $tUseSvnAccessFile, $content );
			$content					= str_replace( '###CREATEAUTHFILE###', $tUseAuthUserFile, $content );
			$content					= str_replace( '###ADMINEMAIL###', $tAdminEmail, $content );
			
		} else {
			
			$tMessage 					= _("can't open config template for reading!");
			$error						= 1;
			
		}
			
	}
	
	if( $error == 0 ) {
		
		if( $fh_out = @fopen($confignew, "w" ) ) {
			
			if( ! @fwrite( $fh_out, $content ) ) {
				
				$tMessage				= _("Can't write new config.inc.php file!" );
				$error					= 1;
				
			} 
			
		} else {
			
			$tMessage 					= _("can't open config.inc.php for writing. Please make sure the config directory is writeable for the webserver user!" );
			$error						= 1;
		}
		
	}
	
	if( $error == 0 ) {
		
		if( @copy( $confignew, $configfile) ) {
			
			if( ! @unlink( $confignew ) ) {
				
				$error					= 1;
				$tMessage				= _("Error deleting temporary config file");
				
			} else {
				
				$tResult[]				= _("config.inc.php successfully created");
				
			}
			
		} else {
			
			$error						= 1;
			$tMessage					= _("Error copying temporary config file!");
			
		}
	}
	
	if( $error == 0 ) {
		
		if( $tCreateDatabaseTables == "YES" ) {
			
			$dbh						= db_connect_install($tDatabaseHost, $tDatabaseUser, $tDatabasePassword, $tDatabaseName);
			
			if( $tDropDatabaseTables == "YES" ) {
		
				$ret					= dropDatabaseTables( $dbh );
				if( $ret['error'] != 0 ) {
				
					$tMessage			= $ret['errormsg'];
					$error				= 1;
				
				} else {
					
					$tResult[]			= _("Database tables successfully dropped");
				}
				
			} else {
					
				$tResult[]				= _("No database tables dropped");
					 
			}
			
			if( $error == 0 ) {
				
				$ret 					= createDatabaseTables( $dbh );
				if( $ret['error'] != 0 ) {
				
					$tMessage			= $ret['errormsg'];
				
				} else {
					
					$tResult[]			= _("Database tables successfully created");			
					
				}
			
			}
			
			if( $error == 0 ) {
				
				$ret					= createAdmin( $tUsername, $tPassword, $tGivenname, $tName, $tAdminEmail, $dbh );
				if( $ret['error'] != 0 ) {
				
					$tMessage			= $ret['errormsg'];
				
				} else {
					
					$tResult[]			= _("Admin account successfully created");			
					
				}
			}
			
			db_disconnect( $dbh );
			
		} else {
			
			$tResult[]					= _("No database tables created");
		}
		
	}
	
	if( $error == 0 ) {
		
		include ("../templates/installresult.tpl");
		
	} else {
	
		include ("../templates/install.tpl");
		
	}	
}

?>