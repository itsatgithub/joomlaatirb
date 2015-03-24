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

class ScienceModelProject extends JModel
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
		$row =& $this->getTable('project');

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
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_projects', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_projects', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}

		$query = 'DELETE FROM #__sci_projects'
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

			$query = 'SELECT p.*, pa.description as action_type, pg.short_description as grant_type'
			. ', pg.short_description as grant_type_description, po.description as owner'
			. ', pf.short_description as funding_entity'
			. ', pf.short_description as funding_entity_description, pr.description as role'
			. ', un.short_description as uneix'
			. ', pi.name as pi_principal_investigator'
			. ' FROM `#__sci_projects` AS p'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = p.group_leader_id'
			. ' LEFT JOIN `#__sci_project_action_types` AS pa ON p.action_type_id = pa.id'
			. ' LEFT JOIN `#__sci_project_grant_types` AS pg ON p.grant_type_id = pg.id'
			. ' LEFT JOIN `#__sci_project_owners` AS po ON p.owner_id = po.id'
			. ' LEFT JOIN `#__sci_project_roles` AS pr ON p.role_id = pr.id'
			. ' LEFT JOIN `#__sci_project_funding_entities` AS pf ON p.funding_entity_id = pf.id'
			. ' LEFT JOIN `#__sci_project_uneix` AS un ON p.uneix_id = un.id'
			. ' LEFT JOIN `#__sci_principal_investigators` AS pi ON p.principal_investigator_id = pi.id'
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

			$query = 'SELECT p.*, pa.description as action_type, pg.description as grant_type, po.description as owner, pt.description as timing, pf.description as funding_entity, pr.description as role'
			. ' FROM #__sci_projects AS p'
			. ' LEFT JOIN #__sci_project_action_types AS pa'
			. ' ON p.action_type_id = pa.id'
			. ' LEFT JOIN #__sci_project_grant_types AS pg'
			. ' ON p.grant_type_id = pg.id'
			. ' LEFT JOIN #__sci_project_owners AS po'
			. ' ON p.owner_id = po.id'
			. ' LEFT JOIN #__sci_project_timings AS pt'
			. ' ON p.timing_id = pt.id'
			. ' LEFT JOIN #__sci_project_roles AS pr'
			. ' ON p.role_id = pr.id'
			. ' LEFT JOIN #__sci_project_funding_entities AS pf'
			. ' ON p.funding_entity_id = pf.id'
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
			$project->principal_investigator = null;
			$project->principal_investigator_id = null;
			$project->beneficiary = null;
			$project->title = null;
			$project->acronym = null;
			$project->reference = null;
			$project->start_date = null;
			$project->awarding_date = null;
			$project->end_date = null;
			$project->irb_code = null;
			$project->total_granted_budget = null;
			$project->total_budget = null;
			$project->overheads_total_budget = null;
			$project->year_budget_year_1 = null;
			$project->budget_year_1 = null;
			$project->year_overheads_year_1 = null;
			$project->overheads_year_1 = null;
			$project->year_budget_year_2 = null;
			$project->budget_year_2 = null;
			$project->year_overheads_year_2 = null;
			$project->overheads_year_2 = null;
			$project->year_budget_year_3 = null;
			$project->budget_year_3 = null;
			$project->year_overheads_year_3 = null;
			$project->overheads_year_3 = null;
			$project->year_budget_year_4 = null;
			$project->budget_year_4 = null;
			$project->year_overheads_year_4 = null;
			$project->overheads_year_4 = null;
			$project->year_budget_year_5 = null;
			$project->budget_year_5 = null;
			$project->year_overheads_year_5 = null;
			$project->overheads_year_5 = null;
			$project->year_budget_year_6 = null;
			$project->budget_year_6 = null;
			$project->year_overheads_year_6 = null;
			$project->overheads_year_6 = null;
			$project->funding_entity_id = null;
			$project->funding_entity_specific = null;
			$project->grant_type_id = null;
			$project->grant_type_specific = null;
			$project->owner_id = null;
			$project->call = null;
			$project->action_type_id = null;
			$project->role_id = null;
			$project->consortium = null;
			$project->modified = null;
			$project->uneix_id = null;
			$project->comments = null;
			$project->coordinator = null;
			$this->_data = $project;

			return (boolean) $this->_data;
		}
		return true;
	}

}
