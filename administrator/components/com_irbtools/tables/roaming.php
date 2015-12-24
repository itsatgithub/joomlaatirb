<?php
/**
 * Joomla! 1.5 component irbtools
 *
 * @version $Id: irbtools.php 2010-10-13 07:12:40 svn $
 * @author IRB Barcelona
 * @package Joomla
 * @subpackage irbtools
 * @license GNU/GPL
 *
 * IRB Barcelona Tools
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
* @subpackage		irbtools
*/
class TableRoaming extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $code = null;
	var $description = null;
	var $long_number = null;
	var $from = null;
	var $to = null;
	var $username = null;
	var $email = null;

    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__irbtools_roamings', 'id', $db);
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