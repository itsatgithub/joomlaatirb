<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Irbtools Component Exception Model
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsModelRoaming extends JModel
{
	/**
	 * Abstract id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	/**
	 * Method to set the  identifier
	 *
	 * @access	public
	 * @param	int  identifier
	 */
	function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	/**
	 * Method to get a roaming
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the award data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to store a roaming
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		
		$row =& $this->getTable('roaming');
		
		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		} else {
			// returns the id
			return $row->id;
		}
	}

	/**
	 * Method to delete an roaming
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete( $id )
	{
		$user =& JFactory::getUser();
		
		// access control
		$irbuser =& JModel::getInstance( 'user', 'irbtoolsmodel' );
		$rights = $irbuser->getRights($user->username, 'roaming');
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );			
			return;
		}
		
		$query = 'DELETE FROM #__irbtools_roamings'
		. ' WHERE id = ' . $id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}


	/**
	 * Method to load roaming data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			// empty condition
			$where = array();

			// get only the _id
			$where[] = 'e.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT e.*'
			. ' FROM `#__irbtools_roamings` AS e'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the exception data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$item = new stdClass();
			$item->id = 0;
			$item->code = null;
			$item->description = null;
			$item->long_number = null;
			$item->from = null;
			$item->to = null;
			$item->username = null;
			$item->email = null;
			$this->_data = $item;

			return (boolean) $this->_data;
		}
		return true;
	}

	
	function getOwner($long_number)
	{
		$query = 'SELECT u.*'
		. ' FROM `#__irbtools_roaming_users` AS u'
		. ' LEFT JOIN `#__irbtools_roaming_telephones` AS t ON t.owner_id = u.id'
		. ' WHERE t.long_number = \'' . $long_number. '\''		
		;
		$this->_db->setQuery($query);		
		$result = $this->_db->loadObject();
		return $result->owner;
	}
	
	// Roberto 2015-12-23 Get the number's owner email
	function getOwnerEmail($long_number)
	{
		$query = 'SELECT u.*'
		. ' FROM `#__irbtools_roaming_users` AS u'
		. ' LEFT JOIN `#__irbtools_roaming_telephones` AS t ON t.owner_id = u.id'
		. ' WHERE t.long_number = \'' . $long_number. '\''		
		;
		$this->_db->setQuery($query);		
		$result = $this->_db->loadObject();
		return $result->email;
	}
	
}
