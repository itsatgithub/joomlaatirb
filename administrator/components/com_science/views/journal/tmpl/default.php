<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
// Set toolbar items for the page
$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title(   JText::_( 'Journal' ).': <small><small>[ ' . $text.' ]</small></small>' );
JToolBarHelper::save();
if (!$edit)  {
	JToolBarHelper::cancel();
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'cancel', 'Close' );
}
//JToolBarHelper::help( 'screen.weblink.edit' );
?>


<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.description.value == ""){
			alert( "<?php echo JText::_( 'Item must have a description', true ); ?>" );
		} else if (form.short_description.value == ""){
			alert( "<?php echo JText::_( 'Item must have a short description', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>
<style type="text/css">
table.paramlist td.paramlist_key {
	width: 92px;
	text-align: left;
	height: 30px;
}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="title"> <?php echo JText::_( 'Description' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="description"
			id="description" size="50" maxlength="100"
			value="<?php echo $this->item->description;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="alias"> <?php echo JText::_( 'Short description' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="short_description"
			id="short_description" size="20" maxlength="50"
			value="<?php echo $this->item->short_description;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="alias"> <?php echo JText::_( 'Order' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="order" id="order"
			size="4" maxlength="4" value="<?php echo $this->item->order;?>" /></td>
	</tr>
</table>
</fieldset>
</div>

<div class="col width-30">
<fieldset class="adminform"><legend><?php echo JText::_( 'Impact factors' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="title"> <?php echo JText::_( 'Year' ); ?>
		</label></td>
		<td width="100" align="right" class="key"><label for="title"> <?php echo JText::_( 'Impact factor' ); ?>
		</label></td>
	</tr>
	<?php
	for ($i = $this->start_year; $i <= $this->end_year; $i++ )
	{
		?>
	<tr>
		<td width="100" align="right"><?php echo $i; ?></td>
		<td width="100" align="right"><input type="text"
			name="impact_factor[<?php echo $i; ?>]"
			value="<?php echo $this->impact_factor[$i]; ?>" size="6"
			align="right" /></td>
	</tr>
	<?php
	}
	?>
</table>
</fieldset>
</div>

<input type="hidden" name="option" value="com_science" /> <input
	type="hidden" name="controller" value="journal" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="id"
	value="<?php echo $this->item->id; ?>" /> <input type="hidden"
	name="start_year" value="<?php echo $this->start_year; ?>" /> <input
	type="hidden" name="end_year" value="<?php echo $this->end_year; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?></form>
