-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2009 at 11:50 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `svnadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) NOT NULL,
  `headline_en` varchar(255) NOT NULL,
  `headline_de` varchar(255) NOT NULL,
  `helptext_de` longtext NOT NULL,
  `helptext_en` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_topic` (`topic`),
  FULLTEXT KEY `helptext_de` (`helptext_de`),
  FULLTEXT KEY `helptext_en` (`helptext_en`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Table of help texts';

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `logmessage` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table of log messages';

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

DROP TABLE IF EXISTS `preferences`;
CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `page_size` int(4) NOT NULL,
  `user_sort_fields` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `user_sort_order` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of user preferences';

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `right_name` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL,
  `description_de` varchar(255) NOT NULL,
  `allowed_action` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` datetime NOT NULL,
  `created_user` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table of rights to grant to users';

-- --------------------------------------------------------

--
-- Table structure for table `semaphores`
--

DROP TABLE IF EXISTS `semaphores`;
CREATE TABLE IF NOT EXISTS `semaphores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='table of semaphores';

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `session_expires` int(10) unsigned NOT NULL DEFAULT '0',
  `session_data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session`),
  KEY `idx_expires` (`session_expires`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `svngroups`
--

DROP TABLE IF EXISTS `svngroups`;
CREATE TABLE IF NOT EXISTS `svngroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groupname` (`groupname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn user groups';

-- --------------------------------------------------------

--
-- Table structure for table `svnmailinglists`
--

DROP TABLE IF EXISTS `svnmailinglists`;
CREATE TABLE IF NOT EXISTS `svnmailinglists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mailinglist` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `emailaddress` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `description` mediumtext COLLATE latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of available svn mailing lists';

-- --------------------------------------------------------

--
-- Table structure for table `svnprojects`
--

DROP TABLE IF EXISTS `svnprojects`;
CREATE TABLE IF NOT EXISTS `svnprojects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo_id` int(10) unsigned NOT NULL,
  `svnmodule` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modulepath` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_repoid` (`repo_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn modules';

-- --------------------------------------------------------

--
-- Table structure for table `svnrepos`
--

DROP TABLE IF EXISTS `svnrepos`;
CREATE TABLE IF NOT EXISTS `svnrepos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reponame` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `repopath` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `repouser` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `repopassword` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `different_auth_files` tinyint(1) NOT NULL DEFAULT '0',
  `auth_user_file` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `svn_access_file` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of svn repositories';

-- --------------------------------------------------------

--
-- Table structure for table `svnusers`
--

DROP TABLE IF EXISTS `svnusers`;
CREATE TABLE IF NOT EXISTS `svnusers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `givenname` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `passwordexpires` tinyint(1) NOT NULL DEFAULT '1',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `emailaddress` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `admin` char(1) COLLATE latin1_german1_ci NOT NULL DEFAULT 'n',
  `mode` varchar(10) COLLATE latin1_german1_ci NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `password_modified` datetime NOT NULL,
  `superadmin` tinyint(1) NOT NULL DEFAULT '0',
  `securityquestion` varchar(255) DEFAULT '',
  `securityanswer` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userid` (`userid`,`deleted`),
  KEY `idx_mode` (`locked`),
  KEY `idx_passwordexpires` (`passwordexpires`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of all known users';

-- --------------------------------------------------------

--
-- Table structure for table `svn_access_rights`
--

DROP TABLE IF EXISTS `svn_access_rights`;
CREATE TABLE IF NOT EXISTS `svn_access_rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `path` longtext NOT NULL,
  `valid_from` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  `valid_until` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  `access_right` set('none','read','write') NOT NULL DEFAULT 'none',
  `recursive` enum('yes','no') NOT NULL DEFAULT 'yes',
  `created` datetime NOT NULL,
  `created_user` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_projectid` (`project_id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_groupid` (`group_id`),
  KEY `idx_path` (`path`(512)),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table of user or group access rights';

-- --------------------------------------------------------

--
-- Table structure for table `svn_groups_responsible`
--

DROP TABLE IF EXISTS `svn_groups_responsible`;
CREATE TABLE IF NOT EXISTS `svn_groups_responsible` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `allowed` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` datetime NOT NULL,
  `created_user` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_projectid_userid_groupid` (`user_id`,`group_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `svn_projects_mailinglists`
--

DROP TABLE IF EXISTS `svn_projects_mailinglists`;
CREATE TABLE IF NOT EXISTS `svn_projects_mailinglists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `mailinglisten_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `moduleid` (`project_id`,`mailinglisten_id`),
  KEY `mailinglistenid` (`mailinglisten_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of modules and mailinglist relations';

-- --------------------------------------------------------

--
-- Table structure for table `svn_projects_responsible`
--

DROP TABLE IF EXISTS `svn_projects_responsible`;
CREATE TABLE IF NOT EXISTS `svn_projects_responsible` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_projectid` (`project_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table of project responsible users';

-- --------------------------------------------------------

--
-- Table structure for table `svn_users_groups`
--

DROP TABLE IF EXISTS `svn_users_groups`;
CREATE TABLE IF NOT EXISTS `svn_users_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `created_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_groupid` (`group_id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of user group relations';

-- --------------------------------------------------------

--
-- Table structure for table `users_rights`
--

DROP TABLE IF EXISTS `users_rights`;
CREATE TABLE IF NOT EXISTS `users_rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `right_id` int(10) NOT NULL,
  `allowed` enum('none','add','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` datetime NOT NULL,
  `created_user` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_user` varchar(255) NOT NULL,
  `deleted` datetime NOT NULL,
  `deleted_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_right_id` (`right_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table of granted user rights';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svnpasswordreset`
--

CREATE TABLE IF NOT EXISTS `svnpasswordreset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unixtime` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `idstr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;