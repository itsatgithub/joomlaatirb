<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	// Set toolbar items for the page
	JToolBarHelper::title( JText::_( 'Manage software version' ), 'generic.png' );
?>
<table class="adminlist">
<tr>
	<td><?php echo JText::_('Your version');?></td>
	<td><?php echo $this->vlocal['version'];?></td>
	<td><?php echo $this->vlocal['creationDate'];?></td>
	<td><?php echo $this->vlocal['description'];?></td>
</tr>
<tr>
	<td><?php echo JText::_('Last version');?></td>
	<td><?php echo $this->vremote['version'];?></td>
	<td><?php echo $this->vremote['creationDate'];?></td>
	<td><?php echo $this->vremote['description'];?></td>
</tr>
</table>
<!--
<br>
<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm2">
	<input type="hidden" name="install_url" value="https://joomlaatirb.googlecode.com/svn/trunk/tools/com_science/zips/com_science.zip" />
	<input type="hidden" name="type" value="" />
	<input type="hidden" name="installtype" value="url" />
	<input type="hidden" name="task" value="doInstall" />
	<input type="hidden" name="option" value="com_installer" />
	<?php echo JHTML::_( 'form.token' ); ?>
<input type="submit" value="<?php echo JText::_('Install last version');?>">
</form>
 -->
