ALTER TABLE `svn_access_rights` CHANGE `user_id` `user_id` INT(10) NOT NULL DEFAULT '0';
ALTER TABLE `svn_access_rights` CHANGE `group_id` `group_id` INT(10) NOT NULL DEFAULT '0';

ALTER TABLE `preferences` CHANGE `modified_user` `modified_user` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `preferences` CHANGE `deleted_user` `deleted_user` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `preferences` ADD `tooltip_show` INT(5) NOT NULL DEFAULT '700' AFTER `user_sort_order`, ADD `tooltip_hide` INT(5) NOT NULL DEFAULT '300' AFTER `tooltip_show`;

ALTER TABLE `log` CHANGE `username` `username` VARCHAR(255) NOT NULL DEFAULT ' ', CHANGE `ipaddress` `ipaddress` VARCHAR(15) NOT NULL DEFAULT ' ';
