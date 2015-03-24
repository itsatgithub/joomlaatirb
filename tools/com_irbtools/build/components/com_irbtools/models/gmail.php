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

/**
 * Used by the Google API
 */
require_once( '/usr/share/php5/google-api-php-client/autoload.php' );

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
	 * Parameter
	 *
	 * @var object
	 */
	var $_client_id = null;
	
	/**
	 * Parameter
	 *
	 * @var object
	 */
	var $_service_account_name = null;
	
	/**
	 * Parameter
	 *
	 * @var object
	 */
	var $_key_file_location = null;

	/**
	 * Parameter
	 *
	 * @var object
	 */
	var $_delegatedAdmin = null;
	
	/**
	 * Google_Client for making API calls with
	 *
	 * @var object
	 */
	var $_client = null;
	
	/**
	 * Google_Service
	 *
	 * @var object
	 */
	var $_service = null;
	
	
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
		
		/**
		 * Roberto 2015-01-20 Google Directory API
		 */
		
		/**
		 * Client ID from https://console.developers.google.com/
		 * Must be added in Google Apps Admin console under Security -> Advanced -> Manage API client     access
		 * Requires scope https://www.googleapis.com/auth/admin.directory.user or
		 * https://www.googleapis.com/auth/admin.directory.user.readonly
		 */
		$this->_client_id = $params->get( 'irbtoolsConfig_GoogleClientId', '' );
		
		/**
		 * Service Account Name or "Email Address" as reported on     https://console.developers.google.com/
		 */
		$this->_service_account_name = $params->get( 'irbtoolsConfig_GoogleServiceAccountName', '' );
		/**
		 * This is the .p12 file the Google Developer Console gave you for your app
		 */
		$this->_key_file_location = $params->get( 'irbtoolsConfig_GoogleKeyFileLocation', '' );
		
		/**
		 * Email address for admin user that should be used to perform API actions
		 * Needs to be created via Google Apps Admin interface and be added to an admin role
		 * that has permissions for Admin APIs for Users
		 */
		$this->_delegatedAdmin = $params->get( 'irbtoolsConfig_GoogleDelegatedAdmin', '' );

		/**
		 * Array of scopes you need for whatever actions you want to perform
		 * See https://developers.google.com/admin-sdk/directory/v1/guides/authorizing
		 */
		$scopes = array(
				'https://www.googleapis.com/auth/admin.directory.user',
				'https://www.googleapis.com/auth/admin.directory.group'
		);
		 
		/**
		 * Create AssertionCredentails object for use with Google_Client
		 */ 
		$cred = new Google_Auth_AssertionCredentials(
				$this->_service_account_name,
				$scopes,
				file_get_contents($this->_key_file_location)
		);
		/**
		 * This piece is critical, API requests must be used with sub account identifying the
		 * delegated admin that these requests are to be processed as
		 */
		$cred->sub = $this->_delegatedAdmin;
		 
		/**
		 * Create Google_Client for making API calls with
		 */
		$this->_client = new Google_Client();
		$this->_client->setApplicationName("Insert user");
		$this->_client->setAssertionCredentials($cred);
		if ($this->_client->getAuth()->isAccessTokenExpired()) {
			$this->_client->getAuth()->refreshTokenWithAssertion($cred);
		}	
		
		/**
		 * Create Google_Service_Directory
		 */
		$this->_service = new Google_Service_Directory($this->_client);
		
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
    
    
    /**
     * Create gmail account using Google API
     *
     * @param
     * @return TRUE, FALSE
     */
    function insertUser($username, $name, $surname, $password, $imap, $pop)
    {    	
    	/**
    	 * Create user
    	 */
    	$nameInstance = new Google_Service_Directory_UserName();
    	$nameInstance -> setGivenName($name);
    	$nameInstance -> setFamilyName($surname);
    	
    	$userInstance = new Google_Service_Directory_User();
    	$userInstance -> setName($nameInstance);
    	$userInstance -> setHashFunction("MD5");
    	$userInstance -> setPrimaryEmail($username);
    	$userInstance -> setPassword(hash("md5", $password));
    	try
    	{
    		$createUserResult = $this->_service->users->insert($userInstance);
    		return TRUE;
    	}
    	catch (Google_IO_Exception $gioe)
    	{
    		echo "Error in connection: ".$gioe->getMessage();
    		return FALSE;
    	}
    	catch (Google_Service_Exception $gse)
    	{
    		echo "User already exists: ".$gse->getMessage();
    		return FALSE;
    	} 
    }
    
    
    /**
     * Insert a member to a group using Google API
     *
     * @param
     * @return TRUE, FALSE
     */
    function insertGroupMember($email, $role, $type, $group)
    {
    	$memberInstance = new Google_Service_Directory_Member();
    	$memberInstance->setEmail($email);
    	$memberInstance->setRole($role);
    	$memberInstance->setType($type);
    	try
    	{
    		$insertMembersResult = $this->_service->members->insert($group, $memberInstance);
    		return TRUE;
    	}
    	catch (Google_IO_Exception $gioe)
    	{
    		echo "Error in connection: ".$gioe->getMessage();
    		return FALSE;
    	}
    }
}
?>
