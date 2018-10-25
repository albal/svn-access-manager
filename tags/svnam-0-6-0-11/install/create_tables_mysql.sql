-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. Mai 2018 um 14:06
-- Server-Version: 5.5.56-MariaDB
-- PHP-Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `svnam`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(10) unsigned NOT NULL,
  `topic` varchar(255) NOT NULL,
  `headline_en` varchar(255) NOT NULL,
  `headline_de` varchar(255) NOT NULL,
  `helptext_de` longtext NOT NULL,
  `helptext_en` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table of help texts';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL,
  `logtimestamp` varchar(14) NOT NULL DEFAULT '00000000000000',
  `username` varchar(255) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `logmessage` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of log messages';

-- --------------------------------------------------------

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `preferences`
--

DROP TABLE IF EXISTS `preferences`;
CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) NOT NULL,
  `page_size` int(4) NOT NULL,
  `user_sort_fields` varchar(255) NOT NULL,
  `user_sort_order` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL,
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of user preferences';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(10) unsigned NOT NULL,
  `right_name` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL,
  `description_de` varchar(255) NOT NULL,
  `allowed_action` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of rights to grant to users';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(255) NOT NULL,
  `session_expires` int(10) unsigned NOT NULL DEFAULT '0',
  `session_data` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svngroups`
--

DROP TABLE IF EXISTS `svngroups`;
CREATE TABLE IF NOT EXISTS `svngroups` (
  `id` int(10) unsigned NOT NULL,
  `groupname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of svn user groups';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svnmailinglists`
--

