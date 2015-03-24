--
-- Dumping data for table `jos_sci_collaborations`
--
TRUNCATE `jos_sci_collaborations`;

INSERT INTO `jos_sci_collaborations` (`id`, `group_leader_id`, `title`, `start_year`, `end_year`, `modified`) VALUES
(3, 15, 'Antonio Celada Collaboration', '2006', '2007', '2010-06-11 06:36:27'),
(4, 1, 'Ferran Azorin Collaboration', '2006', '2007', '2010-06-11 06:37:59');

--
-- Dumping data for table `jos_sci_collaborations_collaboration_levels`
--
TRUNCATE `jos_sci_collaborations_collaboration_levels`;

INSERT INTO `jos_sci_collaborations_collaboration_levels` (`id`, `collaboration_id`, `collaboration_level_id`) VALUES
(5, 3, 1),
(3, 4, 2);

--
-- Dumping data for table `jos_sci_collaborations_collaboration_sectors`
--
TRUNCATE `jos_sci_collaborations_collaboration_sectors`;

INSERT INTO `jos_sci_collaborations_collaboration_sectors` (`id`, `collaboration_id`, `collaboration_sector_id`) VALUES
(5, 3, 1),
(3, 4, 2);

--
-- Dumping data for table `jos_sci_collaborations_collaboration_types`
--
TRUNCATE `jos_sci_collaborations_collaboration_types`;

INSERT INTO `jos_sci_collaborations_collaboration_types` (`id`, `collaboration_id`, `collaboration_type_id`) VALUES
(3, 4, 2),
(7, 3, 2);

--
-- Dumping data for table `jos_sci_collaboration_details`
--
TRUNCATE `jos_sci_collaboration_details`;

INSERT INTO `jos_sci_collaboration_details` (`id`, `collaboration_id`, `research_name`, `institution`, `city`, `country_id`) VALUES
(3, 4, 'John Mayor', 'Univ. London', 'London', 225),
(4, 3, 'Inger Smith', 'Unvi. Edinburg', 'Edinburgh', 225);

--
-- Dumping data for table `jos_sci_patents`
--
TRUNCATE `jos_sci_patents`;

INSERT INTO `jos_sci_patents` (`group_leader_id`, `title`, `inventors`, `publication_number`, `publication_date`, `patent_status_id`, `modified`) VALUES
(15, 'Antonio Celada Patent', 'Andrés Cañadas, Lucio Tendre', 'Pub-123', '2006-08-01', 3, '2010-06-11 06:51:08'),
(1, 'Ferran Azorin Ferran Azorin', 'Daniel Lebran', 'App-321', '2010-09-01', 2, '2010-06-11 06:52:33');

--
-- Dumping data for table `jos_sci_projects`
--
TRUNCATE `jos_sci_projects`;

INSERT INTO `jos_sci_projects` (`group_leader_id`, `principal_investigator`, `beneficiary`, `title`, `acronym`, `reference`, `start_date`, `end_date`, `irb_code`, `total_budget`, `overheads_total_budget`, `year_budget_year_1`, `budget_year_1`, `year_overheads_year_1`, `overheads_year_1`, `year_budget_year_2`, `budget_year_2`, `year_overheads_year_2`, `overheads_year_2`, `year_budget_year_3`, `budget_year_3`, `year_overheads_year_3`, `overheads_year_3`, `year_budget_year_4`, `budget_year_4`, `year_overheads_year_4`, `overheads_year_4`, `year_budget_year_5`, `budget_year_5`, `year_overheads_year_5`, `overheads_year_5`, `funding_entity_id`, `funding_entity_specific`, `grant_type_id`, `owner_id`, `call`, `timing_id`, `action_type_id`, `role_id`, `consortium`, `modified`) VALUES
(1, 'F. Azorin', 'candidate', 'Demo de proyecto', 'acro', 'ref', '2006-06-01', '2010-06-01', 'code', 15000.00, 500.00, '2006', 1000.00, '2006', 100.00, '2007', 2000.00, '2007', 100.00, '2008', 3000.00, '2008', 100.00, '2009', 4000.00, '2009', 100.00, '2010', 5000.00, '2010', 100.00, 34, 'This is the particular Entity', 1, 1, 'call 123', 1, 1, 1, 'The Consortium', '2010-06-11 06:41:47'),
(15, 'Antonio Celada', 'Andrew Barrimore', 'Antonio Celada''s  proyect ', 'ABC-123', 'REF-321', '2010-01-01', '2010-12-31', 'code-123', 2500.00, 0.00, '2010', 2500.00, '2010', 0.00, '', 0.00, '', 0.00, '', 0.00, '', 0.00, '', 0.00, '', 0.00, '', 0.00, '', 0.00, 14, '', 9, 1, 'call abc', 1, 2, 2, 'EPFL', '2010-06-11 06:45:39');

