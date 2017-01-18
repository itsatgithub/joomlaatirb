<?php
  /**
   * Joomla! 1.5 component Science
   *
   * @version $Id: install.helper.php 2009-10-16 08:00:35 svn $
   * @author GPL@vui
   * @package Joomla
   * @subpackage Science
   * @license GNU/GPL
   *
   * Scientific Production manager.
   *
   * This component file was created using the Joomla Component Creator by Not Web Design
   * http://www.notwebdesign.com/joomla_component_creator/
   *
   */
  
  // no direct access
  defined('_JEXEC') or die('Restricted access');
  
  /**
   * Science Helper
   *
   * @package Joomla
   * @subpackage Science
   * @since 1.5
   */
  class JHTMLScienceInstallHelper
  {
      function getVersionLocal()
      {
          // Código extraido de PhocaGallery
          $folder = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_science';
          if (JFolder::exists($folder)) {
              $xmlFilesInDir = JFolder::files($folder, '.xml$');
          } else {
			  $xmlFilesInDir = null;
          }
          
          $xml_items = '';
          if (count($xmlFilesInDir)) {
              foreach ($xmlFilesInDir as $xmlfile) {
                  if ($data = JApplicationHelper::parseXMLInstallFile($folder . DS . $xmlfile)) {
                      foreach ($data as $key => $value) {
                          $xml_items[$key] = $value;
                      }
                  }
              }
          }
          
          if (isset($xml_items['version']) && $xml_items['version'] != '')
          {
              $version['version'] = $xml_items['version'];
              $version['creationDate'] = $xml_items['creationdate'];
              $version['description'] = $xml_items['description'];
              return $version;
          } else {
              return '';
          }
      }
      
      function getVersionRemote()
      {
          //Código extraido de JFusion
          ob_start();
          $url = 'https://github.com/itsatgithub/joomlaatirb/tree/master/administrator/components/com_science/science.xml';
          $username = "";
          $password = "";
          
          if (function_exists('curl_init')) {
              //curl is the preferred function
              $crl = curl_init();
              $timeout = 5;
              curl_setopt($crl, CURLOPT_URL, $url);
              curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
			  //curl_setopt($crl, CURLOPT_USERPWD, "$username:$password");
              curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
              $JVersionRaw = curl_exec($crl);
              curl_close($crl);
          } else {
              //get the file directly if curl is disabled
              $context = stream_context_create(array(
    			'http' => array(
        			'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    				)
    			));       	
              $JVersionRaw = file_get_contents($url, false, $context);
              if (!strpos($JVersionRaw, '<install')) {
                  //file_get_content is often blocked by hosts, return an error message
                  echo JText::_('CURL_DISABLED_ERROR1');
                  return;
              }
          }
          
          $parser = JFactory::getXMLParser('Simple');
          
          if ($parser->loadString($JVersionRaw)) {
              if (isset($parser->document)) {
                  $JVersion = $parser->document;
              } else {
                  echo JText::_('CURL_DISABLED_ERROR2');
                  return;
              }
          }
          unset($parser);
          ob_end_clean();
          
          $version['version'] = $JVersion->version[0]->data();
          $version['creationDate'] = $JVersion->creationDate[0]->data();
          $version['description'] = $JVersion->description[0]->data();
          return $version;
      }
      
      function _sql($sql)
      {
          jimport('joomla.filesystem.file');
          
          $sql_file = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_science' . DS . 'sql' . DS . $sql . '.sql';
          $sql_query = JFile::read($sql_file);
          $db =& JFactory::getDBO();
          $db->setQuery($sql_query);
          if (!$db->queryBatch()) {
              echo $db->stderr() . '<br/>';
          } else {
              $msg = '<table bgcolor="#d9f9e2" width ="100%">';
              $msg .= '<tr style="height:30px"><td><b>' . JText::_('SQL UPDATE SUCESS!') . ' ' . $sql . '</b></td></tr>';
              $msg .= '</table>';
              echo $msg;
          }
      }
      
	function sqldiff($local, $remote)
	{
		$files = JFolder::files(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_science' . DS . 'sql');
      	if ($local != null) {
              foreach ($files as $f) {
                  if (substr($f, 12, 1) == "") {
                      $actual = substr($f, 0, 8);
                      if (($actual > $local) && ($actual <= $remote)) {
                          JHTMLScienceInstallHelper::_sql($actual);
                      }
                  }
              }
          } else {
              foreach ($files as $f) {
                  if (substr($f, 12, 1) == "") {
                      JHTMLScienceInstallHelper::_sql($actual);
                  }
              }
          }
      }
         
          
      function datetosql($date)
      {
      	return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
      }
      
}
?>
