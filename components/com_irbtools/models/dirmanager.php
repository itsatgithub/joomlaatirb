<?php
/**
 * Joomla! 1.5 component irbtools
 *
 * @version $Id: dirmanager.php 2010-10-13 07:12:40 svn $
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
 * irbtools Component irbtools Model
 *
 * @author      IRB Barcelona
 * @package		Joomla
 * @subpackage	irbtools
 * @since 1.5
 */
class IrbtoolsModelDirmanager extends JModel
{
	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_ldap_server = null;
	
	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_ldap_rdn = null;

	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_ldap_pass = null;
	
	/**
	 * Parameter
	 *
	 * @var char
	 */
	var $_ldap_root = null;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
    	// get params
    	$params =& JComponentHelper::getParams( 'com_irbtools' );
    	$this->_ldap_server = $params->get( 'irbtoolsConfig_LdapServer', '' );
    	$this->_ldap_rdn = $params->get( 'irbtoolsConfig_LdapRdn', '' );
    	$this->_ldap_pass = $params->get( 'irbtoolsConfig_LdapPass', '' );
    	$this->_ldap_root = $params->get( 'irbtoolsConfig_LdapRoot', '' );
	}
    
	/**
     * Update the date on the Ldap directory
     * 
     * @param 
     * @return
     * 
     */
    function updateFechaBajaMail($email, $date)
    {	
    	// Roberto 2013-01-31 Incluyo el scrip de Curro
    	$perl_script = JPATH_COMPONENT.DS.'helpers'.DS.'change_email_date.pl';
    	$result = exec($perl_script . " -a -r " . $email . " " . $date);
    	if ($result < 0) {
    		return false;
    	} else {
    		return true;
    	}
    	
    	/*
    	 * Roberto 2013-01-28 Sustituido por el scrip Python de Curro
    	 * 
    	// busco el usuario en el directorio
    	$ldaprdn  = $this->_ldap_rdn;
    	$ldappass = $this->_ldap_pass;
    	$ldaproot = $this->_ldap_root;
    	
    	echo "ldaprdn = " . $this->_ldap_rdn . "<br>";
    	echo "ldappass = " . $this->_ldap_pass . "<br>";
    	echo "ldaproot = " . $this->_ldap_root . "<br>";
    	 
    	$edir = ldap_connect($this->_ldap_server);    	
    	if ($edir)
    	{    		
	    	$edir_bind = ldap_bind($edir, $ldaprdn, $ldappass);
	    	$filter = "(&(objectclass=organizationalPerson)(mail=" . $email . "))";
	    	$edir_sr = ldap_search($edir, $ldaproot, $filter);
	    	$info = ldap_get_entries($edir, $edir_sr);
	    	    	
	    	if ($info["count"] != 1)
	    	{
	    		return false;
	    	}
	    	
	    	// modifico el atributo de IRB-FechaBajaMail
	    	$new = array();
	    	$new["IRB-FechaBajaMail"] = $date;
	    	$dn = $info[0]['dn'];
	    	
	    	// write and read the write to verify the operation
	    	$count = 0;
	    	While ((strcmp($info[0]["irb-fechabajamail"][0], $date) != 0) || $count > 10)
	    	{
	    		ldap_modify($edir, $dn, $new);
	    		$info = ldap_get_entries($edir, $edir_sr);
	    		$count++;
	    	echo "ldap value = " . $info[0]["irb-fechabajamail"][0];
	    	echo "date = " . $date;
	    	break;
	    	}
	    	
	    	echo "ldap value = " . $info[0]["irb-fechabajamail"][0];
	    	echo "date = " . $date;
	    	break;
	    	 
	    	// cierro la conexión con eDir
	    	ldap_close($edir);
	    	 
			return true;
    	} else {
    		return false;
    	}
    	*/	
    }
}
?>