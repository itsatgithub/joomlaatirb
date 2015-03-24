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
class TableCollaborationDetails extends JTable {

	var $id = null;
	var $collaboration_id = null;
	var $research_name = null;
	var $institution = null;
	var $city = null;
	var $country_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_collaboration_details', 'id', $db);
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