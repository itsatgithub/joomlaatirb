<?php
/**
 * Joomla! 1.5 component ScientificPublications
 *
 * @version $Id: controller.php 2010-03-01 10:08:36 svn $
 * @author Gplavui
 * @package Joomla
 * @subpackage ScientificPublications
 * @license GNU/GPL
 *
 * Application to manage scientific publications on ajoomla site
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL ^ E_NOTICE);

jimport( 'joomla.application.component.controller' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

/**
 * ScientificPublications Controller
 *
 * @package Joomla
 * @subpackage ScientificPublications
 */
class ScientificpublicationsController extends JController
{
	/**
	 * Constructor
	 * @access private
	 * @subpackage ScientificPublications
	 */
	function __construct()
	{
		parent::__construct();
	}

	function add()
	{
		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publication&id=0' );
	}

	function edit()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publication&id='.$cid[0] );
	}

	function display( )
	{
		//echo 'a';die;
		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'publications');
		};
		parent::display();
	}


	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$post = JRequest::get('post');
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('publication');

		if ($model->store($post)) {
			$msg = JText::_( 'Publication Saved' );
		} else {
			$msg = JText::_( 'Error Saving Publication' );
		}

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publications' );
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('publication');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publications' );
	}


	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('publication');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publications' );
	}


	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('publication');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publications' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publications' );
	}

	function close()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_scientificpublications&view=publications' );
	}

}
?>