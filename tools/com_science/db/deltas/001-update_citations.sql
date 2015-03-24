--//

ALTER TABLE `jos_sci_publications` CHANGE `citations` `citations` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

--//@UNDO

ALTER TABLE `jos_sci_publications` CHANGE `citations` `citations` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

--//
 