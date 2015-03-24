CREATE TABLE IF NOT EXISTS `jos_sci_user_login_control` (
  `username` varchar(20) NOT NULL,
  `failed_login_attempts` tinyint(1) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;