<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>

<?php echo JText::_( 'INTRO' ); ?>
<br />

<form
	action="<?php echo $this->action; ?>" method="post" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<th width="20"
			class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>">
		<input type="checkbox" name="toggle" value=""
			onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
		<th width="30%"
			class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>">
			<?php echo JText::_( 'Name' ); ?></th>
		<th
			class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>">
			<?php echo JText::_( 'Description' ); ?></th>
		<th width="10"
			class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>">
			<?php echo JText::_( 'Subscribed' ); ?></th>
	</tr>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row =& $this->items[$i];

		$checked 	= JHTML::_('grid.checkedout',   $row, $i );

		$img 	= $row->subscribed ? 'tick.png' : 'publish_x.png';
		$task 	= $row->subscribed ? 'unpublish' : 'publish';
		$alt 	= $row->subscribed ? JText::_( 'Subscribe' ) : JText::_( 'Unsubscribe' );
		$action = $row->subscribed ? JText::_( 'Unsubscribe Item' ) : JText::_( 'Subscribe item' );
		$prefix = '';

		$subscribed = '
	<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
	<img src="administrator/images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
	;

	?>
	<tr class="sectiontableentry<?php echo $item->odd + 1; ?>">
		<td><?php echo $checked; ?></td>
		<td height="20"><?php echo $row->name; ?></td>
		<td height="20"><?php echo $row->description; ?></td>
		<td align="center"><?php echo $subscribed; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
<input type="hidden" name="option" value="com_irbgoogle" /> <input
	type="hidden" name="task" value="save_subscription" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="filter_order" value="<?php echo $this->lists['order']; ?>" /> <input
	type="hidden" name="filter_order_Dir" value="" /> <!-- input type="submit" name="Submit" class="button" value="<?php echo JText::_('GO') ?>" /-->
</form>

