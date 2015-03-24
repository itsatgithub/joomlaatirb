<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class IrbtoolsModelSirecs extends JModel
{
	/**
	 * Abstract data
	 *
	 * @var array
	 */
	var $_data = null;
	
	/**
	 * External database data
	 *
	 * @var array
	 */
	var $_mydb = null;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$option = array(); //prevent problems
		
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = 'irbsvr3.irb.pcb.ub.es';    // Database host name
		$option['user']     = 'intranetuser';       // User for database authentication
		$option['password'] = '72T4manV';   // Password for database authentication
		$option['database'] = 'sciprod';      // Database name
		$option['prefix']   = '';             // Database prefix (may be empty)
		 
		$this->_mydb =& JDatabase::getInstance( $option );
		
		parent::__construct();
    }
    
    function &getFile46()
    {
    	/*    	
    	// get data		
		$query = "SELECT p.id, p.title, p.authors, p.journal, p.volume, p.issue, p.pages, p.year, p.pubmed_id, p.date_of_print, p.selected_extranet"
 		. " FROM `jos_sci_publications` as p"
		. " ORDER BY p.id"
		;
		$this->_mydb->setQuery($query);
		$this->_data = $this->_mydb->loadAssocList();
		
		foreach ($this->_data AS $key => $value)
		{
			// Compiling research groups
			$query2 = "SELECT gl.research_group"
			. " FROM `jos_sci_group_leaders` AS gl"
			. " LEFT JOIN jos_sci_publication_group_leader AS pgl ON pgl.group_leader_id = gl.id"
			. " LEFT JOIN jos_sci_publications AS p ON p.id = pgl.publication_id"
			. " WHERE p.id =" . $value['id']
			;
			$this->_mydb->setQuery($query2);
			$groups = $this->_mydb->loadAssocList();
			
			$gr_string = '';
			foreach ($groups AS $group)
			{
				$gr_string .= $group['research_group'] . ",";
			}
			// take out last comma
			$gr_string = substr($gr_string, 0, -1);
			$this->_data[$key]['research_groups'] = $gr_string;			
		}
		*/
    	
    	$this->_data[0] = "12345678901234567890";
    	$this->_data[1] = "12345678901234567890";
    	
		if ($this->_data) {
			return $this->_data;
		} else {
			return false;
		}    	
    }
       
}
?>