ALTER TABLE `jos_sci_research_contracts` ADD `funding_sector_id` INT( 11 ) NOT NULL AFTER `end_date` ;

-- 
-- Table structure for table `jos_sci_research_contracts_funding_sectors`
-- 

CREATE TABLE `jos_sci_research_contracts_funding_sectors` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(255) NOT NULL,
  `short_description` varchar(100) NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `jos_sci_research_contracts_funding_sectors`
-- 

INSERT INTO `jos_sci_research_contracts_funding_sectors` (`id`, `description`, `short_description`, `order`) VALUES 
(1, 'Corporate Sector. Public Companies', 'Corporate Sector. Public Companies', 1),
(2, 'Corporate Sector. Private Companies', 'Corporate Sector. Private Companies', 2),
(3, 'Corporate Sector. IPSFL. Other institutions', 'Corporate Sector. IPSFL. Other institutions', 3),
(4, 'Foreign Sector. Foreign Public Companies', 'Foreign Sector. Foreign Public Companies', 4),
(5, 'Foreign Sector. Foreign Private Companies', 'Foreign Sector. Foreign Private Companies', 5),
(6, 'Foreign Sector. Foreign Universities', 'Foreign Sector. Foreign Universities', 6),
(7, 'Public Administration', 'Public Administration', 7),
(8, 'Philantropy', 'Philantropy', 8);
