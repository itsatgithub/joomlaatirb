ALTER TABLE `jos_sci_theses` CHANGE `author` `author` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `jos_sci_theses` CHANGE `university` `university` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `jos_sci_theses` CHANGE `director` `director` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `jos_sci_theses` CHANGE `codirector` `codirector` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `jos_sci_theses` CHANGE `tutor` `tutor` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `jos_sci_theses` ADD `doctoral_programme` VARCHAR( 255 ) NOT NULL AFTER `university` ;
INSERT INTO `jos_sci_thesis_honors` (
`id` ,
`description` ,
`short_description` ,
`order`
)
VALUES (
'5', 'Excellent Cum Laude', 'Excellent Cum Laude', '5'
);
