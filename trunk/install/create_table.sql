-- phpMyAdmin SQL Dump
-- version 2.9.1.1-Debian-7
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Oct 03, 2008 at 08:51 AM
-- Server version: 5.0.32
-- PHP Version: 5.2.0-8+etch11
-- 
-- Database: `svnadmin`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `help`
-- 

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `topic` varchar(255) NOT NULL,
  `headline_en` varchar(255) NOT NULL,
  `headline_de` varchar(255) NOT NULL,
  `helptext_de` longtext NOT NULL,
  `helptext_en` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_topic` (`topic`),
  FULLTEXT KEY `helptext_de` (`helptext_de`),
  FULLTEXT KEY `helptext_en` (`helptext_en`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table of help texts';

-- --------------------------------------------------------

-- 
-- Table structure for table `log`
-- 

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `logmessage` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of log messages';

-- --------------------------------------------------------

-- 
-- Table structure for table `preferences`
-- 

DROP TABLE IF EXISTS `preferences`;
CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `page_size` int(4) NOT NULL,
  `user_sort_fields` varchar(255) collate latin1_german1_ci NOT NULL,
  `user_sort_order` varchar(255) collate latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) collate latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) collate latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_userid` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of user preferences';

-- --------------------------------------------------------

-- 
-- Table structure for table `rights`
-- 

DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of rights to grant to users';

-- --------------------------------------------------------

-- 
-- Table structure for table `semaphores`
-- 

DROP TABLE IF EXISTS `semaphores`;
CREATE TABLE IF NOT EXISTS `semaphores` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `action` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table of semaphores';

-- --------------------------------------------------------

-- 
-- Table structure for table `sessions`
-- 

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `session_expires` int(10) unsigned NOT NULL default '0',
  `session_data` text collate utf8_unicode_ci,
  PRIMARY KEY  (`session`),
  KEY `idx_expires` (`session_expires`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `svn_access_rights`
-- 

DROP TABLE IF EXISTS `svn_access_rights`;
CREATE TABLE IF NOT EXISTS `svn_access_rights` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of user or group access rights';

-- --------------------------------------------------------

-- 
-- Table structure for table `svn_projects_mailinglists`
-- 

DROP TABLE IF EXISTS `svn_projects_mailinglists`;
CREATE TABLE IF NOT EXISTS `svn_projects_mailinglists` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of modules and mailinglist relations';

-- --------------------------------------------------------

-- 
-- Table structure for table `svn_projects_responsible`
-- 

DROP TABLE IF EXISTS `svn_projects_responsible`;
CREATE TABLE IF NOT EXISTS `svn_projects_responsible` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of project responsible users';

-- --------------------------------------------------------

-- 
-- Table structure for table `svn_users_groups`
-- 

DROP TABLE IF EXISTS `svn_users_groups`;
CREATE TABLE IF NOT EXISTS `svn_users_groups` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of user group relations';

-- --------------------------------------------------------

-- 
-- Table structure for table `svngroups`
-- 

DROP TABLE IF EXISTS `svngroups`;
CREATE TABLE IF NOT EXISTS `svngroups` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn user groups';

-- --------------------------------------------------------

-- 
-- Table structure for table `svnmailinglists`
-- 

DROP TABLE IF EXISTS `svnmailinglists`;
CREATE TABLE IF NOT EXISTS `svnmailinglists` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of available svn mailing lists';

-- --------------------------------------------------------

-- 
-- Table structure for table `svnprojects`
-- 

DROP TABLE IF EXISTS `svnprojects`;
CREATE TABLE IF NOT EXISTS `svnprojects` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn modules';

-- --------------------------------------------------------

-- 
-- Table structure for table `svnrepos`
-- 

DROP TABLE IF EXISTS `svnrepos`;
CREATE TABLE IF NOT EXISTS `svnrepos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn repositories';

-- --------------------------------------------------------

-- 
-- Table structure for table `svnusers`
-- 

DROP TABLE IF EXISTS `svnusers`;
CREATE TABLE IF NOT EXISTS `svnusers` (
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
  `superadmin` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `idx_userid` (`userid`),
  KEY `idx_mode` (`locked`),
  KEY `idx_passwordexpires` (`passwordexpires`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of all known users';

-- --------------------------------------------------------

-- 
-- Table structure for table `users_rights`
-- 

DROP TABLE IF EXISTS `users_rights`;
CREATE TABLE IF NOT EXISTS `users_rights` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of granted user rights';
