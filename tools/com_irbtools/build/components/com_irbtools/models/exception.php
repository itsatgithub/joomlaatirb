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
class IrbtoolsModelException extends JModel
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
	 * Method to get a award
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
	 * Method to store an award
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('exception');

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
	 * Method to delete an exception
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
		$rights = $irbuser->getRights($user->username, 'exceptions');
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );			
			return;
		}
		
		$query = 'DELETE FROM #__irbtools_exceptions'
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
	 * Method to load exception data
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
			. ' FROM `#__irbtools_exceptions` AS e'
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
			$item->command = null;
			$item->irbpeople_user_id = null;
			$item->name = null;
			$item->surname1 = null;
			$item->surname2 = null;
			$item->gender = null;
			$item->department = null;
			$item->unit = null;
			$item->research_group = null;
			$item->email = null;
			$item->phone = null;
			$item->position = null;
			$item->location = null;
			$item->second_affiliation = null;
			$this->_data = $item;

			return (boolean) $this->_data;
		}
		return true;
	}

}
