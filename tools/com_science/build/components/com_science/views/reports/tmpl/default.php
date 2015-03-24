<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

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
<?php echo $this->escape( JText::_( 'REPORT_TOP_TABLE') ); ?></div>
<?php endif; ?>

<form
	action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_science&view=report"
	method="post" name="adminForm" onSubmit="return myValidate(this);"><!-- <form action="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_science&view=report&format=raw" method="post" name="josForm" id="josForm" class="form-validate"> -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th colspan="2" class="white"><?php echo JText::_('SELECT_DATE'); ?></th>
		</tr>
	</thead>
	<tr class="sectiontableentry1">
		<td width="15%"><?php echo JText::_('REPORT_START_DATE'); ?>:<span
			style='color: red;'>*</span></td>
		<td><input class="required" type="text" name="start_date" id="start_date"
			value="<?php echo $this->start_date; ?>" size="10" /> <img
			class="calendar" src="templates/system/images/calendar.png"
			alt="calendar"
			onclick="return showCalendar('start_date', '%Y-%m-%d');" /></td>
	</tr>
	<tr class="sectiontableentry1">
		<td><?php echo JText::_('REPORT_END_DATE'); ?>:<span
			style='color: red;'>*</span></td>
		<td><input class="required" type="text" name="end_date" id="end_date"
			value="<?php echo $this->end_date; ?>" size="10" /> <img
			class="calendar" src="templates/system/images/calendar.png"
			alt="calendar" onclick="return showCalendar('end_date', '%Y-%m-%d');" />
		</td>
	</tr>
	<tr>
		<td colspan="2"><br></br>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th colspan="3" class="white"><?php echo JText::_( 'SELECT_GROUP'); ?></th>
		</tr>
	</thead>

	<!-- 
IMPORTANT NOTE: The +1 in the index are due to the fact that index for the gl_rows array is from 0 to n, 
and the index for the Research Groups in the DB table is from 1 to n+1
 -->

 <?php
 if ( !$this->isGroupLeader )
 {
 	?>
	<tr>
		<td colspan="3" width="100%"><input type="checkbox" name="toggle"
			value=""
			onclick="checkAll(<?php echo count( $this->gl_rows )+1; ?>);" /><?php echo JText::_( 'CHECK_ALL' ); ?>
		</td>
	</tr>
	<?php
 }
 ?>
 
<?php
// set of data
$ini = 0;
$limit = count( $this->gl_rows );
?>

	<!-- first row -->
	<tr>
		<td width="28%" valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo 'Cell & Developmental Biology'; ?>
				</td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if ($row->research_programme == 'Cell & Developmental Biology')
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $row->name; ?></td>
					</tr>
					<?php			
				}				
			}
			?>
		</table>
		</td>
		<td width="28%" valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo 'Structural & Computational Biology'; ?>
				</td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if ($row->research_programme == 'Structural & Computational Biology')
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $row->name; ?></td>
					</tr>
					<?php
				}
			}
			?>
		</table>
		</td>
		<td valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo 'Molecular Medicine'; ?>
				</td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if ($row->research_programme == 'Molecular Medicine')
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $row->name; ?></td>
					</tr>
					<?php
				}
			}
			?>
		</table>
		</td>
	</tr>

	<tr>
		<td colspan="3"><br></br>
		</td>
	</tr>
</table>

<table width="100%" class="table">
	<!-- second row -->
	<tr>
		<td width="28%" valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo 'Chemistry & Molecular Pharmacology'; ?>
				</td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if ($row->research_programme == 'Chemistry & Molecular Pharmacology')
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $row->name; ?></td>
					</tr>
					<?php
				}
			}
			?>
		</table>
		</td>
		<td width="28%" valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo 'Oncology'; ?>
				</td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if ($row->research_programme == 'Oncology')
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $row->name; ?></td>
					</tr>
					<?php
				}
			}
			?>
		</table>
		</td>
		<td valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo JText::_( 'CORE_FACILITIES'); ?></td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if (($row->research_programme_id >= 6 AND $row->research_programme_id <= 11)
						OR $row->research_programme_id == 15) // Roberto 2013-07-24 Nueva core facility
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $this->gl_rows[$i]->research_programme . ' (' . $row->name . ')'; ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><br></br>
		</td>
	</tr>
</table>

<table width="100%" class="table">
	<!-- third row -->
	<tr>
		<td width="28%" valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"><?php echo 'Administration'; ?>
				</td>
			</tr>
			<?php
			for($i = $ini; $i < $limit; $i++)
			{
				$row =& $this->gl_rows[$i];
				if ($row->research_programme_id == 14)
				{
					$checked = '<input type="checkbox" id="cb' . ($row->id)
					. '" name="cid[]" value="' . ($row->id)
					. '" onclick="isChecked(this.checked);"'
					. (($this->gl_rows[$i]->user_username == $this->groupLeaderName) ? ' checked="checked"' : '')
					. (($this->isGroupLeader && !($this->gl_rows[$i]->user_username == $this->groupLeaderName)) ? 'disabled' : '')
					. ' />'
					;
					?>
					<tr>
						<td width="5%"><?php echo $checked; ?></td>
						<td><?php echo $row->name; ?></td>
					</tr>
					<?php
				}
			}
			?>
		</table>
		</td>
		<td width="28%" valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"></td>
			</tr>
		</table>
		</td>
		<td valign="top">
		<table width="100%">
			<tr>
				<td colspan="2"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><br></br>
		</td>
	</tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th colspan="2" class="white"><?php echo JText::_('SELECT_REPORT'); ?></th>
		</tr>
	</thead>

	<tr class="sectiontableentry1">
		<td width="5%"><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="publications" checked="checked" /></td>
		<td><?php echo JText::_('REPORT1'); ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<td><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="collaborations" checked="checked" /></td>
		<td><?php echo JText::_('REPORT2'); ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<td><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="projects" checked="checked" /></td>
		<td><?php echo JText::_('REPORT3'); ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<td><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="researchcontracts" checked="checked" /></td>
		<td><?php echo JText::_('REPORT31'); ?></td>
	</tr>
	
	<tr class="sectiontableentry1">
		<td><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="theses" checked="checked" /></td>
		<td><?php echo JText::_('REPORT4'); ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<td><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="patents" checked="checked" /></td>
		<td><?php echo JText::_('REPORT5'); ?></td>
	</tr>
	<tr class="sectiontableentry1">
		<td><input type="checkbox" id="report_<?php echo $i; ?>"
			name="reports[]" value="awards" checked="checked" /></td>
		<td><?php echo JText::_('REPORT6'); ?></td>
	</tr>
</table>
<br></br>
<fieldset class="input"><input type="submit" name="Submit"
	value="<?php echo JText::_('REPORT') ?>" /></fieldset>
</form>

