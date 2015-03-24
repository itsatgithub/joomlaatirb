<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Table class
 *
 * @package          Joomla
 * @subpackage		Science
 */
class TableCollaborationscollaborationsectors extends JTable {

	var $id = null;
	var $collaboration_id = null;
	var $collaboration_sector_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_collaborations_collaboration_sectors', 'id', $db);
	}

	/**
	 * Delete all the rows belonging to a particular collaboration
	 *
	 * Is based in the delete function defined in libraries/joomla/database/table.php
	 *
	 * @access public
	 * @return true if successful otherwise returns and error message
	 */
	function delete_all_collaboration_sectors( $oid=null )
	{
		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval( $oid );
		}

		$query = 'DELETE FROM '.$this->_db->nameQuote( $this->_tbl ).
				' WHERE collaboration_id = '. $this->_db->Quote($this->$k);
		$this->_db->setQuery( $query );

		if ($this->_db->query())
		{
			return true;
		}
		else
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	}

}
?>