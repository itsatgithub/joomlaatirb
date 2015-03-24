<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class IrbtoolsViewVersion extends JView
{
	function display($tpl = null)
	{
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_irbtools'.DS .'helpers'.DS.'irbtoolsinstallhelper.php');
		
		$params = JComponentHelper::getParams ('com_irbtools');
		$local = JHTML::_('irbtoolsinstallhelper.getVersionLocal');
		$remote = JHTML::_('irbtoolsinstallhelper.getVersionRemote');
		
		$this->assignRef('params', $params);
		$this->assignRef('vlocal', $local);
		$this->assignRef('vremote', $remote);

		parent::display($tpl);
	}
}
?>
