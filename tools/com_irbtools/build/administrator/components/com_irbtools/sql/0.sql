--
-- Table structure for table `jos_irbtools_users`
--

CREATE TABLE IF NOT EXISTS `jos_irbtools_users` (
  `username` VARCHAR( 20 ) NOT NULL,
  PRIMARY KEY ( `username` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `jos_irbtools_apps`
--

CREATE TABLE IF NOT EXISTS `jos_irbtools_apps` (
  `appname` VARCHAR( 20 ) NOT NULL,
  PRIMARY KEY ( `appname` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `jos_irbtools_user_app`
--

CREATE TABLE IF NOT EXISTS `jos_irbtools_user_app` (
  `username` varchar(20) NOT NULL,
  `appname` varchar(20) NOT NULL,
  PRIMARY KEY  (`username`,`appname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table structure for table `jos_irbtools_exceptions`
-- 

CREATE TABLE `jos_irbtools_exceptions` (
  `id` int(11) NOT NULL auto_increment,
  `command` varchar(3) NOT NULL,
  `irbpeople_user_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `research_group` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `second_affiliation` varchar(255) NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

-- 
-- Dumping data for table `jos_irbtools_exceptions`
-- 

INSERT INTO `jos_irbtools_exceptions` (`id`, `command`, `irbpeople_user_id`, `name`, `surname`, `gender`, `department`, `unit`, `research_group`, `email`, `phone`, `position`, `location`, `second_affiliation`, `modified`) VALUES 
(60, 'mod', '972', 'Markus', 'Muttenthaler', 'Male', 'Research Programmes', 'Chemistry & Molecular Pharmacology', 'Fernando Albericio: Combinatorial Chemistry', 'markus.muttenthaler@irbbarcelona.org', '', 'Postdoctoral Fellow', 'EM01B23', '', '2011-06-14 14:52:23'),
(65, 'add', '1000', 'Marko', 'Marjanovic', 'Male', 'Research Programmes', 'Oncology', 'Travis Stracker: Genomic Instability and Cancer Laboratory ', 'marko.marjanovic@irbbarcelona.org', '+34 93 40 31197', 'Postdoctoral Fellow', 'EM01B44 ', '', '2011-09-05 12:00:55'),
(63, 'del', '975', '', '', '', '', '', '', '', '', '', '', '', '2011-07-05 09:33:14'),
(64, 'add', '934', 'Irene', 'Amata', 'Female', 'Research Programmes', 'Oncology', 'Angel R. Nebreda: Signalling and Cell Cycle', 'irene.amata@irbbarcelona.org', '+34 93 40 20596', 'Postdoctoral Fellow', 'EM01B53', '', '2011-08-31 14:58:04'),
(29, 'del', '926', '', '', '', '', '', '', '', '', '', '', '', '2011-02-22 14:39:00'),
(30, 'del', '922', '', '', '', '', '', '', '', '', '', '', '', '2011-02-22 14:39:11'),
(35, 'mod', '399', 'Ferran', 'Azorín', 'Male', 'Research Programmes', 'Cell & Developmental Biology ', 'Ferran Azorín: Chromatin Structure and Function', 'ferran.azorin@irbbarcelona.org', '+34 93 40 34958', 'Group Leader', 'EMPBA31 ', 'Professor (IBMB-CSIC)', '2011-03-23 09:41:23'),
(38, 'mod', '118', 'Eva', 'Poca', 'Female', 'Research Programmes', 'Chemistry & Molecular Pharmacology ', '', 'eva.poca@irbbarcelona.org', '+34 93 40 37124', 'Programme Secretary', 'EM01B23', '', '2011-03-23 09:40:59'),
(39, 'mod', '264', 'Martha', 'Brigg', 'Female', 'Research Programmes', 'Cell & Developmental Biology', '', 'martha.brigg@irbbarcelona.org', '+34 93 40 20258', 'Programme Secretary', 'EMPBA21', '', '2011-03-23 09:40:49'),
(42, 'mod', '237', 'Ainoa', 'Olza', 'Female', 'Research Programmes', 'Cell & Developmental Biology', '', 'ainoa.olza@irbbarcelona.org', '+34 93 40 39757', 'Programme Technician', 'EMPBAB13BIS', '', '2011-03-23 09:40:40'),
(43, 'mod', '290', 'Natàlia', 'Molner', 'Female', 'Research Programmes', 'Molecular Medicine', '', 'natalia.molner@irbbarcelona.org', '+34 93 40 34046', 'Programme Secretary', 'EMPBA11', '', '2011-03-23 09:40:28'),
(44, 'mod', '293', 'Natàlia', 'Plana', 'Female', 'Research Programmes', 'Molecular Medicine', '', 'natalia.plana@irbbarcelona.org', '+34 93 40 34701', 'Programme Technician', 'EMPBA11', '', '2011-03-23 09:40:18'),
(11, 'add', '17', 'Joan J.', 'Guinovart', 'Male', 'Research Programmes', 'Molecular Medicine', 'Joan J. Guinovart: Metabolic engineering and diabetes', 'guinovart@irbbarcelona.org', '+34 93 40 37111', 'Group Leader', 'EMPBB13', 'Professor (Biochemistry and Molecular Biology Dept. - UB)', '2011-03-23 09:44:47'),
(28, 'del', '704', '', '', '', '', '', '', '', '', '', '', '', '2011-02-22 14:37:35'),
(13, 'del', '728', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00'),
(16, 'del', '709', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00'),
(17, 'del', '116', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00'),
(19, 'del', '673', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00'),
(20, 'del', '906', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00'),
(22, 'mod', '836', 'Angel ', 'R. Nebreda', 'Male', 'Research Programmes', 'Oncology', 'Angel R. Nebreda: Signalling and cell cycle', 'angel.nebreda@irbbarcelona.org', '+34 93 40 31379', 'Group Leader', 'EM01B53', 'ICREA Research Professor', '2011-03-23 09:44:37'),
(24, 'mod', '834', 'Anna Maria', 'Vilches', 'Female', 'Administration', 'Human Resources', '', 'anna.vilches@irbbarcelona.org', '+34 93 40 20592', 'Joint Health and Safety Service Technician', 'TR04A8', '', '2011-10-24 10:01:16'),
(25, 'ins', '99902', 'Cristina', 'Nadal', 'Female', 'Research Programmes', 'Oncology', 'MetLab: Growth control and cancer metastasis', '', '+34 93 40 39961', 'Postdoctoral Researcher', 'EMPBB51', 'Member of the Institut d''Investigacions Sanitàries IDIBAPS-Hopsital Clínic/IRB Barcelona', '2011-03-23 09:41:48'),
(45, 'mod', '889', 'Esther', 'Fernández', 'Female', 'Research Programmes', 'Structural & Computational Biology ', '', 'esther.fernandez.rodriguez@irbbarcelona.org', '+34 93 40 39953', 'Programme Secretary', 'EMPBB43', '', '2011-03-23 09:40:00'),
(48, 'add', '256', 'Olga', 'Bausà', 'Female', 'Research Programmes', 'Molecular Medicine', 'Manuel Palacín: Heterogenic and Multigenic Diseases ', 'olga.bausa@irbbarcelona.org', '+34 93 40 37198', 'Project Manager', 'EMPBA13', '', '2011-03-23 09:39:27'),
(47, 'add', '17', 'Joan J.', 'Guinovart', 'Male', 'Research Programmes', 'Molecular Medicine', 'Joan J. Guinovart: Metabolic Engineering and Diabetes  ', 'guinovart@irbbarcelona.org', '+34 93 40 37111', 'Group Leader', 'EMPBB13 ', 'Professor (Biochemistry and Molecular Biology Dept. - UB)', '2011-03-23 09:39:39'),
(49, 'add', '325', 'Antonio', 'Zorzano', 'Male', 'Administration', 'Scientific Programmes', '', 'antonio.zorzano@irbbarcelona.org', '+34 93 40 37197', 'Programme Coordinator', 'EMPBA11', 'Professor (Biochemistry and Molecular Biology Dept. - UB)', '2011-03-23 09:39:16'),
(50, 'add', '420', 'Eduard', 'Batlle', 'Male', 'Administration', 'Scientific Programmes', '', 'eduard.batlle@irbbarcelona.org', '+34 93 40 39008', 'Programme Coordinator', 'EM01B51 ', 'ICREA Research Professor', '2011-03-23 09:39:05'),
(51, 'add', '333', 'Ernest', 'Giralt', 'Male', 'Administration', 'Scientific Programmes', '', 'ernest.giralt@irbbarcelona.org', '+34 93 40 37125', 'Programme Coordinator', 'EM01B21 ', 'Professor (Organic Chemistry Dept. - UB)', '2011-03-23 09:38:55'),
(52, 'add', '429', 'Miquel', 'Coll', 'Male', 'Administration', 'Scientific Programmes', '', 'miquel.coll@irbbarcelona.org', '+34 93 40 34951', 'Programme Coordinator', 'EMPBB43 ', 'Professor (IBMB-CSIC)', '2011-03-23 09:38:45'),
(53, 'add', '394', 'Marco', 'Milán', 'Male', 'Administration', 'Scientific Programmes', '', 'marco.milan@irbbarcelona.org', '+34 93 40 34902', 'Programme Coordinator', 'EMPBB21', 'ICREA Research Professor', '2011-03-23 09:34:10'),
(77, 'add', '761', 'Cristina', 'Méndez', 'Female', 'Research Programmes', 'Oncology', '', 'cristina.mendez@irbbarcelona.org', '+34 93 40 34716', 'Secretaria de Programa', 'EM01B53', '', '2012-05-25 12:35:27'),
(67, 'add', '1016', 'Salvatore', 'Bongarzone', 'Male', 'Research Programmes', 'Chemistry & Molecular Pharmacology ', 'Fernando Albericio: Combinatorial Chemistry', 'salvatore.bongarzone@irbbarcelona.org', '+34 93 40 34869', 'Postdoctoral Fellow', 'EM01B23', '', '2011-10-06 09:09:59'),
(69, 'mod', '569', 'Joan', 'Pous', 'Male', 'Research Programmes', 'Structural & Computational Biology', '', 'joan.pous@irbbarcelona.org', '+34 93 40 34882', 'Postdoctoral Fellow at the High Throughput Crystallography Platform', 'EMS1B43', '', '2011-11-21 12:11:19'),
(70, 'add', '986', 'Juan Manuel', 'Murillo', 'Male', 'Research Programmes', 'Oncology', 'Angel R. Nebreda: Signalling and Cell Cycle ', 'juanmanuel.murillo@irbbarcelona.org', '+34 93 40 20596', 'Postdoctoral Fellow', 'EM01B53', '', '2011-11-30 12:54:51'),
(58, 'add', '717', 'Neus', 'Teixidó', 'Female', 'Research Programmes', 'Molecular Medicine', 'Carme Caelles: Cell Signalling', 'neus.teixido@irbbarcelona.org', '+34 93 40 37131', 'Postdoctoral Fellow', 'EMPBB11', '', '2011-05-11 16:46:35'),
(66, 'add', '999', 'Christopher', 'Sinadinos', 'Male', 'Research Programmes', 'Cell & Developmental Biology', 'Marco Milan: Development and Growth Control Laboratory ', 'christopher.sinadinos@irbbarcelona.org', '+34 93 40 34901', 'Postdoctoral Fellow', 'EMPBB21', '', '2011-09-09 08:49:18'),
(68, 'add', '1017', 'Samira', 'Jaeger', 'Female', 'Research Programmes', 'Structural & Computational Biology ', 'Modesto Orozco: Molecular Modelling and Bioinformatics', 'samira.jaeger@irbbarcelona.org', '+34 93 40 37155', 'Postdoctoral Fellow', 'EMPBC33 ', '', '2012-01-03 15:59:39'),
(71, 'del', '1025', '', '', '', '', '', '', '', '', '', '', '', '2011-11-30 15:11:49'),
(72, 'del', '1033', '', '', '', '', '', '', '', '', '', '', '', '2011-11-30 15:11:59'),
(73, 'del', '1032', '', '', '', '', '', '', '', '', '', '', '', '2011-12-13 17:28:51'),
(74, 'del', '805', '', '', '', '', '', '', '', '', '', '', '', '2011-12-15 11:50:19'),
(75, 'del', '1040', '', '', '', '', '', '', '', '', '', '', '', '2012-01-03 15:58:29'),
(76, 'mod', '1052', 'Begoña', 'Domínguez', 'Female', 'Research Programmes', 'Oncology', '', 'begona.dominguez@irbbarcelona.org', '+34 93 40 34982', 'Programme Technician', 'EM01B51 ', '', '2012-02-01 17:57:18'),
(78, 'del', '1095', '', '', '', '', '', '', '', '', '', '', '', '2012-09-17 10:42:24'),
(79, 'add', '1127', 'Federica', 'Lombardi', 'Female', 'Research Programmes', 'Molecular', 'Antonio Zorzano', 'federica.lombardi@irbbarcelona.org', '+34 93 40 34700', 'Postdoctoral Fellow', 'EMPBA11', '', '2012-09-17 11:22:31');

--
-- Table structure for table `jos_irbtools_commands`
--

CREATE TABLE IF NOT EXISTS `jos_irbtools_commands` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(50) NOT NULL,
  `short_description` varchar(50) NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `jos_irbtools_commands`
--

INSERT INTO `jos_irbtools_commands` (`id`, `description`, `short_description`, `order`) VALUES
(1, 'add', 'add', 1),
(2, 'mod', 'mod', 2),
(3, 'del', 'del', 3),
(4, 'ins', 'ins', 4);

--
-- Table structure for table `jos_irbtools_email_log`
--

CREATE TABLE IF NOT EXISTS `jos_irbtools_email_log` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

--
-- Table structure for table `jos_irbtools_phones`
--

CREATE TABLE IF NOT EXISTS `jos_irbtools_phones` (
  `id` int(11) NOT NULL,
  `group` varchar(50) NOT NULL,
  `phone` varchar(9) NOT NULL,
  `fax` varchar(9) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_irbtools_phones`
--

INSERT INTO `jos_irbtools_phones` (`id`, `group`, `phone`, `fax`) VALUES
(1, 'directorate', '+34 93 40 37111', '+34 93 40 20432'),
(2, 'raa', '+34 93 40 37118', '+34 93 40 37114'),
(3, 'ocer', '+34 93 40 34636', '+34 93 40 37114'),
(4, 'hr', '+34 93 40 20250', '+34 93 40 37114'),
(5, 'its', '+34 93 40 39999', '+34 93 40 39998'),
(6, 'finance', '+34 93 40 39810', '+34 93 40 37114'),
(7, 'moumut', '+34 93 40 37296', '+34 93 40 20260'),
(8, 'genomics', '+34 93 40 34550', ''),
(9, 'masspe', '+34 93 40 39815', '+34 93 40 39801'),
(10, 'proexp', '+34 93 40 20263', '+34 93 40 20264'),
(11, 'cgonzalez_g', '+34 93 40 39982', '+34 93 40 20448'),
(12, 'ariera_g', '+34 93 40 37096', '+34 93 40 37095'),
(13, 'egiralt_g', '+34 93 40 37127', ''),
(14, 'falbericio_g', '+34 93 40 37127', ''),
(15, 'azorzano_g', '+34 93 40 34701', '+34 93 40 34717'),
(16, 'mpalacin_g', '+34 93 40 34701', '+34 93 40 34717'),
(17, 'mmacias_g', '+34 93 40 37188', ''),
(18, 'ebatlle_g', '+34 93 40 34982', ''),
(19, 'lribas_g', '+34 93 40 34867', '+34 93 40 34870'),
(20, 'fazorin_g', '+34 93 40 34963', ''),
(21, 'ccaelles_g', '+34 93 40 37131', ''),
(22, 'reritja_g', '+34 93 40 39941', ''),
(23, 'guinovart_g', '+34 93 40 37162', ''),
(24, 'acelada_g', '+34 93 40 37164', '+34 93 40 34747'),
(25, 'jcasanova_g', '+34 93 40 34966', ''),
(26, 'mcoll_g', '+34 93 40 34950', ''),
(27, 'ifita_g', '+34 93 40 34956', ''),
(28, 'rgomis_g', '+34 93 40 39961', '+34 93 40 34848'),
(29, 'mpons_g', '+34 93 40 34677', ''),
(30, 'orozco', '+34 93 40 37155', '+34 93 40 37157'),
(31, 'esoriano_g', '+34 93 40 37119', '+34 93 40 37116'),
(32, 'paloy_g', '+34 93 40 39689', '+34 93 40 39954'),
(33, 'jluders_g', '+34 93 40 20201', '+34 93 40 20256'),
(34, 'biostats', '+34 93 40 20553', '+34 93 40 20257'),
(35, 'ebl', '+34 93 40 20228', '+34 93 40 20288'),
(36, 'adm', '+34 93 40 20499', '+34 93 40 31116'),
(37, 'lmb', '+34 93 40 20461', ''),
(38, 'purchasing', '+34 93 40 20271', '+34 93 40 37114'),
(39, 'innovation', '+34 93 40 31193', '+34 93 40 37114'),
(40, 'tstracker_g', '+34 93 40 31197', '+34 93 40 31196'),
(41, 'anebreda_g', '+34 93 40 20596', '+34 93 40 20595'),
(42, 'rmendez_g', '+34 93 40 31901', '+34 93 40 31902');


