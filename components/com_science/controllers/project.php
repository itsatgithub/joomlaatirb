<?php
/**
 * @version		$Id: science.php 10381 2008-06-01 03:35:53Z  $
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
class ScienceControllerProject extends JController
{

	function display() {

		JRequest::setVar( 'view', 'project' );
		parent::display();
	}

	function save_data() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );
		
		$awarding_date = new DateTime($awarding_date->toMySQL);
		
		$start_date = new JDate(strtotime($post['start_date']));
		$post['start_date'] = $start_date->toMySQL();

		$end_date = new DateTime($end_date->toMySQL);

		// store data. the function returns the saved id
		$model =& $this->getModel('project');
		$project_id = $model->store($post);

		if ($project_id) {
			$mainframe->enqueueMessage( JText::_('PROJECT_STORE_OK') );
			if ($post['save']){
				JRequest::setVar('view', 'projects' );
				JRequest::setVar('id', $project_id );
			} else if ($post['saveandadd']){
				JRequest::setVar('id', null );
			}
			parent::display();
		} else {
			$msg = JText::_('PROJECT_STORE_KO');
			$this->setRedirect('index.php?option=com_science&view=projects', $msg);
		}
	}
}

?>
