--
-- Table structure for table `jos_sci_research_contracts`
--

CREATE TABLE IF NOT EXISTS `jos_sci_research_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_leader_id` int(11) NOT NULL,
  `irb_code` varchar(50) NOT NULL,
  `acronym` varchar(50) NOT NULL,
  `company` varchar(255) NOT NULL,
  `principal_investigator` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` decimal(9,2) NOT NULL DEFAULT '0.00',
  `overhead` decimal(9,2) NOT NULL DEFAULT '0.00',
  `comments` varchar(255) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Modifica el tamaño de la columna
--

ALTER TABLE `jos_sci_user_items` CHANGE `description` `description` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

--
-- Inserta las nuevas pestañas
--

INSERT INTO `jos_sci_user_items` (
`id` ,
`description`
)
VALUES (
'15', 'view_researchcontracts'
), (
'16', 'view_researchcontract' 
);

--
-- Dumping new data for table `jos_sci_user_rol_item_right`
--

INSERT INTO `jos_sci_user_rol_item_right` (`rol_id`, `item_id`, `right_id`) VALUES
(1, 15, 2),
(1, 16, 2),
(2, 15, 2),
(2, 16, 2),
(3, 15, 1),
(3, 16, 1),
(4, 15, 1),
(4, 16, 1),
(5, 15, 1),
(5, 16, 1),
(6, 15, 1),
(6, 16, 1);
