<?php
/**
 * @version             $Id: password.php 1.6.0 2009-08-04 Prog@ndy $
 * @package             Joomla
 * @subpackage  Content
 * @copyright   Copyright (C) 2007 - 2009 Prog@ndy
 * @license             http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL,
 */
/**
* Password Plugin
*
* <b>Usage:</b>
* a) <code>{password}</code>
* b) <code>{password PASSWORD}</code>
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.plugin.plugin');

class plgContentContentpassword extends JPlugin {

/**
 * Constructor
 *
 * For php4 compatability we must not use the __constructor as a constructor for plugins
 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
 * This causes problems with cross-referencing necessary for the observer design pattern.
 *
 * @param object $subject The object to observe
 * @param object $params  The object that holds the plugin parameters
 * @since 1.5
 */
	function plgContentContentpassword(& $subject, $config) {
		parent :: __construct($subject, $config);

		// load plugin parameters
            //$this->_plugin = &JPluginHelper::getPlugin( 'content', 'contentpassword' );
            //$this->_params = new JParameter( $this->_plugin->params );
            $this->_params = new JParameter( $config['params'] );
			$this->_Use_Session = $this->_params->get( 'session', false );
			if ($this->_Use_Session) $this->_session = &JFactory::getSession();
			$this->_alt_uri = $this->_params->get( 'alt_uri', false );
			
		//Load the language file
		JPlugin::loadLanguage( 'plg_content_contentpassword', JPATH_ADMINISTRATOR );
		
	}
	
	function onSearchClearContent( &$row, &$params, $page=0 ) {
		return $this->onPrepareContent( $row, $params, $page );
	}
	
	function onPrepareContent( &$row, &$params, $page=0 ) {
		global $mainframe;
		
			
		// simple performance check to determine whether bot should process further
			if ( strpos( $row->text, '{password' ) === false ) {
			return true;
		}
		
		
		
		// define the regular expression for the bot
		$this->_passwordregex = "!\{/?password[ ]?([^}]*)\}!";
		$this->_paramregex = "!(\w+)=\"([^\"]+)!";
	
	
		// check whether plugin has been unpublished
		if ( !$this->_params->get( 'enabled', 1 ) ) {
			if (isset($row->text)) $row->text = preg_replace( $this->_passwordregex, '', $row->text );
			if (isset($row->introtext)) $row->introtext = preg_replace( $this->_passwordregex, '', $row->introtext );
			if (isset($row->fulltext)) $row->fulltext = preg_replace( $this->_passwordregex, '', $row->fulltext );
			return true;
		}

		
		if ($this->_alt_uri) { 
			$uri = & JFactory::getURI();
			$this->_SeitenURL = $uri->toString( );
		} else {
			$this->_SeitenURL = JRequest::getURI( );
		}
		
		
		$this->_postpass = JRequest::getString("contentpass", '', 'POST', JREQUEST_ALLOWRAW);
		
		if (isset($row->text)) $this->_checkPassword($row, "text");
		if (isset($row->introtext)) $this->_checkPassword($row, "introtext");
		if (isset($row->fulltext)) $this->_checkPassword($row, "fulltext");
		
	
		return true;
	}
	
	function _checkMySQL($sql, $password) {
		$db = JFactory::getDBO();
		//if (strpos($sql, "%password%")===FALSE) return false;
		//	$sql .= "'".$db->getEscaped($password)."'";
		//} else {
			$sql = str_replace("%password%", $db->getEscaped($password), $sql);
		//}
		$db->setQuery($sql);
		return ($db->loadRow())!==null;
	}
	
	function _checkPassword(&$row, $replace_type) {
		$matches = array();
		$access = false;
		$infotext = "Please type the password to access the content.";

		if (!preg_match_all( $this->_passwordregex, $row->$replace_type, $matches, PREG_SET_ORDER )) return true;
		
		foreach ($matches as $paramstring ){
			$params = array();
			if (preg_match_all( $this->_paramregex, $paramstring[1], $params, PREG_SET_ORDER )) {
				foreach ($params as $param) {
					if ($param[1] == "pass") {
						if ($param[2] == $this->_postpass) $access = true;
					} elseif ($param[1] == 'text') {
						$infotext = $param[2];
					} elseif ($param[1] == 'sql') {
						if (!$access) $access = $this->_checkMySQL($param[2], $this->_postpass);
					}
				}
			} elseif ($paramstring[1] == $this->_postpass) { 
				$access = true;
			}
			
		}
		if ('' == $this->_postpass) $access = false;
		
		$sessionkey = (isset($row->alias)) ? $row->alias : '';
		$sessionkey = md5($sessionkey . $row->id . $replace_type);
		
		if ( !$access && $this->_Use_Session ) {
			$access = $this->_session->get($sessionkey ,false,__CLASS__);
		}
	
		if ( ($this->_postpass !== $this->_params->get("password", false)) && !$access  ) {
			$error = (JRequest::getWord('vst_key', '', 'POST') == 'versteckt') ? ('<p class="contentpassword_error">'.JText::_("Wrong password!").'</p>') :'';
			$passform = "<div class=\"contentpassword\"><h3 class=\"contentpassword_title\">".JText::_("Password protected content")."</h3>"
				. "<form action=\" " . JRoute::_($this->_SeitenURL) . " \" method=\"post\" class=\"contentpassword_form\">"
				. "<p class=\"contentpassword_text\">" .JText::_($infotext). $error . "</p>"
				. "<input type=\"password\" name=\"contentpass\" size=\"20\" maxlength=\"30\" class=\"contentpassword_input\" />"
				. "<input type=\"hidden\" name=\"vst_key\" value=\"versteckt\" />"
				. "<input type=\"submit\" value=\"".JText::_("CheckPW")."\" class=\"contentpassword_submit\" />"
				. "</form></div>";
			if ( strpos( $row->text, '{/password}' ) === false ) {
				$row->$replace_type = $passform;
			} else {
				$row->$replace_type = preg_replace( 
					$this->_passwordregex, 
					'', 
					preg_replace('%\{password[^\}]*(.(?!\{password))*?\{/password\}%s', str_replace("\\", "\\\\", $passform), $row->$replace_type)
				);
			}
		}else{
			//perform the replacement

			if ($this->_Use_Session) $this->_session->set($sessionkey, true ,__CLASS__);
			$row->$replace_type = preg_replace( $this->_passwordregex, '', $row->$replace_type );
		}
	}
}