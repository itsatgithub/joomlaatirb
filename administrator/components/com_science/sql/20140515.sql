ALTER TABLE `jos_sci_projects` ADD `grant_type_specific` VARCHAR( 255 ) NULL AFTER `grant_type_id`; 
ALTER TABLE `jos_sci_projects` CHANGE `funding_entity_specific` `funding_entity_specific` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL; 
INSERT INTO `jos_sci_project_grant_types` (`id`, `description`, `short_description`, `order`) VALUES
(121, 'Others ( specify Grant/Fellowship )', 'Others  ( specify Grant/Fellowship )', 600),
(120, 'Others:Grants4Targets', 'Others - Grants4Targets', 590);
INSERT INTO `jos_sci_project_funding_entities` (
`id` ,
`description` ,
`short_description` ,
`order`
)
VALUES (
'76', 'Industry:Bayer Pharma AG', 'Industry - Bayer Pharma AG', '533');

