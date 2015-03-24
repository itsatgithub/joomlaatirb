<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Science component Award view
 */
class IrbtoolsViewRoaming extends JView
{
	/**
	 * Abstract name
	 *
	 * @var name
	 */
	var $_name = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		// IMP: Set this always
		$this->_name = 'roaming';
	}

	/**
	 * Display view
	 *
	 * @since 1.5
	 */
	function display($tpl = null)
	{
		global $mainframe, $option;
			
		// Get some objects
		$model =& JModel::getInstance( 'roaming', 'irbtoolsmodel' );
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$uri =& JFactory::getURI();
		$params =& $mainframe->getParams();

		// access control
		$irbuser =& JModel::getInstance( 'user', 'irbtoolsmodel' );
		$rights = $irbuser->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}		

		// get the roamings from the session
		$session = JFactory::getSession();
		$roaming_array = $session->get('roamings', array());
		$this->assignRef('items', $roaming_array);
		
		// build list of services
		$query = 'SELECT CONCAT(se.level1, \' - \', se.level2, \' - \', se.level3) AS value, CONCAT(se.level1, \' - \', se.level2, \' - \', se.level3) AS text'
		. ' FROM `#__irbtools_roaming_services` AS se'
		. ' ORDER BY se.order ASC'
		;
		$db->setQuery($query);
		$genericlist = NULL;
		$genericlist[] = JHTML::_('select.option',  '', JText::_( '- Select Service -' ), 'value', 'text');
		$genericlist = array_merge( $genericlist, $db->loadObjectList() );
		$lists['services'] = JHTML::_('select.genericlist', $genericlist, 'description', "class='required'", 'value', 'text', $roaming->description );
		
		// build list of telephones
		// users with the 'administrator' profile should have access to all the telephones on the list
		$profile = $irbuser->getProfile( $user->username, $this->_name );
		$where = (strcmp($profile, 'administrator') == 0 ? '': ' WHERE us.username = \'' . $user->username. '\'');	
		$query = 'SELECT DISTINCT te.long_number AS value, CONCAT(te.short_number, \' - \', ru.owner) AS text'
		. ' FROM `#__irbtools_roaming_telephones` AS te'
		. ' LEFT JOIN `#__irbtools_roaming_users` AS ru ON ru.id = te.owner_id'
		. ' LEFT JOIN `#__irbtools_roaming_telephone_user` AS tu ON tu.long_number = te.long_number'
		. ' LEFT JOIN `#__irbtools_users` AS us ON us.username = tu.username'
		. $where		
		. ' ORDER BY te.short_number ASC'
		;		
		$db->setQuery($query);
		
		$genericlist = NULL;
		$genericlist[] = JHTML::_('select.option',  '', JText::_( '- Select Telephone -' ), 'value', 'text');
		$genericlist = array_merge( $genericlist, $db->loadObjectList() );
		$lists['telephones'] = JHTML::_('select.genericlist', $genericlist, 'long_number', "class='required'", 'value', 'text', $roaming->long_number );
		
		// set the variables
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assign('action', $uri->toString());

		parent::display($tpl);
	}
}
?>