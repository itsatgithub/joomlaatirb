ALTER TABLE `jos_irbtools_exceptions` CHANGE `surname` `surname1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `jos_irbtools_exceptions` ADD `surname2` VARCHAR( 255 ) NOT NULL AFTER `surname1`;
