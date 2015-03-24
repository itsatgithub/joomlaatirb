<?php
/**
 * Joomla! 1.5 component Science, Award view
 *
 * @version $Id: view.html.php 2009-10-16 08:00:35 svn $
 * @package Joomla
 * @subpackage Irbtools
 *
 * Irbtools
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Science component Award view
 */
class IrbtoolsViewException extends JView
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
		$this->_name = 'exceptions';
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
		$model =& JModel::getInstance( 'exception', 'irbtoolsmodel' );
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

		// set the id. 0 = new
		$id = JRequest::getVar( 'id' );
		if (!$id) {
			$id = 0;
		}

		// set the id
		$model->setId( $id );

		$exception =& $model->getData();
		$this->assignRef('exception', $exception);

		$lists['commands'] = JHTML::_('irbtoolshelper.getCommandsList', 'command', $exception->command, '', false);

		// set the variables
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assign('action', $uri->toString());

		parent::display($tpl);
	}
}
?>