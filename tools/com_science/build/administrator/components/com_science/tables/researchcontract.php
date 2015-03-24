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
class TableResearchcontract extends JTable {

	var $id = null;
	var $group_leader_id = null;
	var $irb_code = null;
	var $acronym = null;
	var $company = null;
	var $principal_investigator = null;
	var $start_date = null;
	var $end_date = null;
	var $funding_sector_id = null;
	var $budget = null;
	var $overhead = null;
	var $comments = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_research_contracts', 'id', $db);
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