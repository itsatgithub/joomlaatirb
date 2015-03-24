<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_irbtoolsl/assets/'); ?>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->escape( JText::_( 'FILEMANAGER_TOP_FORM') ); ?></div>
<?php endif; ?>

<table class="table">
<tbody>
<tr class="sectiontableentry1">
	<td>
		<?php echo JText::_('SEND_TEXT'); ?>
	</td>
	<td>
		<form action="<?php echo $this->action; ?>" method="post" name="josForm" id="josForm">
			<button class="validate" name="task" value="send" type="submit"><?php echo JText::_('Send') ?></button>
			<input type="hidden" name="option" value="com_irbtools" />
			<input type="hidden" name="controller" value="filemanager" />
			<input type="hidden" name="view" value="filemanager" />
			<input type="hidden" name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?>			
		</form>			
	</td>
</tr>
</tbody>
</table>

<table width="100%" border="1">
<tbody>
<?php
$rows = explode("\n", $this->irbpeoplefile);
foreach ($rows as $row)
{
	list($id, $name, $surname1, $surname2, $department, $unit, $research_group, $email, $phone, $position, $location, $second_affiliation) = explode(";", $row);
	?>
	<tr class="sectiontableentry1">
		<td><?php echo $id; ?></td>
		<td><?php echo utf8_encode($name); ?></td>
		<td><?php echo utf8_encode($surname1); ?></td>
		<td><?php echo utf8_encode($surname2); ?></td>
		<td><?php echo utf8_encode($department); ?></td>
		<td><?php echo utf8_encode($unit); ?></td>
		<td width="15%"><?php echo utf8_encode($research_group); ?></td>
		<td width="20%"><?php echo $email; ?></td>
		<td width="20%"><?php echo $phone; ?></td>
		<td><?php echo $position; ?></td>
		<td><?php echo $location; ?></td>
		<td><?php echo utf8_encode($second_affiliation); ?></td>
	</tr>
	<?php
}
?>
</tbody>
</table>
