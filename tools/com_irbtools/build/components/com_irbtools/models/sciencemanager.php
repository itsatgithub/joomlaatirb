<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class IrbtoolsModelSciencemanager extends JModel
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
		
		// Roberto 2012-03-15 datos operacionales
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = 'irbsvr3.irb.pcb.ub.es';    // Database host name
		$option['user']     = 'intranetuser';       // User for database authentication
		$option['password'] = '72T4manV';   // Password for database authentication
		$option['database'] = 'sciprod';      // Database name
		$option['prefix']   = '';             // Database prefix (may be empty)
		
		// datos de desarrollo
		/*
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = 'localhost';    // Database host name
		$option['user']     = 'root';       // User for database authentication
		$option['password'] = 'root';   // Password for database authentication
		$option['database'] = 'joomlaatirb';      // Database name
		$option['prefix']   = '';             // Database prefix (may be empty)
		*/
		
		$irbdb =& JDatabase::getInstance( $option );
    	    	
    	// get data
    	// Roberto 2012-03-15 modificado para sacar selected_extranet de otra tabla.
    	// Roberto 2014-05-09 modificado para incluir phd_selected_publication, irb_selected_publication
		$query = "SELECT p.id, p.title, p.authors, p.journal, p.volume, p.issue"
		. " , p.pages, p.year, p.pubmed_id, p.date_of_print, pgl.selected_extranet, pt.description"
		. " , gl.research_group AS research_groups"
		. " , p.phd_selected_publication AS phd_selected_publication, p.irb_selected_publication AS irb_selected_publication"
 		. " FROM `jos_sci_publications` as p"
 		. " LEFT JOIN jos_sci_publication_types AS pt ON pt.id = p.type_id"
 		. " LEFT JOIN jos_sci_publication_group_leader AS pgl ON pgl.publication_id = p.id"
 		. " LEFT JOIN jos_sci_group_leaders AS gl ON gl.id = pgl.group_leader_id"
 		. " ORDER BY p.id"
		;
		$irbdb->setQuery($query);
		$this->_data = $irbdb->loadAssocList();
		
		/**
		 * Roberto 2012-03-15 No es necesario componer la lista de grupos ya que ahora salen en dos lineas diferentes
		 * 
		foreach ($this->_data AS $key => $value)
		{
			// Compiling research groups
			$query2 = "SELECT gl.research_group"
			. " FROM `jos_sci_group_leaders` AS gl"
			. " LEFT JOIN jos_sci_publication_group_leader AS pgl ON pgl.group_leader_id = gl.id"
			. " LEFT JOIN jos_sci_publications AS p ON p.id = pgl.publication_id"
			. " WHERE p.id =" . $value['id']
			;
			$irbdb->setQuery($query2);
			$groups = $irbdb->loadAssocList();
			
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