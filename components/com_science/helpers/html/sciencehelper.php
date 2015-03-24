<?php
/**
 * @version		$Id: icon.php 11917 2009-05-29 19:37:05Z ian $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Content Component HTML Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class JHTMLScienceHelper
{
	function getGroupLeadersList($name,$selected=null,$javascript='')
	{
		$db    =& JFactory::getDBO();

		// build list of group leaders
		$query = 'SELECT t.id AS value, t.name AS text'
		. ' FROM `#__sci_group_leaders` AS t'
		. ' ORDER BY t.name'
		;
		$db->setQuery($query);
		$groupleaderslist[] = JHTML::_('select.option',  '', JText::_( '- Select Group Leader -' ), 'value', 'text');
		$groupleaderslist = array_merge( $groupleaderslist, $db->loadObjectList() );
		return JHTML::_('select.genericlist', $groupleaderslist, $name, $javascript, 'value', 'text', $selected );
	}

	function getGroupLeadersListBis()
	{
		$db    =& JFactory::getDBO();

		// build list of group leaders
		$query = 'SELECT id, name'
		. ' FROM `#__sci_group_leaders` AS t'
		. ' ORDER BY t.name'
		;
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	function getCountriesList($name,$selected = '199',$javascript)
	{
		$db    =& JFactory::getDBO();

		// build list of countries
		$query = 'SELECT t.id AS value, t.printable_name AS text'
		. ' FROM `#__sci_countries` AS t'
		. ' ORDER BY t.name'
		;
		$db->setQuery($query);
		$countrylist[] = JHTML::_('select.option',  '', JText::_( '- Select Country -' ), 'value', 'text');
		$countrylist = array_merge( $countrylist, $db->loadObjectList() );
		return JHTML::_('select.genericlist', $countrylist, $name, $javascript, 'value', 'text', $selected );
	}

	function getYearsList($name,$selected,$javascript)
	{
		$db    =& JFactory::getDBO();

		// build list of years
		$query = 'SELECT y.id AS value, y.description AS text'
		. ' FROM `#__sci_years` AS y'
		. ' ORDER BY y.order'
		;
		$db->setQuery($query);
		$yearlist[] = JHTML::_('select.option',  '', JText::_( '- Select Year -' ), 'value', 'text');
		$yearlist = array_merge( $yearlist, $db->loadObjectList() );
		return JHTML::_('select.genericlist', $yearlist, $name, $javascript, 'value', 'text', $selected );
	}

	function getYearsListYear($name,$selected,$javascript,$with_ongoing=true)
	{
		$db    =& JFactory::getDBO();

		// build list of years
		$query = 'SELECT y.description AS value, y.description AS text'
		. ' FROM `#__sci_years` AS y'
		. ' ORDER BY y.order'
		;
		$db->setQuery($query);
		$yearlist[] = JHTML::_('select.option',  '', JText::_( '- Select Year -' ), 'value', 'text');
		$yearlist = array_merge( $yearlist, $db->loadObjectList() );

		if(!$with_ongoing){
			unset($yearlist[1]);
		}

		return JHTML::_('select.genericlist', $yearlist, $name, $javascript, 'value', 'text', $selected );
	}

	function getGroupLeaderId($username)
	{
		$db    =& JFactory::getDBO();

		// build list of years
		$query = 'SELECT gl.id'
		. ' FROM `#__sci_group_leaders` AS gl'
		. ' WHERE gl.user_username = \'' . $username . '\''
		;
		$db->setQuery($query);
		return $db->loadResult();
	}

	function getGroupLeaderName($id)
	{
		$db    =& JFactory::getDBO();

		// build list of years
		$query = 'SELECT gl.name'
		. ' FROM `#__sci_group_leaders` AS gl'
		. ' WHERE gl.id = \'' . $id . '\''
		;
		$db->setQuery($query);
		return $db->loadResult();
	}

	function getGroupLeaderNames($id_publication)
	{
		$db    =& JFactory::getDBO();

		// build list of years
		$query = 'SELECT gl.name'
		. ' FROM `#__sci_publications` AS p'
		. ' LEFT JOIN `#__sci_publication_group_leader` AS pgl'
		. ' ON p.id = pgl.publication_id'
		. ' LEFT JOIN `#__sci_group_leaders` AS gl'
		. ' ON gl.id = pgl.group_leader_id'
		. ' WHERE pgl.publication_id = \'' . $id_publication . '\''
		;

		$db->setQuery($query);
		$result = $db->loadObjectList();
		$names = '';
		foreach ($result as $gl){
			$names .= $gl->name.', ';
		}
		return substr($names,0,-2);
	}

	function getTypeofPublication($id)
	{
		$db    =& JFactory::getDBO();

		// build list of years
		$query = 'SELECT pt.description'
		. ' FROM `#__sci_publication_types` AS pt'
		. ' WHERE pt.id = \'' . $id . '\''
		;
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 * From php.net, search for 'utf8_decode'. The function is in the user comments
	 *
	 * @param array $input Data array
	 * @return array decoded
	 */
	function utf8_array_decode( $input )
	{
		foreach ($input as $key => $val) {
			$input[$key] = utf8_decode($val);
		}
		return $input;
	}

}