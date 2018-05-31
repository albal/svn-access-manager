-- MySQL dump 10.14  Distrib 5.5.56-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: svnamtest
-- ------------------------------------------------------
-- Server version	5.5.56-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help` (
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
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1 COMMENT='Table of help texts';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logtimestamp` varchar(14) NOT NULL DEFAULT '00000000000000',
  `username` varchar(255) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `logmessage` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_timestamp` (`logtimestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1 COMMENT='Table of log messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preferences`
--

DROP TABLE IF EXISTS `preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `page_size` int(4) NOT NULL,
  `user_sort_fields` varchar(255) NOT NULL,
  `user_sort_order` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of user preferences';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rights`
--

DROP TABLE IF EXISTS `rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `right_name` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL,
  `description_de` varchar(255) NOT NULL,
  `allowed_action` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='Table of rights to grant to users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `session_id` varchar(255) NOT NULL,
  `session_expires` int(10) unsigned NOT NULL DEFAULT '0',
  `session_data` text,
  PRIMARY KEY (`session_id`),
  KEY `idx_expires` (`session_expires`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svn_access_rights`
--

DROP TABLE IF EXISTS `svn_access_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svn_access_rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL DEFAULT '0',
  `path` longtext NOT NULL,
  `valid_from` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  `valid_until` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  `access_right` enum('none','read','write') NOT NULL DEFAULT 'none',
  `recursive` enum('yes','no') NOT NULL DEFAULT 'yes',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_projectid` (`project_id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_groupid` (`group_id`),
  KEY `idx_path` (`path`(512)),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Table of user or group access rights';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svn_groups_responsible`
--

DROP TABLE IF EXISTS `svn_groups_responsible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svn_groups_responsible` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `allowed` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_projectid_userid_groupid` (`user_id`,`group_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svn_projects_mailinglists`
--

DROP TABLE IF EXISTS `svn_projects_mailinglists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svn_projects_mailinglists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `mailinglisten_id` int(10) unsigned NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `moduleid` (`project_id`,`mailinglisten_id`),
  KEY `mailinglistenid` (`mailinglisten_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of modules and mailinglist relations';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svn_projects_responsible`
--

DROP TABLE IF EXISTS `svn_projects_responsible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svn_projects_responsible` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_projectid` (`project_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table of project responsible users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svn_users_groups`
--

DROP TABLE IF EXISTS `svn_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svn_users_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_groupid` (`group_id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table of user group relations';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svngroups`
--

DROP TABLE IF EXISTS `svngroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svngroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `groupname` (`groupname`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table of svn user groups';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svnmailinglists`
--

DROP TABLE IF EXISTS `svnmailinglists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svnmailinglists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mailinglist` varchar(255) NOT NULL,
  `emailaddress` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of available svn mailing lists';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svnpasswordreset`
--

DROP TABLE IF EXISTS `svnpasswordreset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svnpasswordreset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unixtime` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `idstr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svnprojects`
--

DROP TABLE IF EXISTS `svnprojects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svnprojects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo_id` int(10) unsigned NOT NULL,
  `svnmodule` varchar(255) NOT NULL,
  `modulepath` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_repoid` (`repo_id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table of svn modules';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svnrepos`
--

DROP TABLE IF EXISTS `svnrepos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svnrepos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reponame` varchar(255) NOT NULL,
  `repopath` varchar(255) NOT NULL,
  `repouser` varchar(255) NOT NULL,
  `repopassword` varchar(255) NOT NULL,
  `different_auth_files` tinyint(1) NOT NULL DEFAULT '0',
  `auth_user_file` varchar(255) NOT NULL,
  `svn_access_file` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table of svn repositories';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `svnusers`
--

DROP TABLE IF EXISTS `svnusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `svnusers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `givenname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `passwordexpires` tinyint(1) NOT NULL DEFAULT '1',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `emailaddress` varchar(255) NOT NULL DEFAULT '',
  `admin` char(1) NOT NULL DEFAULT 'n',
  `user_mode` varchar(10) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  `password_modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `superadmin` tinyint(1) NOT NULL DEFAULT '0',
  `securityquestion` varchar(255) DEFAULT '',
  `securityanswer` varchar(255) DEFAULT '',
  `custom1` varchar(255) DEFAULT '',
  `custom2` varchar(255) DEFAULT '',
  `custom3` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userid` (`userid`,`deleted`),
  KEY `idx_mode` (`locked`),
  KEY `idx_passwordexpires` (`passwordexpires`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Table of all known users';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Tabellenstruktur für Tabelle `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL,
  `validfrom` varchar(14) NOT NULL DEFAULT '00000000000000',
  `validuntil` varchar(14) NOT NULL DEFAULT '99999999999999',
  `message` text NOT NULL,
  `created` varchar(14) DEFAULT '00000000000000',
  `create_user` varchar(255) NOT NULL DEFAULT '',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT '',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);


--
-- Table structure for table `users_rights`
--

DROP TABLE IF EXISTS `users_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `right_id` int(10) NOT NULL,
  `allowed` enum('none','read','add','edit','delete') NOT NULL DEFAULT 'none',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_right_id` (`right_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='Table of granted user rights';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `workinfo`
--

DROP TABLE IF EXISTS `workinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usertimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='table of workinfo';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-31  9:43:08
