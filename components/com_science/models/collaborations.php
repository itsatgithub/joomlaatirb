<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

/**
 * Science Component Collaborations Model
 *
 * @package		Science
 * @since 		1.5
 */

class ScienceModelCollaborations extends JModel
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

		/*
		 * formating the content
		 */
		if (count($this->_data))
		{
			foreach ($this->_data as $k=>$v)
			{
				// formating the collaboration_type to return a CSV string
				$query2 = 'SELECT ct.short_description AS short_description'
				. ' FROM `jos_sci_collaboration_types` AS ct'
				. ', `jos_sci_collaborations_collaboration_types` AS cct'
				. ' WHERE cct.collaboration_type_id = ct.id'
				. ' AND cct.collaboration_id = ' . $v->id
				;
				$rows2 = $this->_getList($query2);
				$v->ct_description = "";
				foreach ($rows2 as $row2)
				{
					$v->ct_description .= $row2->short_description . ", ";
				}
				// delete last two characters
				$v->ct_description = substr($v->ct_description, 0, -2);

				// formating the collaboration_level to return a CSV string
				$query3 = 'SELECT cl.short_description AS short_description'
				. ' FROM `jos_sci_collaboration_levels` AS cl'
				. ', `jos_sci_collaborations_collaboration_levels` AS ccl'
				. ' WHERE ccl.collaboration_level_id = cl.id'
				. ' AND ccl.collaboration_id = ' . $v->id
				;
				$rows3 = $this->_getList($query3);
				$v->cl_description = "";
				foreach ($rows3 as $row3)
				{
					$v->cl_description .= $row3->short_description . ", ";
				}
				// delete last two characters
				$v->cl_description = substr($v->cl_description, 0, -2);

				// formating the institution to have just the first one
				$query4 = 'SELECT *'
				. ' FROM `jos_sci_collaboration_details` AS cd'
				. ' WHERE cd.collaboration_id = ' . $v->id
				. ' ORDER BY cd.id'
				;
				$rows4 = $this->_getList($query4);
				$v->institution_description = "";
				foreach ($rows4 as $row4)
				{
					$v->institution_description = $row4->institution;
					break;
				}
			}
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
		$query = 'SELECT c.*'
		. ', gl.name AS gl_name'
		//. ', ct.description AS ct_description'
		//. ', cl.description AS cl_description'
		. ' FROM `#__sci_collaborations` AS c'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = c.group_leader_id'
		. ' LEFT JOIN `#__sci_collaborations_collaboration_types` AS cct ON cct.collaboration_id = c.id'
		. ' LEFT JOIN `#__sci_collaboration_types` AS ct ON ct.id = cct.collaboration_type_id'
		. ' LEFT JOIN `#__sci_collaborations_collaboration_levels` AS ccl ON ccl.collaboration_id = c.id'
		. ' LEFT JOIN `#__sci_collaboration_levels` AS cl ON cl.id = ccl.collaboration_level_id'
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
			. ' c.id LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(c.title) LIKE "%' . $filter_search . '%"'
			// 2012-05-18 Roberto Añado filtro por Group Leader
			. ' OR LOWER(gl.name) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(ct.description) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(cl.description) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(c.start_year) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(c.end_year) LIKE "%' . $filter_search . '%"'
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

		// Array of allowable order fields
		$orders = array('id', 'title', 'gl_name');

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
			$where[] = 'c.group_leader_id IN ( \'' . $groups . '\' )';
		}

		// dates
		$date = new JDate($start_date);
		$where[] = 'c.start_year <= \'' . $date->toFormat('%Y') . '\'';
		$date = new JDate($end_date);
		// 'Ongoing' is any date in the future
		$where[] = '( c.end_year >= \'' . $date->toFormat('%Y') . '\''
		. ' OR c.end_year = \'Ongoing\' )'
		;

		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
		$orderby = ' ORDER BY gl_report_order, c.title ASC, ct.collaboration_type_id';

		$query = 'SELECT c.*, cd.*, co.printable_name AS country'
		. ', gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ', ct.collaboration_type_id AS type_id'
		. ' FROM `#__sci_collaborations` AS c'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = c.group_leader_id'
		. ' LEFT JOIN `#__sci_collaboration_details` AS cd ON cd.collaboration_id = c.id'
		. ' LEFT JOIN `#__sci_countries` AS co ON co.id = cd.country_id'
		. ' LEFT JOIN `#__sci_collaborations_collaboration_types` AS ct ON ct.collaboration_id = c.id'
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
			$where[] = 'c.group_leader_id = \'' . $group_leader_id . '\'';
		}

		$where[] = 'c.start_year <= \'' . $year . '\'';
		// 'Ongoing' is any date in the future
		$where[] = '( c.end_year >= \'' . $year . '\''
		. ' OR c.end_year = \'Ongoing\' )'
		;
		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

		$orderby = ' ORDER BY gl_report_order, c.title ASC, ct.collaboration_type_id';

		$query = 'SELECT c.*, cd.*, co.printable_name AS country'
		. ', gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order, ct.collaboration_type_id AS type_id'
		. ' FROM `#__sci_collaborations` AS c'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = c.group_leader_id'
		. ' LEFT JOIN `#__sci_collaboration_details` AS cd ON cd.collaboration_id = c.id'
		. ' LEFT JOIN `#__sci_countries` AS co ON co.id = cd.country_id'
		. ' LEFT JOIN `#__sci_collaborations_collaboration_types` AS ct ON ct.collaboration_id = c.id'
		. $where
		. $orderby
		;

		// load the content
		$this->_data = $this->_getList($query);

		/*
		 * formating the content
		 */
		foreach ($this->_data as $k=>$v)
		{
			// cleaning up the same collaboration with two types, delete the 'Internal' one
			if ($v->type_id == '2' or $v->type_id == '3')
			{
				foreach ($this->_data as $r=>$t)
				{
					if ($t->collaboration_id == $v->collaboration_id && $t->type_id == '1')
					{
						unset($this->_data[$r]);
					}
				}
			}
		}

		return $this->_data;
	}
}
?>
