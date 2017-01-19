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

/**
 * HTML View class for the Science component
 */
class ScienceViewPublication extends JView
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
		$this->_name = 'view_publication';
	}

	function display($tpl = null)
	{

		// Get some objects
		$user =& JFactory::getUser();
		
		// access control
		$sci_user =& JModel::getInstance( 'user', 'sciencemodel' );
		$isadministrator = $sci_user->isAdministrator( $user->username );
		$rights = $sci_user->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}
		
		// Get some objects
		$model =& JModel::getInstance( 'publication', 'sciencemodel' );
		
		// publication id
		$id = JRequest::getVar( 'id' );
		if (!$id) {
			$id = 0;
		}
		
		// get the publication data
		$model->setId( $id );
		$publication =& $model->getData();
		
		// get the xml template
		$xml_template = file_get_contents('./components/com_science/views/publication/tmpl/template_opendata.xml');
		$xml_template_creator = file_get_contents('./components/com_science/views/publication/tmpl/template_opendata_creator.xml');
		
		// join the publication data and the xml
		//print_r($publication);
		//break;
		
		// loop for the creators
		$xml_creators = '';
		$authors = str_replace(" and ", ", ", $publication->authors); // remove 'and'
		$authors = str_replace(".", "", $authors); // remove last '.'
		$creators_array = explode(",", $authors);
		
		foreach ($creators_array as $creator) {
			$var_creator = array('#creator#' => trim($creator));
			$xml_creator .= strtr($xml_template_creator, $var_creator);
		}
		// isPartOf
		$ispartof = (($publication->journal) ? $publication->journal . ', ' : '')
			. (($publication->year) ? $publication->year . ', ' : '')
			. (($publication->volume) ? 'vol. ' . $publication->volume . ', ' : '')
			. (($publication->issue) ? 'num. ' . $publication->issue . ', ' : '')
			. (($publication->pages) ? 'p. ' . $publication->pages : '')
			;
		
		// variables to substitute:
		// 		title
		// 		creator (loop)
		// 		available
		// 		bibliographicCitation
		// 		abstract
		// 		publisher
		// 		isformatof
		// 		doi
		// 		isPartOf
		$vars = array(
				'#title#' => $publication->title,
				'#creator_block#' => $xml_creator,
				'#available#' => $publication->epub,
				//'#bibliographicCitation#' => 'COMPLETE',
				//'#abstract#' => 'COMPLETE',
				//'#publisher#' => 'COMPLETE',
				'#isformatof#' => $publication->citations,
				'#doi#' => $publication->citations,
				'#isPartOf#' => $ispartof
		);
		$xml_final = strtr($xml_template, $vars);
		
		// show the results
		header('Content-type: text/plain');
		header('Content-Disposition: attachment;
				filename="mets.xml"');
		echo $xml_final;
		die;
	}
}
?>
