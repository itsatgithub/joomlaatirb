<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * ScientificPublications Component Controller
 */
class ScientificpublicationsController extends JController {
	function display() {
		// Make sure we have a default view
		if( !JRequest::getVar( 'view' )) {
			JRequest::setVar('view', 'scientificpublications' );
		}
		parent::display();
	}

	function save_data() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );

		// store data. the function returns the saved id
		$model =& $this->getModel('scientificpublication');
		$publication_id = $model->store($post);

		if ($publication_id) {
			$mainframe->enqueueMessage( JText::_('PUBLICATION_STORE_OK') );
			if ($post['save']){
				JRequest::setVar('view', 'scientificpublications' );
			}
			parent::display();
		} else {
			$msg = JText::_('PUBLICATION_STORE_KO');
			$this->setRedirect('index.php?option=com_scientificpublications', $msg);
		}
	}
}
?>