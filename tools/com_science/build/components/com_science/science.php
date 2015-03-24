<?php
/**
 * Joomla! 1.5 component Science
 *
 * @version $Id: science.php 2009-10-16 08:00:35 svn $
 * @author GPL@vui
 * @package Joomla
 * @subpackage Science
 * @license GNU/GPL
 *
 * Scientific Production manager.
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require specific controller if requested
$controller = JRequest::getWord('controller');
if( !$controller ) {
	$controller = 'science'; // default controller
}
$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
require_once $path;

//Include Helper file
JHTML::addIncludePath( array( JPATH_COMPONENT.DS.'helpers'.DS.'html' ) );

// Create the controller
$classname	= 'ScienceController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>