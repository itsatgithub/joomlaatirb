ALTER TABLE `jos_sci_projects` ADD `principal_investigator_id` INT( 11 ) NOT NULL AFTER `principal_investigator`;
ALTER TABLE `jos_sci_projects` DROP `principal_investigator`;