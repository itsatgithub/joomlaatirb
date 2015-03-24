<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
JHTML::stylesheet('holidays.css', 'components/com_irbtools/css/');
?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape( JText::_( 'HOLIDAYS_TOP') ); ?>
	</div>
<?php endif; ?>

<?php echo JText::_( 'HOLIDAYS_TOP_INTRO' ); ?>
<br>
<?php echo JText::_( 'HOLIDAYS_CONTACT' ); ?>
<br><br>

<?php echo JText::_( 'HOLIDAYS_TEXT1' ); ?>
<br>
<table>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_PYIH' ); ?>:
	</th>
	<td>
		<?php echo $this->items['previousyearholidaysforyear']; ?>
	</td>
</tr>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_PYSH' ); ?>:
	</th>
	<td>
		<?php echo $this->items['previousyearholidays']; ?>
	</td>
</tr>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_PYRH' ); ?>:
	</th>
	<td>
		<?php echo $this->items['pyhr']; ?>
	</td>
</tr>
</table>

<br>
<?php echo JText::_( 'HOLIDAYS_TEXT2' ); ?>
<br>
<table>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_TAH' ); ?>:
	</th>
	<td>
		<?php echo $this->items['holidaysforyear']; ?>
	</td>
</tr>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_SH' ); ?>:
	</th>
	<td>
		<?php echo $this->items['holidays']; ?>
	</td>
</tr>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_RH' ); ?>:
	</th>
	<td>
		<?php echo $this->items['rh']; ?>
	</td>
</tr>
</table>

<br>
<?php echo JText::_( 'HOLIDAYS_TEXT3' ); ?>
<br>
<table>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_TADFPA' ); ?>:
	</th>
	<td>
		<?php echo $this->items['apsforyear']; ?>
	</td>
</tr>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_SDFPA' ); ?>:
	</th>
	<td>
		<?php echo $this->items['aps']; ?>
	</td>
</tr>
<tr>
	<th>
		<?php echo JText::_( 'HOLIDAYS_RDFPA' ); ?>:
	</th>
	<td>
		<?php echo $this->items['rdfpa']; ?>
	</td>
</tr>
</table>
