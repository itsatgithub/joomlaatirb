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
<?php echo $this->escape( JText::_( 'PUBLICATION_TOP_FORM') ); ?></div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" onSubmit="return myValidate(this);">
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
		<th><?php echo JText::_('PUBLICATION_TYPE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['publicationtypes'] : $this->publication->publication_type; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBMED_ID'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			id="pubmed_id" name="pubmed_id"
			value="<?php echo $this->publication->pubmed_id; ?>" size="10"
			maxlength="250" onchange="pubmedchanged()" /> <?php else: 
			echo $this->publication->pubmed_id;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry2">
		<td align="left" colspan="2">

		<fieldset class="input">
		<table>
			<TR>
				<TD>
				<button class="validate" name="save" value="pubmed" type="submit"><?php echo JText::_('PUBLICATION_VIA_PUBMED') ?></button>
				</td>
				<TD>
				<button class="validate" name="save" value="manually" type="submit"><?php echo JText::_('PUBLICATION_MANUALLY') ?></button>
				</TD>
			</TR>
		</table>
		</fieldset>
		<input type="hidden" name="option" value="com_science" /> <input
			type="hidden" name="controller" value="publication" /> <input
			type="hidden" name="view" value="publication" /> <input type="hidden"
			name="task" value="prepare_data" /> <input type="hidden" name="check"
			value="post" /> <?php echo JHTML::_( 'form.token' ); ?></td>
	</tr>

</table>
</form>

<br>

			<?php if (($this->rights == 'write')): ?>
<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?></span>
<br>
<?php endif; ?>
