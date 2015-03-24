<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * Journal View class for the Science component
 *
 */
class ScienceViewJournal extends JView
{
	function display($tpl = null)
	{
		$params =& JComponentHelper::getParams( 'com_science' );
		$model =& JModel::getInstance( 'journal', 'sciencemodel' );

		// set the id. 0 = new
		$id = JRequest::getVar('id');
		if (!$id) {
			$id = 0;
		}

		// set the id
		$model->setId( $id );

		// Get data from the model
		$item =& $model->getData();
		$start_year = $params->get('scienceConfig_ImpactFactorStartYear');
		$end_year = $params->get('scienceConfig_ImpactFactorEndtYear');
		$impact_factor = array();
		for ($i = $start_year; $i <= $end_year; $i++)
		{
			$impact_factor[$i] = $model->getImpactFactorData($i);
		}

		$this->assignRef('start_year', $start_year);
		$this->assignRef('end_year', $end_year);
		$this->assignRef('item', $item);
		$this->assignRef('impact_factor', $impact_factor);
		parent::display($tpl);
	}
}
