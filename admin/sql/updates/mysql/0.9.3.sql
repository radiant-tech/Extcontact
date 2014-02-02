ALTER TABLE `#__extcontact_details`
MODIFY `section` varchar(50) NOT NULL DEFAULT '' AFTER `fax`;

ALTER TABLE `#__extcontact_details`
MODIFY `overview` mediumtext AFTER `section`;