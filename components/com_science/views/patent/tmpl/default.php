<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_science/assets/'); ?>

<?php JHTML::_('behavior.formvalidation'); ?>
<?php JHTML::_('behavior.mootools'); ?>


<script type="text/javascript">
	function myValidate(f) {
	if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
			return true; 
		}
		else {
			var msg = 'Error message: Some required information is missing or incomplete';
			alert(msg);
		}
		return false;
	}
</script>


<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape( JText::_( 'PATENT_TOP_FORM') ); ?></div>
<?php endif; ?>

<!--form action="<?php //echo $this->action; ?>" method="post" name="josForm" id="josForm" class="form-validate"-->
<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
	<tbody>
		<tr class="sectiontableentry1">
			<th width="20%"><?php echo JText::_( 'COLLABORATION_GROUP_LEADER' ); ?>:
			<?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if (($this->rights == 'write') && (!$this->sci_user->isGroupLeader($this->user->username))):
			echo $this->lists['groupleaders'];
			else:
			echo $this->selected_group_leader_name; ?> <input type="hidden"
				name="group_leader_id"
				value="<?php echo $this->my_group_leader_id; ?>" /> <?php endif; ?>
			</td>
		</tr>
		<tr class="sectiontableentry2">
			<th width="10%"><?php echo JText::_('PATENT_TITLE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="title" value="<?php echo $this->patent->title; ?>"
				size="60" maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'PATENT_TITLE_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->patent->title;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PATENT_INVENTORS'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="inventors"
				value="<?php echo $this->patent->inventors; ?>" size="60"
				maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'PATENT_INVENTORS_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->patent->inventors;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th width="10%"><?php echo JText::_('PATENT_PUBLICATION_NUMBER'); ?>:
			<?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="publication_number"
				value="<?php echo $this->patent->publication_number; ?>" size="50"
				maxlength="50" /> <?php echo JHTML::_('tooltip',  JText::_( 'PATENT_PUBLICATION_NUMBER_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->patent->publication_number;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PATENT_PUBLICATION_DATE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <?php JHTML::_('behavior.calendar'); ?>
			<input class="required" type="text" name="publication_date"
				id="publication_date"
				value="<?php echo $this->patent->publication_date; ?>" size="10"
				maxlength="10" /> <img class="calendar"
				src="templates/system/images/calendar.png" alt="calendar"
				onclick="return 	showCalendar('publication_date', '%Y-%m-%d');" /> <?php else: 
				echo $this->patent->publication_date;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('PATENT_STATUS'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['status'] : $this->patent->patent_status; ?>
			</td>
		</tr>

		<?php if (($this->rights == 'write')): ?>
		<!-- No write, no buttons -->
		<tr class="sectiontableentry2">
			<td align="left">

			<fieldset class="input">
			<table>
				<TR>
					<TD>
					<button class="validate" name="save" value="true" type="submit"><?php echo JText::_('Save') ?></button>
					</td>
					<TD>
					<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
					</TD>
				</TR>
			</table>
			</fieldset>

			<input type="hidden" name="id"
				value="<?php echo $this->patent->id; ?>" /> <input type="hidden"
				name="option" value="com_science" /> <input type="hidden"
				name="controller" value="patent" /> <input type="hidden" name="view"
				value="patent" /> <input type="hidden" name="task" value="save_data" />
			<input type="hidden" name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?>
			</td>
			<td align="right"><!--button class="button validate" name="saveandadd" value="true" type="submit"><?php echo JText::_('Save and Add') ?></button-->
			</td>
		</tr>
		<?php endif; ?>

</table>
</form>
<br>

		<?php if (($this->rights == 'write')): ?>
<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?></span>
<br>
		<?php endif; ?>

		<?php if($this->patent->modified): ?>
<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED2', $this->patent->modified ); ?></i></font>
<?php endif; ?>
