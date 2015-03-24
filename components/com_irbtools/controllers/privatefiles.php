<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.file' );
jimport('joomla.error.log' );


/**
 * Exception Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerPrivatefiles extends JController
{
	function send()
	{
		global $mainframe;

		// Check for request forgeries
		//JRequest::checkToken( 'get' ) or jexit( 'Invalid Token' );

		$params =& $mainframe->getParams();
		$filesFolder = $params->get('irbtoolsConfig_PrivateFilesFolder');
				
		//get data from the request
		$filename = JRequest::getVar( 'filename' );
		$userCode = substr($filename, 0, 5);
		
		// complete path
		$filename = $filesFolder.DS.$filename;
				
		// busco el usuario en el directorio
		$edir = ldap_connect($params->get('irbtoolsConfig_LdapServer'));
		$ldaprdn  = $params->get('irbtoolsConfig_LdapRdn');
		$ldappass = $params->get('irbtoolsConfig_LdapPass');
		
		// Roberto 2014-12-16
		// Verifico que es el propio usuario quien pide la nomina
		// comparando también su correo en el directorio
		$user =& JFactory::getUser();
		$useremail = $user->email;
		// Roberto 2014-12-16
		
    	$edir_bind = ldap_bind($edir, $ldaprdn, $ldappass);   	
    	$filter = "(&(objectclass=organizationalPerson)(IRB-UserCode=" . $userCode . ")(mail=" . $useremail . "))"; 
    	$edir_sr = ldap_search($edir, "o=irbbarcelona", $filter);
    	$info = ldap_get_entries($edir, $edir_sr);

    	if ($info["count"] != 1)
	    {
			// Roberto 2014-12-17
			// log the files that have been sent
			$options = array('format' => "{DATE}\t{TIME}\t WARNING: The file {FILENAME} does not correspond to {NAME} and is not sent");
			$log_filename= "privatefiles-".date( 'M-Y').".log";
			$log = & JLog::getInstance($log_filename, $options);
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Name"=>$user->name,"Filename"=>$filename));
			// Roberto 2014-12-17
			
	    	$mainframe->redirect('index.php?option=com_irbtools&view=privatefiles', JText::_('PRIVATEFILES_KO'));    		
	    }

	    $mailAddress = $info[0]['mail'][0];
		
		// sending the email
		$mailer =& JFactory::getMailer();
		$sender = array( 'its@irbbarcelona.org', 'its' );
		$mailer->setSender($sender);
		$recipient = array( $mailAddress );
		//$recipient = array( 'roberto.bartolome@irbbarcelona.org' );
		$mailer->addRecipient($recipient);		
		$mailer->setSubject( JText::_('PRIVATEFILES_MAIL_SUBJECT') );		
		$mailer->setBody( JText::_('PRIVATEFILES_MAIL_BODY') );
		$mailer->addAttachment($filename);		
		$send =& $mailer->Send();
		
		if ( $send !== true ) {
			$msg = JText::_('SEND_KO');
			$this->setRedirect('index.php?option=com_irbtools&view=privatefiles', $msg);
		} else {
			// Roberto 2014-12-17
			// log the files that have been sent
			$options = array('format' => "{DATE}\t{TIME}\t The file {FILENAME} is sent to {NAME}");
			$log_filename= "privatefiles-".date( 'M-Y').".log";
			$log = & JLog::getInstance($log_filename, $options);
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Name"=>$user->name,"Filename"=>$filename));
			// Roberto 2014-12-17
					
			$msg = JText::_('SEND_OK');
			$this->setRedirect('index.php?option=com_irbtools&view=privatefiles', $msg);
			/*
			$mainframe->enqueueMessage( JText::_('SEND_OK') );
			JRequest::setVar('view', 'privatefiles' );
			parent::display();
			*/
		}
	}
}

?>