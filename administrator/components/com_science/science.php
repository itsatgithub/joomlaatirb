<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Define constants for all pages
 */
define( 'COM_SCIENCE_DIR', 'images'.DS.'science'.DS );
define( 'COM_SCIENCE_BASE', JPATH_ROOT.DS.COM_SCIENCE_DIR );
define( 'COM_SCIENCE_BASEURL', JURI::root().str_replace( DS, '/', COM_SCIENCE_DIR ));

// Require specific controller if requested
$controller = JRequest::getWord('controller');
if( !$controller )
{
	$controller = 'publications'; // default controller
}
$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
require_once $path;

// Require the base controller
//require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

// Create the controller
$classname	= 'ScienceController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
$task = JRequest::getCmd('task');
if (!$task)
{
	$task = 'display';
}

// go
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
?>