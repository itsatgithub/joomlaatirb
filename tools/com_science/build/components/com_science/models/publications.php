<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

/**
 * Science Component Publications Model
 *
 * @package		Science
 * @since 		1.5
 */

class ScienceModelPublications extends JModel
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
		$query = 'SELECT DISTINCT p.*, pt.description AS pt_description, gc.description AS gc_description'
		. ', gc.description AS group_contribution'
		. ' FROM #__sci_publications AS p'
		. ' LEFT JOIN `#__sci_publication_types` AS pt ON pt.id = p.type_id'
		. ' LEFT JOIN `#__sci_publication_group_contributions` AS gc ON gc.id = p.group_contribution_id'
		. ' LEFT JOIN `#__sci_publication_group_leader` AS pgl ON pgl.publication_id = p.id'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = pgl.group_leader_id'
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
			// 21-02-2011 getAssigned is used instead of username
			// $where[] = 'gl.user_username = \'' . $user->username . '\'';
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
			. ' OR LOWER(p.title) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.authors) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.journal) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.issue) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.volume) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.pages) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(p.year) LIKE "%' . $filter_search . '%"'
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
		$orders = array('id', 'title', 'authors', 'journal', 'year', 'issue', 'volume', 'pages');

		// get the order field and direction
		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'id' );
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'DESC'));

		// if order column is unknown use the default
		if (!in_array($filter_order, $orders)) {
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
			$where[] = 'pgl.group_leader_id IN ( \'' . $groups . '\' )';
		}

		$date = new JDate($start_date);
		$where[] = 'p.year >= \'' . $date->toFormat('%Y') . '\'';
		$date = new JDate($end_date);
		$where[] = 'p.year <= \'' . $date->toFormat('%Y') . '\'';
		$today = new JDate();

		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';
		$orderby = ' ORDER BY gl_report_order, p.authors ASC';

		$query = 'SELECT p.*, gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ', gc.description AS gc_description, pt.description AS pt_description, jif.impact_factor AS impact_factor'
		. ', jif.quartile AS quartile'
		. ' FROM `#__sci_publications` AS p'
		. ' LEFT JOIN `#__sci_publication_group_leader` AS pgl ON pgl.publication_id = p.id'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = pgl.group_leader_id'
		. ' LEFT JOIN `#__sci_publication_group_contributions` AS gc ON gc.id = p.group_contribution_id'
		. ' LEFT JOIN `#__sci_publication_types` AS pt ON pt.id = p.type_id'
		. ' LEFT JOIN `#__sci_journals` AS jo ON jo.short_description = p.journal'
		// 2011-05-02 Roberto. Siempre poner IF del año 2009
		//. ' LEFT JOIN `#__sci_journal_impact_factors` AS jif ON (jif.journal_id = jo.id AND jif.year = p.year)'
		// 2012-02-22 Roberto. Jorge comenta poner siempre el último año
		. ' LEFT JOIN `#__sci_journal_impact_factors` AS jif ON (jif.journal_id = jo.id AND jif.year = \''
		. $today->toFormat('%Y')
		. '\')'
		. $where
		. $orderby
		;

		// load the content
		$this->_data = $this->_getList($query);
		
		/*
		 * formating the content
		 */
		$params =& JComponentHelper::getParams( 'com_science' );
		$start_year = $params->get('scienceConfig_ImpactFactorStartYear');
		foreach ($this->_data as $k=>$v)
		{
			// formating the joint_publication_description to return a CSV string
			if ($v->joint_publication) {
				$query2 = 'SELECT *, gl.name AS gl_name'
				. ' FROM `jos_sci_publication_group_leader` AS pgl'
				. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = pgl.group_leader_id'
				. ' WHERE pgl.publication_id = ' . $v->id
				;
				$rows2 = $this->_getList($query2);
				$v->joint_publication_description = "";
				foreach ($rows2 as $row2)
				{
					$v->joint_publication_description .= $row2->gl_name . ", ";
				}
				// delete last two characters
				$v->joint_publication_description = substr($v->joint_publication_description, 0, -2);			
			} else {
				$v->joint_publication_description = "";
			}
			
			// in case of impact factor = 0, calculating the last valid impact factor
			/*
			if (!$v->impact_factor) {
				for ($i = $v->year; $i > $start_year; $i--) {
					if (getimpactfacto($v->id, $i) != 0) {
						$valid_impact_factor = ;
					}
				}
				$v->impact_factor = 99;
			}
			*/
		}

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
			$where[] = 'pgl.group_leader_id = \'' . $group_leader_id . '\'';
		}

		$where[] = 'p.year = \'' . $year . '\'';
		$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

		$orderby = ' ORDER BY p.authors ASC';

		$query = 'SELECT p.*, gl.name AS gl_name'
		. ', gl.research_programme AS gl_research_programme, gl.report_order AS gl_report_order'
		. ', gc.description AS gc_description, pt.description AS pt_description'
		. ' FROM `#__sci_publications` AS p'
		. ' LEFT JOIN `#__sci_publication_group_leader` AS pgl ON pgl.publication_id = p.id'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = pgl.group_leader_id'
		. ' LEFT JOIN `#__sci_publication_group_contributions` AS gc ON gc.id = p.group_contribution_id'
		. ' LEFT JOIN `#__sci_publication_types` AS pt ON pt.id = p.type_id'
		. $where
		. $orderby
		;
		
		// load the content
		$this->_data = $this->_getList($query);
		return $this->_data;
	}

}
?>
