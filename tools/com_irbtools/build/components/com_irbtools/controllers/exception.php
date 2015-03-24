<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Exception Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerException extends JController
{

	/**
	 * Display data
	 *
	 * @since	1.5
	 */
	function display() {

		JRequest::setVar( 'view', 'exception' );
		parent::display();
	}

	/**
	 * Save exception data
	 *
	 * @since	1.5
	 */
	function save_data() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );

		// store data. the function returns the saved id
		$model =& $this->getModel('exception');
		$exception_id = $model->store($post);

		if ($exception_id) {
			$mainframe->enqueueMessage( JText::_('EXCEPTION_STORE_OK') );
			JRequest::setVar('view', 'exceptions' );
			parent::display();
		} else {
			$msg = JText::_('EXCEPTION_STORE_KO');
			$this->setRedirect('index.php?option=com_irbtools', $msg);
		}
	}
}

?>