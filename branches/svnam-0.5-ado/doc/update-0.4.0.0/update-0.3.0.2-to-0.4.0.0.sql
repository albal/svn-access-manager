ALTER TABLE `users_rights` CHANGE `allowed` `allowed` ENUM( 'none', 'read', 'add', 'edit', 'delete' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none';

ALTER TABLE `svnrepos` ADD `different_auth_files` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `repopassword` ,
ADD `auth_user_file` VARCHAR( 255 ) NOT NULL AFTER `different_auth_files` ,
ADD `svn_access_file` VARCHAR( 255 ) NOT NULL AFTER `auth_user_file`;

DROP TABLE IF EXISTS `svn_groups_responsible`;
CREATE TABLE `svn_groups_responsible` (
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
