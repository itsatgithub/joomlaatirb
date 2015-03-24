<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Science Manager view
 */
class IrbtoolsViewSciencemanager extends JView
{
	/**
	 * Abstract name
	 *
	 * @var name
	 */
	var $_name = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		// IMP: Set this always
		$this->_name = 'sciencemanager';
	}

	/**
	 * Display view
	 *
	 * @since 1.5
	 */
	function display($tpl = null)
	{
		global $mainframe, $option;
			
		// Get some objects
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$uri =& JFactory::getURI();
		$params =& $mainframe->getParams();

		// access control
		$irbuser =& JModel::getInstance( 'user', 'irbtoolsmodel' );
		$rights = $irbuser->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}
		
		// get the data
		$model =& $this->getModel('sciencemanager');
		$data = $model->getData();
		
		// excepciones
		/*
		$exceptions_model =& JModel::getInstance( 'exceptions', 'irbtoolsmodel' );
		$lines = $exceptions_model->getFullData();
		*/
		$lines = '';
		
		$scienceFile = JHTML::_('irbtoolshelper.getScienceDisplay', $data, $lines);
		
		// set the variables
		$this->assignRef('sciencefile', $scienceFile);
		$this->assignRef('user', $user);
		$this->assignRef('params', $params);
		$this->assign('action', $uri->toString());

		parent::display($tpl);
	}
}
?>