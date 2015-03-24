<?php
/**
 * Joomla! 1.5 component irbtools
 *
 * @version $Id: gmail.php 2010-10-13 07:12:40 svn $
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
jimport('joomla.error.log' );

require_once 'Zend/Loader.php';

// Google API
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Gapps');

/**
 * irbtools Component irbtools Model
 *
 * @author      IRB Barcelona
 * @package		Joomla
 * @subpackage	irbtools
 * @since 1.5
 */
class IrbtoolsModelGmail extends JModel
{
	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_google_domain = null;
	
	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_google_user = null;
	
	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_google_pass = null;
	
	/**
	 * Parameter
	 *
	 * @var object
	 */
	var $_google_lists = null;
		
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
		$params =& JComponentHelper::getParams( 'com_irbtools' );
    	$this->_google_user = $params->get( 'irbtoolsConfig_GoogleUser', '' );
    	$this->_google_pass = $params->get( 'irbtoolsConfig_GooglePass', '' );
		$this->_google_domain = $params->get( 'irbtoolsConfig_GoogleDomain', '' );		
		// subscripcion a las listas de correo
		$this->_google_lists = array();
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista1', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista2', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista3', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista4', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista5', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista6', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista7', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista8', '' );
		$this->_google_lists[] = $params->get( 'irbtoolsConfig_Lista9', '' );
	}
    
	/**
	* Returns a HTTP client object with the appropriate headers for communicating
	* with Google using the ClientLogin credentials supplied.
	*
	* @param string $user The username, in e-mail address format, to authenticate
	* @param string $pass The password for the user specified
	* @return Zend_Http_Client
	*/
	function getClientLoginHttpClient($user, $pass) 
	{
		$service = Zend_Gdata_Gapps::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
		return $client;
	}
    
	
	/**
     * Create gmail account
     * 
     * @param 
     * @return
     * 
     */
    function createAccount($username, $name, $surname, $password)
    {		
		// datos de conexion
		$client = $this->getClientLoginHttpClient($this->_google_user, $this->_google_pass);
		$gapps = new Zend_Gdata_Gapps($client, $this->_google_domain);

		$options = array('format' => "{DATE}\t{TIME}\t{TEXT}");
		$log_filename= "log_gmail-".date( 'M-Y').".log";
		$log = & JLog::getInstance($log_filename, $options);
				
		// creacion de la cuenta
		try {
			$gapps->createUser($username, $name, $surname, $password);
		} catch (Zend_Gdata_Gapps_ServiceException $e) {
			foreach ($e->getErrors() as $error) {
				$log->addEntry(
					array("Date" => date('d-m-Y')
					,"Time" => date('h:i')
					,"Text" => "Error encountered: {$error->getReason()} ({$error->getErrorCode()})"));
			}		
			return false;
		}
			
		return true;
		
		/*
		foreach($this->_google_lists as $key=>$value)
		{
			if ($value)
			{
				// Roberto 2013-10-02 comento la linea porque da problemas
				//$gapps->addRecipientToEmailList($username . '@' . $this->_google_domain, $value);
				// Roberto 2013-10-16 Añadido el bloque try-catch			
				try {
					$command1 = "/usr/bin/python /usr/local/gam/gam.py update group " . $value . " add member " . $username . '@' . $this->_google_domain;
					$log->addEntry(array("Date" => date('d-m-Y')
						,"Time" => date('h:i')
						,"Text" => $command1));
					$res = shell_exec($command1);					
				} catch (Zend_Gdata_Gapps_ServiceException $e) {
					foreach ($e->getErrors() as $error) {
						echo "Error encountered: {$error->getReason()} ({$error->getErrorCode()})\n";
					}				
				}
			}
		}
		*/		
    }
    
    /**
     * Get the telephone numbers by group
     *
     * @param
     * @return
     *
     */
    function getPhones($group)
    {
    	$query = 'SELECT ph.*'
    	. ' FROM `jos_irbtools_phones` AS ph'
    	. ' WHERE ph.group = \'' . $group . '\''
    	;
    	
    	$this->_db->setQuery($query);
    	$this->_data = $this->_db->loadObject();
    
    	if ($this->_data) {
    		return $this->_data;
    	} else {
    		return false;
    	}
    }
    
}
?>