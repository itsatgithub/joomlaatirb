<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_irbtools/assets/'); ?>

<?php JHTML::_( 'behavior.modal' ); ?> <!-- For the popups -->

<script language="javascript" type="text/javascript">
function tableOrdering( order, dir, task ) {
	var form = document.adminForm;

	form.filter_order.value = order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit( task );
}
</script>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<?php echo $this->escape( JText::_( 'PRIVATEFILES_TOP_TABLE') ); ?></div>
<?php endif; ?>

<?php echo JText::_( 'PRIVATEFILES_INTRO' ); ?>
<br>
<?php echo JText::_( 'PRIVATEFILES_CONTACT' ); ?>
<br><br>

<div style="margin-left: auto; margin-right: auto; width: 80%">
<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" name="adminForm">
<table width="75%" border="0" cellspacing="0" cellpadding="0">

<caption><?php echo JText::_( 'Filter' ); ?>: 
<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->lists['search'];?>" 
	class="text_area" onchange="document.adminForm.submit();" />
<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
<button onclick="this.form.filter_search.value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
<?php
echo JText::_('Display Num') .'&nbsp;';
echo $this->pagination->getLimitBox();
?>
</caption>

<?php
if (count( $this->items ) != '0')
{
?>
	<thead>
	<tr>
		<th><?php echo JHTML::_('grid.sort', 'File name', 'filename', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="50"></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{		
		$item = $this->items[$i]['filename'];
		$item_desc = $this->items[$i]['filedescription'];
		$filesFolder = $this->params->get( 'irbtoolsConfig_PrivateFilesFolder', '' );			
		
		$link_send = JRoute::_( 'index.php?option=com_irbtools&controller=privatefiles&task=send&filename=' . $item );
		$img_url = JURI::root(true) . '/components/com_irbtools/images/email_icon.png';	
		?>
		<tr class="sectiontableentry1">
			<td><?php echo $this->escape($item_desc); ?></td>
			<td align="center">
				<!-- <a class="modal" id="popup" rel="{handler: 'iframe', size: {x: 550, y: 450}}" href="<?php echo $filesFolder . DS . $item; ?>"><img border='0' src='images/M_images/pdf_button.png'></a>-->
				<!-- <a href="<?php echo $filesFolder . DS . $item; ?>"><img border='0' src='images/M_images/pdf_button.png'></a> -->
				<a href='<?php echo $link_send; ?>' title="<?php echo JText::_( 'PRIVATEFILES_SEND' ); ?>"><img border='0' src='<?php echo $img_url; ?>'></a>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td align="center" colspan="9"><?php echo $this->pagination->getPagesLinks(); ?>
		</td>
	</tr>
	</tbody>
<?php
} else {
?>
	<tbody>
		<tr>
			<td align="left"><?php echo JText::_('NO_ROWS'); ?></td>
		</tr>
	</tbody>
<?php
}
?>
</table>
<input type="hidden" name="option" value="com_irbtools" /> 
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" /> 
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
</div>
