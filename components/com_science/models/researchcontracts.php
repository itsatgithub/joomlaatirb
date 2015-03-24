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

class ScienceModelResearchcontracts extends JModel
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
		$query = 'SELECT p.*, gl.name AS group_leader, fs.description AS funding_sector'
		. ' FROM #__sci_research_contracts AS p'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
		. ' LEFT JOIN `#__sci_research_contracts_funding_sectors` AS fs ON fs.id = p.funding_sector_id'
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
			. ' OR LOWER(p.acronym) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(gl.name) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.company) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.principal_investigator) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.budget) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.overhead) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.start_date) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.end_date) LIKE "%' . $filter_search . '%"'
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

		$orders = array('p.id', 'p.acronym', 'group_leader', 'p.principal_investigator'
		, 'p.start_date', 'p.end_date', 'p.budget', 'p.irb_code');

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

		$orderby = ' ORDER BY gl_report_order, p.start_date ASC';

		$query = 'SELECT p.*, gl.name AS gl_name, fs.description AS funding_sector'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ' FROM `#__sci_research_contracts` AS p'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
		. ' LEFT JOIN `#__sci_research_contracts_funding_sectors` AS fs ON fs.id = p.funding_sector_id'
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

		$orderby = ' ORDER BY gl_report_order, p.start_date ASC';

		$query = 'SELECT p.*, gl.name AS gl_name, fs.description AS funding_sector'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ' FROM `#__sci_research_contracts` AS p'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
		. ' LEFT JOIN `#__sci_research_contracts_funding_sectors` AS fs ON fs.id = p.funding_sector_id'
		. $where
		. $orderby
		;

		// load the content
		$this->_data = $this->_getList($query);

		return $this->_data;
	}

}
?>
