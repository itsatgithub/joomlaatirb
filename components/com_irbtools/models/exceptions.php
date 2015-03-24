<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Irbtools Component Exceptions Model
 *
 * @package		Irbtools
 * @since 		1.5
 */

class IrbtoolsModelExceptions extends JModel
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
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getFullData()
	{
		$query = 'SELECT *'
		. ' FROM #__irbtools_exceptions'
		. ' ORDER BY id'
		;
		$data = $this->_getList($query);
		return $data;
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
		$query = 'SELECT e.*'
		. ' FROM #__irbtools_exceptions AS e'
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

		// get the search field
		$filter_search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search');

		// Determine search terms
		if ($filter_search = trim($filter_search))
		{
			$filter_search = JString::strtolower($filter_search);
			$db =& $this->_db;
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER(name) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(surname1) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(surname2) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(department) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(unit) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(research_group) LIKE "%' . $filter_search . '%"'
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

		$orders = array('id', 'command', 'name', 'surname1', 'surname2', 'gender', 'department', 'unit', 'research_group');

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

}
?>
