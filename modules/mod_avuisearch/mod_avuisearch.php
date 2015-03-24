<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

//Variables for the search module
$button			 = true; //$params->get('button', '');
$imagebutton	 = $params->get('imagebutton', '');
$button_pos		 = $params->get('button_pos', 'right');
$button_text	 = $params->get('button_text', JText::_('Search'));
$width			 = intval($params->get('width', 20));
$maxlength		 = $width > 20 ? $width : 20;
$text			 = $params->get('text', JText::_('search...'));
$set_Itemid		 = intval($params->get('set_itemid', 0));
$moduleclass_sfx = $params->get('moduleclass_sfx', '');

require(JModuleHelper::getLayoutPath('mod_avuisearch'));

