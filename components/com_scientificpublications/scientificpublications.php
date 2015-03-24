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

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Initialize the controller
$controller = new ScientificpublicationsController();
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>