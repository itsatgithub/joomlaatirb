<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Irbtools Component Holidays Model
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsModelHolidays extends JModel
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
	 * Method to get a award
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		global $mainframe;
		
		$date =& JFactory::getDate();
		$year = $date->toFormat( '%Y' );
		$user =& JFactory::getUser();
		
		// busco el usuario en el directorio
		$edir = ldap_connect("irbsvr4.irb.pcb.ub.es");
		$ldaprdn  = 'cn=admin,o=irbbarcelona';
		$ldappass = 'irbbarcelona';  // associated password
    	$edir_bind = ldap_bind($edir, $ldaprdn, $ldappass);   	
    	$filter = "(&(objectclass=organizationalPerson)(cn=" . $user->username . "))"; 
    	$edir_sr = ldap_search($edir, "o=irbbarcelona", $filter);
    	$info = ldap_get_entries($edir, $edir_sr);

    	if ($info["count"] != 1)
	    {
	    	$mainframe->redirect('index.php?option=com_irbtools&view=holidays', JText::_('USER_DIRECTORY_KO'));    		
	    }

	    $userCode = $info[0]['irb-usercode'][0];
		
		$option = array(); //prevent problems
		 
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = 'irbsvr3.irb.pcb.ub.es';    // Database host name
		$option['user']     = 'root';       // User for database authentication
		$option['password'] = 'X24mnt32';   // Password for database authentication
		$option['database'] = 'irbdb';      // Database name
		$option['prefix']   = '';             // Database prefix (may be empty)
		 
		$irbdb =& JDatabase::getInstance( $option );
		
		$query = 'select * from irbholidayinfo where personalcode = ' . $userCode 
		. ' and year = ' . $year
		;
   		$irbdb->setQuery($query);
		$result = $irbdb->loadObject();
		$arrFinal = get_object_vars($result);
		
		// some calculations...
		$arrFinal['pyhr'] = $arrFinal['previousyearholidaysforyear'] - $arrFinal['previousyearholidays'];
		$arrFinal['rh'] = $arrFinal['holidaysforyear'] - $arrFinal['holidays'];
		$arrFinal['rdfpa'] = $arrFinal['apsforyear'] - $arrFinal['aps'];
		
		return $arrFinal;
	}


}
