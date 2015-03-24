<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
// Set toolbar items for the page
JToolBarHelper::title( JText::_( 'Manage publications' ), 'generic.png' );
//JToolBarHelper::publishList();
//JToolBarHelper::unpublishList();
//JToolBarHelper::deleteList();
//JToolBarHelper::editListX();
//JToolBarHelper::addNewX();
JToolBarHelper::custom('merge', 'edit.png', 'edit_f2.png', 'Merge publications', false);
JToolBarHelper::preferences('com_science', '380');
JToolBarHelper::help( 'screen.publications.merge', true );
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
			<th class="title"><?php echo JHTML::_('grid.sort',  'Title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title"><?php echo JHTML::_('grid.sort',  'Authors', 'a.authors', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title"><?php echo JHTML::_('grid.sort',  'Journal', 'a.journal', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title" width="5%"><?php echo JHTML::_('grid.sort',  'Pubmed_id', 'a.pubmed_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title" width="5%"><?php echo JText::_( 'Issue' ); ?></th>
			<th class="title" width="5%"><?php echo JText::_( 'Volume' ); ?></th>
			<th class="title" width="5%"><?php echo JText::_( 'Pages' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked = JHTML::_('grid.checkedout',   $row, $i );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $row->id; ?></td>
			<td><?php echo $checked; ?></td>
			<td><?php echo $this->escape($row->title); ?></td>
			<td><?php echo $this->escape($row->authors); ?></td>
			<td><?php echo $this->escape($row->journal); ?></td>
			<td><?php echo $row->pubmed_id; ?></td>
			<td><?php echo $row->issue; ?></td>
			<td><?php echo $row->volume; ?></td>
			<td><?php echo $row->pages; ?></td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_science" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
