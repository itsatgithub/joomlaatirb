<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Science Publications Controller
 *
 * @package		Joomla
 * @subpackage	Science
 * @since 1.5
 */
class ScienceControllerPublications extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display()
	{
		// Make sure we have a default view
		if( !JRequest::getVar( 'view' )) {
			JRequest::setVar('view', 'publications' );
		}
		parent::display();
	}
	
	function _can_delete($year)
	{
		$params =& JComponentHelper::getParams( 'com_science' );
		$allowed_user = $params->get('scienceConfig_AllowedUser');
		$year_limit = $params->get('scienceConfig_YearLimit');
		$user =& JFactory::getUser();
		//echo $user->username . "-" . $allowed_user . "-" . $year . "-" . $year_limit;
		//break;
		
		if (($user->username != $allowed_user) && ($year < $year_limit) ) {
			return false;
		} else {
			return true;
		}
		
	}
	

	function delete()
	{		
		//get data from the request
		$get = JRequest::get( 'get' );
		$id = $get['id'];
		
		$model = $this->getModel( 'publication' );
		$model->setId($id);
		$publication_data = $model->getData();

		//Rodolfo 2014-03-17 GL cannot delete publications before limit year	
		if (!$this->_can_delete($publication_data->year)) {
			$msg = JText::_('NOT_ALLOWED');
			$this->setRedirect(JRoute::_('index.php?option=com_science&view=publications', false), $msg);
			return;
		}
		if( $model->delete( $id ) ) {
			$msg = JText::_('PUBLICATION_DEL_OK');
		} else {
			$msg = JText::_('PUBLICATION_DEL_KO');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_science&view=publications', false), $msg);
	}
		
}

?>
