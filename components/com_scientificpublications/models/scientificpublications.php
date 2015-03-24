<?php
/**
 * Joomla! 1.5 component ScientificPublications
 *
 * @version $Id: scientificpublications.php 2010-03-01 10:08:36 svn $
 * @author Gplavui
 * @package Joomla
 * @subpackage ScientificPublications
 * @license GNU/GPL
 *
 * Application to manage scientific publications on ajoomla site
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * ScientificPublications Component ScientificPublications Model
 *
 * @author      notwebdesign
 * @package		Joomla
 * @subpackage	ScientificPublications
 * @since 1.5
 */
class ScientificpublicationsModelScientificpublications extends JModel {

	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	function getData()
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT p.*, pt.description AS pt_description'
		. ' FROM `#__ga_publications` AS p'
		. ' LEFT JOIN `#__ga_publication_types` AS pt ON pt.id = p.type_id'
		. ' ORDER BY p.year'
		;

		$db->setQuery($query);
		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
}
?>