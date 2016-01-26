<?php
/**
 * @version		$Id: emaillog.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Irbtools
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Table class
 */
class TableEmaillog extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__irbtools_email_log', 'id', $db);
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