ALTER TABLE `jos_sci_projects` ADD `awarding_date` DATE NOT NULL AFTER `reference` ;

ALTER TABLE `jos_sci_projects` ADD `uneix_id` INT( 11 ) NOT NULL AFTER `modified` ;

-- 
-- Table structure for table `jos_sci_project_uneix`
-- 

CREATE TABLE `jos_sci_project_uneix` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(255) NOT NULL,
  `short_description` varchar(100) NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

-- 
-- Dumping data for table `jos_sci_project_uneix`
-- 

INSERT INTO `jos_sci_project_uneix` (`id`, `description`, `short_description`, `order`) VALUES 
(1, 'No', 'No', 1),
(2, 'Agrotecnio Centre de Recerca en Agrotecnologia', 'Agrotecnio Centre de Recerca en Agrotecnologia', 2),
(3, 'CED Centre d''Estudis Demogràfics', 'CED Centre d''Estudis Demogràfics', 3),
(4, 'CIMNE Centre Internacional de Mètodes Numèrics en Enginyeria', 'CIMNE Centre Internacional de Mètodes Numèrics en Enginyeria', 4),
(5, 'CMR[B] Centre de Medicina Regenerativa de Barcelona', 'CMR[B] Centre de Medicina Regenerativa de Barcelona', 5),
(6, 'CRAG Centre de Recerca en Agrigenòmica', 'CRAG Centre de Recerca en Agrigenòmica', 6),
(7, 'CREAF Centre de Recerca Ecològica i Aplicacions Forestals', 'CREAF Centre de Recerca Ecològica i Aplicacions Forestals', 7),
(8, 'CREAL Centre de Recerca en Epidemiologia Ambiental', 'CREAL Centre de Recerca en Epidemiologia Ambiental', 8),
(9, 'CREI Centre de Recerca en Economia Internacional', 'CREI Centre de Recerca en Economia Internacional', 9),
(10, 'CReSA Centre de Recerca en Sanitat Animal', 'CReSA Centre de Recerca en Sanitat Animal', 10),
(11, 'CRESIB Centre de Recerca en Salut Internacional de Barcelona', 'CRESIB Centre de Recerca en Salut Internacional de Barcelona', 11),
(12, 'CRG Centre de Regulació Genòmica', 'CRG Centre de Regulació Genòmica', 12),
(13, 'CRM Centre de Recerca Matemàtica', 'CRM Centre de Recerca Matemàtica', 13),
(14, 'CTFC Centre Tecnològic Forestal de Catalunya', 'CTFC Centre Tecnològic Forestal de Catalunya', 14),
(15, 'CTTC Centre Tecnològic de Telecomunicacions de Catalunya', 'CTTC Centre Tecnològic de Telecomunicacions de Catalunya', 15),
(16, 'CVC Centre de Visió per Computador', 'CVC Centre de Visió per Computador', 16),
(17, 'i2CAT Internet i Innovació Digital a Catalunya', 'i2CAT Internet i Innovació Digital a Catalunya', 17),
(18, 'IBEC Institut de Bioenginyeria de Catalunya', 'IBEC Institut de Bioenginyeria de Catalunya', 18),
(19, 'IC3 Institut Català de Ciències del Clima', 'IC3 Institut Català de Ciències del Clima', 19),
(20, 'ICAC Institut Català d''Arqueologia Clàssica', 'ICAC Institut Català d''Arqueologia Clàssica', 20),
(21, 'ICCC Institut Català de Ciències Cardiovasculars', 'ICCC Institut Català de Ciències Cardiovasculars', 21),
(22, 'ICFO Institut de Ciències Fotòniques', 'ICFO Institut de Ciències Fotòniques', 22),
(23, 'ICIQ Institut Català d''Investigació Química', 'ICIQ Institut Català d''Investigació Química', 23),
(24, 'ICN Institut Català de Nanotecnologia', 'ICN Institut Català de Nanotecnologia', 24),
(25, 'ICP Institut Català de Paleontologia Miquel Crusafont', 'ICP Institut Català de Paleontologia Miquel Crusafont', 25),
(26, 'ICRA Institut Català de Recerca de l''Aigua', 'ICRA Institut Català de Recerca de l''Aigua', 26),
(27, 'ICRPC Institut Català de Recerca en Patrimoni Cultural', 'ICRPC Institut Català de Recerca en Patrimoni Cultural', 27),
(28, 'IDIBAPS Institut d''Investigacions Biomèdiques August Pi i Sunyer', 'IDIBAPS Institut d''Investigacions Biomèdiques August Pi i Sunyer', 28),
(29, 'IDIBELL Institut d''Investigació Biomèdica de Bellvitge', 'IDIBELL Institut d''Investigació Biomèdica de Bellvitge', 29),
(30, 'IDIBGI Institut d''Investigació Biomèdica de Girona Dr. Josep Trueta', 'IDIBGI Institut d''Investigació Biomèdica de Girona Dr. Josep Trueta', 30),
(31, 'IEEC Institut d''Estudis Espacials de Catalunya', 'IEEC Institut d''Estudis Espacials de Catalunya', 31),
(32, 'IFAE Institut de Física d''Altes Energies', 'IFAE Institut de Física d''Altes Energies', 32),
(33, 'IG Institut de Geomàtica', 'IG Institut de Geomàtica', 33),
(34, 'IGTP Institut d''Investigació en Ciències de la Salut Germans Trias i Pujol', 'IGTP Institut d''Investigació en Ciències de la Salut Germans Trias i Pujol', 34),
(35, 'IISPV Institut d''Investigació Sanitària Pere Virgili', 'IISPV Institut d''Investigació Sanitària Pere Virgili', 35),
(36, 'IJC Institut de Recerca Contra la Leucèmia Josep Carreras', 'IJC Institut de Recerca Contra la Leucèmia Josep Carreras', 36),
(37, 'IMIM Institut Hospital del Mar d''Investigacions Mèdiques', 'IMIM Institut Hospital del Mar d''Investigacions Mèdiques', 37),
(38, 'IMPPC Institut de Medicina Predictiva i Personalitzada del Càncer', 'IMPPC Institut de Medicina Predictiva i Personalitzada del Càncer', 38),
(39, 'IPHES Institut Català de Paleoecologia Humana i Evolució Social', 'IPHES Institut Català de Paleoecologia Humana i Evolució Social', 39),
(40, 'IR-Sant Pau Institut de Recerca de l''Hospital de la Santa Creu i Sant Pau', 'IR-Sant Pau Institut de Recerca de l''Hospital de la Santa Creu i Sant Pau', 40),
(41, 'IRB Lleida Institut de Recerca Biomèdica de Lleida', 'IRB Lleida Institut de Recerca Biomèdica de Lleida', 41),
(42, 'IREC Institut de Recerca en Energia de Catalunya', 'IREC Institut de Recerca en Energia de Catalunya', 42),
(43, 'IrsiCaixa Institut de Recerca de la Sida', 'IrsiCaixa Institut de Recerca de la Sida', 43),
(44, 'IRTA Institut de Recerca i Tecnologia Agroalimentaria', 'IRTA Institut de Recerca i Tecnologia Agroalimentaria', 44),
(45, 'MOVE Markets, Organizations and Votes in Economics', 'MOVE Markets, Organizations and Votes in Economics', 45),
(46, 'VHIO Vall d''Hebron Institut d''Oncologia', 'VHIO Vall d''Hebron Institut d''Oncologia', 46),
(47, 'VHIR Vall d''Hebron Institut de Recerca', 'VHIR Vall d''Hebron Institut de Recerca', 47),
(48, 'UNIVERSITAT DE BARCELONA', 'UNIVERSITAT DE BARCELONA', 48),
(49, 'UNIVERSITAT AUTÒNOMA DE BARCELONA', 'UNIVERSITAT AUTÒNOMA DE BARCELONA', 49),
(50, 'UNIVERSITAT POLITÈCNICA DE CATALUNYA', 'UNIVERSITAT POLITÈCNICA DE CATALUNYA', 50),
(51, 'UNIVERSITAT POMPEU FABRA', 'UNIVERSITAT POMPEU FABRA', 51),
(52, 'UNIVERSITAT DE GIRONA', 'UNIVERSITAT DE GIRONA', 52),
(53, 'UNIVERSITAT DE LLEIDA', 'UNIVERSITAT DE LLEIDA', 53),
(54, 'UNIVERSITAT ROVIRA I VIRGILI', 'UNIVERSITAT ROVIRA I VIRGILI', 54);