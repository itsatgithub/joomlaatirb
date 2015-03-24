<?php
/**
 * Joomla! 1.5 component irbtools
 *
 * @version $Id: controller.php 2010-10-13 07:12:40 svn $
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

jimport( 'joomla.application.component.controller' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'irbtoolsinstallhelper.php' );

/**
 * irbtools Controller
 *
 * @package Joomla
 * @subpackage irbtools
 */
class IrbtoolsController extends JController {
    /**
     * Constructor
     * @access private
     * @subpackage irbtools
     */
    function __construct() {
        //Get View
        if(JRequest::getCmd('view') == '') {
            JRequest::setVar('view', 'default');
        }
        $this->item_type = 'Default';
        parent::__construct();
    }
}
?>