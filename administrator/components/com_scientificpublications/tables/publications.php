<?php
/**
 * Joomla! 1.5 component
 *
 * @package Scientificpublications
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
class TablePublications extends JTable {

	var $id = null;
	var $type_id = null;
	var $title = null;
	var $authors = null;
	var $journal = null;
	var $issue = null;
	var $volume = null;
	var $pages = null;
	var $year = null;
	var $citations = null;
	var $published = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__ga_publications', 'id', $db);
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