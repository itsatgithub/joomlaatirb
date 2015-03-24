<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Scientificpublication Model
 *
 * @package Scientificpublications
 */
class ScientificpublicationsModelPublication extends JModel
{
	/**
	 * Scientificpublication id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Scientificpublicatio data
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
	}

	/**
	 * Method to set the Scientificpublication identifier
	 *
	 * @access	public
	 * @param	int Scientificpublicatio identifier
	 */
	function setId($id)
	{
		// Set weblink id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a Scientificpublication
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the weblink data
		if ($this->_loadData())
		{
			// No check needed
		}
		else  $this->_initData();

		return $this->_data;
	}


	/**
	 * Method to store the Scientificpublication
	 *
	 * @param	class $data Data to store
	 * @return	boolean	True on success
	 */
	function store($data)
	{
		$row =& $this->getTable('publications');

		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
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
	 * Method to remove a publication
	 *
	 * @param array $cid id to be deleted
	 * @return	boolean	True on success
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__ga_publications'
			. ' WHERE id IN ( '.$cids.' )'
			;
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to (un)publish a publication
	 *
	 * @param array $cid id to be published
	 * @param int $publish
	 * @return	boolean	True on success
	 */
	function publish($cid = array(), $publish = 1)
	{
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__ga_publications'
			. ' SET published = '.(int) $publish
			. ' WHERE id IN ( '.$cids.' )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}


	/**
	 * Method to load content publication data
	 *
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT p.*, pt.description AS pt_description'
			. ' FROM #__ga_publications AS p'
			. ' LEFT JOIN #__ga_publication_types AS pt ON pt.id = p.type_id'
			. ' WHERE p.id = '.(int) $this->_id;
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the publication data
	 *
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$item = new stdClass();
			$item->id = 0;
			$item->type_id = 0;
			$item->title = '';
			$item->authors = '';
			$item->journal = '';
			$item->issue = '';
			$item->volume = '';
			$item->pages = '';
			$item->year = '';
			$item->citations = '';
			$item->modified = 0;
			$item->published = '';
			$this->_data = $item;
			return (boolean) $this->_data;
		}
		return true;
	}
}