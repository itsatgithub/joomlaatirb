<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Roaming Controller
 *
 * @package		Joomla
 * @subpackage	Irbtools
 */
class IrbtoolsControllerRoaming extends JController
{

	/**
	 * Display data
	 *
	 * @since	1.5
	 */
	function display() {

		JRequest::setVar( 'view', 'roaming' );
		parent::display();
	}

	/**
	 * Save exception data
	 *
	 * @since	1.5
	 */
	function save_data() {
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//get data from the request
		$post = JRequest::get( 'post' );
		
		// check the date range	
		// Roberto 2014-01-24 This check has been removed 
		/*
		$today = date('Y-m-d');
		$limit = date('Y-m-d', strtotime($today . "+ 4 days")); // 4 days from now
		if (strtotime($post['from']) > strtotime($limit)) {
			$mainframe->enqueueMessage( JText::_('ROAMING_DATE_OUT') );
			JRequest::setVar('view', 'roaming' );
			parent::display();
			return;
		}
		*/
		
		// check that the start date and end date are not in the past
		$today = date('Y-m-d');
		if (strtotime($post['from']) < strtotime($today)) {
			echo "control 1";
			break 2;
			$mainframe->enqueueMessage( JText::_('ROAMING_DATE_OUT') );
			JRequest::setVar('view', 'roaming' );
			parent::display();
			return;
		}
		
		// check that start date <= end date
		if ((strtotime($post['to']) != '') && (strtotime($post['from']) > strtotime($post['to']))) {
			echo "control 3";
			var_dump($post);
			break 2;
			$mainframe->enqueueMessage( JText::_('ROAMING_DATE_OUT') );
			JRequest::setVar('view', 'roaming' );
			parent::display();
			return;
		}
		
		// store data. the function returns the saved id
		$session = JFactory::getSession();
		$roaming_array = $session->get('roamings', array());
		$roaming_array[] = array(
				'description' => $post['description'],
				'long_number' => $post['long_number'],
				'from' => $post['from'],
				'to' => $post['to'],
				'username' => $post['username']
		);
		$session->set('roamings', $roaming_array);
		
		$mainframe->enqueueMessage( JText::_('ROAMING_STORE_OK') );
		JRequest::setVar('view', 'roaming' );
		parent::display();
	}
	
	function deletesession() {
		//get data from the request
		$get = JRequest::get( 'get' );
		
		// get the value from the session
		$session = JFactory::getSession();
		$roaming_array = $session->get('roamings', array());
		
		// delete the value
		$new_roaming_array = array();
		foreach ($roaming_array as $item)
		{			
			if ($item['long_number'] === $get['long_number']
				&& $item['from'] === $get['from']
				&& $item['to'] === $get['to']) {
				continue;
			} else {
				$new_roaming_array[] = $item;
			}

		}
		// reassign the value to the session
		$session->set('roamings', $new_roaming_array);		

		JRequest::setVar('view', 'roaming' );
		parent::display();
	}
	
