<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

jimport( 'joomla.filesystem.file' );

/**
 * Exception Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerSciencemanager extends JController
{
	function send()
	{
		global $mainframe;

		// Check for request forgeries
		// 2012-05-15 Roberto Elimino esta opción para permitir la llamada periódica desde el cron a esta función 'send'
		//JRequest::checkToken() or jexit( 'Invalid Token' );

		// get the data
		$model =& $this->getModel('sciencemanager');
		$data = $model->getData();
		
		// get the addresses
		$params =& JComponentHelper::getParams( 'com_irbtools' );		
		$recipient_csv = $params->get('irbtoolsConfig_ScienceMailRecipients');
		$recipient = explode(",", $recipient_csv);
		
		// excepciones
		/*
		$exceptions_model =& JModel::getInstance( 'exceptions', 'irbtoolsmodel' );
		$lines = $exceptions_model->getFullData();
		*/
		$lines = '';
		
		$scienceFile = JHTML::_('irbtoolshelper.getScienceFile', $data, $lines);

		// making the file
		$filename = JPATH_SITE.DS.'tmp'.DS.'science_'.date("Ymd").'.csv';
		JFile::write($filename, $scienceFile);	
				
		// sending the email
		$mailer =& JFactory::getMailer();
		$sender = array( 'roberto.bartolome@irbbarcelona.org', 'Roberto Bartolome' );
		$mailer->setSender($sender);
		//$recipient = array( 'alvaro.cornago@biko2.com', 'cristina.mendez@irbbarcelona.org', 'ocer@irbbarcelona.org', 'its@irbbarcelona.org' );
		//$recipient = array( 'roberto.bartolome@irbbarcelona.org' );
		$mailer->addRecipient($recipient);		
		$mailer->setSubject('Data from Science');		
        $body = 'Please find attached to this mail the Science data file.';
		$mailer->setBody($body);
		$mailer->addAttachment($filename);		
		$send =& $mailer->Send();
		// delete the file
		JFile::delete($filename);		
		
		if ( $send !== true ) {
			$msg = JText::_('SEND_KO');
			$this->setRedirect('index.php?option=com_irbtools&view=sciencemanager', $msg);
		} else {
			$mainframe->enqueueMessage( JText::_('SEND_OK') );
			JRequest::setVar('view', 'sciencemanager' );
			parent::display();
		}
	}
}

?>