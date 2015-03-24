
CREATE TABLE IF NOT EXISTS `jos_ga_publications` (
  `id` int(11) NOT NULL auto_increment,
  `type_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `authors` varchar(500) NOT NULL,
  `journal` varchar(255) NOT NULL,
  `issue` varchar(2) NOT NULL,
  `volume` varchar(2) NOT NULL,
  `pages` varchar(10) NOT NULL,
  `year` varchar(40) NOT NULL,
  `citations` varchar(20) NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `published` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_ga_publication_types` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(100) NOT NULL,
  `short_description` varchar(50) NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `jos_ga_publication_types` (`id`, `description`, `short_description`, `order`) VALUES
(1, 'Scientific paper', 'Scientific paper', 1),
(2, 'Review', 'Review', 2),
(3, 'Book chapter', 'Book chapter', 3);