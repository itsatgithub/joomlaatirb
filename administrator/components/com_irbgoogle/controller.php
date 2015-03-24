<?php
/**
 * Joomla! 1.5 component irbgoogle
 *
 * @version $Id: controller.php 2009-10-29 09:30:44 svn $
 * @author
 * @package Joomla
 * @subpackage irbgoogle
 * @license GNU/GPL
 *
 *
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

/**
 * irbgoogle Controller
 *
 * @package Joomla
 * @subpackage irbgoogle
 */
class IrbgoogleController extends JController
{

	/**
	 * Method to show a weblinks view
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display( )
	{
		$user =& JFactory::getUser();
		$model = $this->getModel('irbgoogle');

		// get the values
		$allemaillists = $model->retrieveAllEmailLists();

		// set the variables
		JRequest::setVar('allemaillists', $allemaillists );
		JRequest::setVar('emaillists', $emaillists );

		// Set a default view if none exists
		if ( !JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'default' );
		}

		parent::display();
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

		$model = $this->getModel('irbgoogle');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_irbgoogle' );
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

		$model = $this->getModel('irbgoogle');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_irbgoogle' );
	}

}
?>