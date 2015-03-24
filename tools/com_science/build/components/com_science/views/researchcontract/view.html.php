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
class ScienceViewResearchcontract extends JView
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
		$this->_name = 'view_researchcontract';
	}

	function display($tpl = null)
	{
		global $mainframe, $option;

		// Get some objects
		$model =& JModel::getInstance( 'researchcontract', 'sciencemodel' );
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
		$javascript_non_numeric = ( $rights == 'write') ? "class='required validate'" : "" ;

		// set the id. 0 = new
		$id = JRequest::getVar( 'id' );
		if (!$id) {
			$id = 0;
		}

		// set the id
		$model->setId( $id );

		$project =& $model->getData();
		$this->assignRef('project', $project);

		// verify that the item exists and/or the user had the rights to read it
		if ( !$project->id && ($rights != 'write')) {
			echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
			return;
		}

		if ( !$project->id && ($sci_user->isGroupLeader($user->username))) {
			$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId', $sci_user->getAssigned($user->username));
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $my_group_leader_id);
		} else {
			$my_group_leader_id = $project->group_leader_id;
			//$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $project->group_leader_id);
		}

		$lists['groupleaders'] = JHTML::_('sciencehelper.getGroupLeadersList', 'group_leader_id', $project->group_leader_id, $javascript);
		
		// build list of funding sectors
		$query = 'SELECT fs.id AS value, fs.short_description AS text'
		. ' FROM #__sci_research_contracts_funding_sectors AS fs'
		. ' ORDER BY fs.order'
		;
		$db->setQuery($query);
		$fundingsectorslist[] = JHTML::_('select.option',  '', JText::_( '- Select Sector of Funding Organisation -' ), 'value', 'text');
		$fundingsectorslist = array_merge( $fundingsectorslist, $db->loadObjectList() );
		$lists['funding_sector'] = JHTML::_('select.genericlist', $fundingsectorslist, 'funding_sector_id', $javascript_non_numeric, 'value', 'text', $project->funding_sector_id );
		
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