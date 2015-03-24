<?php
/**
 * Joomla! 1.5 component Science
 *
 * @version $Id: view.html.php 2009-10-16 08:00:35 svn $
 * @author GPL@vui
 * @package Joomla
 * @subpackage Science
 * @license GNU/GPL
 *
 * Scientific Production manager.
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class ScienceViewJournals extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		$db	=& JFactory::getDBO();
		$uri =& JFactory::getURI();

		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'order',	'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'ASC', 'word' );
		$search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		$search = JString::strtolower( $search );

		// sanitize $filter_order
		if (!in_array($filter_order, array('description', 'short_description', 'order'))) {
			$filter_order = 'description';
		}

		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
			$filter_order_Dir = '';
		}
		
		// Get data from the model
		$items = & $this->get( 'Data');
		$total = & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

		// table ordering
		$lists = array();
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('lists', $lists);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}
?>