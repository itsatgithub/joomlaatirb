--//

DROP TABLE IF EXISTS `jos_irbtools_users`;
CREATE TABLE `jos_irbtools_users` (
  `username` VARCHAR( 20 ) NOT NULL ,
  PRIMARY KEY ( `username` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `jos_irb_apps`
--

DROP TABLE IF EXISTS `jos_irbtools_apps`;
CREATE TABLE `jos_irbtools_apps` (
  `appname` VARCHAR( 20 ) NOT NULL ,
  PRIMARY KEY ( `appname` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `jos_irb_user_app`
--

DROP TABLE IF EXISTS `jos_irbtools_user_app`;
CREATE TABLE IF NOT EXISTS `jos_irbtools_user_app` (
  `username` varchar(20) NOT NULL,
  `appname` varchar(20) NOT NULL,
  PRIMARY KEY  (`username`,`appname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--//@UNDO

DROP TABLE IF EXISTS `jos_irbtools_users`;
DROP TABLE IF EXISTS `jos_irbtools_apps`;
DROP TABLE IF EXISTS `jos_irbtools_user_app`;

--//
