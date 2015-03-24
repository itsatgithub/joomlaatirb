<?php
/**
 * Joomla! 1.5 component irbtools
 *
 * @version $Id: user.php 2010-10-13 07:12:40 svn $
 * @author IRB Barcelona
 * @package Joomla
 * @subpackage irbtools
 * @license GNU/GPL
 *
 * IRB Barcelona Tools
 * 
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


/**
 * irbtools Component User Model
 *
 * @author      IRB Barcelona
 * @package		Joomla
 * @subpackage	irbtools
 * @since 1.5
 */
class IrbtoolsModelUser extends JModel
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
		parent::__construct();
    }
    
    /**
     * Create gmail account
     * 
     * @param 
     * @return
     * 
     */
    function getRights($username, $appname)
    {
    	$query = 'SELECT ua.*'
		. ' FROM `#__irbtools_user_app` AS ua'
		. ' WHERE ua.username = \'' . $username . '\''
		. ' AND ua.appname = \'' . $appname . '\''
		;
   		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();
		
		if ($this->_data) {
			return true;
		} else {
			return false;
		}    	
    }
    
    function getProfile($username, $appname)
    {
    	$query = 'SELECT ua.*'
    	. ' FROM `#__irbtools_user_app` AS ua'
    	. ' WHERE ua.username = \'' . $username . '\''
    	. ' AND ua.appname = \'' . $appname . '\''
    	;
    	$this->_db->setQuery($query);
    	$this->_data = $this->_db->loadObject();
    
    	if ($this->_data) {
    		return $this->_data->profile;
    	} else {
    		return false;
    	}
    }
    
}
?>