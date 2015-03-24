<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

// PEAR include for generating Excel files
require_once( 'Spreadsheet/Excel/Writer.php' );

class IrbtoolsViewFilemanager extends JView
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
		$this->_name = 'filemanager';
	}

	function display($tpl = null)
	{
		echo "hola";
		die;
			
		/*
		 * Excel generation using PEAR library
		 * Instructions to use the library:
		 * 1.- download the library from http://pear.php.net/package/Spreadsheet_Excel_Writer/download
		 * 2.- download the package OLE from http://pear.php.net/package/OLE/download
		 * 3.- add /usr/share/php5/PEAR (o /usr/share/php) in the variable include_path located
		 * in the file /etc/php5/apache2/php.ini
		 * 4.- untar the libraries in /usr/share/php5/PEAR ( O /usr/share/php)
		 */
		/*
		$user =& JFactory::getUser();

		// access control
		$irbuser =& JModel::getInstance( 'user', 'irbtoolsmodel' );
		$rights = $irbuser->getRights( $user->username, $this->_name );
		if (!$rights) {
			echo JText::_( 'ALERTNOTAUTH' );
			return;
		}

		// create excel file
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send("reports_" . date("Ymd") . ".xls");
		
		$worksheet =& $workbook->addWorksheet( JText::_( 'PUBLICATION_HEADER' ) );
		$worksheet->write(0, 1, JText::_( 'PUBLICATION_RESEARCH_PROGRAMME' ));

		// close excel file
		$workbook->close();
		die;
		*/
	}
}
?>