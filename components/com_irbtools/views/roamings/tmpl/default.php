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
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<?php echo $this->escape( JText::_( 'ROAMINGS_TOP_TABLE') ); ?>
</div>
<?php endif; ?>

<?php echo JText::_( 'ROAMINGS_INTRO' ); ?>
<br>
<br>

<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>"
	method="post" name="adminForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">

		<caption>
			<?php echo JText::_( 'Filter' ); ?>
			: <input type="text" name="filter_search" id="filter_search"
				value="<?php echo $this->lists['search'];?>" class="text_area"
				onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?>
			</button>
			<button
				onclick="this.form.filter_search.value='';this.form.submit();">
				<?php echo JText::_('Reset'); ?>
			</button>
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
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Code', 'code', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th scope='col'><?php echo JHTML::_('grid.sort', 'Description', 'description', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Telephone', 'long_number', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th scope='col'><?php echo JHTML::_('grid.sort', 'Owner', 'owner', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'From', 'from', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'To', 'to', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'Username', 'username', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$item =& $this->items[$i];
				?>
			<tr class="sectiontableentry1">
				<td align="center" height="20"><?php echo $this->escape($item->id); ?>
				</td>
				<td><?php echo $this->escape($item->code); ?></td>
				<td><?php echo $this->escape($item->description); ?></td>
				<td><?php echo $this->escape($item->long_number); ?></td>
				<td><?php echo $this->escape($item->owner); ?></td>
				<td><?php echo $this->escape($item->from); ?></td>
				<td><?php echo $this->escape($item->to === '0000-00-00'? '':$item->to); ?></td>
				<td><?php echo $this->escape($item->username); ?></td>
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
