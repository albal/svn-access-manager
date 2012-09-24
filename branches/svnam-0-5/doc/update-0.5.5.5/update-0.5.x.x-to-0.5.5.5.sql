#################################################################################################################################################
#
# These statements are for upgrading from a MYSQL schema version 0.5.x.x to version 0.5.5.5
#
#################################################################################################################################################

ALTER TABLE `svnusers` ADD `custom1` VARCHAR( 255 ) NOT NULL ,
ADD `custom2` VARCHAR( 255 ) NOT NULL ,
ADD `custom3` VARCHAR( 255 ) NOT NULL 