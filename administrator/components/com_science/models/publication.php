<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component Publication Model
 *
 * Note: Public func
 *
 * @package		Science
 * @since 		1.5
 */

class ScienceModelPublication extends JModel
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
	 * Method to set the publication identifier
	 *
	 * @access	public
	 * @param	int publication identifier
	 */
	function setId($id)
	{
		$this->_id	= $id;
		$this->_data = null;
	}

	/**
	 * Method to get a publication
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the publication data
		if ($this->_loadData())
		{
			// Nothing to be done in our case
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to delete a publication
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete( $id )
	{
		// deleting entry in jos_sci_publications
		$query = 'DELETE FROM #__sci_publications'
		. ' WHERE id = ' . $id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// deleting entry in jos_sci_publication_group_leader
		$query = 'DELETE FROM #__sci_publication_group_leader'
		. ' WHERE publication_id = ' . $id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}



	/**
	 * Method to update the publication's owner
	 *
	 * @param int $publication_id Publication
	 * @param int $old_publication_id Publication to be deleted
	 * @param int $group_leader_id New group leader
	 * @return TRUE, FALSE
	 */
	function update_group_leader($publication_id, $old_publication_id, $group_leader_id)
	{
		// update publication_group_leader
		$query = 'UPDATE #__sci_publication_group_leader AS pgl'
		. ' SET pgl.publication_id = ' . $publication_id
		. ' WHERE pgl.publication_id = ' . $old_publication_id
		. ' AND pgl.group_leader_id = ' . $group_leader_id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// remove the old publication
		if (!$this->delete( $old_publication_id )) {
			return false;
		}

		return true;
	}


	/**
	 * Method to load publication data
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
			$where[] = 'p.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT p.*, pgl.group_leader_id'
			. ' FROM `#__sci_publications` AS p'
			. ' LEFT JOIN #__sci_publication_group_leader AS pgl ON pgl.publication_id = p.id '
			. $where
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
			$item->type_id = null;
			$item->pubmed_id = null;
			$item->epub = null;
			$item->title = null;
			$item->authors = null;
			$item->journal = null;
			$item->issue = null;
			$item->volume = null;
			$item->pages = null;
			$item->year = null;
			$item->coauthors_type_id = null;
			$item->group_contribution_id = null;
			$item->citations = null;
			$item->modified = null;
			$item->impact_factor = null;
			$item->joint_publication = null;
			$item->book_title = null;
			$item->volume_title = null;
			$item->editor = null;
			$item->publisher = null;
			$item->end_date = null;
			$item->group_leader_id = null;
			$this->_data = $item;

			return (boolean) $this->_data;
		}
		return true;
	}

}