<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_science/assets/'); ?>

<?php JHTML::_('behavior.formvalidation'); ?>

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
<?php echo $this->escape( JText::_( 'THESIS_TOP_FORM') ); ?></div>
<?php endif; ?>

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
			<th width="10%"><?php echo JText::_('THESIS_TITLE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class="required"
				type="text" name="title" value="<?php echo $this->thesis->title; ?>"
				size="100" maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'THESIS_TITLE_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->thesis->title;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('THESIS_AUTHOR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class="required"
				type="text" name="author"
				value="<?php echo $this->thesis->author; ?>" size="100"
				maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'THESIS_AUTHOR_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->thesis->author;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('THESIS_UNIVERSITY'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class="required"
				type="text" name="university"
				value="<?php echo $this->thesis->university; ?>" size="100"
				maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'THESIS_UNIVERSITY_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->thesis->university;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('THESIS_DOCTORAL_PROGRAMME'); ?>:
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input 
				type="text" name="doctoral_programme"
				value="<?php echo $this->thesis->doctoral_programme; ?>" size="100"
				maxlength="255" />
				<?php else:
				echo $this->thesis->doctoral_programme;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('THESIS_YEAR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['year'] : $this->thesis->year; ?>
			</td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('THESIS_LECTURE_DATE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <?php JHTML::_('behavior.calendar'); ?>
			<input class="required" type="text" name="lecture_date"
				id="lecture_date" value="<?php echo $this->thesis->lecture_date; ?>"
				size="10" maxlength="10" /> <img class="calendar"
				src="templates/system/images/calendar.png" alt="calendar"
				onclick="return 	showCalendar('lecture_date', '%Y-%m-%d');" /> <?php else: 
				echo $this->thesis->lecture_date;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_( 'THESIS_LANGUAGE' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['languages'] : $this->thesis->language; ?>
			</td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('THESIS_DIRECTOR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class="required"
				type="text" name="director"
				value="<?php echo $this->thesis->director; ?>" size="100"
				maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'THESIS_DIRECTOR_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->thesis->director;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('THESIS_CODIRECTOR'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="codirector" value="<?php echo $this->thesis->codirector; ?>"
				size="100" maxlength="255" /> <?php else: 
				echo $this->thesis->codirector;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('THESIS_TUTOR'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="tutor" value="<?php echo $this->thesis->tutor; ?>" size="100"
				maxlength="255" /> <?php else: 
				echo $this->thesis->tutor;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_( 'THESIS_HONOR' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['honor'] : $this->thesis->honor; ?>
			</td>
		</tr>
		<?php if (($this->rights == 'write')): ?>
		<!-- No write, no buttons -->
		<tr class="sectiontableentry2">
			<td align="left">

			<fieldset class="input">
			<button class="validate" name="save" value="true" type="submit"><?php echo JText::_('Save') ?></button>
			<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
			</fieldset>
			<input type="hidden" name="id"
				value="<?php echo $this->thesis->id; ?>" /> <input type="hidden"
				name="option" value="com_science" /> <input type="hidden"
				name="controller" value="thesis" /> <input type="hidden" name="view"
				value="thesis" /> <input type="hidden" name="task" value="save_data" />
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

		<?php if($this->thesis->modified): ?>
<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED2', $this->thesis->modified ); ?></i></font>
<?php endif; ?>
