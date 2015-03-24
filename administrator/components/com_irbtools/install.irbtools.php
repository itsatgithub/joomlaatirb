<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_irbtools' . DS . 'helpers' . DS . 'irbtoolsinstallhelper.php');
  
function com_install()
{
	jimport('joomla.html.pane');
	// Declaramos BaseDir
	$basedir = dirname(__FILE__);
      
	// Cargamos la base de datos para comprobar si existe alguna tabla
	// y saber así si se trata de una instalación nueva o una actualización
	$db =& JFactory::getDBO();
	$table_list = $db->getTableList();
	$table_prefix = $db->getPrefix();
	$table_name = $table_prefix . 'irbtools_apps';
      
	if (array_search($table_name, $table_list) === false) {
		$isnew = true;
	} else {
		$isnew = false;
	}
      
	if ($isnew)	{
		echo "New installation<br>";
		JHTMLIrbtoolsInstallHelper::_sql('0');
	} else {
		echo "Update<br>";
	}
            
	// @todo The following must be debugged
       
	// check agains the local version
	$local = JHTMLIrbtoolsInstallHelper::getVersionLocal();
	$remote = JHTMLIrbtoolsInstallHelper::getVersionRemote();
      
	$local_cd = JHTMLIrbtoolsInstallHelper::datetosql($local['creationDate']);
	$remote_cd = JHTMLIrbtoolsInstallHelper::datetosql($remote['creationDate']);      
	echo "local creation date: antes " . $local['creationDate'] . " despues " . $local_cd . "<br>";
	echo "remote creation date: antes  " . $remote['creationDate'] . " despues " . $remote_cd . "<br>";
	JHTMLIrbtoolsInstallHelper::sqldiff($local_cd, $remote_cd);
      
	// Borramos este fichero, para forzar a que en la siguiente actualización se use el nuevo
	// JFile::delete(JPATH_ADMINISTRATOR . "/components/com_irbtools/install.irbtools.php"); <-- Uncomment when OK
      
	return true;
}
?>