ALTER TABLE `jos_sci_projects` ADD `year_budget_year_6` VARCHAR( 4 ) NOT NULL AFTER `overheads_year_5` ,
ADD `budget_year_6` DECIMAL( 9, 2 ) NOT NULL AFTER `year_budget_year_6` ,
ADD `year_overheads_year_6` VARCHAR( 4 ) NOT NULL AFTER `budget_year_6` ,
ADD `overheads_year_6` DECIMAL( 9, 2 ) NOT NULL AFTER `year_overheads_year_6` 