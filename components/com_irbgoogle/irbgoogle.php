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

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Initialize the controller
$controller = new IrbgoogleController();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>