<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'error'.DS.'log.php' );

// getting the parameters
$mainframe =& JFactory::getApplication('site');
// needed to set mailer variables
$config =& JFactory::getConfig();
$sender = array(
		$config->getValue( 'config.mailfrom' ),
		$config->getValue( 'config.fromname' )
);
$params =& $mainframe->getParams( 'com_irbtools' );

$db =& JFactory::getDBO();
$query = 'SELECT `id`, `description`, `long_number`, `from`, `to`, `username`, `email`'
. ' FROM `#__irbtools_roamings`'
. ' WHERE `code` = \'\''
;
$db->setQuery($query);
$roaming_requests = $db->loadObjectList();

// group the roaming requests by telephone number
$requests_by_owner = array();
foreach ($roaming_requests as $req)
{
	// get the telefone owner
	$query = 'SELECT u.*'
	. ' FROM `#__irbtools_roaming_users` AS u'
	. ' LEFT JOIN `#__irbtools_roaming_telephones` AS t ON t.owner_id = u.id'
	. ' WHERE t.long_number = \'' . $req->long_number. '\''
	;	
	$db->setQuery($query);
	$result = $db->loadObject();
	$owner = $result->owner;
	
	$array_aux = array();
	$array_aux['id'] = $req->id;
	$array_aux['description'] = $req->description;
	$array_aux['long_number'] = $req->long_number;
	$array_aux['from'] = $req->from;
	$array_aux['to'] = $req->to;
	$array_aux['username'] = $req->username;
	$array_aux['email'] = $req->email;
	$requests_by_owner[$owner]['requests'][] = $array_aux;
}