--
-- Dumping data for table `jos_sci_publications` IMPORTANT!!!DO NOT CHANGE publication_id
--
TRUNCATE `jos_sci_publications`;

INSERT INTO `jos_sci_publications` (`id`, `type_id`, `pubmed_id`, `epub`, `title`, `authors`, `journal`, `issue`, `volume`, `pages`, `year`, `coauthors_type_id`, `book_title`, `volume_title`, `editor`, `publisher`, `group_contribution_id`, `citations`, `joint_publication`, `end_date`, `modified`) VALUES
(3, 1, '17406789', '2007 Apr 4', 'Gliosarcoma with primitive neuroectodermal differentiation: case report and review of the literature.', 'Kaplan KJ and Perry A', 'Aatcc Rev', '10', '10', '10-10', '2007', 1, '', '', '', '', 2, '10.1007/s11060-007-9', 0, '', '2010-06-09 19:09:23'),
(4, 1, '16479517', '2006 Feb 8', 'Antibody persistence 3 years after immunization of adolescents with quadrivalent meningococcal conjugate vaccine.', 'Vu DM, Welsch JA, Zuno-Mitchell P, Dela Cruz JV and Granoff DM', 'J Infect Dis', '10', '11', '10-10', '2006', 1, '', '', '', '', 3, '10.1086/500512', 0, '', '2010-03-31 19:19:15');

--
-- Dumping data for table `jos_sci_publication_group_leader` IMPORTANT!!!DO NOT CHANGE publication_id
--
TRUNCATE `jos_sci_publication_group_leader`;

INSERT INTO `jos_sci_publication_group_leader` (`publication_id`, `group_leader_id`) VALUES
(3, 1),
(4, 15);

--
-- Dumping data for table `jos_sci_theses`
--
TRUNCATE `jos_sci_theses`;

INSERT INTO `jos_sci_theses` (`id`, `group_leader_id`, `title`, `author`, `university`, `year`, `lecture_date`, `language_id`, `director`, `codirector`, `tutor`, `honor_id`, `modified`) VALUES
(1, 15, 'Thesis Antonio Celada', 'Alvaro Castro', 'Univ de La Habana', '2010', '2010-06-01', 1, 'Antonio Celada', 'None', 'Mark Lagrange', 3, '2010-06-11 06:47:24'),
(2, 1, 'Thesis Ferran Azorin', 'Nomiro Shazuka', 'Univ. de Tokio', '2009', '2009-08-07', 1, 'Shitoshi Akiro', 'None', 'Ferran Azorin', 3, '2010-06-11 06:49:32');

--
-- Dumping data for table `jos_sci_awards`
--
TRUNCATE `jos_sci_awards`;

INSERT INTO `jos_sci_awards` (`group_leader_id`, `title`, `awarding_body`, `awardee`, `year`, `modified`) VALUES
(15, 'Antonio Celada Award', 'Antonio Celada Body', 'Antonio Celada Awardee', '2010', '2010-06-11 06:53:20'),
(1, 'Ferran Azorin Title', 'Ferran Azorin Body', 'Ferran Azorin Awardee', '2010', '2010-06-11 06:53:59');
