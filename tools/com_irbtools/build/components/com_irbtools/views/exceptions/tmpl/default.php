<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_irbtools/assets/'); ?>

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
	<?php echo $this->escape( JText::_( 'EXCEPTIONS_TOP_TABLE') ); ?></div>
<?php endif; ?>

<?php echo JText::_( 'EXCEPTIONS_INTRO' ); ?>
<br>
<br>

<fieldset class="input">
<form
	action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_irbtools&view=exception"
	method="post" name="add">
	<table>
		<tr>
			<td>
			<button name="saveandadd" value="true" type="submit"><?php echo JText::_('ADD') ?></button>
			</td>
		</tr>
	</table>
</form>
</fieldset>

<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0">

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
		<th width="32" height="20" scope='col'><?php echo JHTML::_('grid.sort', 'Num', 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="5%" scope='col'><?php echo JHTML::_('grid.sort', 'Com', 'command', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Userid', 'irbpeople_user_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Name', 'name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Surname1', 'surname1', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Surname2', 'surname2', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Gender', 'gender', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Department', 'department', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Unit', 'unit', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Research Group', 'research_group', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<th scope='col' width="10%"></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$item =& $this->items[$i];
		$link = JRoute::_( 'index.php?option=com_irbtools&view=exception&id=' . $item->id );
		$del_link = JRoute::_( 'index.php?option=com_irbtools&controller=exceptions&task=delete&id=' . $item->id );
		?>
		<tr class="sectiontableentry1">
			<td align="center" height="20"><?php echo $this->escape($item->id); ?></td>
			<td><?php echo $this->escape($item->command); ?></td>
			<td><?php echo $this->escape($item->irbpeople_user_id); ?></td>
			<td><?php echo $this->escape($item->name); ?></td>
			<td><?php echo $this->escape($item->surname1); ?></td>
			<td><?php echo $this->escape($item->surname2); ?></td>
			<td><?php echo $this->escape($item->gender); ?></td>
			<td><?php echo $this->escape($item->department); ?></td>
			<td><?php echo $this->escape($item->unit); ?></td>
			<td><?php echo $this->escape($item->research_group); ?></td>
			<td align="center">
				<a href='<?php echo $link; ?>' title="<?php echo JText::_( 'MODIFY' ); ?>">
					<img border='0' src='images/M_images/edit.png'></a>
				<a href='<?php echo $del_link; ?>' onClick="return confirm('<?php echo JText::_( 'ARE_YOU_SURE' ); ?>');"
					title="<?php echo JText::_( 'DELETE' ); ?>">
					<img border='0' src='administrator/images/publish_x.png'></a>
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

<fieldset class="input">
<form
	action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_irbtools&view=exception"
	method="post" name="add">
<table>
	<tr>
		<td>
		<button name="saveandadd" value="true" type="submit"><?php echo JText::_('ADD') ?></button>
		</td>
	</tr>
</table>
</form>
</fieldset>
