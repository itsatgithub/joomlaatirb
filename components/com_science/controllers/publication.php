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
class ScienceControllerPublication extends JController
{

	function display() {

		JRequest::setVar( 'view', 'publication' );
		parent::display();
	}

	function prepare_data() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );

		$model =& $this->getModel('publication');
		$publication =& $model->getData();

		if ((($post['save'] == 'pubmed') || ($post['save'] == 'Fetch data from PubMed')) && ($post['type_id'] != '3') & (!empty($post['pubmed_id']))){ //submit via pubmed
			$publication = $model->getPubMedData($post['pubmed_id']);
			if ($publication->title) {
				$mainframe->enqueueMessage( JText::_('PUBLICATION_PUBMED_OK') );
			} else {
				$mainframe->enqueueMessage( JText::_('PUBLICATION_PUBMED_KO') );
			}
		}

		if ($post['type_id'] == '3') {
			JRequest::setVar('layout', 'bookchapter');
		} else {
			JRequest::setVar('layout', 'paper');
		}

		$publication->type_id = $post['type_id'];
		$publication->group_leader_id = $post['group_leader_id'];

		JRequest::setVar('publication', $publication );

		parent::display();

	}

	function save_data() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		//get data from the request
		$post = JRequest::get( 'post' );
			
		// store data. the function returns the saved id
		$model =& $this->getModel('publication');

		if (empty($post['id'])){
			unset($post['id']);
		}

		if ($post['save'] == 'getpubmed'){ //submit via pubmed
			$publication = $model->getPubMedData($post['pubmed_id']);
			$publication->id = $post['id'];
				
			// @todo I believe it is not needed to have the 'group_leader_id'
			unset($publication->group_leader_id);
			
			// Roberto 2013-05-29 Validation before save
			if (!$this->_can_save($publication->year)) {
				$mainframe->enqueueMessage( JText::_('NOT_ALLOWED') );
				parent::display();
				return;
			}

			$publication_id = $model->store($publication);
			if ($publication_id) {
				$mainframe->enqueueMessage( JText::_('PUBLICATION_STORE_OK') );
			} else {
				$mainframe->enqueueMessage( JText::_('PUBLICATION_STORE_KO') );
			}			
			parent::display();
			return;
		}
				
		/*
		 * 24/10/2011 Roberto. Solo puede almacenar un numero máximo de publicaciones
		 */
		
		// cuantas tiene diferentes de la que vamos a salvar
		$params =& JComponentHelper::getParams( 'com_science' );
		$max_number = $params->get('scienceConfig_MaxPublicationsExtranet');
		
		$number_of_extranet = $model->getNumberExtranet($post['group_leader_id'], $post['id']);
		if ($number_of_extranet >= $max_number && $post['selected_extranet'] == 1)
		{
			$mainframe->enqueueMessage( JText::_('PUBLICATION_MAX_NUM_EXTRANET') );
			parent::display();
			return;
		}
		
		/*
		 * fin de la modificacion
		 */
		
		// Roberto 2013-05-29 Validation before save
		if (!$this->_can_save($post['year'])) {
			$mainframe->enqueueMessage( JText::_('NOT_ALLOWED') );
			parent::display();
			return;
		}
		
		// save the data
		$publication_id = $model->store($post);
		
		if (!empty($post['group_leader_id'])) {
			// store publication - group leader data
			$model2 =& $this->getModel('publicationgroupleader');			
			$pub_gl_data['id']= $post['id_pub_gl'];		
			$pub_gl_data['publication_id']= $publication_id;
			$pub_gl_data['group_leader_id']= $post['group_leader_id'];
			// Roberto 2012-03-15 Añado selected_extranet
			$pub_gl_data['selected_extranet']= $post['selected_extranet'];
			$pub_gl_data_id = $model2->store($pub_gl_data);
		}

		if ($publication_id) {
			$mainframe->enqueueMessage( JText::_('PUBLICATION_STORE_OK') );
			if ($post['save']){
				JRequest::setVar('layout', 'default');
				JRequest::setVar('view', 'publications' );
			}
			parent::display();
		} else {
			$msg = JText::_('PUBLICATION_STORE_KO');
			$this->setRedirect('index.php?option=com_science', $msg);
		}
	}

	function save_pubmed_data() {
		global $mainframe;

		//get data from the request
		$post = JRequest::get( 'post' );

		// store data. the function returns the saved id
		$model =& $this->getModel('publication');
		$pubmed_data = $model->getPubMedData($post['pubmed_id']);

		// store publication - group leader data
		$model2 =& $this->getModel('publicationgroupleader');

		if ($post['id']){
			$pubmed_data['id'] = $post['id'];
		}

		if ($pubmed_data['pubmed_id']) {
			
			// Roberto 2013-05-29 Validation before save
			if (!$this->_can_save($pubmed_data->year)) {
				$mainframe->enqueueMessage( JText::_('NOT_ALLOWED') );
				parent::display();
				return;
			}
			
			$publication_id = $model->store($pubmed_data);

			$pub_gl_data['id'] = (empty($post['id_pub_gl'])) ? null : $post['id_pub_gl'];
			$pub_gl_data['publication_id']= $publication_id;
			$pub_gl_data['group_leader_id']= $post['group_leader_id'];
			/*echo '<pre>';
			 //print_r($pub_gl_data);
			 echo '</pre>';*/
			$pub_gl_data_id = $model2->store($pub_gl_data);

			JRequest::setVar('id', $publication_id );
			$mainframe->enqueueMessage( JText::_('PUBLICATION_PUBMED_OK') );
		} else {
			$mainframe->enqueueMessage( JText::_('PUBLICATION_PUBMED_KO') );
		}
		parent::display();

	}
	
	function _can_save($year)
	{
		$params =& JComponentHelper::getParams( 'com_science' );
		$allowed_user = $params->get('scienceConfig_AllowedUser');
		$year_limit = $params->get('scienceConfig_YearLimit');
		$user =& JFactory::getUser();
		//echo $user->username . "-" . $allowed_user . "-" . $year . "-" . $year_limit;
		//break;
		
		if (($user->username != $allowed_user) && ($year < $year_limit) ) {
			return false;
		} else {
			return true;
		}
		
	}
}
?>