	function send_request()
	{
		global $mainframe;
		
		//get data from the request
		$post = JRequest::get( 'post' );
		
		// get the value from the session
		$session = JFactory::getSession();
		$roaming_requests = $session->get('roamings', array());
		
		// getting the parameters
		$params =& JComponentHelper::getParams( 'com_irbtools' );
		
    	// needed to save the data on the db
    	$user =& JFactory::getUser(); 
    	$roaming =& $this->getModel('roaming');    	 
		$roamings_model =& $this->getModel('roamings');
		
		// needed to set mailer variables
		$config =& JFactory::getConfig();
		$sender = array(
				$config->getValue( 'config.mailfrom' ),
				$config->getValue( 'config.fromname' )
		);
		
		// group the roaming requests by telephone number
		$requests_by_owner = array();
		foreach ($roaming_requests as $req)
		{
			// get the telefone owner
			$model = $this->getModel('roaming');
			$owner = $model->getOwner($req['long_number']);			
			$requests_by_owner[$owner]['requests'][] = $req;
		}    	
    	
		// complete the data
		foreach ($requests_by_owner as $key => &$req)
		{	
			$req['request_number'] = ''; // to be calculated later
			// to be saved on the database
			$to_be_saved = array();
			// lines for the email
			$lines_email_now = '';
			$lines_email_delay = '';
			$lines_email_movistar = '';
				
			foreach ($req['requests'] as $order)
			{		
				// verifying dates
				$today = date('Y-m-d');				
				$limit = date('Y-m-d', strtotime($today . "+ " . $params->get( 'irbtoolsConfig_NumberOfDays', '' ) . " days")); // days from now
				if (strtotime($order['from']) > strtotime($limit)) {					
					// the order can not be sent to Movistar
					$to_be_saved[] = array(
							'code' => '',
							'description' => $order['description'],
							'long_number' => $order['long_number'],
							'from' => $order['from'],
							'to' => $order['to'],
							'username' => $user->username
					);
											
					// this is the line to be sent by email
					$lines_email_delay .= $order['description']." - "
						.$order['long_number']
						." ("
						.date("d/m/Y", strtotime($order['from']))
						." - "
						.(strcmp($order['to'], '') == 0? 'no end date': date("d/m/Y", strtotime($order['to'])))
						.")\n";				

				} else {
					// is there any request number?
					if (empty($req['request_number'])) {
						$req['request_number'] = $roamings_model->getRequestId();
					}
				
					// the order must be sent to Movistar
					$to_be_saved[] = array(
							'code' => $req['request_number'],
							'description' => $order['description'],
							'long_number' => $order['long_number'],
							'from' => $order['from'],
							'to' => $order['to'],
							'username' => $user->username
					);
					
					// this is the line to be sent by email						
					$lines_email_now .= $order['description']." - "
						.$order['long_number']
						." ("
						.date("d/m/Y", strtotime($order['from']))
						." - "
						.(strcmp($order['to'], '') == 0? 'no end date': date("d/m/Y", strtotime($order['to'])))
						.")\n";
						
					// this is the line to be sent to Movistar
					$lines_email_movistar .= $order['description']." - "
						.$order['long_number']
						." ("
						.date("d/m/Y", strtotime($order['from']))
						." - "
						.(strcmp($order['to'], '') == 0? 'no end date': date("d/m/Y", strtotime($order['to'])))
						.")\n";				
					
				}
			}
				
			// send email to user with requests ok
			if (!empty($lines_email_now)) {
				// get the email template
				$mailer =& JFactory::getMailer();
				$mailer->setSender($sender);
				$mailer->addRecipient($user->email);

				$emailUserSubject = $params->get( 'irbtoolsConfig_RoamingUserEmailSubject', '' );
				$emailUserBody = $params->get( 'irbtoolsConfig_RoamingUserEmailBody', '' );
				// replace the email subject
				$p1 = array('#request_number');
				$p2 = array($req['request_number']);
				$subject = str_replace($p1, $p2, $emailUserSubject);
				$mailer->setSubject($subject);						
				// replace the email body
				$p1 = array("#request_number", "#requester", "#name", "#lines");
				$p2 = array($req['request_number'], $user->name, $key, $lines_email_now);
				$mailmsg = str_replace($p1, $p2, $emailUserBody);
				$mailer->setBody($mailmsg);
				
				$send = $mailer->Send();
				if ( $send !== true ) {
					// redirecting
					$mainframe->redirect('index.php?option=com_irbtools&view=roaming', JText::_('ROAMING_MAIL_KO'));					
				}			
			}
							
			// email to user with requests delayed
			if (!empty($lines_email_delay)) {
				// get the email template
				$mailer =& JFactory::getMailer();
				$mailer->setSender($sender);
				$mailer->addRecipient($user->email);

				$emailUserSubject = $params->get( 'irbtoolsConfig_DelayedRoamingUserEmailSubject', '' );
				$mailer->setSubject($emailUserSubject);						
				$emailUserBody = $params->get( 'irbtoolsConfig_DelayedRoamingUserEmailBody', '' );
				// replace the email body
				$p1 = array("#requester", "#name", "#lines");
				$p2 = array($user->name, $key, $lines_email_delay);
				$mailmsg = str_replace($p1, $p2, $emailUserBody);
				$mailer->setBody($mailmsg);
				
				$send = $mailer->Send();
				if ( $send !== true ) {
					// redirecting
					$mainframe->redirect('index.php?option=com_irbtools&view=roaming', JText::_('ROAMING_MAIL_KO'));					
				}			
			}
									
			// email to movistar
			if (!empty($lines_email_movistar)) {
				// get the email template
				$mailer =& JFactory::getMailer();
				$mailer->setSender($sender);

				$emailRecipientCsv = $params->get( 'irbtoolsConfig_RoamingEmailRecipients', '' );
				$recipient = explode(",", $emailRecipientCsv);
				$mailer->addRecipient($recipient);
				
				$emailRecipientCcCsv = $params->get( 'irbtoolsConfig_RoamingEmailCc', '' );
				$cc = explode(",", $emailRecipientCcCsv);
				$mailer->addCC($cc);
				
				$emailRecipientReplyToCsv = $params->get( 'irbtoolsConfig_RoamingEmailReplyTo', '' );
				$reply_to = explode(",", $emailRecipientReplyToCsv);
				$mailer->addReplyTo($reply_to);
				
				$emailSubject = $params->get( 'irbtoolsConfig_RoamingEmailSubject', '' );
				$emailBody = $params->get( 'irbtoolsConfig_RoamingEmailBody', '' );
				// replace the email subject
				$p1 = array('#request_number');
				$p2 = array($req['request_number']);
				$subject = str_replace($p1, $p2, $emailSubject);
				$mailer->setSubject($subject);
				// replace the email body
				$p1 = array("#request_number", "#requester", "#name", "#lines");
				$p2 = array($req['request_number'], $user->name, $key, $lines_email_movistar);
				$mailmsg = str_replace($p1, $p2, $emailBody);
				$mailer->setBody($mailmsg);
					
				$send = $mailer->Send();
				if ( $send !== true ) {
					// redirecting
					$mainframe->redirect('index.php?option=com_irbtools&view=roaming', JText::_('ROAMING_MAIL_KO'));
				}
					
			}
			
			// save the data on the database
			foreach ($to_be_saved as $row)
			{
				// writing the roaming table
				$roaming->store($row);
			}
		}
		
		// cleaning up the session variables
		$session->set('roamings', array());
		
		// redirecting
		$mainframe->redirect('index.php?option=com_irbtools&view=roaming', JText::_('ROAMING_MAIL_OK'));	
		
	}
}

?>