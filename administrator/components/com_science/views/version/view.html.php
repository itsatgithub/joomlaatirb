<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ScienceViewVersion extends JView
{
	function display($tpl = null)
	{
		require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_science' . DS . 'helpers' . DS . 'scienceinstallhelper.php');
		 
		$local = JHTML::_('scienceinstallhelper.getVersionLocal');
		$remote = JHTML::_('scienceinstallhelper.getVersionRemote');
		//$local = ScienceHelper::getVersionLocal();
		//$remote = JHTMLScienceInstallHelper::getVersionRemote();

		$this->assignRef('vlocal', $local);
		$this->assignRef('vremote', $remote);

		parent::display($tpl);
	}
}
?>
