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

<script type="text/javascript">
window.addEvent( 'domready', function() 
{
function currencyFormat(fld, milSep, decSep, e) {
  var sep = 0;
  var key = '';
  var i = j = 0;
  var len = len2 = 0;
  var strCheck = '0123456789';
  var aux = aux2 = '';
  var whichCode = (window.Event) ? e.which : e.keyCode;

  if (whichCode == 13) return true;  // Enter
  if (whichCode == 8) return true;  // Delete
  key = String.fromCharCode(whichCode);  // Get key value from key code
  if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
  len = fld.value.length;
  for(i = 0; i < len; i++)
  if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
  aux = '';
  for(; i < len; i++)
  if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
  aux += key;
  len = aux.length;
  if (len == 0) fld.value = '';
  if (len == 1) fld.value = '0'+ decSep + '0' + aux;
  if (len == 2) fld.value = '0'+ decSep + aux;
  if (len > 2) {
    aux2 = '';
    for (j = 0, i = len - 3; i >= 0; i--) {
      if (j == 3) {
        aux2 += milSep;
        j = 0;
      }
      aux2 += aux.charAt(i);
      j++;
    }
    fld.value = '';
    len2 = aux2.length;
    for (i = len2 - 1; i >= 0; i--)
    fld.value += aux2.charAt(i);
    fld.value += decSep + aux.substr(len - 2, len);
  }
  return false;
}

</script>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<div
	class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php echo $this->escape( JText::_( 'RESEARCHCONTRACT_TOP_FORM') ); ?></div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
	<tbody>
		<tr class="sectiontableentry1">
			<th width="25%"><?php echo JText::_( 'RESEARCHCONTRACT_GROUP_LEADER' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if (($this->rights == 'write') && (!$this->sci_user->isGroupLeader($this->user->username))):
			echo $this->lists['groupleaders'];
			else:
			echo $this->selected_group_leader_name; ?> <input class="required" type="hidden"
				name="group_leader_id"
				value="<?php echo $this->my_group_leader_id; ?>" /> <?php endif; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_IRB_CODE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="irb_code"
				value="<?php echo $this->project->irb_code; ?>" size="30"
				maxlength="30" /> <?php else: 
				echo $this->project->irb_code;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_ACRONYM'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="acronym" value="<?php echo $this->project->acronym; ?>"
				size="20" maxlength="50" /> <?php echo JHTML::_('tooltip',  JText::_( 'RESEARCHCONTRACT_ACRONYM_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->project->acronym;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_COMPANY'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text" name="company"
				value="<?php echo $this->project->company; ?>" size="60"
				maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'RESEARCHCONTRACT_COMPANY_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->project->company;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_PRINCIPAL_INVESTIGATOR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required'
				type="text" name="principal_investigator"
				value="<?php echo $this->project->principal_investigator; ?>"
				size="60" maxlength="100" /> <?php else: 
				echo $this->project->principal_investigator;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('RESEARCHCONTRACT_START_DATE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php JHTML::_('behavior.calendar'); ?>
			<input type="text" name="start_date" id="start_date"
				value="<?php echo $this->project->start_date; ?>" maxlength="10"
				size="10" /> <img class="calendar"
				src="templates/system/images/calendar.png" alt="calendar"
				onclick="return 	showCalendar('start_date', '%Y-%m-%d');" /> <?php else: 
				echo $this->project->start_date;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry2">
			<th><?php echo JText::_('RESEARCHCONTRACT_END_DATE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php JHTML::_('behavior.calendar'); ?>
			<input type="text" name="end_date" id="end_date"
				value="<?php echo $this->project->end_date; ?>" size="10"
				maxlength="10" /> <img class="calendar"
				src="templates/system/images/calendar.png" alt="calendar"
				onclick="return 	showCalendar('end_date', '%Y-%m-%d');" /> <?php else: 
				echo $this->project->end_date;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('RESEARCHCONTRACT_FUNDING_SECTOR'); ?>: 
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['funding_sector'] : $this->project->funding_sector; ?>
			</td>
		</tr>
		<tr class="sectiontableentry2">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_BUDGET'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required'
				type="text" name="budget"
				value="<?php echo $this->project->budget; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo $this->project->budget;
				endif; ?></td>
		</tr>
<?php
if (!$this->sci_user->isGroupLeader($this->user->username))
{
	?>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_OVERHEAD'); ?>:
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="overhead"
				value="<?php echo $this->project->overhead; ?>"
				size="10" maxlength="10" /> <?php else: 
				echo $this->project->overhead;
				endif; ?></td>
		</tr>
		<?php
}
?>
		<tr class="sectiontableentry2">
			<th width="10%"><?php echo JText::_('RESEARCHCONTRACT_COMMENTS'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="comments" value="<?php echo $this->project->comments; ?>" size="60"
				maxlength="255" /> <?php else: 
				echo $this->project->comments;
				endif; ?></td>
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
					</td>
				</TR>
			</table>
			</fieldset>

			<input type="hidden" name="id"
				value="<?php echo $this->project->id; ?>" /> <input type="hidden"
				name="option" value="com_science" /> <input type="hidden"
				name="controller" value="researchcontract" /> <input type="hidden"
				name="view" value="researchcontract" /> <input type="hidden" name="task"
				value="save_data" /> <input type="hidden" name="check" value="post" />
				<?php echo JHTML::_( 'form.token' ); ?></td>
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

		<?php if($this->project->modified): ?>
<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED2', $this->project->modified ); ?></i></font>
		<?php endif; ?>

