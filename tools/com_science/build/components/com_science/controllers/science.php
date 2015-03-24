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

/**
 * Science Collaboration Controller
 *
 * @package		Joomla
 * @subpackage	Science
 * @since 1.5
 */

class ScienceControllerScience extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display()
	{
		// Make sure we have a default view
		if( !JRequest::getVar( 'view' )) {
			// Roberto 2015-02-10 Luca pide no mostrar la página general
			//JRequest::setVar('view', 'science' );
			JRequest::setVar('view', 'publications' );
		}
		parent::display();
	}

}

?>