// complete the data
foreach ($requests_by_owner as $key => &$req)
{
	$req['request_number'] = ''; // to be calculated later
	// to be saved on the database
	$to_be_saved = array();
	// lines for the email
	$lines_email_now = '';
	$user_emails = array();
	$user_names = '';
	$lines_email_delay = '';
	$lines_email_movistar = '';
	// Roberto 2015-12-23 new emails to be used
	$cc_email_now = '';
	
	foreach ($req['requests'] as $order)
	{
		// verifying dates
		$today = date('Y-m-d');
		$limit = date('Y-m-d', strtotime($today . "+ " . $params->get( 'irbtoolsConfig_NumberOfDays', '' ) . " days")); // 4 days from now
		
		if (strtotime($order['from']) <= strtotime($limit)) {
				
			// is there any request number?
			if (empty($req['request_number'])) {
				$year = date('Y');
				
				$query = 'SELECT *'
				. ' FROM #__irbtools_roamings'
				. ' ORDER BY code DESC'
				;
				$db->setQuery($query);
				$list = $db->loadObjectList();
				
				foreach($list as $data)
				{
					// it takes just the first one on the list
					$code = $data->code;
					$table_number = intval(substr($code, -4)); // 4 digits
					$table_year = intval(substr($code, 0, 4));
						
					// new year
					if ($table_year != $year) {
						$req['request_number'] = $year . '-0001'; // first code for the year
						break;
					} else {
						$table_number++;
						$req['request_number'] = $table_year . '-' . str_pad($table_number, 4, "0", STR_PAD_LEFT);
						break;
					}
				}			
			}
			
			// the order must be sent to Movistar
			$to_be_saved[] = array(
					'id' => $order['id'],
					'code' => $req['request_number'],
					'description' => $order['description'],
					'long_number' => $order['long_number'],
					'from' => $order['from'],
					'to' => $order['to'],
					'username' => $order['username'],
					'email' => $order['email']
			);
				
			// this is the name and email addreses of the user (the email can have many)
			$query = 'SELECT u.*'
			. ' FROM `#__users` AS u'
			. ' WHERE u.username = \'' . $order['username'] . '\''
			;
			$db->setQuery($query);
			$user = $db->loadObject();
			if (false === strpos($user_names, $user->name)) {
				$user_names .= $user->name . ', ';
				$user_emails[] = $user->email;
			}
			
			// Roberto 2015-12-23
			if (!strcmp($order['email'], $user->email)) {
				$cc_email_now .= $order['email'] . ",";
			}
			
			// this is the line to be sent by email to the users
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
	
	// take out commas from the end of the string
	$user_names = substr($user_names, 0, -2);
	if (!empty($cc_email_now)) {
		rtrim($cc_email_now, ",");
	}
	
	// send email to user with requests ok
	if (!empty($lines_email_now)) {
		// get the email template
		$mailer =& JFactory::getMailer();
		$mailer->setSender($sender);				
		$mailer->addRecipient($user_emails);						

		// Roberto 2015-12-23 Added ITS as CC on the email (from the parameters...)
		$emailRecipientCcCsv = $params->get( 'irbtoolsConfig_RoamingEmailCc', '' );
		$cc = explode(",", $emailRecipientCcCsv);
		$mailer->addCC($cc);

		// Roberto 2015-12-23
		if (!empty($cc_email_now)) {
			$cc = explode(",", $cc_email_now);
			$mailer->addCC($cc);
		}
		
		$emailUserSubject = $params->get( 'irbtoolsConfig_RoamingUserEmailSubject', '' );
		$emailUserBody = $params->get( 'irbtoolsConfig_RoamingUserEmailBody', '' );
		// replace the email subject
		$p1 = array('#request_number');
		$p2 = array($req['request_number']);
		$subject = str_replace($p1, $p2, $emailUserSubject);
		$mailer->setSubject($subject);
		
		// replace the email body
		$p1 = array("#request_number", "#requester", "#name", "#lines");
		$p2 = array($req['request_number'], $user_names, $key, $lines_email_now);
		$mailmsg = str_replace($p1, $p2, $emailUserBody);
		$mailer->setBody($mailmsg);
		$send = $mailer->Send();
		$options = array('format' => "{DATE}\t{TIME}\t{TEXT}");
		$log_filename= "log_roamings-".date( 'M-Y').".log";
		$log = & JLog::getInstance($log_filename, $options);
		if ( $send == true ) {
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Mail sent to the user ' . $user_names . ' Request number: ' . $req['request_number'] . ' ' . $lines_email_now));
		} else {
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Error sending email to the user ' . $user_names . ' Request number: ' . $req['request_number'] . ' ' . $lines_email_now));
			break;
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
		$p2 = array($req['request_number'], $user_names, $key, $lines_email_movistar);
		$mailmsg = str_replace($p1, $p2, $emailBody);
		$mailer->setBody($mailmsg);
		$send = $mailer->Send();
		$options = array('format' => "{DATE}\t{TIME}\t{TEXT}");
		$log_filename= "log_roamings-".date( 'M-Y').".log";
		$log = & JLog::getInstance($log_filename, $options);
		if ( $send == true ) {
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Mail sent to Movistar. Request number: ' . $req['request_number'] . ' ' . $lines_email_movistar));
		} else {
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Error sending email to Movistar. Request number: ' . $req['request_number'] . ' ' . $lines_email_movistar));
			break;
		}
	}
			
	// save the data on the database
	foreach ($to_be_saved as $row)
	{
		$query = 'UPDATE #__irbtools_roamings'
		. ' SET code = \'' . $row['code'] . '\''
		. ' WHERE id = ' . $row['id']
		;
		$db->setQuery($query);
		$options = array('format' => "{DATE}\t{TIME}\t{TEXT}");
		$log_filename= "log_roamings-".date( 'M-Y').".log";
		$log = & JLog::getInstance($log_filename, $options);
		if ($db->query()) {
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Update OK: ' . $db->getQuery()));					
		} else {
			$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Error in update: ' . $db->getQuery()));					
			break 2; // two foreach
		}
	}
}

// always log a message
$options = array('format' => "{DATE}\t{TIME}\t{TEXT}");
$log_filename= "log_roamings-".date( 'M-Y').".log";
$log = & JLog::getInstance($log_filename, $options);
$log->addEntry(array("Date" => date('d-m-Y'),"Time" => date('h:i'),"Text" => 'Cron executed'));

