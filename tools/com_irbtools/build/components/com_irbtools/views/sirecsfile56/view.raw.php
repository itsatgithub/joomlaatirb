<?php
/**
 * Joomla! 1.5 component Science
 *sta
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

ini_set( 'display_errors', 1 );
// the path /usr/share/php5/PEAR needed for opensuse servers
//ini_set( 'include_path', '.:./includes:./includes/PEAR:/usr/share/php5/PEAR' );

// PEAR include for generating Excel files
require_once( 'Spreadsheet/Excel/Writer.php' );

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
		/*
		 * Excel generation using PEAR library
		 * Instructions to use the library:
		 * 1.- download the library from http://pear.php.net/package/Spreadsheet_Excel_Writer/download
		 * 2.- download the package OLE from http://pear.php.net/package/OLE/download
		 * 3.- add /usr/share/php5/PEAR (o /usr/share/php) in the variable include_path located
		 * in the file /etc/php5/apache2/php.ini
		 * 4.- untar the libraries in /usr/share/php5/PEAR ( O /usr/share/php)
		 */
		$user =& JFactory::getUser();

		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$rights = $sci_user->getRights($user->username, $this->_name);
		if ( $rights == null ) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}

		// report rows
		$items = array();

		//get data from request
		$post = JRequest::get('post');
		$start_date = $post['start_date'];
		$end_date = $post['end_date'];
		$groups = unserialize( base64_decode( $post['groups'] ) );
		$reports = unserialize( base64_decode( $post['reports'] ) );

		// create excel file
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send("reports_" . date("Ymd") . ".xls");

		foreach ( $reports as $key=>$report )
		{
			switch ( $report )
			{
				case 'publications':
					$model =& JModel::getInstance( 'publications', 'sciencemodel' );
					$items['publications'] = $model->getReportData( $start_date, $end_date, $groups );
					// general decode. it does not work for the timem beins as it passes a class, not an array.
					//$items['publications'] = JHTML::_('sciencehelper.utf8_array_decode', $items['publications']);
						
					// is there any book chapter, to adjust the colums?
					$elems =& $items['publications'];
					$someBookChapter = false;
					for ($i=0, $n=count( $elems ); $i < $n; $i++)
					{
						$item =& $elems[$i];
						if ($item->type_id == 3)
						{
							$someBookChapter = true;
						}
					}
						
					$j = 0; // column index
					if (count($items['publications']))
					{
						$worksheet =& $workbook->addWorksheet( JText::_( 'PUBLICATION_HEADER' ) );
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_RESEARCH_PROGRAMME' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_GROUP_LEADER' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_TYPE' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_TITLE' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_AUTHORS' ));
						if ($someBookChapter) {
							$worksheet->write(0, $j++, JText::_( 'PUBLICATION_BOOK_TITLE' ));
						}
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_JOURNAL' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_VOLUME' ));
						if ($someBookChapter) {
							$worksheet->write(0, $j++, JText::_( 'PUBLICATION_VOLUME_TITLE' ));
						}
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_ISSUE' ));
						if ($someBookChapter) {
							$worksheet->write(0, $j++, JText::_( 'PUBLICATION_EDITOR' ));
							$worksheet->write(0, $j++, JText::_( 'PUBLICATION_PUBLISHER' ));
						}
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_PAGES' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_YEAR' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_INTERNATIONAL_COAUTHORS' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_GROUP_CONTRIBUTION' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_JOINT_PUBLICATION' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_IF_JOINT_PUBLICATION' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_IMPACT_FACTOR' ));
						$worksheet->write(0, $j++, JText::_( 'PUBLICATION_CITATIONS' ));
							
						$items =& $items['publications'];

						for ($i=0, $n=count( $items ); $i < $n; $i++)
						{
							$j = 0; // columns index
							$item =& $items[$i];
								
							$worksheet->write( $i+1, $j++, $item->gl_research_programme );
							$worksheet->write( $i+1, $j++, utf8_decode( $item->gl_name ) );
							$worksheet->write( $i+1, $j++, $item->pt_description );
							$worksheet->write( $i+1, $j++, utf8_decode( $item->title ) );
							$worksheet->write( $i+1, $j++, utf8_decode( $item->authors ) );
							if ($someBookChapter) {
								$worksheet->write($i+1, $j++, $item->book_title );
							}
							$worksheet->write( $i+1, $j++, $item->journal );
							$worksheet->write( $i+1, $j++, $item->volume);
							if ($someBookChapter) {
								$worksheet->write($i+1, $j++, $item->volume_title );
							}
							$worksheet->write( $i+1, $j++, $item->issue);
							if ($someBookChapter) {
								$worksheet->write($i+1, $j++, $item->editor );
								$worksheet->write($i+1, $j++, $item->publisher );
							}
							$worksheet->write( $i+1, $j++, $item->pages);
							$worksheet->write( $i+1, $j++, $item->year);
							$worksheet->write( $i+1, $j++, ($item->coauthors_type_id == 2) ? JText::_( 'YES' ) : JText::_( 'NO' ));
							$worksheet->write( $i+1, $j++, $item->gc_description );
							$worksheet->write( $i+1, $j++, ($item->joint_publication) ? JText::_( 'YES' ) : JText::_( 'NO' ));
							$worksheet->write( $i+1, $j++, utf8_decode( $item->joint_publication_description ));
							$worksheet->write( $i+1, $j++, $item->impact_factor );
							$worksheet->write( $i+1, $j++, $item->citations );
						}
					}
					break;
						
				case 'collaborations':
					$model =& JModel::getInstance( 'collaborations', 'sciencemodel' );
					$items['collaborations'] = $model->getReportData( $start_date, $end_date, $groups );
					// model needed to get collaboration's levels, sectors and types
					$model_collaboration =& JModel::getInstance( 'collaboration', 'sciencemodel' );
						
					// counting max number of sectors
					$elems =& $items['collaborations'];
					$maxSectors = 0;
					for ($i=0, $n=count( $elems ); $i < $n; $i++)
					{
						$item =& $elems[$i];

						$model_collaboration->setId($item->collaboration_id);
						$sectorsNumber = $model_collaboration->getCollaborationSectorsNumber();
						if ($sectorsNumber > $maxSectors)
						{
							$maxSectors = $sectorsNumber;
						}
					}
						
					$j = 0; // colum index
					if (count($items['collaborations']))
					{
						$worksheet =& $workbook->addWorksheet( JText::_( 'COLLABORATION_HEADER' ) );
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_RESEARCH_PROGRAMME' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_GROUP_LEADER' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_TYPE' ));
						for ($k=0 ; $k < $maxSectors; $k++) {
							$str_tmp = JText::_( 'COLLABORATION_SECTOR' );
							$str_tmp .= ' ';
							$t = $k+1;
							$str_tmp .= $t;
							$worksheet->write(0, $j++, $str_tmp);
						}
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_LEVEL' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_TITLE' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_RESEARCH_NAME' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_INSTITUTION' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_CITY' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_COUNTRY' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_START_YEAR' ));
						$worksheet->write(0, $j++, JText::_( 'COLLABORATION_END_YEAR' ));
							
						$old_id = 0; // working with more than one row per collaboration
						$items =& $items['collaborations'];

						for ($i=0, $n=count( $items ); $i < $n; $i++)
						{
							$j = 0; // columns index
							$item =& $items[$i];
								
							$model_collaboration->setId($item->collaboration_id); // new collaboration row
							$collaboration_type_str = $model_collaboration->getCollaborationTypesString();
							$collaboration_sector_array = $model_collaboration->getCollaborationSectorsArrayDescription();
							$collaboration_level_str = $model_collaboration->getCollaborationLevelsString();
								
							$worksheet->write( $i+1, $j++, ($item->collaboration_id != $old_id) ? $item->gl_research_programme : '' );
							$worksheet->write( $i+1, $j++, ($item->collaboration_id != $old_id) ? utf8_decode( $item->gl_name ) : '' );
							$worksheet->write( $i+1, $j++, $collaboration_type_str);
							for ($k=0 ; $k < $maxSectors; $k++) {
								$worksheet->write( $i+1, $j++, $collaboration_sector_array[$k]);
							}
							$worksheet->write( $i+1, $j++, $collaboration_level_str);
							$worksheet->write( $i+1, $j++, ($item->collaboration_id != $old_id) ? $item->title : '' );
							$worksheet->write( $i+1, $j++, utf8_decode( $item->research_name ) );
							$worksheet->write( $i+1, $j++, utf8_decode( $item->institution) );
							$worksheet->write( $i+1, $j++, $item->city);
							$worksheet->write( $i+1, $j++, $item->country);
							$worksheet->write( $i+1, $j++, ($item->collaboration_id != $old_id) ? $item->start_year : '' );
							$worksheet->write( $i+1, $j++, ($item->collaboration_id != $old_id) ? $item->end_year : '' );
								
							$old_id = $item->collaboration_id;
						}
					}
					break;
						
				case 'projects':
					$model =& JModel::getInstance( 'projects', 'sciencemodel' );
					$items['projects'] = $model->getReportData( $start_date, $end_date, $groups );
						
					// code update - 2010-11-18 - See ticket
					$col_gt = 0;
					$col_fe = 0;
					$aux_start_date = new JDate($start_date);
					$year_min = $aux_start_date->toFormat('%Y');
					$aux_end_date = new JDate($end_date);
					$year_max = $aux_end_date->toFormat('%Y');
						
					$elems =& $items['projects'];
					for ($i=0, $n=count( $elems ); $i < $n; $i++)
					{
						$item =& $elems[$i];

						// Counting max number of columns in funding entity
						$desc_fe = ($item->funding_entity_id == 34) ? $item->funding_entity_specific : $item->fe_description;
						$array_fe = explode(":", $desc_fe);
						$num_fe = count($array_fe);
						if ($num_fe > $col_fe) {
							$col_fe = $num_fe;
						}

						// Counting max number of columns in grant type
						$desc_gt = $item->gt_description;
						$array_gt = explode(":", $desc_gt);
						$num_gt = count($array_gt);
						if ($num_gt > $col_gt) {
							$col_gt = $num_gt;
						}
					}

					$j = 0; // columns index
					if (count($items['projects']))
					{
						$worksheet =& $workbook->addWorksheet( JText::_( 'PROJECT_HEADER' ) );
						$worksheet->write(0, $j++, JText::_( 'PROJECT_GROUP_LEADER' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_PRINCIPAL_INVESTIGATOR' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_BENEFICIARY_REPORT' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_TITLE' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_ACRONYM' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_REFERENCE' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_START_DATE' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_END_DATE' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_IRB_CODE' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_TOTAL_BUDGET' ));

						if (!$sci_user->isGroupLeader($user->username))
						{
							$worksheet->write(0, $j++, JText::_( 'PROJECT_OVERHEAD_TOTAL_BUDGET' ));
								
							for ($x=$year_min; $x<=$year_max; $x++)
							{
								$worksheet->write(0, $j++, JText::_( 'PROJECT_BUDGET_YEAR' ) . ' ' . $x);
								$worksheet->write(0, $j++, JText::_( 'PROJECT_OVERHEAD_YEAR' ) . ' ' . $x);
							}
						}

						for ($k=1 ; $k <= $col_fe; $k++) {
							$worksheet->write(0, $j++, JText::_( 'PROJECT_FUNDING_ENTITY' ) . ' ' . $k);
						}
						for ($k=1 ; $k <= $col_gt; $k++) {
							$worksheet->write(0, $j++, JText::_( 'PROJECT_GRANT_TYPE' ) . ' ' . $k);
						}
						$worksheet->write(0, $j++, JText::_( 'PROJECT_OWNER' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_CALL' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_TIMING' ));
						$worksheet->write(0, $j++, JText::_( 'PROJECT_ACTION_TYPE' ));
							
						$items =& $items['projects'];

						for ($i=0, $n=count( $items ); $i < $n; $i++)
						{
							$j = 0; // columns index
							$item =& $items[$i];
								
							$worksheet->write($i+1, $j++, utf8_decode( $item->gl_name ));
							$worksheet->write($i+1, $j++, utf8_decode( $item->principal_investigator));
							$worksheet->write($i+1, $j++, $item->beneficiary);
							$worksheet->write($i+1, $j++, utf8_decode( $item->title ));
							$worksheet->write($i+1, $j++, $item->acronym);
							$worksheet->write($i+1, $j++, $item->reference);
							$worksheet->write($i+1, $j++, $item->start_date);
							$worksheet->write($i+1, $j++, $item->end_date);
							$worksheet->write($i+1, $j++, $item->irb_code);
							$worksheet->write($i+1, $j++, $item->total_budget);

							if (!$sci_user->isGroupLeader($user->username))
							{
								$worksheet->write($i+1, $j++, $item->overheads_total_budget);

								// looping over the blank years
								$aux_start_date = new JDate($item->start_date);
								$start_year = $aux_start_date->toFormat('%Y');
								for ($k = $year_min; $k < $start_year; $k++)
								{
									$worksheet->write($i+1, $j++, '0');
									$worksheet->write($i+1, $j++, '0');
								}
								// the rest of the years are printed
								$worksheet->write($i+1, $j++, $item->budget_year_1);
								$worksheet->write($i+1, $j++, $item->overheads_year_1);
								$k++;

								// next line
								if ($k <= $year_max)
								{
									$worksheet->write($i+1, $j++, $item->budget_year_2);
									$worksheet->write($i+1, $j++, $item->overheads_year_2);
									$k++;
								}

								// next line
								if ($k <= $year_max)
								{
									$worksheet->write($i+1, $j++, $item->budget_year_3);
									$worksheet->write($i+1, $j++, $item->overheads_year_3);
									$k++;
								}

								// next line
								if ($k <= $year_max)
								{
									$worksheet->write($i+1, $j++, $item->budget_year_4);
									$worksheet->write($i+1, $j++, $item->overheads_year_4);
									$k++;
								}

								// next line
								if ($k <= $year_max)
								{
									$worksheet->write($i+1, $j++, $item->budget_year_5);
									$worksheet->write($i+1, $j++, $item->overheads_year_5);
									$k++;
								}

								// looping again until the end
								for ($z = $k; $z <= $year_max; $z++)
								{
									$worksheet->write($i+1, $j++, '0');
									$worksheet->write($i+1, $j++, '0');
								}

							}
								
							// the 34 is the 'other' so we must show the description
							$desc_fe = utf8_decode( ($item->funding_entity_id == 34) ? $item->funding_entity_specific : $item->fe_description);
							$array_fe = explode(":", $desc_fe);
							for ($k=0 ; $k < $col_fe; $k++) {
								$worksheet->write($i+1, $j++, $array_fe[$k]);
							}
							$desc_gt = utf8_decode( $item->gt_description );
							$array_gt = explode(":", $desc_gt);
							for ($k=0 ; $k < $col_gt; $k++) {
								$worksheet->write($i+1, $j++, $array_gt[$k]);
							}
							$worksheet->write($i+1, $j++, $item->ow_description);
							$worksheet->write($i+1, $j++, $item->call);
							$worksheet->write($i+1, $j++, $item->ti_description);
							$worksheet->write($i+1, $j++, $item->at_description);
						}
					}
					break;
						
				case 'theses':
					$model =& JModel::getInstance( 'theses', 'sciencemodel' );
					$items['theses'] = $model->getReportData( $start_date, $end_date, $groups );

					if (count($items['theses']))
					{
						$worksheet =& $workbook->addWorksheet( JText::_( 'THESIS_HEADER' ) );
						$worksheet->write(0, 0, JText::_( 'THESIS_RESEARCH_PROGRAMME' ));
						$worksheet->write(0, 1, JText::_( 'THESIS_GROUP_LEADER' ));
						$worksheet->write(0, 2, JText::_( 'THESIS_TITLE' ));
						$worksheet->write(0, 3, JText::_( 'THESIS_AUTHOR' ));
						$worksheet->write(0, 4, JText::_( 'THESIS_UNIVERSITY' ));
						$worksheet->write(0, 5, JText::_( 'THESIS_YEAR' ));
						$worksheet->write(0, 6, JText::_( 'THESIS_LECTURE_DATE' ));
						$worksheet->write(0, 7, JText::_( 'THESIS_LANGUAGE' ));
						$worksheet->write(0, 8, JText::_( 'THESIS_DIRECTOR' ));
						$worksheet->write(0, 9, JText::_( 'THESIS_CODIRECTOR' ));
						$worksheet->write(0, 10, JText::_( 'THESIS_TUTOR' ));
						$worksheet->write(0, 11, JText::_( 'THESIS_HONOR' ));

						$items =& $items['theses'];

						for ($i=0, $n=count( $items ); $i < $n; $i++)
						{
							$item =& $items[$i];
							$worksheet->write($i+1, 0, $item->gl_research_programme);
							$worksheet->write($i+1, 1, utf8_decode( $item->gl_name ));
							$worksheet->write($i+1, 2, utf8_decode( $item->title ));
							$worksheet->write($i+1, 3, utf8_decode( $item->author ));
							$worksheet->write($i+1, 4, $item->university);
							$worksheet->write($i+1, 5, $item->year);
							$worksheet->write($i+1, 6, $item->lecture_date);
							$worksheet->write($i+1, 7, $item->tl_description);
							$worksheet->write($i+1, 8, utf8_decode( $item->director ));
							$worksheet->write($i+1, 9, utf8_decode( $item->codirector ));
							$worksheet->write($i+1, 10, $item->tutor);
							$worksheet->write($i+1, 11, $item->th_description);
						}
					}
					break;

				case 'patents':
					$model =& JModel::getInstance( 'patents', 'sciencemodel' );
					$items['patents'] = $model->getReportData( $start_date, $end_date, $groups );

					if (count($items['patents']))
					{
						$worksheet =& $workbook->addWorksheet( JText::_( 'PATENT_HEADER' ) );
						$worksheet->write(0, 0, JText::_( 'PATENT_RESEARCH_PROGRAMME' ));
						$worksheet->write(0, 1, JText::_( 'PATENT_GROUP_LEADER' ));
						$worksheet->write(0, 2, JText::_( 'PATENT_TITLE' ));
						$worksheet->write(0, 3, JText::_( 'PATENT_INVENTORS' ));
						$worksheet->write(0, 4, JText::_( 'PATENT_PUBLICATION_NUMBER' ));
						$worksheet->write(0, 5, JText::_( 'PATENT_PUBLICATION_DATE' ));
						$worksheet->write(0, 6, JText::_( 'PATENT_STATUS' ));
							
						$items =& $items['patents'];

						for ($i=0, $n=count( $items ); $i < $n; $i++)
						{
							$item =& $items[$i];
							$worksheet->write($i+1, 0, $item->gl_research_programme);
							$worksheet->write($i+1, 1, utf8_decode( $item->gl_name ));
							$worksheet->write($i+1, 2, $item->title);
							$worksheet->write($i+1, 3, utf8_decode( $item->inventors ));
							$worksheet->write($i+1, 4, $item->publication_number);
							$worksheet->write($i+1, 5, $item->publication_date);
							$worksheet->write($i+1, 6, $item->ps_description);
						}
					}
					break;

				case 'awards':
					$model =& JModel::getInstance( 'awards', 'sciencemodel' );
					$items['awards'] = $model->getReportData( $start_date, $end_date, $groups );

					if (count($items['awards']))
					{
						$worksheet =& $workbook->addWorksheet( JText::_( 'AWARD_HEADER' ) );
						$worksheet->write(0, 0, JText::_( 'AWARD_RESEARCH_PROGRAMME' ));
						$worksheet->write(0, 1, JText::_( 'AWARD_GROUP_LEADER' ));
						$worksheet->write(0, 2, JText::_( 'AWARD_TITLE' ));
						$worksheet->write(0, 3, JText::_( 'AWARD_BODY' ));
						$worksheet->write(0, 4, JText::_( 'AWARD_AWARDEE' ));
						$worksheet->write(0, 5, JText::_( 'AWARD_YEAR' ));
						$worksheet->write(0, 6, JText::_( 'AWARD_END_YEAR' ));

						$items =& $items['awards'];

						for ($i=0, $n=count( $items ); $i < $n; $i++)
						{
							$item =& $items[$i];
							$worksheet->write($i+1, 0, $item->gl_research_programme);
							$worksheet->write($i+1, 1, utf8_decode( $item->gl_name ));
							$worksheet->write($i+1, 2, utf8_decode( $item->title ));
							$worksheet->write($i+1, 3, utf8_decode( $item->awarding_body ));
							$worksheet->write($i+1, 4, utf8_decode( $item->awardee ));
							$worksheet->write($i+1, 5, $item->year);
							$worksheet->write($i+1, 6, $item->end_year);
						}
					}
					break;
			}
		}

		// close excel file
		$workbook->close();
		die;
	}
}
?>