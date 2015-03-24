<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class JCronViewAbout extends JView
{
	/**
	 * view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'JCron - CronTasks Scheduler' ), 'logo.png' );
        JToolBarHelper::preferences('com_jcron',250,400);
        JToolBarHelper::help('screen.users.jcron');

        $_CONFIG['incl_code'] = "\n<"."?php /*JCron Code*/ $"."from_template = 1;@include('components/com_jcron/jcron.php');/*DO NOT REMOVE ANYTHING*/ ?".">\n";
        $this->assignRef('code', $_CONFIG['incl_code']);
		JHTML::_('stylesheet','style.css','administrator/components/com_jcron/assets/');
		parent::display($tpl);
	}
}