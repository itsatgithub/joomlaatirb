<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Gmail Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerDirmanager extends JController
{

	/**
	 * Display data
	 *
	 * @param None
	 * @return None
	 */
	function display()
	{
	    // Display the revue
	    parent::display();
	}

	/**
	 * Create the account on Gmail
	 * 
	 * @param None
	 * @return None
	 */
	function updateAccount()
	{
		global $mainframe;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// get data from the request
		$post = JRequest::get('post');
		
		 // Set the view and the model
		$view = JRequest::getVar( 'view', 'dirmanager' );
		$layout = JRequest::getVar( 'layout', 'default' );
		$view =& $this->getView( $view, 'html' );
		$model =& $this->getModel( 'dirmanager' );
		$view->setModel( $model, true );
		$view->setLayout( $layout );
				
		// check that the date format is ok
		if (!preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $post['date']))
		{
			$mainframe->redirect('index.php?option=com_irbtools&view=dirmanager', JText::_('DIRMANAGER_FECHANOVALIDA'));
		}
		
		//get the day, month and year
		list($my_day, $my_month, $my_year) = sscanf($post['date'], "%d-%d-%d");	
				
		// check that the date is logical. IMP the format is month, day, year
		if (!checkdate($my_month, $my_day, $my_year))
		{
			$mainframe->redirect('index.php?option=com_irbtools&view=dirmanager', JText::_('DIRMANAGER_FECHANOVALIDA'));
		}
				
		// update
		$aux = $model->updateFechaBajaMail($post['email'], $post['date']);
		if ($aux)
		{	
			$mailer =& JFactory::getMailer();
			$config =& JFactory::getConfig();
			$sender = array(
					$config->getValue( 'config.mailfrom' ),
					$config->getValue( 'config.fromname' )
			);
			$mailer->setSender($sender);
				
			// get the addresses
			$params =& JComponentHelper::getParams( 'com_irbtools' );
    		$emailSubject = $params->get( 'irbtoolsConfig_DirmanagerEmailSubject', '' );
    		$emailBody = $params->get( 'irbtoolsConfig_DirmanagerEmailBody', '' );
			$recipient_csv = $params->get('irbtoolsConfig_GoogleMailRecipients');
			
			$recipient = explode(",", $recipient_csv);
			$mailer->addRecipient($post['email']);
			$mailer->addCC($recipient);
			$mailer->setSubject($emailSubject . " " . $post['email']);
			
			// content
			$mailmsg = $emailBody;
			// replacement
			$p1 = array("#email", "#date");
			$p2 = array($post['email'], $post['date']);
			$mailmsg = str_replace($p1, $p2, $mailmsg);
			$mailer->setBody($mailmsg);
				
			$send =& $mailer->Send();
			if ( $send )
			{
				// writing the log table
				$logarray = array();
				$logarray['text'] = $post['name'] . '-' . $mailmsg;
				$logmodel =& $this->getModel('emaillog');
				$logmodel->store($logarray);
			
				// redirecting
				$mainframe->redirect('index.php?option=com_irbtools&view=dirmanager', JText::_('DIRMANAGER_FECHABAJAEMAIL_OK'));
			}				
		} else {
			// redirecting
			$mainframe->redirect('index.php?option=com_irbtools&view=dirmanager', JText::_('DIRMANAGER_FECHABAJAEMAIL_KO'));
		}
	}
}

?>