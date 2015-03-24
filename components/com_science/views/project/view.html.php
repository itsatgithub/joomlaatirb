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
class ScienceViewProject extends JView
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
		$this->_name = 'view_project';
	}

	function display($tpl = null)
	{
		global $mainframe, $option;

		//Add javascript for autocurrency
		/*$document = &JFactory::getDocument();
		$document->addScript( '/components/com_science/assets/autoCurrency.js' );*/

		// Get some objects
		$model =& JModel::getInstance( 'project', 'sciencemodel' );
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
		/*if ( !$project->id && $sci_user->isReader($user->username)) {
		echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
		return;
		}

		$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId',$user->username);
		$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName',$project->group_leader_id);*/

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

		// build list of action_types
		$query = 'SELECT at.id AS value, at.short_description AS text'
		. ' FROM #__sci_project_action_types AS at'
		. ' ORDER BY at.order'
		;
		$db->setQuery($query);
		$actiontypeslist[] = JHTML::_('select.option',  '', JText::_( '- Select Action Type -' ), 'value', 'text');
		$actiontypeslist = array_merge( $actiontypeslist, $db->loadObjectList() );
		$lists['actiontypes'] = JHTML::_('select.genericlist', $actiontypeslist, 'action_type_id', '', 'value', 'text', $project->action_type_id );

		// build list of grant_types
		$query = 'SELECT gt.id AS value, gt.short_description AS text'
		. ' FROM #__sci_project_grant_types AS gt'
		. ' ORDER BY gt.order'
		;
		$db->setQuery($query);
		$granttypeslist[] = JHTML::_('select.option',  '', JText::_( '- Select Grant Type -' ), 'value', 'text');
		$granttypeslist = array_merge( $granttypeslist, $db->loadObjectList() );
		$lists['granttypes'] = JHTML::_('select.genericlist', $granttypeslist, 'grant_type_id', $javascript_non_numeric, 'value', 'text', $project->grant_type_id );

		// build list of owners
		$query = 'SELECT o.id AS value, o.short_description AS text'
		. ' FROM #__sci_project_owners AS o'
		. ' ORDER BY o.order'
		;
		$db->setQuery($query);
		$ownerslist[] = JHTML::_('select.option',  '', JText::_( '- Select Owner -' ), 'value', 'text');
		$ownerslist = array_merge( $ownerslist, $db->loadObjectList() );
		$lists['owners'] = JHTML::_('select.genericlist', $ownerslist, 'owner_id', $javascript, 'value', 'text', $project->owner_id );

		// build list of roles
		$query = 'SELECT r.id AS value, r.short_description AS text'
		. ' FROM #__sci_project_roles AS r'
		. ' ORDER BY r.order'
		;
		$db->setQuery($query);
		$roleslist[] = JHTML::_('select.option',  '', JText::_( '- Select Role -' ), 'value', 'text');
		$roleslist = array_merge( $roleslist, $db->loadObjectList() );
		$lists['roles'] = JHTML::_('select.genericlist', $roleslist, 'role_id', '', 'value', 'text', $project->role_id );

		// build list of fundinf entities
		$query = 'SELECT f.id AS value, f.short_description AS text'
		. ' FROM #__sci_project_funding_entities AS f'
		. ' ORDER BY f.order'
		;
		$db->setQuery($query);
		$fundingentitieslist[] = JHTML::_('select.option',  '', JText::_( '- Select Funding Entity -' ), 'value', 'text');
		$fundingentitieslist = array_merge( $fundingentitieslist, $db->loadObjectList() );
		$lists['fundingentities'] = JHTML::_('select.genericlist', $fundingentitieslist, 'funding_entity_id', $javascript_non_numeric, 'value', 'text', $project->funding_entity_id );

		// build list of uneix
		$query = 'SELECT f.id AS value, f.short_description AS text'
		. ' FROM #__sci_project_uneix AS f'
		. ' ORDER BY f.order'
		;
		$db->setQuery($query);
		$uneixlist[] = JHTML::_('select.option',  '', JText::_( '- Select Entity -' ), 'value', 'text');
		$uneixlist = array_merge( $uneixlist, $db->loadObjectList() );
		$lists['uneix'] = JHTML::_('select.genericlist', $uneixlist, 'uneix_id', $javascript_non_numeric, 'value', 'text', $project->uneix_id );
		
		// Roberto 2013-05-14 Añado la lista de IP
		$query = 'SELECT pi.id AS value, pi.name AS text'
		. ' FROM #__sci_principal_investigators AS pi'
		. ' ORDER BY pi.name'
		;
		$db->setQuery($query);
		$pilist[] = JHTML::_('select.option',  '', JText::_( '- Select Principal Investigators -' ), 'value', 'text');
		$pilist = array_merge( $pilist, $db->loadObjectList() );
		$lists['principal_investigators'] = JHTML::_('select.genericlist', $pilist, 'principal_investigator_id', $javascript_non_numeric, 'value', 'text', $project->principal_investigator_id );
		
		$lists['groupleaders'] = JHTML::_('sciencehelper.getGroupLeadersList', 'group_leader_id', $project->group_leader_id, $javascript);
		$lists['year_budget_year_1'] = JHTML::_('sciencehelper.getYearsListYear', 'year_budget_year_1', $project->year_budget_year_1, '', false);
		$lists['year_overheads_year_1'] = JHTML::_('sciencehelper.getYearsListYear', 'year_overheads_year_1', $project->year_overheads_year_1, '', false);
		$lists['year_budget_year_2'] = JHTML::_('sciencehelper.getYearsListYear', 'year_budget_year_2', $project->year_budget_year_2, '', false);
		$lists['year_overheads_year_2'] = JHTML::_('sciencehelper.getYearsListYear', 'year_overheads_year_2', $project->year_overheads_year_2, '', false);
		$lists['year_budget_year_3'] = JHTML::_('sciencehelper.getYearsListYear', 'year_budget_year_3', $project->year_budget_year_3, '', false);
		$lists['year_overheads_year_3'] = JHTML::_('sciencehelper.getYearsListYear', 'year_overheads_year_3', $project->year_overheads_year_3, '', false);
		$lists['year_budget_year_4'] = JHTML::_('sciencehelper.getYearsListYear', 'year_budget_year_4', $project->year_budget_year_4, '', false);
		$lists['year_overheads_year_4'] = JHTML::_('sciencehelper.getYearsListYear', 'year_overheads_year_4', $project->year_overheads_year_4, '', false);
		$lists['year_budget_year_5'] = JHTML::_('sciencehelper.getYearsListYear', 'year_budget_year_5', $project->year_budget_year_5, '', false);
		$lists['year_overheads_year_5'] = JHTML::_('sciencehelper.getYearsListYear', 'year_overheads_year_5', $project->year_overheads_year_5, '', false);
		$lists['year_budget_year_6'] = JHTML::_('sciencehelper.getYearsListYear', 'year_budget_year_6', $project->year_budget_year_6, '', false);
		$lists['year_overheads_year_6'] = JHTML::_('sciencehelper.getYearsListYear', 'year_overheads_year_6', $project->year_overheads_year_6, '', false);
		
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