<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

/**
 * Science Component Awards Model
 *
 * @package		Science
 * @since 		1.5
 */

class ScienceModelAwards extends JModel
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
		$query = 'SELECT a.*'
		. ' FROM #__sci_awards AS a'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = a.group_leader_id'
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
			. ' a.id LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(title) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(awarding_body) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(awardee) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(year) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(end_year) LIKE "%' . $filter_search . '%"'
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

		$orders = array('id');

		// get the order field and direction
		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'id' );

		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'DESC'));

		// if order column is unknown use the default
		if (!in_array($filter_order, $orders))
		{
			$filter_order = 'id';
		}

		// validate the order direction, must be ASC or DESC
		if ($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
		{
			$filter_order_Dir = 'DESC';
		}

		// return the ORDER BY clause
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		/*global $mainframe, $option;

		// Array of allowable order fields
		$orders = array('a.id', 'a.title', 'a.awarding_body', 'a.awardee', 'a.year');

		// get the order field and direction
		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'a.id' );
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'DESC'));

		// if order column is unknown use the default
		if (!in_array($filter_order, $orders)) {
		$filter_order = 'a.id';
		}

		// validate the order direction, must be ASC or DESC
		if ($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
		{
		$filter_order_Dir = 'DESC';
		}

		// return the ORDER BY clause
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;*/
	}

	/**
	 * Method to get item data for the report
	 *
	 * @access public
	 * @return array
	 */
	function getReportData( $start_date, $end_date, $groups )
	{
		$groups = implode('\', \'', $groups);

		// get only the user's records
		$user =& JFactory::getUser();
		$where = array();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) {
			$where[] = 'gl.user_username = \'' . $sci_user->getAssigned($user->username) . '\'';
		} else {
			$where[] = 'a.group_leader_id IN ( \'' . $groups . '\' )';
		}

		// dates
		$year_start_date = new JDate($start_date);
		$year_end_date = new JDate($end_date);
		$year_start = $year_start_date->toFormat('%Y');
		$year_end = $year_end_date->toFormat('%Y');
		// Roberto 2014-06-03 Teniendo en cuenta que el campo end_year puede estar vacio
		$where[] = '(( a.year >= \'' . $year_start . '\' AND a.year <= \'' . $year_end . '\' ) OR ( a.year <= \'' . $year_start . '\' AND ( a.end_year >= \'' . $year_start . '\' OR a.end_year = \'\' )))';
		
		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
		$orderby = ' ORDER BY gl_report_order, a.title ASC';

		$query = 'SELECT a.*, gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ' FROM `#__sci_awards` AS a'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = a.group_leader_id'
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
	 * @param string $group_leader_id Group leader identifier
	 * @param string $year Year
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
			$where[] = 'a.group_leader_id = \'' . $group_leader_id . '\'';
		}

		$where[] = 'a.year <= \'' . $year . '\'';
		$where[] = '( a.end_year >= \'' . $year . '\' OR a.end_year = \'\' )';
		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

		$orderby = ' ORDER BY gl_report_order, a.title ASC';

		$query = 'SELECT a.*, gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ' FROM `#__sci_awards` AS a'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = a.group_leader_id'
		. $where
		. $orderby
		;
		
		// load the content
		$this->_data = $this->_getList($query);

		return $this->_data;
	}

}
?>
