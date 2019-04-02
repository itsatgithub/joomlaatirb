<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Irbtools Component Privatefiles Model
 *
 * @package		Irbtools
 * @since 		1.5
 */

class IrbtoolsModelPrivatefiles extends JModel
{
	/**
	 * Frontpage data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Frontpage total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
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
	
	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		global $mainframe, $option;
		
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{	
			$this->_data = array();
						
			$user =& JFactory::getUser();
			
			// busco el usuario en el directorio
			$edir = ldap_connect("irbldap1.irb.pcb.ub.es");
			$ldaprdn  = 'cn=admin,o=irbbarcelona';
			$ldappass = 'irbbarcelona';  // associated password
	    	$edir_bind = ldap_bind($edir, $ldaprdn, $ldappass);   	
	    	$filter = "(&(objectclass=organizationalPerson)(cn=" . $user->username . "))"; 
	    	$edir_sr = ldap_search($edir, "o=irbbarcelona", $filter);
	    	$info = ldap_get_entries($edir, $edir_sr);

	    	if ($info["count"] != 1)
		    {
		    	$mainframe->redirect('index.php?option=com_irbtools&view=privatefiles', JText::_('USER_DIRECTORY_KO'));    		
		    }

		    $userCode = $info[0]['irb-usercode'][0];
			
			// get the search field
			$filter_search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search');			
			if ($filter_search = trim($filter_search))
			{
				$filter_search = JString::strtolower($filter_search);			
			}
						
			// open this directory 
			$params =& JComponentHelper::getParams( 'com_irbtools' );
			$filesFolder = JPATH_SITE . DS . $params->get( 'irbtoolsConfig_PrivateFilesFolder', '' );			
			$myDirectory = opendir($filesFolder);

			// get each entry
			$arr_aux = array();
			while($entryName = readdir($myDirectory))
			{
				// Removing hidden files
				if(preg_match("/^\./", $entryName))
				{
					continue;
				}
				
				// IMPORTANT. Getting only my files (starting with my code)
				$exp = "/^" . $userCode . "/";
				if(!preg_match($exp, $entryName))
				{
					continue;
				}
							
				// getting the filtered files
				if ( $filter_search != '' )
				{
					// filter
					$pos = strpos($entryName, $filter_search);
					if ($pos !== false)
					{
						$arr_aux['filename'] = $entryName;
					}
					
				} else {
					
					// do not filter
					$arr_aux['filename'] = $entryName;
				}
				
				// geting the data
				$n = sscanf($arr_aux['filename'], "%5s_%4s_%2s_%2s.pdf", $userid, $year, $month, $number);
				$payday = $year.'-'.$month.'-01'; // first day
				
				/** Roberto 8/3/2012
				 *  Desde RRHH quiren poner tambien los certificados de retención. Estos certificados se distinguen
				 *  porque acaban en el código '99'. 'fiflesdescription' será por tanto 'Paycheck' o 'Cerfificate' 
				 *  dependiendo de este 99 en el nombre del fichero.
				 */
				if ($number == '99')
				{
					$arr_aux['filedescription'] = "Certificate ".date("F", strtotime($payday))." ".$year;
				} else {
					$arr_aux['filedescription'] = "Paycheck ".date("F", strtotime($payday))." ".$year;	
				}

				$this->_data[] = $arr_aux;
			}
						
			// close directory
			closedir($myDirectory);
		}

		// sort
		sort($this->_data);
		
		// Roberto 2013-11-18
		$reverse = array_reverse($this->_data);
		
		// pagination
		$short_data = array_slice($reverse, $this->getState('limitstart'), $this->getState('limit'));		
		//$short_data = array_slice($this->_data, $this->getState('limitstart'), $this->getState('limit'));		
		return $short_data;
	}

	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$this->_total = count($this->_data);					
		}
		
		return $this->_total;
	}

	/**
	 * Method to get the pagination object
	 *
	 * @access public
	 * @return object
	 */
	function getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');		
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
	
	/**
	 * Method to verifyf whether a file is mine or not
	 *
	 * @access	private
	 * @param string filename Name of the file
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _isMyFile( $filename )
	{
		return true;
	}
	
}
?>
