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

/*
 * Define constants for all pages
 */
define( 'COM_IRBTOOLS_DIR', 'images'.DS.'irbtools'.DS );
define( 'COM_IRBTOOLS_BASE', JPATH_ROOT.DS.COM_IRBTOOLS_DIR );
define( 'COM_IRBTOOLS_BASEURL', JURI::root().str_replace( DS, '/', COM_IRBTOOLS_DIR ));

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller
require_once JPATH_COMPONENT.DS.'helpers'.DS.'irbtoolsinstallhelper.php';

// Initialize the controller
$controller = new IrbtoolsController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>