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
class ScienceViewCollaboration extends JView
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
		$this->_name = 'view_collaboration';
	}

	function display($tpl = null)
	{
		global $mainframe, $option;
			
		// Get some objects
		$model =& JModel::getInstance( 'collaboration', 'sciencemodel' );
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
		$javascript_no_num = ( $rights == 'write') ? "class='required'" : "" ;

		// set the id. 0 = new
		$id = JRequest::getVar( 'id' );
		if (!$id) {
			$id = 0;
		}

		// set the id
		$model->setId( $id );

		// get collaboration data
		$collaboration =& $model->getData();
		$this->assignRef('collaboration', $collaboration);
		// get collaboration level data
		$collaboration_levels =& $model->getCollaborationLevelsArray();
		$this->assignRef('collaboration_levels', $collaboration_levels);
		// get collaboration sector data
		$collaboration_sectors =& $model->getCollaborationSectorsArray();
		$this->assignRef('collaboration_sectors', $collaboration_sectors);
		// get collaboration type data
		$collaboration_types =& $model->getCollaborationTypesArray();
		$this->assignRef('collaboration_types', $collaboration_types);

		// verify that the item exists and/or the user had the rights to read it
		if ( !$collaboration->id && ($rights != 'write')) {
			echo JText::_( 'OPERATION NOT ALLOWED OR WRONG ITEM' );
			return;
		}

		if ( !$collaboration->id && ($sci_user->isGroupLeader($user->username))) {
			$my_group_leader_id = JHTML::_('sciencehelper.getGroupLeaderId', $sci_user->getAssigned($user->username));
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $my_group_leader_id);
		} else {
			$my_group_leader_id = $collaboration->group_leader_id;
			$selected_group_leader_name = JHTML::_('sciencehelper.getGroupLeaderName', $collaboration->group_leader_id);
		}

		//build list of researchers
		$lists['collaborationdetails'] =& $model->getCollaborationDetails();

		// build list of levels
		$query = 'SELECT t.id AS value, t.short_description AS text'
		. ' FROM `#__sci_collaboration_levels` AS t'
		. ' ORDER BY t.order'
		;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$total = count($rows);
		$lists['levels'] = '';
		for($i = 0; $i < $total; $i++)
		{
			$row =& $rows[$i];
				
			$lists['levels'] .= '<input type="checkbox" id="le_cb' . $i
			. '" name="le_cid[]" value="' . $row->value . '"'
			. (in_array($row->value, $collaboration_levels) ? ' checked="checked"' : '')
			. ' />'
			. $row->text
			. '<br>'
			;
		}
		$lists['levels'] = substr($lists['levels'], 0, -4); // take out the last 4 characters

		// build list of sectors
		$query = 'SELECT t.id AS value, t.short_description AS text'
		. ' FROM `#__sci_collaboration_sectors` AS t'
		. ' ORDER BY t.order'
		;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$total = count($rows);
		$lists['sectors'] = '';
		for($i = 0; $i < $total; $i++)
		{
			$row =& $rows[$i];
				
			$lists['sectors'] .= '<input type="checkbox" id="se_cb' . $i
			. '" name="se_cid[]" value="' . $row->value . '"'
			. (in_array($row->value, $collaboration_sectors) ? ' checked="checked"' : '')
			. ' />'
			. $row->text
			. '<br>'
			;
		}
		$lists['sectors'] = substr($lists['sectors'], 0, -4); // take out the last 4 characters

		// build list of types
		$query = 'SELECT t.id AS value, t.short_description AS text'
		. ' FROM `#__sci_collaboration_types` AS t'
		. ' ORDER BY t.order'
		;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$total = count($rows);
		$lists['types'] = '';
		for($i = 0; $i < $total; $i++)
		{
			$row =& $rows[$i];
				
			$lists['types'] .= '<input type="checkbox" id="ty_cb' . $i
			. '" name="ty_cid[]" value="' . $row->value . '"'
			. (in_array($row->value, $collaboration_types) ? ' checked="checked"' : '')
			. ' />'
			. $row->text
			. '<br>'
			;
		}
		$lists['types'] = substr($lists['types'], 0, -4); // take out the last 4 characters

		$lists['groupleaders'] = JHTML::_('sciencehelper.getGroupLeadersList', 'group_leader_id', $collaboration->group_leader_id, $javascript);
		$lists['countries'] = JHTML::_('sciencehelper.getCountriesList', 'country_id', null, $javascript);
		$lists['start_year'] = JHTML::_('sciencehelper.getYearsListYear', 'start_year', $collaboration->start_year, $javascript_no_num, false);
		$lists['end_year'] = JHTML::_('sciencehelper.getYearsListYear', 'end_year', $collaboration->end_year, $javascript_no_num);

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