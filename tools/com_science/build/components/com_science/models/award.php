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
class ScienceModelAward extends JModel
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
		$row =& $this->getTable('award');

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
	 * Method to delete an award
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
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_awards', $this->_id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_awards', $this->_id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}
		
		$query = 'DELETE FROM #__sci_awards'
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
	 * Method to load award data
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
				//$where[] = 'gl.user_username = \'' . $user->username . '\'';
			}
				
			// get only the _id
			$where[] = 'a.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT a.*'
			. ' FROM `#__sci_awards` AS a'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = a.group_leader_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;

			// permission control
			/*	$user =& JFactory::getUser();
			$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
			$join_group_leader = '';
			$check_group_leader = '';
			if (!($sci_user->isAdministrator($user->username) || $sci_user->isPartAdministrator($user->username) || $sci_user->isReader($user->username))){
			$join_group_leader = ' LEFT JOIN #__sci_group_leaders AS gl'
			. ' ON a.group_leader_id = gl.id';
			$check_group_leader = 'AND gl.user_username = \''.$user->username.'\'';
			}
				
			$query = 'SELECT a.*, y.description as year_label'
			. ' FROM #__sci_awards AS a'
			. ' LEFT JOIN #__sci_years AS y ON y.id = a.year'
			. $join_group_leader
			. ' WHERE a.id = ' . $this->_id . ' '
			. $check_group_leader
			;

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;*/
		}
		return true;
	}

	/**
	 * Method to initialise the award data
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
			$award = new stdClass();
			$award->id = 0;
			$award->group_leader_id = 0;
			$award->title = null;
			$award->awarding_body = null;
			$award->awardee = null;
			$award->year = null;
			$award->end_year = null;
			$award->modified = null;
			$this->_data = $award;

			return (boolean) $this->_data;
		}
		return true;
	}

}
