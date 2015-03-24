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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the irbgoogle component
 */
class IrbgoogleViewDefault extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		// Initialize some variables
		$db =& JFactory::getDBO();
		$uri =& JFactory::getURI();
		$model =& JModel::getInstance( 'irbgoogle', 'irbgooglemodel' );
		$user	=& JFactory::getUser();

		if (empty($user->id)) {
			echo JText::_( 'ALERTNOTAUTH' );
			return false;
		}

		// Get the page/component configuration
		$params =& $mainframe->getParams();

		// Get some data from the model
		$items =& $model->getData();

		// get the lists the user is subscribed to
		$emaillists = $model->retrieveEmailLists( $user->email );
		$arr_emaillists = array();
		foreach ($emaillists as $list)
		{
			$arr_emaillists[] = $list->emaillist->name;
		}

		// preparing items to display
		$k = 0;
		$count = count($items);
		for($i = 0; $i < $count; $i++)
		{
			$item =& $items[$i];
			$item->odd = $k;
			$item->count = $i;
			$k = 1 - $k;
				
			// setting up the subscribed attribute
			if ( in_array($item->name, $arr_emaillists) ) {
				$item->subscribed = 1;
			} else {
				$item->subscribed = 0;
			}
		}

		// push data into the template
		$this->assignRef('user', $user);
		$this->assignRef('params', $params);
		$this->assignRef('items', $items);

		// ordering action
		$this->assign('action',	$uri->toString());

		parent::display($tpl);
	}
}
?>