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
class IrbtoolsControllerGmail extends JController
{

	/**
	 * Display data
	 *
	 * @param None
	 * @return None
	 */
	function display()
	{
	    // Set the view and the model
	    $view = JRequest::getVar( 'view', 'gmail' );
	    $layout = JRequest::getVar( 'layout', 'default' );
	    $view =& $this->getView( $view, 'html' );
	    $model =& $this->getModel( 'gmail' );
	    $view->setModel( $model, true );   
	    $view->setLayout( $layout );
	    
	    // Display the revue
	    parent::display();
	}

	/**
	 * Create the account on Gmail
	 * 
	 * @param None
	 * @return None
	 */
	function createAccount()
	{
		global $mainframe;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// get data from the request
		$post = JRequest::get('post');
		
		// get params
		$params =& JComponentHelper::getParams( 'com_irbtools' );
    	$emailSubject = $params->get( 'irbtoolsConfig_EmailSubject', '' );
    	$emailBody = $params->get( 'irbtoolsConfig_EmailBody', '' );
    	$google_lists = array();
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista1', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista2', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista3', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista4', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista5', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista6', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista7', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista8', '' );
    	$google_lists[] = $params->get( 'irbtoolsConfig_Lista9', '' );
    	 
		// busco el usuario en el directorio
		$edir = ldap_connect("irbldap1.irb.pcb.ub.es");
		$ldaprdn  = 'cn=admin,o=irbbarcelona';
		$ldappass = 'irbbarcelona';  // associated password
    	$edir_bind = ldap_bind($edir, $ldaprdn, $ldappass);   	
    	$filter = "(&(objectclass=organizationalPerson)(givenName=" . $post['name'] . ")(sn=" . $post['surname'] . "))"; 
    	    	
    	$edir_sr = ldap_search($edir, "o=irbbarcelona", $filter);
    	$info = ldap_get_entries($edir, $edir_sr);
    	
    	if ($info["count"] != 1)
    	{
    		ldap_close($edir);
    		$mainframe->redirect('index.php?option=com_irbtools&view=gmail', JText::_('USER_DIRECTORY_KO'));    		
    	}
    	
    	// Roberto 25-11-2013 Los datos introducidos en el formulario deben de ser iguales a los del directorio
    	// para evitar problemas de mayúsculas
    	$post['name'] = $info[0]['givenname'][0];
    	$post['surname'] = $info[0]['sn'][0];
    	
    	/*
    	// Roberto 2014-09-19
    	// validate the username
    	$pattern = "^[a-zA-Z]+\.[a-zA-Z.]$";
    	if (!eregi($pattern,$email))
    	{
    		$mainframe->redirect('index.php?option=com_irbtools&view=gmail', JText::_('GMAIL_USERNAME_NOT_VALID'));
    	}
    	// Roberto 2014-09-19
    	 */
    	
    	// Roberto 25-11-2013 paso todo el email a minúsculas
    	$post['username'] = strtolower($post['username']);
    	
    	// Roberto 16-11-2012 Añado el teléfono y el fax en los valores del directorio
    	$model =& $this->getModel('gmail');
    	   	 
		// modifico los atributos de IRB-Email, mail, IRB-PasswordInicial
		$new = array();
		$new["IRB-Email"] = "true";
		$new["mail"] = $post['username'] . '@irbbarcelona.org';
		$new["IRB-PasswordInicial"] = $post['password'];
		// Roberto 16-11-2012 Añado el teléfono y el fax en los valores del directorio				
		$mygroup = substr($info[0]['groupmembership'][0], 3, (strpos($info[0]['groupmembership'][0], ',') - 3));
		
		// Roberto 2014-06-04 Añado esta linea para poder actualizar teléfono y fax
		$phones = $model->getPhones($mygroup);		
		$new["telephoneNumber"] = $phones->phone;
		$new["facsimileTelephoneNumber"] = $phones->fax;

		// modifico el directory antes de crear la cuenta en Google. Esto es para evitar salir sin cerrar la 
		// conexión con edir
		$dn = $info[0]['dn'];
		ldap_modify($edir, $dn, $new);
		
    	// cierro la conexión con eDir
    	ldap_close($edir);
    	
    	$options = array('format' => "{DATE}\t{TIME}\t{TEXT}");
    	$log_filename= "log_gmail-".date( 'M-Y').".log";
    	$log = & JLog::getInstance($log_filename, $options);
    			
    	// creo la cuenta en Google
		$result = $model->insertUser($post['username'] . '@irbbarcelona.org', $post['name'], $post['surname'], $post['password'], TRUE, FALSE);		
		if ($result)
		{
			// group subscription				
			foreach($google_lists as $key=>$value)
			{
				if ($value)
				{
					// Subscribe the user to the group
					$model->insertGroupMember($post['username'] . '@irbbarcelona.org', 'MEMBER', 'USER', $value);
					$log->addEntry(array("Date" => date('d-m-Y')
						,"Time" => date('h:i')
						,"Text" => 'Added ' . $post['username'] . '@irbbarcelona.org' . ' to ' . $value));
				}
			}
											
			// mailer
			$mailer =& JFactory::getMailer();
			$config =& JFactory::getConfig();
			$sender = array( 
			    $config->getValue( 'config.mailfrom' ),
			    $config->getValue( 'config.fromname' )
			);
			$mailer->setSender($sender);
			
			// get the addresses
			$params =& JComponentHelper::getParams( 'com_irbtools' );		
			$recipient_csv = $params->get('irbtoolsConfig_GoogleMailRecipients');
			$recipient = explode(",", $recipient_csv);						 
			$mailer->addRecipient($recipient);
			$mailer->addCC('its@irbbarcelona.org');
			$mailer->addReplyTo('its-support@irbbarcelona.org');
			$mailer->setSubject($emailSubject . " " . $post['name'] . " " . $post['surname']);
			
			// content
			$mailmsg = $emailBody;			
			// replacement
			$p1 = array("#name", "#username", "#password",  "#uid");
			$p2 = array($post['name'], $post['username'], $post['password'], $info[0]['uid'][0]);
			$mailmsg = str_replace($p1, $p2, $mailmsg);					
			$mailer->IsHTML(true);
			$mailer->Encoding = 'base64';
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
				$mainframe->redirect('index.php?option=com_irbtools&view=gmail', JText::_('CREATE_ACCOUNT_OK'));				
			}
		} else {
			// 2012-02-13 Roberto
			$mensaje = JText::_('CREATE_ACCOUNT_GOOGLE_KO');
			// replacement
			$p1 = array("#name", "#username");
			$p2 = array($post['name'], $post['username']);
			$texto = str_replace($p1, $p2, $mensaje);					
			
			$mainframe->redirect('index.php?option=com_irbtools&view=gmail', $texto, 'error');
		}
	}
}

?>