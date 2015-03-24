--//

ALTER TABLE `jos_sci_publication_group_leader` ADD `selected_extranet` TINYINT NOT NULL
ALTER TABLE `jos_sci_publications` DROP `selected_extranet`

--//@UNDO

ALTER TABLE `jos_sci_publication_group_leader` DROP `selected_extranet`
ALTER TABLE `jos_sci_publications` ADD `selected_extranet` TINYINT NOT NULL

--//
 