CREATE TABLE `#__extcontact_sections` (
  `id` integer NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` integer unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;