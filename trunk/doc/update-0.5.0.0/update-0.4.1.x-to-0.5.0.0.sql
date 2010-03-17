ALTER TABLE `log` CHANGE `timestamp` `timestamp` VARCHAR( 19 ) NOT NULL;
UPDATE `log` SET `timestamp` = CONCAT(SUBSTR(`timestamp`,1,4), SUBSTR(`timestamp`,6,2), SUBSTR(`timestamp`,9,2), SUBSTR(`timestamp`,12,2), SUBSTR(`timestamp`,15,2), SUBSTR(`timestamp`,18,2));
ALTER TABLE `log` CHANGE `timestamp` `timestamp` VARCHAR( 14 ) NOT NULL;
ALTER TABLE `log` CHANGE `timestamp` `logtimestamp` VARCHAR( 10 ) NOT NULL;



ALTER TABLE `semaphores` CHANGE `timestamp` `timestamp` VARCHAR( 19 ) NOT NULL;
UPDATE `semaphores` SET `timestamp` = CONCAT(SUBSTR(`timestamp`,1,4), SUBSTR(`timestamp`,6,2), SUBSTR(`timestamp`,9,2), SUBSTR(`timestamp`,12,2), SUBSTR(`timestamp`,15,2), SUBSTR(`timestamp`,18,2));
ALTER TABLE `semaphores` CHANGE `timestamp` `timestamp` VARCHAR( 14 ) NOT NULL;
RENAME TABLE `semaphores`  TO `workinfo` ;
ALTER TABLE `workinfo` CHANGE `timestamp` `usertimestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;



ALTER TABLE `preferences` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `preferences` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `preferences` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `rights` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `rights` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `rights` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svngroups` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svngroups` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svngroups` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svnmailinglists` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svnmailinglists` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svnmailinglists` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svnprojects` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svnprojects` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svnprojects` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svnrepos` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svnrepos` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svnrepos` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svnusers` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svnusers` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svnusers` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;
ALTER TABLE `svnusers` CHANGE `mode` `user_mode` VARCHAR( 10 ) NOT NULL;



ALTER TABLE `svn_access_rights` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svn_access_rights` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svn_access_rights` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svn_groups_responsible` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svn_groups_responsible` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svn_groups_responsible` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svn_projects_mailinglists` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svn_projects_mailinglists` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svn_projects_mailinglists` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svn_projects_responsible` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svn_projects_responsible` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svn_projects_responsible` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `svn_users_groups` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `svn_users_groups` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `svn_users_groups` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `users_rights` CHANGE `created` `created` VARCHAR( 19 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 19 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 19 ) NOT NULL;
UPDATE `users_rights` SET `created` = CONCAT(SUBSTR(`created`,1,4), SUBSTR(`created`,6,2), SUBSTR(`created`,9,2), SUBSTR(`created`,12,2), SUBSTR(`created`,15,2), SUBSTR(`created`,18,2) ), `modified` = CONCAT(SUBSTR(`modified`,1,4), SUBSTR(`modified`,6,2), SUBSTR(`modified`,9,2), SUBSTR(`modified`,12,2), SUBSTR(`modified`,15,2), SUBSTR(`modified`,18,2) ), `deleted` = CONCAT(SUBSTR(`deleted`,1,4), SUBSTR(`deleted`,6,2), SUBSTR(`deleted`,9,2), SUBSTR(`deleted`,12,2), SUBSTR(`deleted`,15,2), SUBSTR(`deleted`,18,2) );
ALTER TABLE `users_rights` CHANGE `created` `created` VARCHAR( 14 ) NOT NULL, CHANGE `modified` `modified` VARCHAR( 14 ) NOT NULL, CHANGE `deleted` `deleted` VARCHAR( 14 ) NOT NULL;



ALTER TABLE `sessions` CHANGE `session` `session_id` VARCHAR( 255 ) NOT NULL ;