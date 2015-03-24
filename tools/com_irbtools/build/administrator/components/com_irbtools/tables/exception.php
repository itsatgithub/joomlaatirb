<?php
/**
 * @version		$Id: exception.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Irbtools
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Table class
 */
class TableException extends JTable
{
	var $id = null;
	var $command = null;
	var $irbpeople_user_id = null;
	var $name = null;
	var $surname1 = null;
	var $surname2 = null;
	var $gender = null;
	var $department = null;
	var $unit = null;
	var $research_group = null;
	var $email = null;
	var $phone = null;
	var $position = null;
	var $location = null;
	var $second_affiliation = null;
	var $modified = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__irbtools_exceptions', 'id', $db);
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