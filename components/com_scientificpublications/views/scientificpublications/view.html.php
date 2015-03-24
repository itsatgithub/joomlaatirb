<?php
/**
 * Joomla! 1.5 component ScientificPublications
 *
 * @version $Id: view.html.php 2010-03-01 10:08:36 svn $
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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the ScientificPublications component
 */
class ScientificpublicationsViewScientificpublications extends JView {
	function display($tpl = null) {

		global $mainframe;

		$db =& JFactory::getDBO();
		$params =& $mainframe->getParams();

		//Get Menu Item Parameters
		$menu_params =& $mainframe->getPageParameters();

		$display_paper_type = $menu_params->get('display_paper_type');
		$parameters['filter_paper_type'] = $menu_params->get('filter_paper_type');
		$parameters['paper_type'] = $menu_params->get('paper_type');

		if ($menu_params->get('filter_paper_type')){
			$where = "AND p.type_id = ".$menu_params->get('paper_type');
		} else {
			$where = "";
		}

		//get data from the request
		$get = JRequest::get( 'get' );
		if (isset($get['year'])){
			$where = "AND p.year = ".$get['year'];
		} else {
			$where = "";
		}

		// build list of publications
		$query = 'SELECT p.*, pt.description as type'
		. ' FROM `#__ga_publications` AS p'
		. ' LEFT JOIN `#__ga_publication_types` AS pt'
		. ' ON p.type_id = pt.id '
		. ' WHERE p.published = 1 '
		. $where
		. ' ORDER BY p.year DESC, p.title'
		;
		$db->setQuery($query);
		$publicationlist = $db->loadObjectList();

		$this->assignRef('display_paper_type', $display_paper_type);
		$this->assignRef('publicationlist', $publicationlist);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
?>