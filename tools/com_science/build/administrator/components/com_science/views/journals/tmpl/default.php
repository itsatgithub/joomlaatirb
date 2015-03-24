<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
// Set toolbar items for the page
JToolBarHelper::title(   JText::_( 'Journal Manager' ), 'generic.png' );
//JToolBarHelper::publishList();
//JToolBarHelper::unpublishList();
JToolBarHelper::deleteList();
JToolBarHelper::editListX();
JToolBarHelper::addNewX();
//JToolBarHelper::preferences('com_register', '380');
//JToolBarHelper::help( 'screen.weblink' );
?>
<form action="index.php" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%"><?php echo JText::_( 'Filter' ); ?>: <input
			type="text" name="search" id="search"
			value="<?php echo $this->lists['search'];?>" class="text_area"
			onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button
			onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
	</tr>
</table>
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort',  'Description', 'description', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title"><?php echo JHTML::_('grid.sort',  'Short description', 'short_description', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title"><?php echo JHTML::_('grid.sort',  'Order', 'order', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link = JRoute::_( 'index.php?option=com_science&view=journal&task=edit&id='. $row->id );
		$checked = JHTML::_('grid.checkedout',   $row, $i );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td><?php echo $checked; ?></td>
			<td><span class="editlinktip hasTip"
				title="<?php echo JText::_( 'Edit Journal' );?>::<?php echo $this->escape($row->description); ?>">
			<a href="<?php echo $link; ?>"><?php echo $this->escape($row->description); ?></a></span>
			</td>
			<td><?php echo $this->escape($row->short_description); ?></td>
			<td><?php echo $this->escape($row->order); ?></td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_science" />
<input type="hidden" name="controller" value="journals" />
<input type="hidden" name="view" value="journals" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
