<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class IrbtoolsModelFilemanager extends JModel
{
	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_data = null;
	
    /**
	 * Constructor
	 */
	function __construct()
	{
		$this->_initData();
		
		parent::__construct();
    }
    
    function &getData()
    {
		$option = array(); //prevent problems
		 
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = 'irbsvr3.irb.pcb.ub.es';    // Database host name
		$option['user']     = 'intranetuser';       // User for database authentication
		$option['password'] = '72T4manV';   // Password for database authentication
		$option['database'] = 'irbdb';      // Database name
		$option['prefix']   = '';             // Database prefix (may be empty)
		 
		$irbdb =& JDatabase::getInstance( $option );
    	    	
    	// get data
    	/*
    	$query = "SELECT vpp.personalcode AS personalcode"
		. ", vpp.name AS name"
		. ", vpp.surname1 AS surname"
		. ", vpp.organization_unit AS department"
		. ", vpp.professional_unit AS unit"
		. ", vpp.research_group AS research_group"
		. ", vpp.email AS email"
		. ", vpp.professional_phone AS phone"
		. ", vpp.position AS position"
		. ", vpp.location AS location"
		. ", p.second_affiliation AS second_affiliation"
		. " FROM view_personal_professional AS vpp"		
		. " LEFT JOIN personal AS p ON p.personalcode = vpp.personalcode"
		;	
		*/
		
    	$query = "SELECT DISTINCT p.personalcode"
		. ", p.name AS name"
		. ", p.surname1 AS surname1"
		. ", p.surname2 AS surname2"
		. ", ge.description AS gender"
		. ", ou.description AS department"
		. ", u.description AS unit"
		. ", rg.description AS research_group"
		. ", pro.email AS email"
		. ", pro.phone AS phone"
		. ", po.description AS position"
		. ", pro.location AS location"
		. ", p.second_affiliation AS second_affiliation"
		. " FROM personal AS p"
		. " LEFT JOIN professional AS pro ON pro.professional_personal = p.personalcode"
		. " LEFT JOIN position AS po ON po.positioncode = pro.position"
		. " LEFT JOIN unit AS u ON u.unitcode = pro.professional_unit"
		. " LEFT JOIN research_group AS rg ON rg.research_groupcode = pro.research_group"
		. " LEFT JOIN organization_unit AS ou ON ou.organization_unitcode = u.organization_unit"
		. " LEFT JOIN gender AS ge ON ge.gendercode = p.gender"
		. " WHERE"
		//. " (pro.end_date IS NULL OR (pro.start_date < NOW() AND pro.end_date > NOW()))" // sin verificacion de fechas
		. " p.deleted = ''"
		. " AND pro.deleted = ''"
		. " AND pro.current != ''"
		. " AND p.state = '5'"
		//. " INTO OUTFILE '" . $filename . "'"
		//. " FIELDS TERMINATED BY '" . $sep . "'"
		//. " ENCLOSED BY '\"'"
		;
		
   		$irbdb->setQuery($query);
   		//echo $irbdb->getQuery();
   		//break;
		$this->_data = $irbdb->loadAssocList();
		
		if ($this->_data) {
			return $this->_data;
		} else {
			return false;
		}    	
    }
    
   	/**
	 * Method to initialise the data
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
			$item->command = null;
			$this->_data = $item;

			return (boolean) $this->_data;
		}
		return true;
	}
    
}
?>