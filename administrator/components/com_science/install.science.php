<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
  
  function com_install()
  {
      jimport('joomla.html.pane');
      
      // Declaramos BaseDir
      $basedir = dirname(__FILE__);
      
      // Cargamos Fichero de lenguaje
      //$lang =& JFactory::getLanguage();
      //$lang->load('com_advhelloworld', JPATH_BASE);
      
      // Cargamos la base de datos para comprobar si existe alguna tabla 
      // y saber así si se trata de una instalación nueva o una actualización
      $db =& JFactory::getDBO();
      $table_list = $db->getTableList();
      $table_prefix = $db->getPrefix();
      $table_name = $table_prefix . 'sci_publications';
      
      if (array_search($table_name, $table_list) === false) {
          $isnew = true;
      } else {
          $isnew = false;
      }
      
      require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_science' . DS . 'helpers' . DS . 'scienceinstallhelper.php');
      
      $local = JHTMLScienceInstallHelper::getVersionLocal();
      $remote = JHTMLScienceInstallHelper::getVersionRemote();
      
      $local_cd = JHTMLScienceInstallHelper::datetosql($local['creationDate']);
      $remote_cd = JHTMLScienceInstallHelper::datetosql($remote['creationDate']);
      
      if ($isnew) {
          echo "New installation<br>";
          //JHTMLScienceInstallHelper::_sql('0');
      } else {
          echo "Update<br>";
      }
      
      echo "local creation date: antes " . $local['creationDate'] . " despues " . $local_cd . "<br>";
      echo "remote creation date: antes  " . $remote['creationDate'] . " despues " . $remote_cd . "<br>";
      //JHTMLScienceInstallHelper::sqldiff($local_cd, $remote_cd);
      
      // Borramos este fichero, para forzar a que en la siguiente actualización se use el nuevo
      // JFile::delete(JPATH_SITE . "/administrator/components/com_science/install.science.php"); <-- Uncomment when OK
      
      return true;
  }
?>