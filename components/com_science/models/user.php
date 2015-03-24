<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Science Component User Model
 *
 * @package		Science
 * @since 		1.5
 */

class ScienceModelUser extends JModel
{
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
	 * Method to check the Administrator condition
	 *
	 * @access public
	 * @params $username User name
	 * @return TRUE, FALSE
	 */
	function isAdministrator($username)
	{
		$query = 'SELECT u.user_username'
		. ' FROM `#__sci_users` AS u'
		. ' LEFT JOIN `#__sci_user_roles` AS ro on ro.id = u.rol_id'
		. ' WHERE u.user_username = \'' . $username . '\''
		. ' AND ro.description = \'administrator\' '
		. ' AND u.verified_by_admin = 1'
		;
		$this->_db->setQuery($query);
		return (boolean) $this->_db->loadObject();
	}

	/**
	 * Method to check the Part Administrator condition
	 *
	 * @access public
	 * @params $username User name
	 * @return TRUE, FALSE
	 */
	function isPartAdministrator($username)
	{
		$query = 'SELECT u.user_username'
		. ' FROM `#__sci_users` AS u'
		. ' LEFT JOIN `#__sci_user_roles` AS ro on ro.id = u.rol_id'
		. ' WHERE u.user_username = \'' . $username . '\''
		. ' AND ro.description = \'part_administrator\' '
		. ' AND u.verified_by_admin = 1'
		;

		$this->_db->setQuery($query);
		return (boolean) $this->_db->loadObject();
	}

	/**
	 * Method to check the Reader condition
	 *
	 * @access public
	 * @params $username User name
	 * @return TRUE, FALSE
	 */
	function isReader($username)
	{
		$query = 'SELECT u.user_username'
		. ' FROM `#__sci_users` AS u'
		. ' LEFT JOIN `#__sci_user_roles` AS ro on ro.id = u.rol_id'
		. ' WHERE u.user_username = \'' . $username . '\''
		. ' AND ro.description = \'reader\' '
		. ' AND u.verified_by_admin = 1'
		;
		$this->_db->setQuery($query);
		return (boolean) $this->_db->loadObject();
	}

	/**
	 * Method to check the Group Leader condition
	 *
	 * @access public
	 * @params $username User name
	 * @return TRUE, FALSE
	 */
	function isGroupLeader($username)
	{
		$query = 'SELECT u.user_username'
		. ' FROM `#__sci_users` AS u'
		. ' LEFT JOIN `#__sci_user_roles` AS ro on ro.id = u.rol_id'
		. ' WHERE u.user_username = \'' . $username . '\''
		. ' AND ro.description = \'group_leader\''
		. ' AND u.verified_by_admin = 1'
		;
		$this->_db->setQuery($query);
		return (boolean) $this->_db->loadObject();
	}

	/**
	 * Method to get the user rights for a particular aplication element (view or report)
	 *
	 * @access public
	 * @params
	 * $username User name
	 * $item Element to verify the permissions with
	 * @return Permission (read, write, NULL)
	 */
	function getRights( $username, $item )
	{
		// 21-02-2011 Updated, so the username can be also the assigned_to column in case of GLs
		$query = 'SELECT r.description'
		. ' FROM `#__sci_user_rights` AS r'
		. ' LEFT JOIN `#__sci_user_rol_item_right` AS rir ON rir.right_id = r.id'
		. ' LEFT JOIN `#__sci_user_items` AS i ON i.id = rir.item_id'
		. ' LEFT JOIN `#__sci_user_roles` AS ro on ro.id = rir.rol_id'
		. ' LEFT JOIN `#__sci_users` AS u ON u.rol_id = ro.id'
		. ' WHERE (u.user_username = \'' . $username . '\' OR u.assigned_to = \'' . $username . '\')'
		. ' AND i.description = \'' . $item . '\''
		;
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		return $result;
	}


