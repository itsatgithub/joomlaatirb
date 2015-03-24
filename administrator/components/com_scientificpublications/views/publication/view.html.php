<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class ScientificpublicationsViewPublication extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();

		$lists = array();

		// get the item data
		$model =& JModel::getInstance('Publication', 'ScientificpublicationsModel');
		//$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

		$id = JRequest::getVar('id');
		if ($id)
		{
			$model->setId($id);
			$item =& $model->getData();
			JRequest::setVar('edit', true);
		} else {
			$item = new stdClass();
			$item->published = 1;
			JRequest::setVar('edit', false);
		}

		// for the lists
		$javascript = ( $rights == 'write') ? "class='required validate-numeric'" : "" ;

		// build list of genders
		$query = 'SELECT pt.id AS value, pt.description AS text'
		. ' FROM `#__ga_publication_types` AS pt'
		. ' ORDER BY pt.order'
		;
		$db->setQuery($query);
		$ptlist[] = JHTML::_('select.option',  '', JText::_( '- Select Type -' ), 'value', 'text');
		$ptlist = array_merge( $ptlist, $db->loadObjectList() );
		$lists['publication_types'] = JHTML::_('select.genericlist', $ptlist, 'type_id', $javascript, 'value', 'text', $item->type_id );

		// build the html select list
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );

		// clean data
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'title' );

		$this->assignRef('lists', $lists);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}
