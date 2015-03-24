<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

/**
 * HTML View class for the Science component
 */
class IrbtoolsViewSirecs extends JView
{
	/**
	 * Abstract name
	 *
	 * @var name
	 */
	var $_name = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		// IMP: Set this always
		$this->_name = 'view_reports';
	}

	function display($tpl = null)
	{
		global $mainframe;

		$db	=& JFactory::getDBO();
		$user =& JFactory::getUser();

		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$rights = $sci_user->getRights($user->username, $this->_name);
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}

		// if the user is a GL we have to pass it to the window in order
		// to select/unselect the checkboxes
		if ($sci_user->isGroupLeader($user->username)) {
			$this->assign('groupLeaderName', $sci_user->getAssigned($user->username));
			$this->assign('isGroupLeader', true);
		} else {
			$this->assign('groupLeaderName', '');
			$this->assign('isGroupLeader', false);
		}

		// Add the Calendar includes to the document <head> section
		JHTML::_('behavior.calendar');

		// Get the page/component configuration
		$params =& $mainframe->getParams();
		$this->assignRef('params', $params);
			
		// build table of choices for the Programme/Group
		$query = 'SELECT *'
		. ' FROM `#__sci_group_leaders`'
		. ' ORDER BY report_order'
		;
		$db->setQuery($query);
		$gl_rows = $db->loadObjectList();
		$this->assign('gl_rows', $gl_rows);	

		// dates
		$date = new JDate();
		$this->assign('start_date', $date->toFormat('%Y-01-01'));
		$this->assign('end_date', $date->toFormat('%Y-%m-%d'));

		parent::display($tpl);
	}
}
?>