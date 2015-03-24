<?php
/**
 * Joomla! 1.5 component irbgoogle
 *
 * @version $Id: irbgoogle.php 2009-10-29 09:30:44 svn $
 * @author
 * @package Joomla
 * @subpackage irbgoogle
 * @license GNU/GPL
 *
 *
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
define( 'COM_IRBGOOGLE_DIR', 'images'.DS.'irbgoogle'.DS );
define( 'COM_IRBGOOGLE_BASE', JPATH_ROOT.DS.COM_IRBGOOGLE_DIR );
define( 'COM_IRBGOOGLE_BASEURL', JURI::root().str_replace( DS, '/', COM_IRBGOOGLE_DIR ));

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

// Initialize the controller
$controller = new IrbgoogleController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>