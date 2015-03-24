<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_irbtoolsl/assets/'); ?>
<?php JHTML::_('behavior.formvalidation'); ?>
<?php JHTML::_('behavior.calendar'); ?>

<script type="text/javascript">
	function myValidate(f) {
		if (document.formvalidator.isValid(f)) {
				f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
				var msg = 'In case you need it, do not forget to add data or voice roaming to your present request.';
				alert(msg);
				return true; 
			}
			else {
				var msg = 'Error message: Some required information is missing or incomplete';
				alert(msg);
			}
		return false;
	}
</script>

<script type="text/javascript">
// Changes the cursor to an hourglass
function cursor_wait() {
document.body.style.cursor = 'wait';
}

// Returns the cursor to the default pointer
function cursor_clear() {
document.body.style.cursor = 'default';
}
</script>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->escape( JText::_( 'ROAMING_TOP_FORM') ); ?>
</div>
<?php endif; ?>

<?php echo JText::_( 'ROAMING_TOP_TEXT'); ?>
<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" onSubmit="return myValidate(this);">
	<table width="100%" class="table">
		<tbody>
			<tr class="sectiontableentry1">
				<th width="15%"><?php echo JText::_('ROAMING_TELEPHONE'); ?>:<span
					style='color: red;'>*</span>
				</th>
				<td><?php echo $this->lists['telephones']; ?>
				</td>
			</tr>
			<tr class="sectiontableentry1">
				<th width="15%"><?php echo JText::_('ROAMING_SERVICE'); ?>:<span
					style='color: red;'>*</span>
				</th>
				<td><?php echo $this->lists['services']; ?>
				</td>
			</tr>
			<tr class="sectiontableentry1">
				<th><?php echo JText::_('ROAMING_FROM'); ?>:<span
					style='color: red;'>*</span>
				</th>
				<td>
					<input class="required" type="text" name="from" id="from" value="<?php echo $this->roaming->from; ?>" size="10" maxlength="10" />
					<img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('from', '%Y-%m-%d');" />
				</td>
			</tr>
			<tr class="sectiontableentry1">
				<th><?php echo JText::_('ROAMING_TO'); ?>:
				</th>
				<td><input type="text" name="to" id="to" value="<?php echo $this->roaming->to; ?>" size="10" maxlength="10" />
					<img class="calendar" src="templates/system/images/calendar.png" alt="calendar" onclick="return showCalendar('to', '%Y-%m-%d');" />
					<?php echo JHTML::_('tooltip',  JText::_( 'ROAMING_TO_TOOLTIP' ) ); ?>
				</td>
			</tr>
			<tr class="sectiontableentry1">
				<td align="left">
					<fieldset class="input">
						<table>
							<tr>
								<td>
									<button class="validate" name="save" value="true" type="submit">
										<?php echo JText::_('Add') ?>
									</button>
								</td>
								<td>
									<button onclick="window.history.go(-1);return false;">
										<?php echo JText::_('Cancel') ?>
									</button>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td align="right"></td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="username"
		value="<?php echo $this->user->username; ?>" /> <input type="hidden"
		name="id" value="<?php echo $this->roaming->id; ?>" /> <input
		type="hidden" name="option" value="com_irbtools" /> <input
		type="hidden" name="controller" value="roaming" /> <input
		type="hidden" name="view" value="roaming" /> <input type="hidden"
		name="task" value="save_data" /> <input type="hidden" name="check"
		value="post" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br>
<form action="<?php echo JRoute::_( 'index.php?option=com_irbtools&amp;controller=roaming&amp;task=send_request' );?>" method="post" name="josForm">
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
		<?php
		if (count( $this->items ) != '0')
		{
			?>
		<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
			<?php echo $this->escape( JText::_( 'ROAMING_REQUESTS') ); ?>
		</div>
		<thead>
			<tr>
				<th scope='col'><?php echo JHTML::_('grid.sort', 'Description', 'description', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="15%" scope='col'><?php echo JHTML::_('grid.sort', 'Long Number', 'long_number', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'From', 'from', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="10%" scope='col'><?php echo JHTML::_('grid.sort', 'To', 'to', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="5" scope='col'></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->items as &$item)
			{
				$del_link = JRoute::_( 'index.php?option=com_irbtools&controller=roaming&task=deletesession&long_number=' . $item['long_number'] . '&from=' . $item['from'] . '&to=' . $item['to']  );
				?>
			<tr class="sectiontableentry1">
				<td><?php echo $this->escape($item['description']); ?></td>
				<td><?php echo $this->escape($item['long_number']); ?></td>
				<td><?php echo $this->escape($item['from']); ?></td>
				<td><?php echo $this->escape($item['to']); ?></td>
				<td align="center"><a href='<?php echo $del_link; ?>'
					onClick="return confirm('<?php echo JText::_( 'ARE_YOU_SURE' ); ?>');"
					title="<?php echo JText::_( 'DELETE' ); ?>"> <img border='0'
						src='administrator/images/publish_x.png'>
				</a>
				</td>
			</tr>
			<?php
			}
			?>
			<tr class="sectiontableentry1">
				<td align="left">
					<fieldset class="input">
						<table>
							<tr>
								<td>
									<button class="validate" name="send_request" value="true" type="submit" onMouseDown="cursor_wait()">
										<?php echo JText::_('ROAMING_SEND') ?>
									</button>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td align="right"></td>
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
	<input type="hidden" name="task" value="send_request" />
	<input type="hidden" name="boxchecked" value="0" />
</form>

<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?>
</span>
<br>
