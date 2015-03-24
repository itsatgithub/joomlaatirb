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
class TablePublication extends JTable {

	var $id = null;
	var $type_id = null;
	var $pubmed_id = null;
	var $epub = null;
	var $title = null;
	var $authors = null;
	var $journal = null;
	var $issue = null;
	var $volume = null;
	var $pages = null;
	var $year = null;
	var $coauthors_type_id = null;
	var $book_title = null;
	var $volume_title = null;
	var $editor = null;
	var $publisher = null;
	var $date_of_print = null;
	var $group_contribution_id = null;
	var $citations = null;
	var $joint_publication = null;
	// Roberto 2012-03-15 este campo ya no esta en esta tabla 
	//var $selected_extranet = null;
	var $end_date = null;
	// @todo is group_leader_id needed?? It is an issue on the store.
	//var $group_leader_id = null; // coming from the join table
	var $phd_selected_publication = null;
	var $irb_selected_publication = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_publications', 'id', $db);
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