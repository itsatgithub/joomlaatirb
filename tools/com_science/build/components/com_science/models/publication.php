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
	 * Abstract data
	 *
	 * @var array
	 */
	var $_xmldata = null;
	
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
		$this->_data	= null;
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
	 * Method to store the thesis
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('publication');

		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		} else {
			// returns the id
			return $row->id;
		}
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
		$user =& JFactory::getUser();
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		if ($sci_user->isGroupLeader($user->username)) 
		{
			if ( !$sci_user->canWrite( $sci_user->getAssigned($user->username), 'view_publications', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		} else {
			if ( !$sci_user->canWrite( $user->username, 'view_publications', $id ))
			{
				echo JText::_( 'ALERTNOTAUTH' );
				return false;
			}
		}
		
		/**
		 * deleting entry in jos_sci_publications and jos_sci_publication_group_leader
		 */
		$query = 'DELETE FROM #__sci_publications'
		. ' WHERE id = ' . $id
		;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

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

			// get only the user's records
			$user =& JFactory::getUser();
			$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
			if ($sci_user->isGroupLeader($user->username)) {
				$where[] = 'gl.user_username = \'' . $sci_user->getAssigned($user->username) . '\'';
			}
				
			// get only the _id
			$where[] = 'p.id = ' . $this->_id;
				
			// format the where clause
			$where = (count($where)) ? ' WHERE '.implode(' AND ', $where) : '';

			$query = 'SELECT p.*, pt.description AS publication_type'
			. ', gc.description AS group_contribution, pgl.group_leader_id AS group_leader_id'
			. ', pgl.id AS id_pub_gl, ct.description AS coauthors_type, pgl.selected_extranet AS selected_extranet'
			. ' FROM `#__sci_publications` AS p'
			. ' LEFT JOIN `#__sci_publication_group_leader` AS pgl ON pgl.publication_id = p.id'
			. ' LEFT JOIN `#__sci_group_leaders` AS gl ON gl.id = pgl.group_leader_id'
			. ' LEFT JOIN `#__sci_publication_types` AS pt ON pt.id = p.type_id'
			. ' LEFT JOIN `#__sci_publication_group_contributions` AS gc ON gc.id = p.group_contribution_id'
			. ' LEFT JOIN `#__sci_publication_coauthors_types` AS ct ON ct.id = p.coauthors_type_id'
			. $where
			;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			
			// Roberto 2012-02-22
			// Incluyo el impact factor
			$this->_data->impact_factor = $this->getImpactFactor($this->_data->journal, $this->_data->year);
			
			return (boolean) $this->_data;

		}
		return true;
	}


	/**
	 * Method to get the impact factor data
	 *
	 * @param string journal Journal description
	 * @param string year Year to look for
	 * @return	boolean	True on success
	 */
	function getImpactFactor($journal, $year)
	{
		$query = 'SELECT imp.impact_factor'
		. ' FROM #__sci_journal_impact_factors AS imp'
		. ' LEFT JOIN `#__sci_journals` AS jo ON jo.id = imp.journal_id'
		. ' WHERE imp.year = \'' . $year. '\''
		. ' AND jo.description = \'' . $journal. '\''
		;
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if ($result) {
			return $result;
		} else {
			return 0; // no value
		}
	}




	/**
	 * Method to load publication data from PUB MED
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function getPubMedData($pubmed_id)
	{
		libxml_use_internal_errors(true);

		if ($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmax=0&usehistory=y&term='. $pubmed_id))
		{
			if ($xml = simplexml_load_file("http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=xml&query_key={$xml->QueryKey}&WebEnv={$xml->WebEnv}&retstart=0&retmax=10"))
			{
				$docs = $xml->DocSum;
			}
		}

		if (!$xml) {
			$errors = libxml_get_errors();

			foreach ($errors as $error) {
				echo display_xml_error($error, $xml);
			}

			libxml_clear_errors();
			return false;
		}

		/**
		echo '<pre>';
		print_r($docs);
		echo '</pre>';
		*/

		if ($docs->Id){
			$publication = new stdClass();
			$publication->pubmed_id = $pubmed_id;
			$publication->title = (string)$docs->Item[5];
			
			/**
			 * Roberto 26/1/2012 Añado date_of_print
			 */
			if (!empty($docs->Item[0])){
				$publication->date_of_print = (string)$docs->Item[0];
			}
			
			if (!empty($docs->Item[1])){
				$publication->epub = (string)$docs->Item[1];
			}
			if (!empty($docs->Item[2])){
				// added ucwords and strtolower to have a common journal name
				$publication->journal = ucwords(strtolower($docs->Item[2]));
			}
			if (!empty($docs->Item[7])){
				$publication->issue = (string)$docs->Item[7];
			}
			if (!empty($docs->Item[6])){
				$publication->volume = (string)$docs->Item[6];
			}
			if (!empty($docs->Item[8])){
				$publication->pages = (string)$docs->Item[8];
			}
			if (!empty($docs->Item[17])){
				$publication->citations = (string)$docs->Item[17];
			}

			$publication->end_year = (string)$docs->Item[0];

			$temp_year = split(" ",(string)$docs->Item[0]);
			$publication->year = $temp_year[0];
			//$publication->year = (string)$docs->Item[0];
				
			$authors = '';
			$num_authors = count($docs->Item[3]);
			$count_authors = 1;
			foreach($docs->Item[3] as $author){
				if (($num_authors > 1) && ($count_authors == ($num_authors - 1))) {
					$authors .= $author.' and ';
				} else {
					$authors .= $author.', ';
				}
				$count_authors++;
			}
			$publication->authors = substr($authors,0,-2);
			// added final point to the string
			$publication->authors .= '.';
				
			if (substr($publication->title,0,1) == '[') {
				$publication->title = substr($publication->title,0,-1);
				$publication->title = substr($publication->title,1);
			}
				
			// code updated - 2010-11-17 - See ticket
			$publication->impact_factor = $this->getImpactFactor($publication->journal, $publication->year);

			return $publication;
		} else {
			return false;
		}
	}

	/**
	 * Method to print XML errors
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */

	function display_xml_error($error, $xml)
	{
		$return  = $xml[$error->line - 1] . "\n";
		$return .= str_repeat('-', $error->column) . "^\n";

		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= "Warning $error->code: ";
				break;
			case LIBXML_ERR_ERROR:
				$return .= "Error $error->code: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "Fatal Error $error->code: ";
				break;
		}

		$return .= trim($error->message) .
	               "\n  Line: $error->line" .
	               "\n  Column: $error->column";

		if ($error->file) {
			$return .= "\n  File: $error->file";
		}

		return "$return\n\n--------------------------------------------\n\n";
	}
	

	
	/**
	 * Return the number of selected publications for the extranet for a particular group leader 
	 *
	 * @access	private
	 * @return	integet
	 */
	function getNumberExtranet($group_leader_id, $publication_id)
	{
		/*
		 * Roberto 2012-03-15 antigua select
		$query = 'SELECT count(*)'
		. ' FROM `jos_sci_publications` AS p'
		. ' LEFT JOIN `jos_sci_publication_group_leader` AS pgl ON pgl.publication_id = p.id'
		. ' LEFT JOIN `jos_sci_group_leaders` AS gl ON gl.id = pgl.group_leader_id'
		. ' WHERE p.selected_extranet = 1'
		. ' AND gl.id = ' . $group_leader_id
		. ' AND p.id != ' . $publication_id
		;
		*/
		$query = 'SELECT count(*)'
		. ' FROM `jos_sci_publication_group_leader` AS p'
		. ' WHERE p.selected_extranet = 1'
		. ' AND p.group_leader_id = ' . $group_leader_id
		. ' AND p.publication_id != ' . $publication_id
		;
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		return $result;
	}
	


	/**
	 * Method to initialise the thesis data
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
			$item->date_of_print = null;
			$item->end_date = null;
			$item->phd_selected_publication = null;
			$item->irb_selected_publication = null;
			$this->_data = $item;

			return (boolean) $this->_data;
		}
		return true;
	}
	
}