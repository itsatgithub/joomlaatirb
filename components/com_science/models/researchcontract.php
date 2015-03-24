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

class ScienceModelResearchcontract extends JModel
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
	 * Method to get a project
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the project data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to store the project
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('researchcontract');

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
	 * Method to delete a project
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
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_researchcontracts', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_researchcontracts', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}

		$query = 'DELETE FROM #__sci_research_contracts'
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
	 * Method to load project data
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

			$query = 'SELECT p.*, gl.name AS group_leader, fs.description AS funding_sector'
			. ' FROM `#__sci_research_contracts` AS p'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
			. ' LEFT JOIN `#__sci_research_contracts_funding_sectors` AS fs ON fs.id = p.funding_sector_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the project data
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
			$project = new stdClass();
			$project->id = 0;
			$project->group_leader_id = 0;
			$project->irb_code = null;
			$project->acronym = null;
			$project->company = null;			
			$project->principal_investigator = null;
			$project->start_date = null;
			$project->end_date = null;
			$project->funding_sector_id = 0;
			$project->budget = null;
			$project->overhead = null;
			$project->comments = null;
			$this->_data = $project;

			return (boolean) $this->_data;
		}
		return true;
	}

}