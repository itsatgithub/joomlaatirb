<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );


class JCronViewJCron extends JView
{
	
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'JCron Scheduler - View Tasks ' ), 'logo.png' );
        JToolBarHelper::publish();
        JToolBarHelper::unpublish();
        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
		JToolBarHelper::deleteList('Are you sure you want to delete selected items?');
		JToolBarHelper::preferences('com_jcron',250,400);
        JToolBarHelper::help('screen.users.jcron');

        JHTML::_('stylesheet','style.css','administrator/components/com_jcron/assets/');

		// Get data from the model
		$items		= & $this->get( 'Data');

		$this->assignRef('items',		$items);
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}