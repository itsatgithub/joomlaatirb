<?php
/**
 * Joomla! 1.5 component irbgoogle
 *
 * @version $Id: view.html.php 2009-10-29 09:30:44 svn $
 * @author
 * @package Joomla
 * @subpackage irbgoogle
 * @license GNU/GPL
 *
 *
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class IrbgoogleViewDefault extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		// Initialize some variables
		$db =& JFactory::getDBO();
		$uri =& JFactory::getURI();
		$user =& JFactory::getUser();
		$model =& JModel::getInstance( 'irbgoogle', 'irbgooglemodel' );

		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'i.name', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_sedarch = $mainframe->getUserStateFromRequest( $option.'filter_search', 'filter_search', '', 'string' );
		$filter_search = JString::strtolower( $filter_search );

		// get data from Google
		$model->retrieveAllEmailLists();
		// cleaning data on the db
		$model->cleanAllEmailLists();

		// Get some data from the model
		$items =& $model->getData();
		$total =& $model->getTotal();
		$pagination	=& $model->getPagination();
			
		// prepare list array
		$lists = array();
		$javascript = 'onchange="document.adminForm.submit();"';

		// set the values
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['search'] = $filter_search;

		// preparing items to display
		$k = 0;
		$count = count($items);
		for($i = 0; $i < $count; $i++)
		{
			$item =& $items[$i];
			$item->odd = $k;
			$item->count = $i;
			$k = 1 - $k;
		}

		// push data into the template
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('items', $items);
		$this->assignRef('total', $total);
		$this->assignRef('pagination', $pagination);

		// ordering action
		$this->assign('action',	$uri->toString());

		parent::display($tpl);
	}
}
?>