<?php
/**
 * @package		Science
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component Collaboration Details Model
 *
 * @package		Science
 * @since 1.5
 */
class ScienceModelCollaborationDetails extends JModel
{
	/**
	 * Author id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Author data
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
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int identifier
	 */
	function setId($id)
	{
		$this->_id	= $id;
		$this->_data = null;
	}

	/**
	 * Method to get data
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to store data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('collaborationdetails');

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
	 * Method to delete
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($id,$collaboration_id)
	{
		// permission control. Delete actions are performed from the 'collaborations' view.
		$user =& JFactory::getUser();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) 
		{
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_collaborations', $collaboration_id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_collaborations', $collaboration_id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}
		
		$row =& $this->getTable('collaborationdetails');
		if (!$row->delete($id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	/**
	 * Method to load collaboration details data
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
			$query = 'SELECT c.*, co.name as country'
			. ' FROM #__sci_collaboration_details AS c'
			. ' LEFT JOIN #__sci_countries AS co'
			. ' ON c.country_id = co.id'
			. ' WHERE c.id = ' . $this->_id
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the collaboration details data
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
			$collaborationdetails = new stdClass();
			$collaborationdetails->id = 0;
			$collaborationdetails->collaboration_id = null;
			$collaborationdetails->research_name = null;
			$collaborationdetails->institution = null;
			$collaborationdetails->city = null;
			$collaborationdetails->country_id = null;
			$this->_data = $collaborationdetails;
			return (boolean) $this->_data;
		}
		return true;
	}
}