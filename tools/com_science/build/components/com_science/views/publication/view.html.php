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
class ScienceViewPublication extends JView
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
		$this->_name = 'view_publication';
	}

	function display($tpl = null)
	{
		global $mainframe, $option;
			
		// Get some objects
		$model =& JModel::getInstance( 'publication', 'sciencemodel' );
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$uri =& JFactory::getURI();
		$params =& $mainframe->getParams();

		if ($this->getLayout() == 'other') {
			$this->getLayout() == 'paper';
		}

		if (($this->getLayout() == 'bookchapter') || ($this->getLayout() == 'paper')) {
			$this->_displayExtendedForm($tpl);
			return;
		}

		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$isadministrator = $sci_user->isAdministrator( $user->username );
		$rights = $sci_user->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}
		$javascript = ( $rights == 'write') ? "class='required validate-numeric'" : "" ;

		$publication =& $model->getData();
		$this->assignRef('publication', $publication);

		if ( (!$publication->id) && ($sci_user->isGroupLeader($user->username))) {
			$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId', $sci_user->getAssigned($user->username));
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $my_group_leader_id);
		}		
		
		// build list of types
		$query = 'SELECT pt.id AS value, pt.short_description AS text'
		. ' FROM `#__sci_publication_types` AS pt'
		. ' ORDER BY pt.order'
		;
		$db->setQuery($query);
		$publicationtypelist[] = JHTML::_('select.option',  '', JText::_( '- Select Publication Type -' ), 'value', 'text');
		$publicationtypelist = array_merge( $publicationtypelist, $db->loadObjectList() );
		$lists['publicationtypes'] = JHTML::_('select.genericlist', $publicationtypelist, 'type_id', $javascript, 'value', 'text', $publication->type_id );

		$lists['groupleaders'] = JHTML::_('sciencehelper.getGroupLeadersList', 'group_leader_id', '', $javascript);
		//$lists['groupleadersbis'] = JHTML::_('sciencehelper.getGroupLeadersListBis');

		//Get Menu Item Parameters
		$menu_params =& $mainframe->getPageParameters();
		$this->assignRef('menu_params', $menu_params);

		// set the variables
		$this->assign('action', $uri->toString());
		$this->assign('rights', $rights);
		$this->assign('sci_user', $sci_user);
		$this->assign('isadministrator', $isadministrator);
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('my_group_leader_id', $my_group_leader_id);
		$this->assignRef('selected_group_leader_name', $selected_group_leader_name);

		parent::display($tpl);
	}

	function _displayExtendedForm($tpl)
	{
		global $mainframe, $option;
			
		// Get some objects
		$model =& JModel::getInstance( 'publication', 'sciencemodel' );
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$uri =& JFactory::getURI();
		$params =& $mainframe->getParams();

		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$isadministrator = $sci_user->isAdministrator( $user->username );
		$rights = $sci_user->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}
		$javascript = ( $rights == 'write') ? "class='required validate-numeric'" : "" ;
		$javascript_nonumeric = ( $rights == 'write') ? "class='required'" : "" ;

		// set the id. 0 = new
		$id = JRequest::getVar( 'id' );
		$publication = JRequest::getVar( 'publication' );

		if ($id) { //Edit Form
			$model->setId( $id );
			$publication =& $model->getData();
			$publication->type = JHTML::_('sciencehelper.getTypeofPublication',$publication->type_id);
			$publication->groupleader = JHTML::_('sciencehelper.getGroupLeaderNames',$publication->id);
			// Code updated - 2010-11-17 - See ticket
			$publication->impact_factor = $model->getImpactFactor($publication->journal, $publication->year);
		} else { //New Form
			$publication->type = JHTML::_('sciencehelper.getTypeofPublication',$publication->type_id);
			$publication->groupleader = JHTML::_('sciencehelper.getGroupLeaderName',$publication->group_leader_id);
		}
		$this->assignRef('publication', $publication);

		// verify that the item exists and/or the user had the rights to read it
		if ( !$publication->id && ($rights != 'write')) {
			echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
			return;
		}

		// build list of journals
		$query = 'SELECT j.short_description AS value, j.short_description AS text'
		. ' FROM `#__sci_journals` AS j'
		. ' ORDER BY j.order'
		;
		$db->setQuery($query);
		$journallist[] = JHTML::_('select.option',  '', JText::_( '- Select Journal -' ), 'value', 'text');
		$journallist = array_merge( $journallist, $db->loadObjectList() );
		$lists['journals'] = JHTML::_('select.genericlist', $journallist, 'journal', $javascript_nonumeric, 'value', 'text', $publication->journal );

		// build list of types
		$query = 'SELECT pt.id AS value, pt.short_description AS text'
		. ' FROM `#__sci_publication_types` AS pt'
		. ' ORDER BY pt.order'
		;
		$db->setQuery($query);
		$publicationtypelist[] = JHTML::_('select.option',  '', JText::_( '- Select Publication Type -' ), 'value', 'text');
		$publicationtypelist = array_merge( $publicationtypelist, $db->loadObjectList() );
		$lists['publicationtypes'] = JHTML::_('select.genericlist', $publicationtypelist, 'type_id', $javascript, 'value', 'text', $publication->type_id );

		// build list of group_contributions
		$query = 'SELECT pt.id AS value, pt.short_description AS text'
		. ' FROM `#__sci_publication_group_contributions` AS pt'
		. ' ORDER BY pt.order'
		;
		$db->setQuery($query);
		$groupcontributionlist[] = JHTML::_('select.option',  '', JText::_( '- Select Group Contribution -' ), 'value', 'text');
		$groupcontributionlist = array_merge( $groupcontributionlist, $db->loadObjectList() );
		$lists['groupcontribution'] = JHTML::_('select.genericlist', $groupcontributionlist, 'group_contribution_id', $javascript, 'value', 'text', $publication->group_contribution_id );

		// build list of coauthors types
		$query = 'SELECT ct.id AS value, ct.short_description AS text'
		. ' FROM `#__sci_publication_coauthors_types` AS ct'
		. ' ORDER BY ct.order'
		;
		$db->setQuery($query);
		$coauthorstypeslist[] = JHTML::_('select.option',  '', JText::_( '- Select Coauthors Type -' ), 'value', 'text');
		$coauthorstypeslist = array_merge( $coauthorstypeslist, $db->loadObjectList() );
		$lists['coauthors_type'] = JHTML::_('select.genericlist', $coauthorstypeslist, 'coauthors_type_id', $javascript, 'value', 'text', $publication->coauthors_type_id );

		$lists['joint_publication'] = JHTML::_('select.booleanlist', 'joint_publication' , '', $publication->joint_publication ,'Yes', 'No');

		$lists['selected_extranet'] = JHTML::_('select.booleanlist', 'selected_extranet' , '', $publication->selected_extranet ,'Yes', 'No');
		
		$lists['phd_selected_publication'] = JHTML::_('select.booleanlist', 'phd_selected_publication' , '', $publication->phd_selected_publication ,'Yes', 'No');
		
		$lists['irb_selected_publication'] = JHTML::_('select.booleanlist', 'irb_selected_publication' , '', $publication->irb_selected_publication ,'Yes', 'No');
		
		//Get Menu Item Parameters
		$menu_params =& $mainframe->getPageParameters();
		$this->assignRef('menu_params', $menu_params);

		// set the variables
		$this->assign('action', $uri->toString());
		$this->assign('rights', $rights);
		$this->assign('sci_user', $sci_user);
		$this->assign('isadministrator', $isadministrator);
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);


		parent::display($tpl);
	}
}
?>