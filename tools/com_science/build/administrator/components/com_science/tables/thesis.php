<?php
/**
 * Joomla! 1.5 component Science
 *
 * @version $Id: register.php 2009-07-07 09:14:21 svn $
 * @author
 * @package Joomla
 * @subpackage register
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
class TableThesis extends JTable {

	var $id = null;
	var $group_leader_id = null;
	var $title = null;
	var $author = null;
	var $university = null;
    var $doctoral_programme = null;
	var $year = null;
	var $lecture_date = null;
	var $language_id = null;
	var $director = null;
	var $codirector = null;
	var $tutor = null;
	var $honor_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_theses', 'id', $db);
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
