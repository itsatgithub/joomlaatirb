<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_irbtoolsl/assets/'); ?>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->escape( JText::_( 'SCIENCEMANAGER_TOP_FORM') ); ?></div>
<?php endif; ?>

<table class="table">
<tbody>
<tr class="sectiontableentry1">
	<td>
		<?php echo JText::_('SCIENCEMANAGER_SEND_TEXT'); ?>
	</td>
	<td>
		<form action="<?php echo $this->action; ?>" method="post" name="josForm" id="josForm">
			<button class="validate" name="task" value="send" type="submit"><?php echo JText::_('Send') ?></button>
			<input type="hidden" name="option" value="com_irbtools" />
			<input type="hidden" name="controller" value="sciencemanager" />
			<input type="hidden" name="view" value="sciencemanager" />
			<input type="hidden" name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?>			
		</form>			
	</td>
</tr>
</tbody>
</table>

<table width="100%" border="1">
<tbody>
<?php

$rows = explode("\n", $this->sciencefile);
// separator para evitar problemas con las comas en los nombres de la BD
$sep = ";";			

foreach ($rows as $row)
{
	list($id, $title, $authors, $journal, $volume, $issue, $pages, $year, $link_pubmed, $research_groups
			, $date_of_print, $selected, $description, $phd_selected_publication, $irb_selected_publication) = explode($sep, $row);
	?>
	<tr class="sectiontableentry1">
		<td><?php echo utf8_encode($id); ?></td>
		<td><?php echo utf8_encode($title); ?></td>
		<td><?php echo utf8_encode($authors); ?></td>
		<td><?php echo utf8_encode($journal); ?></td>
		<td><?php echo utf8_encode($volume); ?></td>
		<td><?php echo utf8_encode($issue); ?></td>
		<td><?php echo utf8_encode($pages); ?></td>
		<td><?php echo utf8_encode($year); ?></td>
		<td><?php echo utf8_encode($link_pubmed); ?></td>
		<td><?php echo utf8_encode($research_groups); ?></td>
		<td><?php echo utf8_encode($date_of_print); ?></td>
		<td><?php echo utf8_encode($selected); ?></td>
		<td><?php echo utf8_encode($description); ?></td>
		<td><?php echo utf8_encode($phd_selected_publication); ?></td>
		<td><?php echo utf8_encode($irb_selected_publication); ?></td>
		</tr>
	<?php
}
?>
</tbody>
</table>
