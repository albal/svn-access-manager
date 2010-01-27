ALTER TABLE `svnusers` ADD `securityquestion` VARCHAR( 255 ) NOT NULL ,
ADD `securityanswer` VARCHAR( 255 ) NOT NULL;

CREATE TABLE `svnpasswordreset` (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`unixtime` INT( 11 ) NOT NULL ,
`username` VARCHAR( 255 ) NOT NULL ,
`token` VARCHAR( 255 ) NOT NULL ,
`idstr` VARCHAR( 255 ) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `svnusers` DROP INDEX `idx_userid` ,
ADD UNIQUE `idx_userid` ( `userid` , `deleted` ) 