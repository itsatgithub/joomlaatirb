DROP TABLE `jos_sci_project_timings`;

ALTER TABLE `jos_sci_projects` DROP `timing_id`;

ALTER TABLE `jos_sci_projects` ADD `coordinator` VARCHAR( 255 ) NOT NULL AFTER `consortium`;

ALTER TABLE `jos_sci_projects` ADD `total_granted_budget` DECIMAL( 9, 2 ) NOT NULL AFTER `irb_code`;

UPDATE `joomlaatirb`.`jos_sci_group_leaders` SET `user_username` = 'institutional',
`name` = 'Institutional',
`research_group` = 'Institutional' WHERE `jos_sci_group_leaders`.`id` =39;
