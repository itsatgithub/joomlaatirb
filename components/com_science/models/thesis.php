<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component Thesis Model
 *
 * @package		Register
 * @since 1.5
 */

class ScienceModelThesis extends JModel
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
	 * Method to get a thesis
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the thesis data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to store the thesis
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('thesis');

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
	 * Method to delete a thesis
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
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_theses', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_theses', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}

		$query = 'DELETE FROM #__sci_theses'
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
	 * Method to load thesis data
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
			$where[] = 't.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT t.*, tl.description as language, th.description as honor'
			. ' FROM `#__sci_theses` AS t'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = t.group_leader_id'
			. ' LEFT JOIN `#__sci_thesis_languages` AS tl ON t.language_id = tl.id'
			. ' LEFT JOIN `#__sci_thesis_honors` AS th ON t.honor_id = th.id'
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
				
			$query = 'SELECT t.*, tl.description as language, th.description as honor'
			. ' FROM #__sci_theses AS t'
			. ' LEFT JOIN #__sci_thesis_languages AS tl'
			. ' ON t.language_id = tl.id'
			. ' LEFT JOIN #__sci_thesis_honors AS th'
			. ' ON t.honor_id = th.id'
			. $join_group_leader
			. ' WHERE t.id = ' . $this->_id . ' '
			. $check_group_leader
			;


			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;*/
		}
		return true;
	}

	/**
	 * Method to initialise the thesis data
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
			$thesis = new stdClass();
			$thesis->id = 0;
			$thesis->group_leader_id = 0;
			$thesis->title = null;
			$thesis->author = null;
			$thesis->university = null;
			$thesis->year = null;
			$thesis->lecture_date = null;
			$thesis->language_id = null;
			$thesis->director = null;
			$thesis->codirector = null;
			$thesis->tutor = null;
			$thesis->honor_id = null;
			$thesis->modified = null;
			$this->_data = $thesis;

			return (boolean) $this->_data;
		}
		return true;
	}

}
