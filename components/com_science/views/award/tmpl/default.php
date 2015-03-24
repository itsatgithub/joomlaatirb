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

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php echo $this->escape( JText::_( 'AWARD_TOP_FORM') ); ?></div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
	<tbody>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_( 'COLLABORATION_GROUP_LEADER' ); ?>:
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
			<th><?php echo JText::_('AWARD_TITLE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="title" value="<?php echo $this->award->title; ?>"
				size="60" maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'AWARD_TITLE_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->award->title;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('AWARD_BODY'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="awarding_body"
				value="<?php echo $this->award->awarding_body; ?>" size="60"
				maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'AWARD_BODY_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->award->awarding_body;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('AWARD_AWARDEE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="awardee"
				value="<?php echo $this->award->awardee; ?>" size="60"
				maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'AWARD_AWARDEE_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->award->awardee;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('AWARD_YEAR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['year'] : $this->award->year; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('AWARD_END_YEAR'); ?>:</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['end_year'] : $this->award->end_year; ?>
			</td>
		</tr>

		<?php if (($this->rights == 'write')): ?>
		<!-- No write, no buttons -->
		<tr class="sectiontableentry2">
			<Td align="left">

			<fieldset class="input">
			<table>
				<Tr>
					<Td>
					<button class="validate" name="save" value="true" type="submit"><?php echo JText::_('Save') ?></button>
					</TD>
					<Td>
					<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
					</Td>
				</TR>
			</table>
			</fieldset>

			<input type="hidden" name="id"
				value="<?php echo $this->award->id; ?>" /> <input type="hidden"
				name="option" value="com_science" /> <input type="hidden"
				name="controller" value="award" /> <input type="hidden" name="view"
				value="award" /> <input type="hidden" name="task" value="save_data" />
			<input type="hidden" name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?>
			</td>
			<td align="right"><!--button class="button validate" name="saveandadd" value="true" type="submit"><?php echo JText::_('Save and Add') ?></button-->
			</td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>
</form>
<br>

		<?php if (($this->rights == 'write')): ?>
<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?></span>
<br>
		<?php endif; ?>

		<?php if($this->award->modified): ?>
<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED2', $this->award->modified ); ?></i></font>
<?php endif; ?>
