<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport('joomla.utilities.date');

/**
 * Award Controller
 *
 * @package		Joomla
 * @subpackage	Science
 */
class ScienceControllerAward extends JController
{

	/**
	 * Display data
	 *
	 * @since	1.5
	 */
	function display() {

		JRequest::setVar( 'view', 'award' );
		parent::display();
	}

	/**
	 * Save award data
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
		$model =& $this->getModel('award');
		$award_id = $model->store($post);

		if ($award_id) {
			$mainframe->enqueueMessage( JText::_('AWARD_STORE_OK') );
			if ($post['save']){
				JRequest::setVar('view', 'awards' );
				JRequest::setVar('id', $award_id );
			} else if ($post['saveandadd']){
				JRequest::setVar('id', null );
			}
			parent::display();
		} else {
			$msg = JText::_('AWARD_STORE_KO');
			$this->setRedirect('index.php?option=com_science', $msg);
		}
	}
}

?>