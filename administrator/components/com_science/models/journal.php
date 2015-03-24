<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

class ScienceModelJournal extends JModel
{
	/**
	 * Congress id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Journal data
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

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit = JRequest::getVar('edit',true);
		if($edit)
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int congress identifier
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id = $id;
		$this->_data = null;
	}

	/**
	 * Method to get data
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the weblink data
		if ($this->_loadData())
		{
			/*
			 // Initialize some variables
			 $user = &JFactory::getUser();

			 // Check to see if the category is published
			 if (!$this->_data->cat_pub) {
				JError::raiseError( 404, JText::_("Resource Not Found") );
				return;
				}

				// Check whether category access level allows access
				if ($this->_data->cat_access > $user->get('aid', 0)) {
				JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
				return;
				}
				*/
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to get impact factor data
	 *
	 * @param $year
	 */
	function &getImpactFactorData($year)
	{
		$query = 'SELECT impact_factor'
		. ' FROM #__sci_journal_impact_factors'
		. ' WHERE journal_id = ' . (int) $this->_id
		. ' AND year = \'' . $year. '\''
		;
		$this->_db->setQuery($query);
		$this->_impact_factor_data = $this->_db->loadResult();
		if ( $this->_impact_factor_data ) {
			return $this->_impact_factor_data;
		} else {
			return 0; // default value
		}
	}

	/**
	 * Method to store the journal
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		// get de right table to work with
		$row =& $this->getTable( 'journal' );

		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the web link table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Save impact factor data
	 *
	 * @param $year
	 * @param $value
	 */
	function storeImpactFactor($year, $value)
	{
		$query = 'SELECT *'
		. ' FROM #__sci_journal_impact_factors'
		. ' WHERE journal_id = ' . (int) $this->_id
		. ' AND year = \'' . $year . '\''
		;
		$this->_db->setQuery($query);
		$row = $this->_db->loadObject();
		if ($row)
		{
			// update
			$query = 'UPDATE #__sci_journal_impact_factors'
			. ' SET impact_factor = ' . $value
			. ' WHERE journal_id = ' . (int) $this->_id
			. ' AND year = \'' . $year . '\''
			;
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				JError::raiseError( 500, $this->_db->stderr());
			}
				
		} else {
				
			// insert
			$query = 'INSERT INTO #__sci_journal_impact_factors ( journal_id, year, impact_factor )'
			. ' VALUES ( ' . (int) $this->_id .', \'' . $year . '\', ' . $value.' )'
			;
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				JError::raiseError( 500, $this->_db->stderr());
			}
		}
	}

	/**
	 * Method to remove a journal
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
				
			// remove the journals...
			$query = 'DELETE FROM #__sci_journals'
			. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
				
			// ... and remove the journal_impact_factors
			$query = 'DELETE FROM #__sci_journal_impact_factors'
			. ' WHERE journal_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to load content journal data
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
			$query = 'SELECT j.*'
			. ' FROM #__sci_journals AS j'
			. ' WHERE j.id = ' . (int) $this->_id
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the journal data
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
			$item->description = null;
			$item->short_description = null;
			$item->order = null;

			$this->_data = $item;
			return (boolean) $this->_data;
		}
		return true;
	}

}