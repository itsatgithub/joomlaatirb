<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

/**
 * Science Component Projects Model
 *
 * @package		Science
 * @since 		1.5
 */

class ScienceModelProjects extends JModel
{
	/**
	 * Frontpage data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Frontpage total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get the pagination object
	 *
	 * @access public
	 * @return object
	 */
	function getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Builds the query
	 *
	 * @return an SQL query
	 */
	function _buildQuery()
	{
		$query = 'SELECT p.*, at.description AS at_description'
		. ', gt.short_description AS gt_description'
		. ', fe.short_description AS fe_description, gl.name AS group_leader'
		. ', pi.name AS principal_investigator'
		. ' FROM #__sci_projects AS p'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
		. ' LEFT JOIN `#__sci_project_action_types` AS at ON at.id = p.action_type_id'
		. ' LEFT JOIN `#__sci_project_grant_types` AS gt ON gt.id = p.grant_type_id'
		. ' LEFT JOIN `#__sci_project_funding_entities` AS fe ON fe.id = p.funding_entity_id'
		. ' LEFT JOIN `#__sci_principal_investigators` AS pi ON pi.id = p.principal_investigator_id'
		. $this->_buildQueryWhere()
		. $this->_buildQueryOrderBy();
		;
		
		return $query;
	}

	/**
	 * Builds the WHERE part of a query
	 *
	 * @return string Part of an SQL query
	 */
	function _buildQueryWhere()
	{
		global $mainframe, $option;

		// empty condition
		$where = array();

		// get only the user's records
		$user =& JFactory::getUser();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) {
			// $where[] = 'gl.user_username = \'' . $user->username . '\'';
			// 21-02-2011 in case of GL, the username is the assigned_to
			$where[] = 'gl.user_username = \'' . $sci_user->getAssigned($user->username) . '\'';
		}

		// get the search field
		$filter_search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search');

		// Determine search terms
		if ($filter_search = trim($filter_search))
		{
			$filter_search = JString::strtolower($filter_search);
			$db =& $this->_db;
			$filter_search = $db->getEscaped($filter_search);
			$where[] = '('
			. ' p.id LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.irb_code) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.title) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(gt.short_description) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.acronym) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.beneficiary) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(gl.name) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(pi.name) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(fe.short_description) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.total_budget) LIKE "%' . $filter_search . '%"'
			. ' OR p.start_date LIKE "%' . $filter_search . '%"'
			. ' OR p.end_date LIKE "%' . $filter_search . '%"'
			. ')'
			;
		}

		// return the WHERE clause
		return (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
	}

	/**
	 * Builds the ORDER part of a query
	 *
	 * @return string Part of an SQL query
	 */
	function _buildQueryOrderBy()
	{
		global $mainframe, $option;

		$orders = array('p.id', 'p.title', 'p.acronym', 'p.beneficiary', 'group_leader', 'principal_investigator'
		, 'fe_description', 'gt_description', 'p.start_date', 'p.end_date', 'p.total_budget');

		// get the order field and direction
		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'p.id' );
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'DESC'));

		// if order column is unknown use the default
		if (!in_array($filter_order, $orders))
		{
			$filter_order = 'p.id';
		}

		// validate the order direction, must be ASC or DESC
		if ($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
		{
			$filter_order_Dir = 'DESC';
		}

		// return the ORDER BY clause
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}

	/**
	 * Method to get item data for the report
	 *
	 * @access public
	 * @return array
	 */
	function getReportData( $start_date, $end_date, $groups )
	{
		$groups = implode("', '", $groups);

		// get only the user's records
		$user =& JFactory::getUser();
		$where = array();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) {
			$where[] = 'gl.user_username = \'' . $sci_user->getAssigned($user->username) . '\'';
		} else {
			$where[] = 'p.group_leader_id IN ( \'' . $groups . '\' )';
		}

		$where[] = '((p.start_date < \'' . $start_date . '\' AND p.end_date >= \'' . $start_date . '\') OR (p.start_date >= \'' . $start_date . '\' AND p.start_date <= \'' . $end_date . '\'))';
		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

		$orderby = ' ORDER BY gl_report_order, p.title ASC';

		$query = 'SELECT p.*, gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ', at.description AS at_description, gt.description AS gt_description'
		. ', ow.description AS ow_description, ro.description AS ro_description'
		. ', fe.description AS fe_description'
		. ', pi.name AS principal_investigator'
		. ', pu.description AS pu_description'
		. ' FROM `#__sci_projects` AS p'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
		. ' LEFT JOIN `#__sci_project_action_types` AS at ON at.id = p.action_type_id'
		. ' LEFT JOIN `#__sci_project_owners` AS ow ON ow.id = p.owner_id'
		. ' LEFT JOIN `#__sci_project_roles` AS ro ON ro.id = p.role_id'
		. ' LEFT JOIN `#__sci_project_grant_types` AS gt ON gt.id = p.grant_type_id'
		. ' LEFT JOIN `#__sci_project_funding_entities` AS fe ON fe.id = p.funding_entity_id'
		. ' LEFT JOIN `#__sci_principal_investigators` AS pi ON pi.id = p.principal_investigator_id'
		. ' LEFT JOIN `#__sci_project_uneix` AS pu ON pu.id = p.uneix_id'
		. $where
		. $orderby
		;

		// load the content
		$this->_data = $this->_getList($query);

		return $this->_data;
	}


	/**
	 * Method to get item data for the report HTML
	 *
	 * @access public
	 * @return array
	 */
	function getHTMLReportData( $group_leader_id, $year )
	{
		// get only the user's records
		$user =& JFactory::getUser();
		$where = array();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) {
			$where[] = 'gl.user_username = \'' . $sci_user->getAssigned($user->username) . '\'';
		} else {
			$where[] = 'p.group_leader_id = \'' . $group_leader_id . '\'';
		}

		$start_date = $year . '-01-01';
		$end_date = $year . '-12-31';
		$where[] = '((p.start_date < \'' . $start_date . '\' AND p.end_date >= \'' . $start_date . '\') OR (p.start_date >= \'' . $start_date . '\' AND p.start_date <= \'' . $end_date . '\'))';
		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

		$orderby = ' ORDER BY gl_report_order, p.title ASC';

		$query = 'SELECT p.*, gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ', at.description AS at_description, gt.short_description AS gt_description'
		. ', ow.description AS ow_description, ro.description AS ro_description'
		. ', fe.short_description AS fe_description'
		. ', pi.name AS principal_investigator'
		. ' FROM `#__sci_projects` AS p'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
		. ' LEFT JOIN `#__sci_project_action_types` AS at ON at.id = p.action_type_id'
		. ' LEFT JOIN `#__sci_project_owners` AS ow ON ow.id = p.owner_id'
		. ' LEFT JOIN `#__sci_project_roles` AS ro ON ro.id = p.role_id'
		. ' LEFT JOIN `#__sci_project_grant_types` AS gt ON gt.id = p.grant_type_id'
		. ' LEFT JOIN `#__sci_project_funding_entities` AS fe ON fe.id = p.funding_entity_id'
		. ' LEFT JOIN `#__sci_principal_investigators` AS pi ON pi.id = p.principal_investigator_id'
		. $where
		. $orderby
		;

		// load the content
		$this->_data = $this->_getList($query);

		return $this->_data;
	}

}
?>
