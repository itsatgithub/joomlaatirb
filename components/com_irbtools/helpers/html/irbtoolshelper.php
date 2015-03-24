<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );

/**
 * Irbtools Component HTML Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Irbtools
 * @since 1.5
 */
class JHTMLIrbtoolsHelper
{
	function getPassword()
	{
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ023456789";
	    srand((double)microtime()*1000000);
	    $i = 0;
	    $pass = '' ;
	    
	    while ($i <= 7)
	    {
	    	$num = rand() % 58; // 0 to 58 = 59 characters
	    	$tmp = substr($chars, $num, 1);
	    	$pass = $pass . $tmp;
	    	$i++;
	    }
	    
	    return $pass;
	}
	
	function getCommandsList($name, $selected, $javascript)
	{
		$db =& JFactory::getDBO();

		// build list of countries
		$query = 'SELECT c.description AS value, c.description AS text'
		. ' FROM `#__irbtools_commands` AS c'
		. ' ORDER BY c.order'
		;
		$db->setQuery($query);
		$commandslist[] = JHTML::_('select.option',  '', JText::_( '- Select Command -' ), 'value', 'text');
		$commandslist = array_merge( $commandslist, $db->loadObjectList() );
		return JHTML::_('select.genericlist', $commandslist, $name, $javascript, 'value', 'text', $selected );
	}
	
	function getIrbpeopleFile($data, $lines)
	{
		// separator es ; para evitar problemas con las comas en los nombres de la BD
		$sep = ";";			
		// building the file
		$filerow = array();
		$filerow['0'] = "User Id" . $sep . "Name" . $sep . "Surname1" .$sep . "Surname2" . $sep . "Gender" . $sep . "Department" . $sep
		. "Unit" . $sep . "Research Group" . $sep . "Email" . $sep . "Phone" . $sep
		. "Position" . $sep . "Location" . $sep . "Second affiliation" . "\n"
		;
		
		// adding data to the file
		foreach($data as $row)
		{
			// uppercases and so on...
			$name =  ucwords(strtolower(strtr(utf8_decode($row['name']), utf8_decode("ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ"), utf8_decode("àáâãäåæçèéêëìíîïðñòóôõöøùüú"))));
			$surname1 =  ucwords(strtolower(strtr(utf8_decode($row['surname1']), utf8_decode("ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ"), utf8_decode("àáâãäåæçèéêëìíîïðñòóôõöøùüú"))));
			/*
			$surname1 = mb_strtolower(strtr(utf8_decode($row['surname1']), utf8_decode("ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ"), utf8_decode("àáâãäåæçèéêëìíîïðñòóôõöøùüú")));
			if (strpos(substr($surname1, 1), "àáâãäåæçèéêëìíîïðñòóôõöøùüú")) {
				$surname1 = strtr(utf8_decode($surname1), utf8_decode("àáâãäåæçèéêëìíîïðñòóôõöøùüú"), utf8_decode("ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ"));
			}
			*/	
			$surname2 =  ucwords(strtolower(strtr(utf8_decode($row['surname2']), utf8_decode("ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ"), utf8_decode("àáâãäåæçèéêëìíîïðñòóôõöøùüú"))));
			$gender = utf8_decode($row['gender']);
			$department = utf8_decode($row['department']);
			$unit = utf8_decode($row['unit']);
			$research_group = utf8_decode($row['research_group']);
			$second_affiliation = utf8_decode($row['second_affiliation']);
			
			$pc = ltrim($row['personalcode'], "0");
			
			$filerow[$pc] = $pc . $sep . $name . $sep . $surname1 . $sep . $surname2 . $sep . $gender . $sep . $department . $sep
			. $unit . $sep . $research_group . $sep . $row['email'] . $sep . $row['phone'] . $sep
			. $row['position'] . $sep . $row['location'] . $sep . $second_affiliation . "\n"
			;
		}

		// excepciones
		foreach($lines as $line)
		{
			// characters code
			$irbpeople_user_id = $line->irbpeople_user_id;
			$line->name = utf8_decode($line->name);
			$line->surname1 = utf8_decode($line->surname1);
			$line->surname2 = utf8_decode($line->surname2);
			$line->gender = utf8_decode($line->gender);
			$line->department = utf8_decode($line->department);
			$line->unit = utf8_decode($line->unit);
			$line->research_group = utf8_decode($line->research_group);
			$line->second_affiliation = utf8_decode($line->second_affiliation);
			
			switch($line->command)
			{
				case 'add':
					$filerow[$irbpeople_user_id . "bis"] = $irbpeople_user_id . "bis" . $sep . $line->name . $sep . $line->surname1 . $sep . $line->surname2 . $sep . $line->gender . $sep . $line->department . $sep
					. $line->unit . $sep . $line->research_group . $sep . $line->email . $sep . $line->phone . $sep
					. $line->position . $sep . $line->location . $sep . $line->second_affiliation . "\n"
					;
					break;
				case 'mod':
					// borro el antiguo
					unset($filerow[$irbpeople_user_id]);
					// creo el nuevo
					$filerow[$irbpeople_user_id] = $irbpeople_user_id . $sep . $line->name . $sep . $line->surname1 . $sep . $line->surname2 . $sep . $line->gender . $sep . $line->department . $sep
					. $line->unit . $sep . $line->research_group . $sep . $line->email . $sep . $line->phone . $sep
					. $line->position . $sep . $line->location . $sep . $line->second_affiliation . "\n"
					;
					break;
				case 'ins':
					$filerow[$irbpeople_user_id] = $irbpeople_user_id . $sep . $line->name . $sep . $line->surname1 . $sep . $line->surname2 . $sep . $line->gender . $sep . $line->department . $sep
					. $line->unit . $sep . $line->research_group . $sep . $line->email . $sep . $line->phone . $sep
					. $line->position . $sep . $line->location . $sep . $line->second_affiliation . "\n"
					;
					break;
				case 'del':
					unset($filerow[$irbpeople_user_id]);
					break;
			}
		}
		
		$filestr = '';
        foreach ($filerow as $value)
		{
			$filestr .= $value;
		}
		return $filestr;
	}

	
	function getScienceFile($data, $lines)
	{
		// separator para evitar problemas con las comas en los nombres de la BD
		$sep = ";";			
		// building the file
		$filerow = array();
		$filerow['0'] = "Publication Id" . $sep . "Title" . $sep . "Authors" . $sep . "Journal" . $sep . "Volume" . $sep
		. "Issue" . $sep . "Pages" . $sep . "Year" . $sep . "Link to Pubmed" . $sep . "Groups" . $sep 
		. "Date of print" . $sep . "Selected" . $sep . "Type". $sep
		. "PhD selected publication" . $sep . "IRB selected publication"
		. "\n"
		;
		
		// adding data to the file
		foreach($data as $row)
		{
			$id = $row['id'];
			$title = $row['title'];
			$authors = $row['authors'];
			$journal = $row['journal'];
			$volume = $row['volume'];
			$issue = $row['issue'];
			$pages = $row['pages'];
			$year = $row['year'];
			if (isset($row['pubmed_id'])) {
				$link_pubmed = "http://www.ncbi.nlm.nih.gov/pubmed?term=" . trim($row['pubmed_id']);
			} else {
				$link_pubmed = "";
			}
			$research_groups = $row['research_groups'];
			$date_of_print = $row['date_of_print'];
			$selected = ($row['selected_extranet'])? "Yes": "No";
			$description = $row['description'];
			$phd_selected_publication = ($row['phd_selected_publication'])? "Yes": "No";
			$irb_selected_publication = ($row['irb_selected_publication'])? "Yes": "No";
				
			$filerow[] = $id . $sep . $title . $sep . $authors . $sep . $journal . $sep . $volume . $sep
			. $issue . $sep . $pages . $sep . $year . $sep . $link_pubmed . $sep . $research_groups . $sep
			. $date_of_print . $sep . $selected . $sep . $description . $sep
			. $phd_selected_publication . $sep . $irb_selected_publication . $sep
			. "\n"
			;
		}
		
		$filestr = '';
        foreach ($filerow as $value)
		{
			$filestr .= $value;
		}
		return $filestr;
	}

