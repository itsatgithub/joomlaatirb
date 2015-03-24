<?php
/**
 * Joomla! 1.5 component Science
 *
 * @version $Id:  2009-07-07 09:14:21 svn $
 * @author
 * @package Joomla
 * @subpackage Science
 * @license GNU/GPL
 *
 * Manages the Science Production
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

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
class TableCollaborationscollaborationtypes extends JTable {

	var $id = null;
	var $collaboration_id = null;
	var $collaboration_type_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__sci_collaborations_collaboration_types', 'id', $db);
	}

	/**
	 * Delete all the rows belonging to a particular collaboration
	 *
	 * Is based in the delete function defined in libraries/joomla/database/table.php
	 *
	 * @access public
	 * @return true if successful otherwise returns and error message
	 */
	function delete_all_collaboration_types( $oid=null )
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