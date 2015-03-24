<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component Award Model
 *
 * @package		Register
 * @since 1.5
 */

class ScienceModelPatent extends JModel
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
		$this->_id	= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a patent
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the patent data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to store the patent
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('patent');

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
	 * Method to delete a patent
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete( $id )
	{
		$user =& JFactory::getUser();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) 
		{
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_patents', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_patents', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}
		
		$query = 'DELETE FROM #__sci_patents'
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
	 * Method to load patent data
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

			// get only the user's records
			$user =& JFactory::getUser();
			$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
			if ($sci_user->isGroupLeader($user->username)) {
				$where[] = 'gl.user_username = \'' . $sci_user->getAssigned($user->username) . '\'';
			}
				
			// get only the _id
			$where[] = 'p.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT p.*, ps.description as patent_status'
			. ' FROM `#__sci_patents` AS p'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
			. ' LEFT JOIN `#__sci_patent_status` AS ps ON p.patent_status_id = ps.id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;

			// permission control
			/*$user =& JFactory::getUser();
			$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
			$join_group_leader = '';
			$check_group_leader = '';
			if (!($sci_user->isAdministrator($user->username) || $sci_user->isPartAdministrator($user->username) || $sci_user->isReader($user->username))){
			$join_group_leader = ' LEFT JOIN #__sci_group_leaders AS gl'
			. ' ON p.group_leader_id = gl.id';
			$check_group_leader = 'AND gl.user_username = \''.$user->username.'\'';
			}
				
			$query = 'SELECT p.*, ps.description as patent_status'
			. ' FROM #__sci_patents AS p'
			. ' LEFT JOIN #__sci_patent_status AS ps'
			. ' ON p.patent_status_id = ps.id'
			. $join_group_leader
			. ' WHERE p.id = ' . $this->_id . ' '
			. $check_group_leader
			;

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;*/
		}
		return true;
	}

	/**
	 * Method to initialise the patent data
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
			$patent = new stdClass();
			$patent->id = 0;
			$patent->group_leader_id = 0;
			$patent->title = null;
			$patent->inventors = null;
			$patent->publication_number = null;
			$patent->publication_date = null;
			$patent->patent_status_id = null;
			$patent->modified = null;
			$this->_data = $patent;

			return (boolean) $this->_data;
		}
		return true;
	}

}