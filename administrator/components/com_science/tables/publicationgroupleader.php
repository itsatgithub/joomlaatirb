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
class TablePublicationgroupleader extends JTable {

	var $id = null;
	var $publication_id = null;
	var $group_leader_id = null;
	// Roberto 2012-03-15 Añado selected_extranet campo en la tabla
	var $selected_extranet = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_publication_group_leader', 'id', $db);
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