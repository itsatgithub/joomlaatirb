<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Irbtools Exceptions Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerExceptions extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display()
	{
		// Make sure we have a default view
		if( !JRequest::getVar( 'view' )) {
			JRequest::setVar('view', 'exceptions' );
		}
		parent::display();
	}

	function delete()
	{
		//get data from the request
		$get = JRequest::get( 'get' );
		$id = $get['id'];

		$model = $this->getModel( 'exception' );
		if( $model->delete( $id ) ) {
			$msg = JText::_('DEL_OK');
		} else {
			$msg = JText::_('DEL_KO');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_irbtools&view=exceptions', false), $msg);
	}
}
?>