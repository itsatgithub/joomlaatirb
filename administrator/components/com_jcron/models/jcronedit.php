<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');


class JCronModelJCronEdit extends JModel
{
	
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a task
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__jcron_tasks '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
            JRequest::checkToken() or jexit( 'Invalid Token' );
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );
		if($data['published'] == "on")
			$data['published'] = 1;
		else
			$data['published'] = 0;

                if($data['ok'] == "1")
			$data['ok'] = 1;
		else
			$data['ok'] = 0;

                if($data['c_crontab'] != "on")
                    $data['mhdmd'] = $data['minute2']." ".$data['hour2']." ".$data['day2']." ".$data['month2']." ".$data['weekday2'];
                else
                    $data['mhdmd'] = $data['crontab'];

                $data['task'] = $data['ttask'];

                $data['ran_at'] = date('Y-m-d H:i:s');

                $bits = explode(' ',$data['mhdmd']);
                if(strpos($bits[0],'/'))
                {
                    $min = explode('/',$bits[0]);
                    $minutes = array();
                    for($i=0;$i<60;$i+=$min[1])
                        $minutes[]=$i;
                    sort($minutes);
                    $bits[0] = implode(',',$minutes);
                }

                if(strpos($bits[1],'/'))
                {
                    $hour = explode('/',$bits[1]);
                    $hours = array();
                    for($i=0;$i<24;$i+=$hour[1])
                    $hours[]=$i;
                    sort($hours);
                    $bits[1] = implode(',',$hours);
                }

                $data['unix_mhdmd'] = implode(" ",$bits);
				
				$data['last_run'] = 1;

		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return array(false);
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return array(false);
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return array(false);
		}

		return array(true,$row->id);
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
             JRequest::checkToken() or jexit( 'Invalid Token' );
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	
	
	function publish()
	{
             JRequest::checkToken() or jexit( 'Invalid Token' );
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();
		if (!$row->publish( $cids, 1)) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}
	
	
	function unpublish()
	{
             JRequest::checkToken() or jexit( 'Invalid Token' );
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();
		if (!$row->publish( $cids, 0)) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}

}
