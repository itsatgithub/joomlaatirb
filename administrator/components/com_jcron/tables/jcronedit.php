<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableJCronEdit extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	
	var $task = null;
	var $type = null;
        var $published = null;
	var $mhdmd = null;
	var $file = null;
	var $ran_at = null;
	var $ok = null;
        var $log_text = null;
        var $unix_mhdmd = null;
        var $last_run = null;
	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableJCronEdit(& $db) {
		parent::__construct('#__jcron_tasks', 'id', $db);
	}
}