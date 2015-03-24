<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * View to acces the Gmail creation.
 * 
 * @version		1.0
 * @package		Joomla
 * @subpackage	com_irbtools
 */
class IrbtoolsViewDirmanager extends JView
{
	/**
	 * Abstract name
	 *
	 * @var _name
	 */
	var $_name = null;
	
	/**
	 * Constructor
	 *
	 * Set up the variables
	 */
	function __construct()
	{
		parent::__construct();

		// IMP: Set this always
		$this->_name = 'dirmanager';
	}
	
	/**
	 * Display function
	 *
    * @param	string $tpl The optional template.
    * @return	Display the template.
	 */
	function display($tpl = null)
	{	
		global $mainframe, $option;
		
		// Initialize some variables
		$db =& JFactory::getDBO();
		$uri =& JFactory::getURI();
		$user =& JFactory::getUser();
		$params =& $mainframe->getParams();
			
		// access control
		$irbuser =& JModel::getInstance( 'user', 'irbtoolsmodel' );
		$rights = $irbuser->getRights($user->username, $this->_name);
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );			
			return;
		}
		
		$account = new stdClass();
		$account->email = '';
		$account->fechabajaemail = '';
		
        // push data into the template
		$this->assign('action', $uri->toString());
		$this->assignRef('params', $params);
		$this->assignRef('account', $account);     

		parent::display($tpl);
    }
}
?>