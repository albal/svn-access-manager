ALTER TABLE `svnusers` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `preferences` CHANGE `user_sort_fields` `user_sort_fields` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `user_sort_order` `user_sort_order` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `rights` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `svngroups` CHANGE `description` `description` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `svnmailinglists` CHANGE `mailinglist` `mailinglist` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `emailaddress` `emailaddress` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `description` `description` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL ,
CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

 ALTER TABLE `svnprojects` CHANGE `svnmodule` `svnmodule` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modulepath` `modulepath` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `description` `description` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `svnrepos` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '';

ALTER TABLE `svn_access_rights` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `svn_groups_responsible` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `svn_projects_mailinglists` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `svn_users_groups` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';

ALTER TABLE `users_rights` CHANGE `created_user` `created_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `modified_user` `modified_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ',
CHANGE `deleted_user` `deleted_user` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT ' ';