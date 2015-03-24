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
<?php echo $this->escape( JText::_( 'COLLABORATION_TOP_FORM') ); ?></div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" class="form-validate" onSubmit="return myValidate(this);">
<table width="100%" class="table">
	<tr class="sectiontableentry1">
		<th width="20%"><?php echo JText::_( 'COLLABORATION_GROUP_LEADER' ); ?>:
		<?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php if (($this->rights == 'write') && (!$this->sci_user->isGroupLeader($this->user->username))):
		echo $this->lists['groupleaders'];
		else:
		echo $this->selected_group_leader_name; ?> <input type="hidden"
			name="group_leader_id"
			value="<?php echo $this->my_group_leader_id; ?>" /> <?php endif; ?></td>
	</tr>
	<tr class="sectiontableentry2">
		<th><?php echo JText::_( 'COLLABORATION_TYPE' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td>
		<table width="100%">
			<tr>
				<td width="40%"><?php echo ($this->rights == 'write') ? $this->lists['types'] : $this->collaboration->type; ?>
				</td>
				<td><?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_TYPE_TOOLTIP' ) ); ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_( 'COLLABORATION_SECTOR' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td>
		<table width="100%">
			<tr>
				<td width="40%"><?php echo ($this->rights == 'write') ? $this->lists['sectors'] : $this->collaboration->sector; ?>
				</td>
				<td><?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_SECTOR_TOOLTIP' ) ); ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr class="sectiontableentry2">
		<th><?php echo JText::_( 'COLLABORATION_LEVEL' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td>
		<table width="100%">
			<tr>
				<td width="40%"><?php echo ($this->rights == 'write') ? $this->lists['levels'] : $this->collaboration->level; ?>
				</td>
				<td><?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_LEVEL_TOOLTIP' ) ); ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('COLLABORATION_TITLE'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="title" value="<?php echo $this->collaboration->title; ?>"
			size="60" maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_TITLE_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->collaboration->title;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry2">
		<th><?php echo JText::_('COLLABORATION_START_YEAR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['start_year'] : $this->collaboration->start_year; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('COLLABORATION_END_YEAR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['end_year'] : $this->collaboration->end_year; ?>
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
			value="<?php echo $this->collaboration->id; ?>" /> <input
			type="hidden" name="option" value="com_science" /> <input
			type="hidden" name="controller" value="collaboration" /> <input
			type="hidden" name="view" value="collaboration" /> <input
			type="hidden" name="task" value="save_collaboration" /> <input
			type="hidden" name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?>
		</td>
		<td align="right"></td>
	</tr>
	<?php endif; ?>
</table>
</form>
<br>
<br>

<table width="100%">
	<caption><?php echo JText::_( 'COLLABORATION_DETAILS' ); ?></caption>
	<thead>
		<tr>
			<th class="white"><strong><?php echo JText::_( 'COLLABORATION_RESEARCH_NAME' ); ?></strong>
			</th>
			<th class="white"><strong><?php echo JText::_( 'COLLABORATION_INSTITUTION' ); ?></strong>
			</th>
			<th class="white"><strong><?php echo JText::_( 'COLLABORATION_CITY' ); ?></strong>
			</th>
			<th class="white"><strong><?php echo JText::_( 'COLLABORATION_COUNTRY' ); ?></strong>
			</th>
			<?php if (($this->rights == 'write')): ?>
			<th class="white"></th>
			<?php endif; ?>
		</tr>
	</thead>
	<?php
	$k = 1;
	foreach($this->lists['collaborationdetails'] as $collaborationdetail) : ?>
	<tr class='sectiontableentry<?php echo $k+1; ?>'>
		<td><?php echo $collaborationdetail->research_name; ?></td>
		<td><?php echo $collaborationdetail->institution; ?></td>
		<td><?php echo $collaborationdetail->city; ?></td>
		<td><?php echo $collaborationdetail->country; ?></td>
		<?php if (($this->rights == 'write') && (count($this->lists['collaborationdetails']) > 1)): ?>
		<td width="10" align="center"><a
			href='<?php echo $_SERVER['PHP_SELF']; ?>?option=com_science&task=del_collaboration_detail&controller=collaboration&collaboration_id=<?php echo $this->collaboration->id; ?>&id=<?php echo $collaborationdetail->id; ?>'
			onClick="return confirm('<?php echo JText::_( 'ARE_YOU_SURE' ); ?>');"><img
			border='0' src='administrator/images/publish_x.png'></a></td>
			<?php else: ?>
		<td></td>
		<?php endif; ?>
	</tr>
	<?php
	$k = 1-$k;
	endforeach; ?>
</table>
<br>
<br>

	<?php if (($this->rights == 'write')): ?>
<form action="<?php echo $this->action; ?>" method="post"
	onSubmit="return myValidate(this);">
<table width="100%" class="table">
	<caption><?php echo JText::_( 'COLLABORATION_DETAILS_FORM' ); ?></caption>
	<tr class="sectiontableentry1">
		<th width="20%"><?php echo JText::_( 'COLLABORATION_RESEARCH_NAME' ); ?>:
		</th>
		<td><input type="text" name="research_name" size="50" maxlength="250" />
		</td>
	</tr>
	<tr class="sectiontableentry2">
		<th><?php echo JText::_( 'COLLABORATION_INSTITUTION' ); ?>:<span
			style='color: red;'>*</span></th>
		<td><input class="required" type="text" name="institution" size="50"
			maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_INSTITUTION_TOOLTIP' ) ); ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_( 'COLLABORATION_CITY' ); ?>:<span
			style='color: red;'>*</span></th>
		<td><input class="required" type="text" name="city" size="50"
			maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_CITY_TOOLTIP' ) ); ?>
		</td>
	</tr>
	<tr class="sectiontableentry2">
		<th><?php echo JText::_( 'COLLABORATION_COUNTRY' ); ?>:<span
			style='color: red;'>*</span></th>
		<td><?php echo $this->lists['countries']; ?> <?php echo JHTML::_('tooltip',  JText::_( 'COLLABORATION_COUNTRY_TOOLTIP' ) ); ?>
		</td>
	</tr>
	<tr class="sectiontableentry2">
		<td colspan='2'>
		<fieldset class="input">
		<table>
			<TR>
				<TD><!-- <button type="submit" name="Submit_and_leave"  class="validate" <?php echo ($this->collaboration->id != 0)?'':'disabled';?> value="true"><?php echo JText::_('SAVE') ?></button>
		</td><TD> -->
				<button type="submit" name="Submit" class="validate"
				<?php echo ($this->collaboration->id != 0)?'':'disabled';?>
					value="true"><?php echo JText::_('ADD_MORE_RESEARCHERS') ?></button>
				</TD>
			</TR>
		</table>
		</fieldset>
		</td>
	</tr>


</table>

<input type="hidden" name="id"
	value="<?php echo $this->collaboration->id; ?>" /> <input type="hidden"
	name="option" value="com_science" /> <input type="hidden"
	name="controller" value="collaboration" /> <input type="hidden"
	name="task" value="add_collaboration_detail" /> <input type="hidden"
	name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?></form>

<br>
<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?></span>
<br>

				<?php endif; ?>

				<?php if($this->collaboration->modified): ?>
<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED2', $this->collaboration->modified ); ?></i></font>
				<?php endif; ?>
