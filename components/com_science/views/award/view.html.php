<?php
/**
 * Joomla! 1.5 component Science, Award view
 *
 * @version $Id: view.html.php 2009-10-16 08:00:35 svn $
 * @author GPL@vui
 * @package Joomla
 * @subpackage Science
 * @license GNU/GPL
 *
 * Scientific Production manager.
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.utilities.date');

/**
 * HTML View class for the Science component Award view
 */
class ScienceViewAward extends JView
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
		$this->_name = 'view_award';
	}

	/**
	 * Display view
	 *
	 * @since 1.5
	 */
	function display($tpl = null)
	{
		global $mainframe, $option;
			
		// Get some objects
		$model =& JModel::getInstance( 'award', 'sciencemodel' );
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$uri =& JFactory::getURI();
		$params =& $mainframe->getParams();

		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$rights = $sci_user->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}
		$javascript = ( $rights == 'write') ? "class='required validate-numeric'" : "" ;

		// set the id. 0 = new
		$id = JRequest::getVar( 'id' );
		if (!$id) {
			$id = 0;
		}

		// set the id
		$model->setId( $id );

		$award =& $model->getData();
		$this->assignRef('award', $award);

		// verify that the item exists and/or the user had the rights to read it
		if ( !$award->id && ($rights != 'write')) {
			echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
			return;
		}

		if ( !$award->id && ($sci_user->isGroupLeader($user->username))) {
			// 21-02-2011 if he is a GL, get the id of the assigned user
			//$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
			$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId', $sci_user->getAssigned($user->username));
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $my_group_leader_id);
		} else {
			$my_group_leader_id = $award->group_leader_id;
			//$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $award->group_leader_id);
		}

		//$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
		//$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName',$award->group_leader_id);

		$lists['groupleaders'] = JHTML::_('sciencehelper.getGroupLeadersList', 'group_leader_id', $award->group_leader_id, $javascript);
		$lists['year'] = JHTML::_('sciencehelper.getYearsListYear', 'year', $award->year, $javascript,false);
		$lists['end_year'] = JHTML::_('sciencehelper.getYearsListYear', 'end_year', $award->end_year, '',false);


		//Get Menu Item Parameters
		$menu_params =& $mainframe->getPageParameters();
		$this->assignRef('menu_params', $menu_params);

		// set the variables
		$this->assign('action', $uri->toString());
		$this->assign('rights', $rights);
		$this->assign('sci_user', $sci_user);
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('my_group_leader_id', $my_group_leader_id);
		$this->assignRef('selected_group_leader_name', $selected_group_leader_name);

		parent::display($tpl);
	}
}
?>