	function getScienceDisplay($data, $lines)
	{
		// separator para evitar problemas con las comas en los nombres de la BD
		$sep = ";";			
		// building the file
		$filerow = array();
		$filerow['0'] = "Publication Id" . $sep . "Title" . $sep . "Authors" . $sep . "Journal" . $sep . "Volume" . $sep
		. "Issue" . $sep . "Pages" . $sep . "Year" . $sep . "Link to Pubmed" . $sep . "Groups" . $sep 
		. "Date of print" . $sep . "Selected" . $sep . "Type" . $sep 
		. "PhD selected publication" . $sep . "IRB selected publication"
		. "\n"
		;
		
		// adding data to the file
		foreach($data as $row)
		{
			/*
			 * the utf8_decode avoid non coding characters on the screen
			 */
			$id = utf8_decode($row['id']);
			$title = utf8_decode($row['title']);
			$authors = utf8_decode($row['authors']);
			$journal = utf8_decode($row['journal']);
			$volume = utf8_decode($row['volume']);
			$issue = utf8_decode($row['issue']);
			$pages = utf8_decode($row['pages']);
			$year = utf8_decode($row['year']);
			if (isset($row['pubmed_id'])) {
				$link_pubmed = "http://www.ncbi.nlm.nih.gov/pubmed?term=" . utf8_decode(trim($row['pubmed_id']));
			} else {
				$link_pubmed = "";
			}
			$research_groups = utf8_decode($row['research_groups']);
			$date_of_print = utf8_decode($row['date_of_print']);
			$selected = ($row['selected_extranet'])? "Yes": "No";
			$description = utf8_decode($row['description']);
			$phd_selected_publication = ($row['phd_selected_publication'])? "Yes": "No";
			$irb_selected_publication = ($row['irb_selected_publication'])? "Yes": "No";

			$filerow[] = $id . $sep . $title . $sep . $authors . $sep . $journal . $sep . $volume . $sep
			. $issue . $sep . $pages . $sep . $year . $sep . $link_pubmed . $sep . $research_groups . $sep
			. $date_of_print . $sep . $selected . $sep . $description . $sep 
			. $phd_selected_publication . $sep . $irb_selected_publication
			. "\n"
			;
		}
		
		$filestr = '';
        foreach ($filerow as $value)
		{
			$filestr .= $value;
		}
		return $filestr;
	}

}