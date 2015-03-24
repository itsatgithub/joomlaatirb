DROP TABLE IF EXISTS `jos_ig_groups`;
CREATE TABLE IF NOT EXISTS `jos_ig_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
