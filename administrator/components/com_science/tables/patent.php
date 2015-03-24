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
class TablePatent extends JTable {

	var $id = null;
	var $group_leader_id = null;
	var $title = null;
	var $inventors = null;
	var $publication_number = null;
	var $publication_date = null;
	var $patent_status_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_patents', 'id', $db);
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