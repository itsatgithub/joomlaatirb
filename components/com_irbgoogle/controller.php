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

jimport('joomla.application.component.controller');

/**
 * irbgoogle Component Controller
 */
class IrbgoogleController extends JController
{
	/**
	 * Displays data
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display()
	{
		parent::display();
	}

	function publish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$user =& JFactory::getUser();

		$model =& JModel::getInstance( 'irbgoogle', 'irbgooglemodel' );
		$model->setId( $cid[0] ); // just one selection at a time
		$lists =& $model->getData();
		$list = $lists[0]; // it takes first element

		$model->subscribeRecipientToEmailList( $user->email, $list->name );

		parent::display();
	}


	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$user =& JFactory::getUser();

		$model =& JModel::getInstance( 'irbgoogle', 'irbgooglemodel' );
		$model->setId( $cid[0] ); // just one selection at a time
		$lists =& $model->getData();
		$list = $lists[0]; // it takes first element

		$model->unsubscribeRecipientToEmailList( $user->email, $list->name );

		parent::display();
	}

}
?>