<?php
/**
 * Joomla! 1.5 component irbgoogle
 *
 * @version $Id: irbgoogle.php 2009-10-29 09:30:44 svn $
 * @author
 * @package Joomla
 * @subpackage irbgoogle
 * @license GNU/GPL
 *
 *
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

// Google API
require_once ('Zend/Loader.php');

// Google API
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Gapps');

class IrbgoogleModelIrbgoogle extends JModel
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
	 * Client object
	 *
	 * @var object
	 */
	var $_client = null;

	/**
	 * Client object
	 *
	 * @var object
	 */
	var $_gapps = null;

	/**
	 * Data from Google
	 *
	 * @var array
	 */
	var $_allemaillists = null;

	/**
	 * Data from Google
	 *
	 * @var array
	 */
	var $_emaillists = null;

	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_paramsDomain = null;

	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_paramsUser = null;

	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_paramsPassword = null;

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

		// Google parameters
		$params =& JComponentHelper::getParams('com_irbgoogle');
		$this->_paramsDomain = $params->get( 'google_domain', '' );
		$this->_paramsUser = $params->get( 'google_user', '' );
		$this->_paramsPassword = $params->get( 'google_password', '' );
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
		$query = 'SELECT i.*'
		. ' FROM #__ig_groups AS i'
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

		// get the search field
		$filter_search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search');

		// Determine search terms
		if ($filter_search = trim($filter_search))
		{
			$filter_search = JString::strtolower($filter_search);
			$db =& $this->_db;
			$filter_search = $db->getEscaped($filter_search);
			$where[] = 'name LIKE "%'.$filter_search.'%"'
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

		// get the order field and direction
		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'i.name' );
		$filter_order_Dir = strtoupper($mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'ASC'));

		// if order column is unknown use the default
		if (!$filter_order)
		{
			$filter_order = 'i.name';
		}

		// validate the order direction, must be ASC or DESC
		if ($filter_order_Dir != 'ASC' && $filter_order_Dir != 'DESC')
		{
			$filter_order_Dir = 'ASC';
		}

		// return the ORDER BY clause
		return ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	}

	/**
	 * Method to (un)publish a link
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__ig_groups'
			. ' SET published = '.(int) $publish
			. ' WHERE id IN ( '.$cids.' )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns a HTTP client object with the appropriate headers for communicating
	 * with Google using the ClientLogin credentials supplied.
	 *
	 * @param string $user The username, in e-mail address format, to authenticate
	 * @param string $pass The password for the user specified
	 * @return Zend_Http_Client
	 */
	function getClientLoginHttpClient($user, $pass)
	{
		$service = Zend_Gdata_Gapps::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
		return $client;
	}

	/**
	 * Outputs the list of email lists to which the specified address is
	 * subscribed.
	 *
	 * @param string $recipient The email address of the recipient whose
	 *          subscriptions should be retrieved. Only a username is
	 *          required if the recipient is a member of the current domain.
	 * @return array
	 */
	function retrieveEmailLists($recipient)
	{
		if (empty($this->_client))
		{
			$this->_client = $this->getClientLoginHttpClient( $this->_paramsUser, $this->_paramsPassword );
			$this->_gapps = new Zend_Gdata_Gapps($this->_client, $this->_paramsDomain );
		}

		$this->_emaillists = $this->_gapps->retrieveEmailLists($recipient);
		return $this->_emaillists;
	}

	/**
	 * Outputs the list of all email lists on the current domain.
	 *
	 * @return array
	 */
	function retrieveAllEmailLists()
	{
		if (empty($this->_client))
		{
			$this->_client = $this->getClientLoginHttpClient( $this->_paramsUser, $this->_paramsPassword );
			$this->_gapps = new Zend_Gdata_Gapps($this->_client, $this->_paramsDomain );
		}

		$this->_allemaillists = $this->_gapps->retrieveAllEmailLists();
		return $this->_allemaillists;
	}

	/**
	 * Cleans the data stored in the db.
	 *
	 * @return array
	 */
	function cleanAllEmailLists()
	{
		/*
		 * Insert new lists if they do not exists and compound the array to
		 * compare with later
		 */
		$arr_allemaillists = array();
		foreach ($this->_allemaillists as $list)
		{
			$arr_allemaillists[] = $list->emaillist->name;
			$query = 'SELECT *'
			. ' FROM #__ig_groups'
			. ' WHERE name = \''. $list->emaillist->name .'\''
			;
			$this->_db->setQuery( $query );
			$this->_db->query();
			$total = $this->_db->getNumRows();

			if (!$total) {
				$query = 'INSERT INTO #__ig_groups(name, published)'
				. ' VALUES (\''. $list->emaillist->name .'\', 0)'
				;
				$this->_db->setQuery( $query );
				$this->_db->query();
			}
		}
		/*
		 * Delete entries. Foreach entry on the bd, if is not in Google
		 * we delete it
		 */
		$this->getData();
		foreach ($this->_data as $key => $item)
		{
			if (!in_array($item->name, $arr_allemaillists))
			{
				// delete the entry on the table
				$query = 'DELETE FROM #__ig_groups'
				. ' WHERE id = ' . $item->id
				;
				$this->_db->setQuery( $query );
				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// initialise the data to force the reload on the view
		$this->_data = null;
	}
}
?>