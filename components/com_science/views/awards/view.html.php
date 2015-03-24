<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * Science package is used in scientific and academia environments.
 * The component com_science helps to record the scientific production details.
 *
 * @version		1.0
 * @package		Science
 * @subpackage	com_science
 * @author		GPLavui http://www.gplavui.com
 */
class ScienceViewAwards extends JView
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
		$this->_name = 'view_awards';
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
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$rights = $sci_user->getRights($user->username, $this->_name);
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}

		$filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'id', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'ASC', 'word' );
		$filter_search = $mainframe->getUserStateFromRequest( $option.'filter_search', 'filter_search', '', 'string' );
		$filter_search = JString::strtolower( $filter_search );

		// Get the page/component configuration
		$params =& $mainframe->getParams();

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

		// preparing items to display
		$k = 0;
		$count = count($items);
		for($i = 0; $i < $count; $i++)
		{
			$item =& $items[$i];
			$item->odd = $k;
			$item->count = $i;
			$k = 1 - $k;
		}

		// push data into the template
		$this->assignRef('user', $user);
		$this->assignRef('rights', $rights); // to show the delete icon on the view
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