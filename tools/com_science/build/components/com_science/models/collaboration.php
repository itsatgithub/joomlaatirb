<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component Collaboration Model
 *
 * @package		Register
 * @since 1.5
 */

class ScienceModelCollaboration extends JModel
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
	 * Abstract data
	 *
	 * @var array
	 */
	var $_collaboration_details = null;

	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_collaboration_sectors = null;

	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_collaboration_levels = null;

	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_collaboration_types = null;

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
		$this->_collaboration_details = null;
		$this->_collaboration_sectors = null;
		$this->_collaboration_levels = null;
		$this->_collaboration_types = null;
	}

	/**
	 * Method to get a collaboration
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the collaboration data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to get sectors from a particular collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function &getCollaborationSectorsArray()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationSectors())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationSectors();

		// returning the array
		$aux = array();
		foreach ($this->_collaboration_sectors as $cs)
		{
			$aux[] = $cs->collaboration_sector_id;
		}
		return $aux;
	}



	/**
	 * Method to get sectors description from a particular collaboration
	 *
	 * @access	public
	 * @return	description array
	 */
	function &getCollaborationSectorsArrayDescription()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationSectors())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationSectors();

		// returning the array
		$aux = array();
		foreach ($this->_collaboration_sectors as $cs)
		{
			$aux[] = $cs->description;
		}
		return $aux;
	}




	/**
	 * Method to get sectors from a particular collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function &getCollaborationSectorsString()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationSectors())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationSectors();

		// returning the array
		$aux = '';
		foreach ($this->_collaboration_sectors as $cs)
		{
			$aux .= $cs->description . ", ";
		}
		return $aux;
	}



	/**
	 * Method to get the number of sectors from a particular collaboration
	 *
	 * @access	public
	 * @return	number of sectors
	 */
	function getCollaborationSectorsNumber()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationSectors())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationSectors();

		// returning the number
		return count($this->_collaboration_sectors);
	}



	/**
	 * Method to get levels from a particular collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function &getCollaborationLevelsArray()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationLevels())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationLevels();

		// returning the array
		$aux = array();
		foreach ($this->_collaboration_levels as $cl)
		{
			$aux[] = $cl->collaboration_level_id;
		}
		return $aux;
	}



	/**
	 * Method to get levels from a particular collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function &getCollaborationLevelsString()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationLevels())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationLevels();

		// returning the array
		$aux = '';
		foreach ($this->_collaboration_levels as $cl)
		{
			$aux .= $cl->description . ", ";
		}
		// delete last two characters
		$aux = substr($aux, 0, -2);
		return $aux;
	}


	/**
	 * Method to get types from a particular collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function &getCollaborationTypesArray()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationTypes())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationTypes();

		// returning the array
		$aux = array();
		foreach ($this->_collaboration_types as $ct)
		{
			$aux[] = $ct->collaboration_type_id;
		}
		return $aux;
	}


	/**
	 * Method to get types from a particular collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function &getCollaborationTypesString()
	{
		// Load the collaboration data
		if ($this->_loadCollaborationTypes())
		{
			// Nothing to be done in our case
		}
		else  $this->_initCollaborationTypes();

		// returning the array
		$aux = '';
		foreach ($this->_collaboration_types as $ct)
		{
			$aux .= $ct->description . ", ";
		}
		// delete last two characters
		$aux = substr($aux, 0, -2);
		return $aux;
	}




	/**
	 * Method to get collaboration details
	 *
	 * @since 1.5
	 */
	function &getCollaborationDetails()
	{
		$query = 'SELECT cd.*, co.printable_name as country'
		. ' FROM #__sci_collaboration_details AS cd'
		. ' LEFT JOIN #__sci_countries AS co ON cd.country_id = co.id'
		. ' WHERE cd.collaboration_id = ' . $this->_id
		. ' ORDER BY cd.id'
		;
		$this->_db->setQuery($query);
		$collaboration_details = $this->_db->loadObjectList();
		return $collaboration_details;
	}



	/**
	 * Method to store the collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		// store the collaboration
		$row =& $this->getTable('collaboration');

		// Bind the form fields to the table
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
		}

		// sectors. delete previous entries before adding the new ones
		$row_sectors =& $this->getTable('collaborationscollaborationsectors');
		if (!$row_sectors->delete_all_collaboration_sectors($row->id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// auxiliar array to bind collaboration sector data
		$aux = array();
		$aux[collaboration_id] = $row->id; // collaboration id

		// savings the collaboration sectors
		foreach ($data[se_cid] as $aux[collaboration_sector_id])
		{
			// store the collaboration sectors
			$row_sectors =& $this->getTable('collaborationscollaborationsectors');

			// Bind the form fields to the table
			if (!$row_sectors->bind($aux)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			// Store the web link table to the database
			if (!$row_sectors->store()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		// levels. delete previous entries before adding the new ones
		$row_levels =& $this->getTable('collaborationscollaborationlevels');
		if (!$row_levels->delete_all_collaboration_levels($row->id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// auxiliar array to bind collaboration level data
		$aux = array();
		$aux[collaboration_id] = $row->id; // collaboration id

		// savings the collaboration levels
		foreach ($data[le_cid] as $aux[collaboration_level_id])
		{
			// store the collaboration levels
			$row_levels =& $this->getTable('collaborationscollaborationlevels');

			// Bind the form fields to the table
			if (!$row_levels->bind($aux)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			// Store the web link table to the database
			if (!$row_levels->store()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		// types. delete previous entries before adding the new ones
		$row_types =& $this->getTable('collaborationscollaborationtypes');
		if (!$row_types->delete_all_collaboration_types($row->id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// auxiliar array to bind collaboration type data
		$aux = array();
		$aux[collaboration_id] = $row->id; // collaboration id

		// savings the collaboration types
		foreach ($data[ty_cid] as $aux[collaboration_type_id])
		{
			// store the collaboration types
			$row_types =& $this->getTable('collaborationscollaborationtypes');

			// Bind the form fields to the table
			if (!$row_types->bind($aux)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			// Store the web link table to the database
			if (!$row_types->store()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		// returns the id
		return $row->id;
	}

	/**
	 * Method to delete a collaboration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete()
	{
		// permission control. Delete actions are performed from the 'collaborations' view.
		$user =& JFactory::getUser();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) 
		{
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_collaborations', $this->_id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_collaborations', $this->_id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}


		// delete the collaboration
		$query = 'DELETE FROM #__sci_collaborations'
		. ' WHERE id = ' . $this->_id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// delete the collaboration details
		$query = 'DELETE FROM #__sci_collaboration_details'
		. ' WHERE collaboration_id = ' . $this->_id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// delete the collaboration levels
		$query = 'DELETE FROM #__sci_collaborations_collaboration_levels'
		. ' WHERE collaboration_id = ' . $this->_id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// delete the collaboration sectors
		$query = 'DELETE FROM #__sci_collaborations_collaboration_sectors'
		. ' WHERE collaboration_id = ' . $this->_id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// delete the collaboration types
		$query = 'DELETE FROM #__sci_collaborations_collaboration_types'
		. ' WHERE collaboration_id = ' . $this->_id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}


	/**
	 * Method to load collaboration data
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
			$where[] = 'c.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT c.*'
			. ' FROM `#__sci_collaborations` AS c'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = c.group_leader_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}



	/**
	 * Method to load collaboration sectors
	 *
	 * @return	boolean	True on success, False otherwise
	 */
	function _loadCollaborationSectors()
	{
		if (empty($this->_collaboration_sectors))
		{
			// empty condition
			$where = array();

			// get only the _id
			$where[] = 'c.collaboration_id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT c.collaboration_sector_id, cs.description'
			. ' FROM `#__sci_collaborations_collaboration_sectors` AS c'
			. ' LEFT JOIN `#__sci_collaboration_sectors` AS cs ON cs.id = c.collaboration_sector_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_collaboration_sectors = $this->_db->loadObjectList();
			return (boolean) $this->_collaboration_sectors;
		}
		return true;
	}



	/**
	 * Method to load collaboration levels
	 *
	 * @return	boolean	True on success, False otherwise
	 */
	function _loadCollaborationLevels()
	{
		if (empty($this->_collaboration_levels))
		{
			// empty condition
			$where = array();

			// get only the _id
			$where[] = 'c.collaboration_id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT c.collaboration_level_id, cl.description'
			. ' FROM `#__sci_collaborations_collaboration_levels` AS c'
			. ' LEFT JOIN `#__sci_collaboration_levels` AS cl ON cl.id = c.collaboration_level_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_collaboration_levels = $this->_db->loadObjectList();
			return (boolean) $this->_collaboration_levels;
		}
		return true;
	}



	/**
	 * Method to load collaboration types
	 *
	 * @return	boolean	True on success, False otherwise
	 */
	function _loadCollaborationTypes()
	{
		if (empty($this->_collaboration_types))
		{
			// empty condition
			$where = array();

			// get only the _id
			$where[] = 'c.collaboration_id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT c.collaboration_type_id, ct.description'
			. ' FROM `#__sci_collaborations_collaboration_types` AS c'
			. ' LEFT JOIN `#__sci_collaboration_types` AS ct ON ct.id = c.collaboration_type_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_collaboration_types = $this->_db->loadObjectList();
			return (boolean) $this->_collaboration_types;
		}
		return true;
	}



	/**
	 * Method to initialise the collaboration data
	 *
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$collaboration = new stdClass();
			$collaboration->id = 0;
			$collaboration->group_leader_id = 0;
			$collaboration->title = null;
			$collaboration->start_year = null;
			$collaboration->end_year = null;
			$collaboration->modified = null;
			$this->_data = $collaboration;

			return (boolean) $this->_data;
		}
		return true;
	}



	/**
	 * Method to initialise the collaboration sectors array data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */

	function _initCollaborationSectors()
	{
		$this->_collaboration_sectors = array();
		return (boolean) $this->_collaboration_sectors;
	}



	/**
	 * Method to initialise the collaboration levels array data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */

	function _initCollaborationLevels()
	{
		$this->_collaboration_levels = array();
		return (boolean) $this->_collaboration_levels;
	}



	/**
	 * Method to initialise the collaboration types array data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */

	function _initCollaborationTypes()
	{
		$this->_collaboration_types = array();
		return (boolean) $this->_collaboration_types;
	}
}