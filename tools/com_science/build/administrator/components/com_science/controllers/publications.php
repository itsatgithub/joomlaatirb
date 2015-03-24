<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Science Publications Controller
 *
 * @package		Joomla
 * @subpackage	Science
 * @since 1.5
 */
class ScienceControllerPublications extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display()
	{
		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'publications');
		};

		parent::display();
	}

	function merge()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$post = JRequest::get('post');
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		// controls
		if (count( $cid ) != 2) {
			echo "<script> alert('".JText::_( 'SELECT_TWO_ITEMS' )."'); window.history.go(-1); </script>\n";
		}

		// the smaller, first...
		if ($cid[0] < $cid[1])
		{
			$pub_1 = $cid[0];
			$pub_2 = $cid[1];
		} else {
			$pub_1 = $cid[1];
			$pub_2 = $cid[0];
		}
		
		// get the publications
		$model_pub_1 =& $this->getModel('publication');
		$model_pub_1->setId($pub_1);
		$publication_1 = $model_pub_1->getData();
		
		$model_pub_2 =& $this->getModel('publication');
		$model_pub_2->setId($pub_2);
		$publication_2 = $model_pub_2->getData();

		// update the publication that will survive
		if ($model_pub_1->update_group_leader($publication_1->id, $publication_2->id, $publication_2->group_leader_id)) {
			$msg = JText::_('MERGE_OK');
		} else {
			$msg = JText::_('MERGE_KO');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_science&view=publications', false), $msg);
	}
}
?>