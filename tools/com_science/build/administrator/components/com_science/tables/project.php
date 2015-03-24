<?php
/**
 * Joomla! 1.5 component Science
 *
 * @package Science
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Table class
 *
 * @package          Joomla
 * @subpackage		Science
 */
class TableProject extends JTable {

	var $id = null;
	var $group_leader_id = null;
	var $principal_investigator = null;
	var $principal_investigator_id = null;
	var $beneficiary = null;
	var $title = null;
	var $acronym = null;
	var $reference = null;
	var $awarding_date = null;
	var $start_date = null;
	var $end_date = null;
	var $irb_code = null;
	var $total_granted_budget = null;
	var $total_budget = null;
	var $overheads_total_budget = null;
	var $year_budget_year_1 = null;
	var $budget_year_1 = null;
	var $year_overheads_year_1 = null;
	var $overheads_year_1 = null;
	var $year_budget_year_2 = null;
	var $budget_year_2 = null;
	var $year_overheads_year_2 = null;
	var $overheads_year_2 = null;
	var $year_budget_year_3 = null;
	var $budget_year_3 = null;
	var $year_overheads_year_3 = null;
	var $overheads_year_3 = null;
	var $year_budget_year_4 = null;
	var $budget_year_4 = null;
	var $year_overheads_year_4 = null;
	var $overheads_year_4 = null;
	var $year_budget_year_5 = null;
	var $budget_year_5 = null;
	var $year_overheads_year_5 = null;
	var $overheads_year_5 = null;
	var $year_budget_year_6 = null;
	var $budget_year_6 = null;
	var $year_overheads_year_6 = null;
	var $overheads_year_6 = null;
	var $funding_entity_id = null;
	var $funding_entity_specific = null;
	var $grant_type_id = null;
	var $grant_type_specific = null;
	var $owner_id = null;
	var $call = null;
	var $action_type_id = null;
	var $role_id = null;
	var $consortium = null;
	var $coordinator = null;
	var $uneix_id = null;
	var $comments = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_projects', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
		return true;
	}

}
?>