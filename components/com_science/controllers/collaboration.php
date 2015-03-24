<?php
/**
 * @version		$Id: science.php 10381 2008-06-01 03:35:53Z $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport('joomla.utilities.date');

/**
 * Science Collaboration Controller
 *
 * @package		Joomla
 * @subpackage	Science
 * @since 1.5
 */
class ScienceControllerCollaboration extends JController
{

	function display() {

		JRequest::setVar( 'view', 'collaboration' );
		parent::display();
	}

	function save_data() { //Save data the first time, collaboration and details
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );

		// store data. the function returns the saved id
		$model =& $this->getModel('collaboration');
		$collaboration_id = $model->store($post);

		// store details data
		unset($post['id']);
		$post['collaboration_id'] = $collaboration_id;
		// store data. the function returns the saved id
		$model =& $this->getModel('collaborationdetails');
		$collaboration_details_id = $model->store($post);

		if ($collaboration_id) {
			$mainframe->enqueueMessage( JText::_('COLLABORATION_STORE_OK') );
			JRequest::setVar('layout', 'default' );
			JRequest::setVar('view', 'collaborations' );
			parent::display();
		} else {
			$msg = JText::_('COLLABORATION_STORE_KO');
			$this->setRedirect('index.php?option=com_science', $msg);
		}
	}

	function save_collaboration() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );

		// store data. the function returns the saved id
		$model =& $this->getModel('collaboration');
		$collaboration_id = $model->store($post);

		if ($collaboration_id) {
			$mainframe->enqueueMessage( JText::_('COLLABORATION_STORE_OK') );
			JRequest::setVar('view', 'collaborations' );
			parent::display();
		} else {
			$msg = JText::_('COLLABORATION_STORE_KO');
			$this->setRedirect('index.php?option=com_science', $msg);
		}
	}

	function add_collaboration_detail() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );

		/*$dateNow = new JDate();
		 $post['modified'] = $dateNow->toMySQL();*/
		//Aixo jo diria que no cal
		/*		$model =& $this->getModel('collaboration');
		 $model->store($post);*/

		if(isset($post['Submit_and_leave'])){
			JRequest::setVar( 'view', 'collaborations' );
		} else {
			JRequest::setVar( 'view', 'collaboration' );
		}

		$collaboration_id = $post['id'];
		unset($post['id']);

		$post['collaboration_id'] = $collaboration_id;
		// store data. the function returns the saved id
		$model =& $this->getModel('collaborationdetails');
		$collaboration_details_id = $model->store($post);

		if ($collaboration_details_id) {
			$mainframe->enqueueMessage( JText::_('COLLABORATION_DETAILS_STORE_OK') );
			JRequest::setVar('id', $collaboration_id );
			//JRequest::setVar( 'view', 'collaboration' );
			parent::display();
		} else {
			$msg = JText::_( 'COLLABORATION_DETAILS_STORE_KO' );
			$this->setRedirect('index.php?option=com_science', $msg);
		}

	}

	function del_collaboration_detail()
	{
		//get data from the request
		$get = JRequest::get( 'get' );
		$id = $get['id'];
		$collaboration_id = $get['collaboration_id'];
		$model =& $this->getModel('collaborationdetails');

		if ( $model->delete($id, $collaboration_id) ) {
			$msg = JText::_('COLLABORATION_DETAILS_DEL_OK');
		} else {
			$msg = JText::_('COLLABORATION_DETAILS_DEL_KO');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_science&view=collaboration&id='.$collaboration_id, false), $msg);
	}
}

?>