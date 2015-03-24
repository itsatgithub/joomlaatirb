<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * Irbtools package
 *
 * @package		Joomla
 * @subpackage	com_irbtools
 */
class IrbtoolsViewPrivatefiles extends JView
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
		$this->_name = 'privatefiles';
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
		if ( $user->username == null ) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}

		// panic! do not show files
		if ( $params->irbtoolsConfig_Panic ) {
			echo JText::_( 'FILEMANAGER_PANIC' );
			return;
		}
		
		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'id', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'ASC', 'word' );
		$filter_search = $mainframe->getUserStateFromRequest( $option.'filter_search', 'filter_search', '', 'string' );
		$filter_search = JString::strtolower( $filter_search );

		// Get some data from the model
		$items =& $this->get('data');
		$total =& $this->get('total');
		$pagination	=& $this->get('pagination');

		// prepare list array
		$lists = array();

		// set the values
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['search'] = $filter_search;

		// push data into the template
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('items', $items);
		$this->assignRef('total', $total);
		$this->assignRef('pagination', $pagination);

		// ordering action
		$this->assign('action',	$uri->toString());

		parent::display($tpl);
	}
}
?>