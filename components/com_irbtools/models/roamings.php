<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Irbtools Component Roamings Model
 *
 * @package		Irbtools
 * @since 		1.5
 */

class IrbtoolsModelRoamings extends JModel
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
	function getData($profile, $username)
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery($profile, $username);
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
		. ' FROM #__irbtools_roamings'
		. ' ORDER BY code, long_number'
		;
		$data = $this->_getList($query);
		return $data;
	}	

	
	
	/**
	 * Method to get delayed data
	 *
	 * @access public
	 * @return array
	 */
	function getDelayedData()
	{		
		$query = 'SELECT ro.*'
		. ' FROM #__irbtools_roamings AS ro'
		. ' WHERE ro.code = ""'		
		. ' ORDER BY ro.username, ro.long_number'
		;
		$data = $this->_getList($query);
		return $data;
		
		// UPDATE `joomlaatirb`.`jos_irbtools_roamings` SET `code` = 'a' WHERE `jos_irbtools_roamings`.`id` =25;
	}	
	
	
	
	
	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getMyData($username)
	{
		$query = 'SELECT ro.*'
		. ' FROM #__irbtools_roamings AS ro'
		. ' LEFT JOIN `#__irbtools_users` AS us ON us.username = ro.username'
		. ' LEFT JOIN `#__irbtools_roaming_telephone_user` AS tu ON tu.username = us.username'
		. ' LEFT JOIN `#__irbtools_roaming_telephones` AS te ON te.long_number = tu.long_number'
		. ' WHERE us.username = \'' . $username. '\' AND te.long_number = ro.long_number'		
		. ' ORDER BY ro.code ASC'
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
	function getTotal($profile, $username)
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery($profile, $username);
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
	function getPagination($profile, $username)
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal($profile, $username), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Builds the query
	 *
	 * @return an SQL query
	 */
	function _buildQuery($profile, $username)
	{
		$query = 'SELECT ro.*, us.owner AS owner'
		. ' FROM `#__irbtools_roamings` AS ro'
		. ' LEFT JOIN `#__irbtools_roaming_telephones` AS te ON te.long_number = ro.long_number'
		. ' LEFT JOIN `#__irbtools_roaming_users` AS us ON us.id = te.owner_id'
		
		. $this->_buildQueryWhere($profile, $username)
		. $this->_buildQueryOrderBy();
		;
		return $query;
	}

	/**
	 * Builds the WHERE part of a query
	 *
	 * @return string Part of an SQL query
	 */
	function _buildQueryWhere($profile, $username)
	{
		global $mainframe, $option;

		// empty condition
		$where = array();
		
		// if user is not an administrator
		if (strcmp($profile, 'administrator') != 0)
		{
			$where[] = ' username = \'' . $username. '\'';
		}
				
		// get the search field
		$filter_search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search');

		// Determine search terms
		if ($filter_search = trim($filter_search))
		{
			$filter_search = JString::strtolower($filter_search);
			$db =& $this->_db;
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'LOWER(`ro`.`code`) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(`ro`.`description`) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(`ro`.`long_number`) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(`ro`.`from`) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(`ro`.`to`) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(`ro`.`username`) LIKE "%' . $filter_search . '%"'
			. ' OR LOWER(`us`.`owner`) LIKE "%' . $filter_search . '%"'
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

		$orders = array('id', 'code', 'description', 'long_number', 'from', 'to', 'username', 'owner');

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
		return ' ORDER BY `'.$filter_order.'` '.$filter_order_Dir;
	}
	
	
	/**
	 * Method to get the last request id
	 *
	 * @access public
	 * @return array
	 */
	function getRequestId()
	{
		$year = date('Y');
		
		$query = 'SELECT *'
		. ' FROM #__irbtools_roamings'
		. ' ORDER BY code DESC'
		;
		$list = $this->_getList($query);
				
		foreach($list as $data)
		{
			// it takes just the first one on the list
			$code = $data->code;
			$table_number = intval(substr($code, -4)); // 4 digits
			$table_year = intval(substr($code, 0, 4));
			
			// new year
			if ($table_year != $year) {
				return $year . '-0001'; // first code for the year
			} else {
				$table_number++;
				return $table_year . '-' . str_pad($table_number, 4, "0", STR_PAD_LEFT);
			}			
		}		
	}

}
?>
