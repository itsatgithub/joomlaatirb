<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * View to acces the holidays.
 * 
 * @version		1.0
 * @package		Joomla
 * @subpackage	com_irbtools
 */
class IrbtoolsViewHolidays extends JView
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
		$model =& JModel::getInstance( 'holidays', 'irbtoolsmodel' );
		$db =& JFactory::getDBO();
		$uri =& JFactory::getURI();
		$user =& JFactory::getUser();
		$params =& $mainframe->getParams();
		
		$items =& $model->getData();
				
        // push data into the template
		$this->assign('items', $items);
		$this->assign('action', $uri->toString());
		$this->assignRef('params', $params);

		parent::display($tpl);
    }
}
?>