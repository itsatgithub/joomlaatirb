<?php
/**
 * Joomla! 1.5 component irbtools
 *
 * @version $Id: irbtools.php 2010-10-13 07:12:40 svn $
 * @author IRB Barcelona
 * @package Joomla
 * @subpackage irbtools
 * @license GNU/GPL
 *
 * IRB Barcelona Tools
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
	$controller = 'gmail'; // default controller
}
require_once JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

//Include Helper file
JHTML::addIncludePath( array( JPATH_COMPONENT.DS.'helpers'.DS.'html' ) );

// Create the controller
$classname	= 'IrbtoolsController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>