<?php
/**
 * Joomla! 1.5 component Science
 *
 * @version $Id: view.html.php 2009-10-16 08:00:35 svn $
 * @author GPL@vui
 * @package Joomla
 * @subpackage Science
 * @license GNU/GPL
 *
 * Scientific Production manager.
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

/**
 * HTML View class for the Science component
 */
class ScienceViewReport extends JView
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
		$this->_name = 'view_report';
	}

	function display($tpl = null)
	{
		global $mainframe;

		$user =& JFactory::getUser();
		$db =& JFactory::getDBO();

		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$rights = $sci_user->getRights($user->username, $this->_name);
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}

		// Get the page/component configuration
		$params =& $mainframe->getParams();

		// report rows
		$items = array();

		//get data from request
		$post = JRequest::get('post');
		
		$start_date = $post['start_date'];
		if( $start_date == null )
		{
			$mainframe->redirect('index.php?option=com_science&view=reports', JText::_('REPORT_NO_START_DATE') );
		}		
		$date = new JDate($start_date);
		$start_year = $date->toFormat('%Y');
		
		$end_date = $post['end_date'];
		if( $end_date == null )
		{
			$mainframe->redirect('index.php?option=com_science&view=reports', JText::_('REPORT_NO_END_DATE') );
		}		
		$date = new JDate($end_date);
		$end_year = $date->toFormat('%Y');
		
		$groups = $post['cid']; // checkbox for groups
		if ( !count( $groups ) )
		{
			$mainframe->redirect('index.php?option=com_science&view=reports', JText::_('REPORT_NO_GROUPS') );
		}
		
		$reports = $post['reports'];
		if ( !count( $reports ) )
		{
			$mainframe->redirect('index.php?option=com_science&view=reports', JText::_('REPORT_NO_REPORTS') );
		}
		
		// starting the cycle using all the research programmes
		$query = 'SELECT *'
		. ' FROM `#__sci_research_programmes` AS rp'
		. ' ORDER BY rp.order'
		;
		$db->setQuery($query);
		$research_programme_array = $db->loadObjectList();

		// each year on the selection, reverse order
		for ($i = $end_year; $i >= $start_year; $i--)
		{
			foreach ( $research_programme_array as $research_programme )
			{
				// foreach group in the research programme
				$query = 'SELECT gl.*, rp.description AS rp_description'
				. ' FROM `#__sci_group_leaders` AS gl'
				. ' LEFT JOIN `#__sci_research_programmes` AS rp ON rp.id = gl.research_programme_id'
				. ' WHERE gl.research_programme_id = ' . $research_programme->id
				. ' ORDER BY gl.report_order'
				;
				$db->setQuery($query);
				$group_leader_array = $db->loadObjectList();
					
				foreach ( $group_leader_array as $group_leader )
				{
					//echo 'control 1';
					if ( in_array( $group_leader->id, $groups ) )
					{
						//echo 'control 2';
						if ( in_array( 'publications', $reports ) )
						{
							//echo 'control 3';
							// get the publications for the year, group_leader, research_programme
							$model =& JModel::getInstance( 'publications', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['publications'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}
							
						if ( in_array( 'collaborations', $reports ) )
						{
							// get the collatorations
							$model =& JModel::getInstance( 'collaborations', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['collaborations'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}

						if ( in_array( 'projects', $reports ) )
						{
							// get the projects
							$model =& JModel::getInstance( 'projects', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['projects'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}

						if ( in_array( 'researchcontracts', $reports ) )
						{
							// get the projects
							$model =& JModel::getInstance( 'researchcontracts', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['researchcontracts'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}
						
						if ( in_array( 'theses', $reports ) )
						{
							// get the theses
							$model =& JModel::getInstance( 'theses', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['theses'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}

						if ( in_array( 'patents', $reports ) )
						{
							// get the patents
							$model =& JModel::getInstance( 'patents', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['patents'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}

						if ( in_array( 'awards', $reports ) )
						{
							// get the awards
							$model =& JModel::getInstance( 'awards', 'sciencemodel' );
							$lines[$i][$research_programme->description][$group_leader->name]['awards'] =
							$model->getHTMLReportData( $group_leader->id, $i );
						}
					}
				}
			}
		}

		$this->assign('start_date', $start_date);
		$this->assign('end_date', $end_date);
		$this->assignRef('groups', $groups);
		$this->assignRef('reports', $reports);
		$this->assignRef('lines', $lines);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
?>