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

jimport('joomla.application.component.view');
jimport('joomla.utilities.date');

/**
 * HTML View class for the Science component
 */
class ScienceViewPatent extends JView
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
		$this->_name = 'view_patent';
	}

	function display($tpl = null)
	{
		global $mainframe, $option;
			
		// Get some objects
		$model =& JModel::getInstance( 'patent', 'sciencemodel' );
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

		$patent =& $model->getData();
		$this->assignRef('patent', $patent);

		// verify that the item exists and/or the user had the rights to read it
		/*if ( !$patent->id && $sci_user->isReader($user->username)) {
		echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
		return;
		}

		$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
		$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName',$patent->group_leader_id);*/

		// verify that the item exists and/or the user had the rights to read it
		if ( !$patent->id && ($rights != 'write')) {
			echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
			return;
		}

		if ( !$patent->id && ($sci_user->isGroupLeader($user->username))) {
			$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId', $sci_user->getAssigned($user->username));
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $my_group_leader_id);
		} else {
			$my_group_leader_id = $patent->group_leader_id;
			//$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $patent->group_leader_id);
		}

		// build list of status
		$query = 'SELECT ps.id AS value, ps.short_description AS text'
		. ' FROM `#__sci_patent_status` AS ps'
		. ' ORDER BY ps.order'
		;
		$db->setQuery($query);
		$statuslist[] = JHTML::_('select.option',  '', JText::_( '- Select Patent Status -' ), 'value', 'text');
		$statuslist = array_merge( $statuslist, $db->loadObjectList() );
		$lists['status'] = JHTML::_('select.genericlist', $statuslist, 'patent_status_id', $javascript, 'value', 'text', $patent->patent_status_id );

		$lists['groupleaders'] = JHTML::_('sciencehelper.getGroupLeadersList', 'group_leader_id', $patent->group_leader_id, $javascript);

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