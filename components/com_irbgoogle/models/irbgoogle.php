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
	 * List id
	 *
	 * @var int
	 */
	var $_id = null;

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
	 * Method to set the list identifier
	 *
	 * @access	public
	 * @param	int identifier
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function &getData()
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

		// get only the checked registers
		$where[] = 'i.published = 1';

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

		if ( $this->_id ) {
			$where[] = 'id = ' . $this->_id
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
		if ($publish) {
			echo 'publicando en google';
		} else {
			echo 'despublicando en google';
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
		/* Roberto 2013-10-30 Deprecated code
		 * 
		if (empty($this->_client))
		{
			$this->_client = $this->getClientLoginHttpClient( $this->_paramsUser, $this->_paramsPassword );
			$this->_gapps = new Zend_Gdata_Gapps($this->_client, $this->_paramsDomain );
		}

		$this->_emaillists = $this->_gapps->retrieveEmailLists($recipient);

		print_r ($this->_emaillists);

		return $this->_emaillists;
		*/
		
		// the command using gam
		$command1 = "/usr/bin/python /usr/local/gam/gam.py info user " . $recipient;
		$res = shell_exec($command1);
		list($prev, $groups) = sscanf($res, "%sGroups:%s");
		print_r($groups);
		//break;		
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
	 * Subscribe a recipient to an existing email list.
	 *
	 * @param string $recipientAddress The address of the recipient who should
	 *          be added.
	 * @param string $emailList The name of the email address the recipient
	 *          be added to.
	 */
	function subscribeRecipientToEmailList( $recipientAddress, $emailList )
	{
		/* Roberto 2013-10-30 Deprecated code
		 * 
		if (empty($this->_client))
		{
			$this->_client = $this->getClientLoginHttpClient( $this->_paramsUser, $this->_paramsPassword );
			$this->_gapps = new Zend_Gdata_Gapps($this->_client, $this->_paramsDomain );
		}

		$this->_gapps->addRecipientToEmailList( $recipientAddress, $emailList );
		*/
		
		// the command using gam
		$command1 = "/usr/bin/python /usr/local/gam/gam.py update group ". $emailList . " add member " . $recipientAddress;
		$res = shell_exec($command1);
		echo "<pre>$res</pre>";
		echo "<pre>$command1</pre>";
		
	}

	/**
	 * Unsubscribe a recipient to an existing email list.
	 *
	 * @param string $recipientAddress The address of the recipient who should
	 *          be added.
	 * @param string $emailList The name of the email address the recipient
	 *          be added to.
	 */
	function unsubscribeRecipientToEmailList( $recipientAddress, $emailList )
	{
		/* Roberto 2013-10-30 Deprecated code
		 * 
		if (empty($this->_client))
		{
			$this->_client = $this->getClientLoginHttpClient( $this->_paramsUser, $this->_paramsPassword );
			$this->_gapps = new Zend_Gdata_Gapps($this->_client, $this->_paramsDomain );
		}

		$this->_gapps->removeRecipientFromEmailList( $recipientAddress, $emailList );
		*/
		
		// the command using gam
		$command1 = "/usr/bin/python /usr/local/gam/gam.py update group ". $emailList . " remove member " . $recipientAddress;
		$res = shell_exec($command1);
		echo "<pre>$res</pre>";
		echo "<pre>$command1</pre>";
	}
}
?>