DROP TABLE IF EXISTS `svnmailinglists`;
CREATE TABLE IF NOT EXISTS `svnmailinglists` (
  `id` int(10) unsigned NOT NULL,
  `mailinglist` varchar(255) NOT NULL,
  `emailaddress` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of available svn mailing lists';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svnpasswordreset`
--

DROP TABLE IF EXISTS `svnpasswordreset`;
CREATE TABLE IF NOT EXISTS `svnpasswordreset` (
  `id` int(11) unsigned NOT NULL,
  `unixtime` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `idstr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svnprojects`
--

DROP TABLE IF EXISTS `svnprojects`;
CREATE TABLE IF NOT EXISTS `svnprojects` (
  `id` int(10) unsigned NOT NULL,
  `repo_id` int(10) unsigned NOT NULL,
  `svnmodule` varchar(255) NOT NULL,
  `modulepath` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of svn modules';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svnrepos`
--

DROP TABLE IF EXISTS `svnrepos`;
CREATE TABLE IF NOT EXISTS `svnrepos` (
  `id` int(10) unsigned NOT NULL,
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
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of svn repositories';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svnusers`
--

DROP TABLE IF EXISTS `svnusers`;
CREATE TABLE IF NOT EXISTS `svnusers` (
  `id` int(10) unsigned NOT NULL,
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
  `custom3` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of all known users';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svn_access_rights`
--

DROP TABLE IF EXISTS `svn_access_rights`;
CREATE TABLE IF NOT EXISTS `svn_access_rights` (
  `id` int(10) unsigned NOT NULL,
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
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of user or group access rights';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svn_groups_responsible`
--

DROP TABLE IF EXISTS `svn_groups_responsible`;
CREATE TABLE IF NOT EXISTS `svn_groups_responsible` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `allowed` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svn_projects_mailinglists`
--

DROP TABLE IF EXISTS `svn_projects_mailinglists`;
CREATE TABLE IF NOT EXISTS `svn_projects_mailinglists` (
  `id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `mailinglisten_id` int(10) unsigned NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of modules and mailinglist relations';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svn_projects_responsible`
--

DROP TABLE IF EXISTS `svn_projects_responsible`;
CREATE TABLE IF NOT EXISTS `svn_projects_responsible` (
  `id` int(10) unsigned NOT NULL,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of project responsible users';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `svn_users_groups`
--

DROP TABLE IF EXISTS `svn_users_groups`;
CREATE TABLE IF NOT EXISTS `svn_users_groups` (
  `id` int(10) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of user group relations';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users_rights`
--

DROP TABLE IF EXISTS `users_rights`;
CREATE TABLE IF NOT EXISTS `users_rights` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) NOT NULL,
  `right_id` int(10) NOT NULL,
  `allowed` enum('none','read','add','edit','delete') NOT NULL DEFAULT 'none',
  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
  `created_user` varchar(255) NOT NULL DEFAULT ' ',
  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  `modified_user` varchar(255) NOT NULL DEFAULT ' ',
  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  `deleted_user` varchar(255) NOT NULL DEFAULT ' '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table of granted user rights';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `workinfo`
--

DROP TABLE IF EXISTS `workinfo`;
CREATE TABLE IF NOT EXISTS `workinfo` (
  `id` int(10) unsigned NOT NULL,
  `usertimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table of workinfo';

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `help`
--
ALTER TABLE `help`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_topic` (`topic`),
  ADD FULLTEXT KEY `helptext_de` (`helptext_de`);
ALTER TABLE `help`
  ADD FULLTEXT KEY `helptext_en` (`helptext_en`);

--
-- Indizes für die Tabelle `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_timestamp` (`logtimestamp`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userid` (`user_id`);

--
-- Indizes für die Tabelle `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_expires` (`session_expires`);

--
-- Indizes für die Tabelle `svngroups`
--
ALTER TABLE `svngroups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groupname` (`groupname`);

--
-- Indizes für die Tabelle `svnmailinglists`
--
ALTER TABLE `svnmailinglists`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `svnpasswordreset`
--
ALTER TABLE `svnpasswordreset`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `svnprojects`
--
ALTER TABLE `svnprojects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_repoid` (`repo_id`),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `svnrepos`
--
ALTER TABLE `svnrepos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `svnusers`
--
ALTER TABLE `svnusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_userid` (`userid`,`deleted`),
  ADD KEY `idx_mode` (`locked`),
  ADD KEY `idx_passwordexpires` (`passwordexpires`),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `svn_access_rights`
--
ALTER TABLE `svn_access_rights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projectid` (`project_id`),
  ADD KEY `idx_userid` (`user_id`),
  ADD KEY `idx_groupid` (`group_id`),
  ADD KEY `idx_path` (`path`(512)),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `svn_groups_responsible`
--
ALTER TABLE `svn_groups_responsible`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projectid_userid_groupid` (`user_id`,`group_id`),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `svn_projects_mailinglists`
--
ALTER TABLE `svn_projects_mailinglists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `moduleid` (`project_id`,`mailinglisten_id`),
  ADD KEY `mailinglistenid` (`mailinglisten_id`);

--
-- Indizes für die Tabelle `svn_projects_responsible`
--
ALTER TABLE `svn_projects_responsible`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projectid` (`project_id`),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `svn_users_groups`
--
ALTER TABLE `svn_users_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_groupid` (`group_id`),
  ADD KEY `idx_userid` (`user_id`),
  ADD KEY `idx_deleted` (`deleted`);

--
-- Indizes für die Tabelle `users_rights`
--
ALTER TABLE `users_rights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_right_id` (`right_id`);

--
-- Indizes für die Tabelle `workinfo`
--
ALTER TABLE `workinfo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `help`
--
ALTER TABLE `help`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `log`
--
ALTER TABLE `log`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `preferences`
--
ALTER TABLE `preferences`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `rights`
--
ALTER TABLE `rights`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svngroups`
--
ALTER TABLE `svngroups`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svnmailinglists`
--
ALTER TABLE `svnmailinglists`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svnpasswordreset`
--
ALTER TABLE `svnpasswordreset`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svnprojects`
--
ALTER TABLE `svnprojects`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svnrepos`
--
ALTER TABLE `svnrepos`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svnusers`
--
ALTER TABLE `svnusers`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svn_access_rights`
--
ALTER TABLE `svn_access_rights`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svn_groups_responsible`
--
ALTER TABLE `svn_groups_responsible`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svn_projects_mailinglists`
--
ALTER TABLE `svn_projects_mailinglists`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svn_projects_responsible`
--
ALTER TABLE `svn_projects_responsible`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `svn_users_groups`
--
ALTER TABLE `svn_users_groups`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `users_rights`
--
ALTER TABLE `users_rights`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `workinfo`
--
ALTER TABLE `workinfo`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
  