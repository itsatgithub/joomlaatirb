<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Science Journal Controller
 *
 */
class ScienceControllerJournal extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display( )
	{
		JRequest::setVar('view' , 'journal');
		parent::display();
	}

	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$post = JRequest::get('post');
		$model = $this->getModel('journal');

		/*
		 * 2011-04-27 not doing this for the moment
		 * 
		// saving impact factors
		$id = JRequest::getVar( 'id', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$model->setId($id);
		$start_year = JRequest::getVar( 'start_year', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$end_year = JRequest::getVar( 'end_year', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$impact_factor = JRequest::getVar( 'impact_factor', array(), 'post', 'array' );

		$old_value = $impact_factor[$start_year];
		for ($i = $start_year; $i <= $end_year; $i++)
		{
			if ( $impact_factor[$i] != 0 ) {
				$model->storeImpactFactor($i, $impact_factor[$i]);
				$old_value = $impact_factor[$i];
			} else {
				$model->storeImpactFactor($i, $old_value);
			}
		}
		*/

		/**
		 * 2012-02-24 saving impact factors as they are entered
		 */
		$id = JRequest::getVar( 'id', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$model->setId($id);
		$start_year = JRequest::getVar( 'start_year', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$end_year = JRequest::getVar( 'end_year', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$impact_factor = JRequest::getVar( 'impact_factor', array(), 'post', 'array' );

		for ($i = $start_year; $i <= $end_year; $i++)
		{
			$model->storeImpactFactor($i, $impact_factor[$i]);
		}		
		/**
		 * 2012-02-24 end of modification
		 */
		
		if ($model->store($post)) {
			$msg = JText::_( 'Journal Saved' );
		} else {
			$msg = JText::_( 'Error saving journal' );
		}
		
		$link = 'index.php?option=com_science&view=journals';
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_science&view=journals' );
	}


}
?>