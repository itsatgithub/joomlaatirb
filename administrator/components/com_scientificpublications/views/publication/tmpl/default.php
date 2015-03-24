<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
// Set toolbar items for the page
$edit = JRequest::getVar('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title( JText::_( 'Scientific Publication Manager' ).': <small><small>[ ' . $text.' ]</small></small>' );
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
		if (form.title.value == ""){
			alert( "<?php echo JText::_( 'Item must have a title', true ); ?>" );
		} else if (form.authors.value == ""){
			alert( "<?php echo JText::_( 'Item must have authors', true ); ?>" );
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
		<td valign="top" align="right" class="key"><?php echo JText::_( 'Type' ); ?>:
		</td>
		<td><?php echo $this->lists['publication_types']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Title' ); ?>:
		</td>
		<td><input class="text_area" type="text" name="title" id="title"
			size="50" maxlength="500" value="<?php echo $this->item->title;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Authors' ); ?>:</td>
		<td><input class="text_area" type="text" name="authors" id="authors"
			size="50" maxlength="500" value="<?php echo $this->item->authors;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Journal' ); ?>:</td>
		<td><input class="text_area" type="text" name="journal" id="journal"
			size="50" maxlength="255" value="<?php echo $this->item->journal;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Issue' ); ?>:</td>
		<td><input class="text_area" type="text" name="issue" id="issue"
			size="2" maxlength="2" value="<?php echo $this->item->issue;?>" /></td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Volume' ); ?>:</td>
		<td><input class="text_area" type="text" name="volume" id="volume"
			size="2" maxlength="2" value="<?php echo $this->item->volume;?>" /></td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Pages' ); ?>:</td>
		<td><input class="text_area" type="text" name="pages" id="pages"
			size="10" maxlength="10" value="<?php echo $this->item->pages;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Year' ); ?>:</td>
		<td><input class="text_area" type="text" name="year" id="year"
			size="10" maxlength="40" value="<?php echo $this->item->year;?>" /></td>
	</tr>
	<tr>
		<td align="right" class="key"><?php echo JText::_( 'Citations' ); ?>:
		</td>
		<td><input class="text_area" type="text" name="citations"
			id="citations" size="20" maxlength="20"
			value="<?php echo $this->item->citations;?>" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><?php echo JText::_( 'Published' ); ?>:
		</td>
		<td><?php echo $this->lists['published']; ?></td>
	</tr>
</table>
</fieldset>
</div>
<input type="hidden" name="option" value="com_scientificpublications" />
<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" /> <?php echo JHTML::_( 'form.token' ); ?>
</form>
