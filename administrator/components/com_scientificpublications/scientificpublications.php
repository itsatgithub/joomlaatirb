<?php
/**
 * Joomla! 1.5 component ScientificPublications
 *
 * @version $Id: scientificpublications.php 2010-03-01 10:08:36 svn $
 * @author Gplavui
 * @package Joomla
 * @subpackage ScientificPublications
 * @license GNU/GPL
 *
 * Application to manage scientific publications on ajoomla site
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Define constants for all pages
 */
define( 'COM_SCIENTIFICPUBLICATIONS_DIR', 'images'.DS.'scientificpublications'.DS );
define( 'COM_SCIENTIFICPUBLICATIONS_BASE', JPATH_ROOT.DS.COM_SCIENTIFICPUBLICATIONS_DIR );
define( 'COM_SCIENTIFICPUBLICATIONS_BASEURL', JURI::root().str_replace( DS, '/', COM_SCIENTIFICPUBLICATIONS_DIR ));

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

// Initialize the controller
$controller = new ScientificpublicationsController( );

// Perform the Request task
$task = JRequest::getCmd('task');
if (!$task)
{
	$task = 'display';
}
$controller->execute( $task );
$controller->redirect();
?>