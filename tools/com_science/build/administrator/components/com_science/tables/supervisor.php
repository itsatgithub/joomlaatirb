<?php
/**
 * Joomla! 1.5 component Science
 *
 * @version $Id:  2009-07-07 09:14:21 svn $
 * @author
 * @package Joomla
 * @subpackage Science
 * @license GNU/GPL
 *
 * Manages the Science Production
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
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
class TableSupervisor extends JTable {

	var $id = null;
	var $name = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_supervisors', 'id', $db);
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