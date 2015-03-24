<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_science/assets/'); ?>

<script language="javascript" type="text/javascript">
function tableOrdering( order, dir, task ) {
	var form = document.adminForm;

	form.filter_order.value = order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit( task );
}
</script>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape( JText::_( 'THESIS_TOP_TABLE') ); ?></div>
<?php endif; ?>

<?php echo JText::_( 'THESIS_INTRO' ); ?>
<br>
<br>

<?php if ( ( count( $this->items ) > 3 ) && ( $this->rights == 'write' ) ) { ?>
<fieldset class="input">
<form
	action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_science&view=thesis"
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
<?php
}
?>

<form action="<?php echo JRoute::_( 'index.php' );?>"
	method="post" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0">

	<caption><?php echo JText::_( 'Filter' ); ?>: <input type="text"
		name="filter_search" id="filter_search"
		value="<?php echo $this->lists['search'];?>" class="text_area"
		onchange="document.adminForm.submit();" />
	<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
	<button onclick="this.form.filter_search.value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
	<?php
	echo JText::_('Display Num') .'&nbsp;';
	echo $this->pagination->getLimitBox();
	?></caption>
	<?php
	if(count( $this->items ) != '0')
	{
		?>
	<thead>
		<tr>
			<th width="10" style="text-align: right;"><?php echo JHTML::_('grid.sort', 'Num', 't.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th height="20" scope='col'><?php echo JHTML::_('grid.sort', 'Title', 't.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%" height="20" scope='col'><?php echo JHTML::_('grid.sort', 'Author', 't.author', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%" height="20" scope='col'><?php echo JHTML::_('grid.sort', 'University', 't.university', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" height="20" scope='col'><?php echo JHTML::_('grid.sort', 'Year', 't.year', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th scope='col' width="10%"></th>
		</tr>
	</thead>
	<?php
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$item =& $this->items[$i];
		$link = JRoute::_( 'index.php?option=com_science&view=thesis&id=' . $item->id );
		$del_link = JRoute::_( 'index.php?option=com_science&controller=theses&task=delete&id=' . $item->id );
		?>
	<tbody>
		<tr class="sectiontableentry<?php echo $item->odd + 1; ?>">
			<td align="right"><?php echo $this->escape($item->id); ?></td>
			<td height="20"><?php echo $this->escape($item->title); ?></td>
			<td height="20"><?php echo $this->escape($item->author); ?></td>
			<td height="20"><?php echo $this->escape($item->university); ?></td>
			<td height="20"><?php echo $this->escape($item->year); ?></td>
			<td align="center"><a href='<?php echo $link; ?>'
				title="<?php echo JText::_( 'MODIFY' ); ?>"><img border='0'
				src='images/M_images/edit.png'></a> <?php if ( $this->rights == 'write' ) { ?>
			<a href='<?php echo $del_link; ?>'
				onClick="return confirm('<?php echo JText::_( 'ARE_YOU_SURE' ); ?>');"
				title="<?php echo JText::_( 'DELETE' ); ?>"><img border='0'
				src='administrator/images/publish_x.png'></a> <?php } ?></td>
		</tr>
	</tbody>
	<?php
	}
	?>
	<tr>
		<td align="center" colspan="6"><?php echo $this->pagination->getPagesLinks(); ?>
		</td>
	</tr>
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
<input type="hidden" name="filter_order"
	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"
	name="filter_order_Dir" value="" /></form>

	<?php if ( $this->rights == 'write' ) { ?>
<fieldset class="input">
<form
	action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_science&view=thesis"
	method="post" name="add">
<table>
	<TR>
		<TD>
		<button name="saveandadd" value="true" type="submit"><?php echo JText::_('ADD') ?></button>
		</TD>
	</tr>
</table>
</form>
</fieldset>
	<?php
	}
	?>