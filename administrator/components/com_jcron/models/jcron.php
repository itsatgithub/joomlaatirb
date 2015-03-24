<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );


class JCronModelJCron extends JModel
{
	
	var $_data;
	//////////////////////pagination///////////////
	
	var $_total = null;
	var $_pagination = null;
	
        //////////////////////pagination///////////////

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$query = ' SELECT * '
			. ' FROM #__jcron_tasks '
		;

		return $query;
	}

	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit')); 
		}

		return $this->_data;
	}
	
	
	//////////////////////pagination///////////////
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		// Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

	}

	
	
	function getTotal()
        {
            // Load the content if it doesn't already exist
            if (empty($this->_total)) {
                $query = $this->_buildQuery();
                $this->_total = $this->_getListCount($query);
            }
            return $this->_total;
        }
  
  
        function getPagination()
        {
              // Load the content if it doesn't already exist
              if (empty($this->_pagination)) {
                  jimport('joomla.html.pagination');
                  $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
              }
              return $this->_pagination;
        }

        //////////////////////pagination///////////////
}