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
		</th>
		<td><?php echo $this->publication->groupleader; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_TYPE'); ?>:</th>
		<td><?php echo $this->publication->type; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBMED_ID'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			id="pubmed_id" name="pubmed_id"
			value="<?php echo $this->publication->pubmed_id; ?>" size="10"
			maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBMED_ID_TOOLTIP' ) ); ?>
			<?php if ($this->publication->id): ?>
		<button class="validate" name="save" value="getpubmed" type="submit"><?php echo JText::_('PUBLICATION_VIA_PUBMED') ?></button>
		<?php endif; ?> <?php else:
		echo $this->publication->pubmed_id;
		endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_EPUB'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="epub" value="<?php echo $this->publication->epub; ?>" size="60"
			maxlength="80" /> <?php else: 
			echo $this->publication->epub;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_DATE_OF_PRINT'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="date_of_print" value="<?php echo $this->publication->date_of_print; ?>"
			size="20" maxlength="20" /> <?php else: 
			echo $this->publication->date_of_print;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_TITLE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php if ($this->rights == 'write'): ?> <input class='required '
			type="text" name="title"
			value="<?php echo $this->publication->title; ?>" size="60"
			maxlength="250" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_TITLE_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->publication->title;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_AUTHORS'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php if ($this->rights == 'write'): ?> <input class='required '
			type="text" name="authors"
			value="<?php echo $this->publication->authors; ?>" size="60"
			maxlength="500" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_AUTHORS_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->publication->authors;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_JOURNAL'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php if ($this->rights == 'write'): ?> <?php echo $this->lists['journals']; ?>
		<?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_JOURNAL_TOOLTIP' ) ); ?>
		<?php else:
		echo $this->publication->journal;
		endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_VOLUME'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="volume" value="<?php echo $this->publication->volume; ?>"
			size="10" maxlength="10" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_VOLUME_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->publication->volume;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_ISSUE'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="issue" value="<?php echo $this->publication->issue; ?>"
			size="10" maxlength="10" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_ISSUE_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->publication->issue;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_PAGES'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="pages" value="<?php echo $this->publication->pages; ?>"
			size="10" maxlength="10" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_PAGES_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->publication->pages;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_YEAR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php if ($this->rights == 'write'): ?> <input class='required '
			type="text" name="year"
			value="<?php echo $this->publication->year; ?>" size="4"
			maxlength="4" /> <?php else: 
			echo $this->publication->year;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_IMPACT_FACTOR'); ?>:
		</th>
		<td><?php echo $this->publication->impact_factor; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th width="10%"><?php echo JText::_('PUBLICATION_CITATIONS'); ?>:</th>
		<td><?php if ($this->rights == 'write'): ?> <input type="text"
			name="citations" value="<?php echo $this->publication->citations; ?>"
			size="60" maxlength="100" /> <?php echo JHTML::_('tooltip',  JText::_( 'PUBLICATION_CITATIONS_TOOLTIP' ) ); ?>
			<?php else:
			echo $this->publication->citations;
			endif; ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_COAUTHORS'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['coauthors_type'] : $this->publication->coauthors_type; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_GROUP_CONTRIBUTION'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['groupcontribution'] : $this->publication->group_contribution; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_JOINT_PUBLICATION'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['joint_publication'] : $this->publication->joint_publication; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_EXTRANET'); ?>:
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['selected_extranet'] : $this->publication->selected_extranet; ?>
		</td>
	</tr>
	<?php if (($this->isadministrator)): ?>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_PHD_SELECTED_PUBLICATION'); ?>:
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['phd_selected_publication'] : $this->publication->phd_selected_publication; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th><?php echo JText::_('PUBLICATION_IRB_SELECTED_PUBLICATION'); ?>:
		</th>
		<td><?php echo ($this->rights == 'write') ? $this->lists['irb_selected_publication'] : $this->publication->irb_selected_publication; ?>
		</td>
	</tr>
	<?php endif; ?>
	

	<?php if (($this->rights == 'write')): ?>
	<!-- No write, no buttons -->
	<tr class="sectiontableentry1">
		<td align="left">

		<fieldset class="input">
		<table>
			<TR>
				<TD>
				<button class="validate" name="save" value="true" type="submit"><?php echo JText::_('Save') ?></button>
				</td>
				<TD>
				<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
				</td>
			</TR>
		</table>
		</fieldset>

		<input type="hidden" name="type_id"
			value="<?php echo $this->publication->type_id; ?>" /> <?php if ($this->publication->group_leader_id): ?>

		<input type="hidden" name="group_leader_id"
			value="<?php echo $this->publication->group_leader_id; ?>" /> <?php endif; ?>

			<?php if ($this->publication->id): ?> <input type="hidden" name="id"
			value="<?php echo $this->publication->id; ?>" /> <?php endif; ?>
		<input type="hidden" name="id_pub_gl" value="<?php echo $this->publication->id_pub_gl; ?>" />
		<input type="hidden" name="option" value="com_science" /> <input
			type="hidden" name="controller" value="publication" /> <input
			type="hidden" name="view" value="publication" /> <input type="hidden"
			name="task" value="save_data" /> <input type="hidden" name="check"
			value="post" /> <?php echo JHTML::_( 'form.token' ); ?></td>
		<td align="right"></td>
	</tr>
	<?php endif; ?>
</table>
</form>

	<?php if (($this->rights == 'write')): ?>
<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?></span>
<br>
	<?php endif; ?>

	<?php if($this->publication->modified): ?>
<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED2', $this->publication->modified ); ?></i></font>
	<?php endif; ?>

