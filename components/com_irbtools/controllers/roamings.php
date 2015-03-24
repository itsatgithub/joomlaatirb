<?php

// Check to ensure this file is included in Joomla!
//defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Irbtools Roamings Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerRoamings extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display()
	{
		// Make sure we have a default view
		if( !JRequest::getVar( 'view' )) {
			JRequest::setVar('view', 'roamings' );
		}
		parent::display();
	}
	
}
?>