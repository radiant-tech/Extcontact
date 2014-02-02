ALTER TABLE `#__extcontact_sections`
ADD `link` varchar(255) NOT NULL DEFAULT '' AFTER `name`;

ALTER TABLE `#__extcontact_details`
DROP `practice`;

ALTER TABLE `#__extcontact_details`
ADD `assistant` varchar(150) NOT NULL default '' AFTER `misc`;
