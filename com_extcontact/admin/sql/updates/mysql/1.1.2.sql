ALTER TABLE `#__extcontact_sections`
CHANGE COLUMN `section` `related` varchar(50) NOT NULL default '' AFTER `link`;