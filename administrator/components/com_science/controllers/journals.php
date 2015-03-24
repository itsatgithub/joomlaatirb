<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Science Journals Controller
 *
 */
class ScienceControllerJournals extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display()
	{
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'view', 'journals' );

		parent::display();
	}

	function add()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		JRequest::setVar( 'hidemainmenu', 1 );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'view', 'journal');
		JRequest::setVar( 'edit', false );

		parent::display();
	}

	function edit()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		JRequest::setVar( 'id', $cid[0] );

		JRequest::setVar( 'hidemainmenu', 1 );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'view', 'journal');
		JRequest::setVar( 'edit', true );

		parent::display();
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
		print_r($cid);
		$model = $this->getModel('journal');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_science&view=journals' );
	}


}
?>