	/**
	 * Method to get the user assigned to a particular user
	 *
	 * @param username user name
	 * @return username assigned to the user
	 */
	function getAssigned( $username )
	{
		$query = 'SELECT assigned_to'
		. ' FROM `#__sci_users` AS u'
		. ' WHERE u.user_username = \'' . $username . '\''
		;
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		return $result;
	}
	
	
	/**
	 * Method to verify the read permission for a particular publication, thesis, etc...
	 *
	 * @access public
	 * @params
	 * $username User name
	 * $item Element to verify the permissions with
	 * $id Particular element to verify
	 * @return TRUE, FALSE
	 */
	function canRead( $username, $item, $id )
	{
		// if it does not have permit, go FALSE
		if ( !$this->getRights( $username, $item )) {
			return false;
		}

		// if group leader, check particular element
		if ( $this->isGroupLeader( $username )) {

			// check that the item belongs to the group leader
			switch ( $item ) {
				case 'view_publication':
				case 'view_publications':
				case 'report_publications': {
					$table = '`#__sci_publications`';
				} break;

				case 'view_collaboration':
				case 'view_collaborations':
				case 'report_collaborations': {
					$table = '`#__sci_collaboratioins`';
				} break;

				case 'view_project':
				case 'view_projects':
				case 'report_projects': {
					$table = '`#__sci_projects`';
				} break;

				case 'view_thesis':
				case 'view_theses':
				case 'report_theses': {
					$table = '`#__sci_theses`';
				} break;

				case 'view_patent':
				case 'view_patents':
				case 'report_patents': {
					$table = '`#__sci_patents`';
				} break;

				case 'view_award':
				case 'view_awards':
				case 'report_awards': {
					$table = '`#__sci_awards`';
				} break;
			}
			
			// 21-02-2011 the username has an GL assigned_to, and it shall be used on the comparison
			$assigned_to = $this->getAssigned( $username );
			
			$query = 'SELECT gl.user_username'
			. ' FROM `#__sci_group_leaders` AS gl'
			. ' LEFT JOIN ' . $table . ' AS t on t.group_leader_id = gl.id'
			. ' WHERE t.id = ' . $id
			;
			$this->_db->setQuery($query);
			$gl_username = $this->_db->loadResult();
			if ( $gl_username == $assigned_to ) {
				return true;
			} else {
				return false;
			}
		} else {
			// has rights and he is not a group leader
			return true;
		}
	}

	/**
	 * Method to verify the write permission for a particular publication, thesis, etc...
	 *
	 * @access public
	 * @params
	 * $username User name
	 * $item Element to verify the permissions with
	 * $id Particular element to verify
	 * @return TRUE, FALSE
	 */
	function canWrite( $username, $item, $id )
	{
		// if it does not have permit, go FALSE
		if ( $this->getRights( $username, $item ) != 'write' ) {
			return false;
		}

		// if group leader, check particular element
		if ( $this->isGroupLeader( $username )) {
			
			// check that the item belongs to the group leader
			switch ( $item ) {
				case 'view_publication':
				case 'view_publications':
				case 'report_publications': {
					$query = 'SELECT gl.user_username'
					. ' FROM `#__sci_group_leaders` AS gl'
					. ' LEFT JOIN `#__sci_publication_group_leader` AS pgl on pgl.group_leader_id = gl.id'
					. ' LEFT JOIN `#__sci_publications` AS t on t.id = pgl.publication_id'
					. ' WHERE t.id = ' . $id
					;
				} break;

				case 'view_collaboration':
				case 'view_collaborations':
				case 'report_collaborations': {
					$query = 'SELECT gl.user_username'
					. ' FROM `#__sci_group_leaders` AS gl'
					. ' LEFT JOIN `#__sci_collaborations` AS t on t.group_leader_id = gl.id'
					. ' WHERE t.id = ' . $id
					;
				} break;

				case 'view_project':
				case 'view_projects':
				case 'report_projects': {
					$query = 'SELECT gl.user_username'
					. ' FROM `#__sci_group_leaders` AS gl'
					. ' LEFT JOIN `#__sci_projects` AS t on t.group_leader_id = gl.id'
					. ' WHERE t.id = ' . $id
					;
				} break;

				case 'view_thesis':
				case 'view_theses':
				case 'report_theses': {
					$query = 'SELECT gl.user_username'
					. ' FROM `#__sci_group_leaders` AS gl'
					. ' LEFT JOIN `#__sci_theses` AS t on t.group_leader_id = gl.id'
					. ' WHERE t.id = ' . $id
					;
				} break;

				case 'view_patent':
				case 'view_patents':
				case 'report_patents': {
					$query = 'SELECT gl.user_username'
					. ' FROM `#__sci_group_leaders` AS gl'
					. ' LEFT JOIN `#__sci_patents` AS t on t.group_leader_id = gl.id'
					. ' WHERE t.id = ' . $id
					;
				} break;

				case 'view_award':
				case 'view_awards':
				case 'report_awards': {
					$query = 'SELECT gl.user_username'
					. ' FROM `#__sci_group_leaders` AS gl'
					. ' LEFT JOIN `#__sci_awards` AS t on t.group_leader_id = gl.id'
					. ' WHERE t.id = ' . $id
					;
				} break;
			}
			
			// 21-02-2011 the username has an GL assigned_to, and it shall be used on the comparison
			$assigned_to = $this->getAssigned( $username );
			
			$this->_db->setQuery($query);
			$gl_username = $this->_db->loadResult();
			if ( $gl_username == $assigned_to ) {
				return true;
			} else {
				return false;
			}
		} else {
			// has rights and he is not a group leader
			return true;
		}
	}
}
?>
