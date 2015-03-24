<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_science/assets/'); ?>

<?php JHTML::_('behavior.formvalidation'); ?>
<?php JHTML::_('behavior.mootools'); ?>


<script type="text/javascript">
window.addEvent( 'domready', function() 
{	
	//Hide or Show last rows
        $('action_type_id').addEvent( 'change', function() {
		if ($('action_type_id').options[1].selected){
			var rows = $$('#td_thin');
			rows.each(function(item){
				item.addClass('thin');
			});
			$('tr1_thin').addClass('thin');
			$('tr2_thin').addClass('thin');
			$('tr4_thin').addClass('thin');
			$('tr1_thin').removeClass('sectiontableentry1');
			$('tr2_thin').removeClass('sectiontableentry1');
			$('tr4_thin').removeClass('sectiontableentry1');

			$('cell_role_1').setStyle('display', 'none');
			$('cell_role_2').setStyle('display', 'none');
			$('cell_consortium_1').setStyle('display', 'none');
			$('cell_consortium_2').setStyle('display', 'none');
			$('cell_coordinator_1').setStyle('display', 'none');
			$('cell_coordinator_2').setStyle('display', 'none');

		//	$('consortium_row').setStyle('display', 'none');
		} else if ($('action_type_id').options[2].selected){
			var rows = $$('#td_thin');
			rows.each(function(item){
				item.removeClass('thin');
			});

			$('tr1_thin').removeClass('thin');
			$('tr2_thin').removeClass('thin');
			$('tr4_thin').removeClass('thin');
			$('tr1_thin').addClass('sectiontableentry1');
			$('tr2_thin').addClass('sectiontableentry1');
			$('tr4_thin').addClass('sectiontableentry1');
			$('cell_role_1').setStyle('display', 'block');
			$('cell_role_2').setStyle('display', 'block');
			$('cell_consortium_1').setStyle('display', 'block');
			$('cell_consortium_2').setStyle('display', 'block');
			$('cell_coordinator_1').setStyle('display', 'block');
			$('cell_coordinator_2').setStyle('display', 'block');
		}
        });

	//Change Label of Consortium
        $('role_id').addEvent( 'change', function() {
		if ($('role_id').options[1].selected){
			$('consortium_label').setHTML("Consortium institution:");
		} else if ($('role_id').options[2].selected){
			$('consortium_label').setHTML("Consortium members:");
		}
        });

	//Hide or Show Specify Funding Entity row	
        $('funding_entity_id').addEvent( 'change', function() {
		// if ($('funding_entity_id').options[32].selected || $('funding_entity_id').options[33].selected || $('funding_entity_id').options[34].selected){
		if ($('funding_entity_id').options[32].selected){
			var rows = $$('#td3_thin');
			rows.each(function(item){
				item.removeClass('thin');
			});
			$('tr3_thin').removeClass('thin');
			$('tr3_thin').addClass('sectiontableentry1');
			$('cell_funding_1').setStyle('display', 'block');
			$('cell_funding_2').setStyle('display', 'block');
		} else {
			var rows = $$('#td3_thin');
			rows.each(function(item){
				item.addClass('thin');
			});			
			$('tr3_thin').addClass('thin');
			$('tr3_thin').removeClass('sectiontableentry1');
			$('cell_funding_1').setStyle('display', 'none');
			$('cell_funding_2').setStyle('display', 'none');
		}
        });
	
})

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
<?php echo $this->escape( JText::_( 'PROJECT_TOP_FORM') ); ?></div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" name="josForm"
	id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
	<tbody>
		<tr class="sectiontableentry1">
			<th width="25%"><?php echo JText::_( 'COLLABORATION_GROUP_LEADER' ); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if (($this->rights == 'write') && (!$this->sci_user->isGroupLeader($this->user->username))):
			echo $this->lists['groupleaders'];
			else:
			echo $this->selected_group_leader_name; ?> <input type="hidden"
				name="group_leader_id"
				value="<?php echo $this->my_group_leader_id; ?>" /> <?php endif; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_TITLE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text" name="title"
				value="<?php echo $this->project->title; ?>" size="60"
				maxlength="255" /> <?php echo JHTML::_('tooltip',  JText::_( 'PROJECT_TITLE_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->project->title;
				endif; ?></td>
		</tr>
		<!-- 
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_PRINCIPAL_INVESTIGATOR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="principal_investigator"
				value="<?php echo $this->project->principal_investigator; ?>"
				size="60" maxlength="100" /> <?php else: 
				echo $this->project->principal_investigator;
				endif; ?></td>
		</tr>
		-->
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_PRINCIPAL_INVESTIGATOR'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['principal_investigators'] : $this->project->pi_principal_investigator; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BENEFICIARY'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="beneficiary"
				value="<?php echo $this->project->beneficiary; ?>" size="60"
				maxlength="100" /> <?php else: 
				echo $this->project->beneficiary;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_ACRONYM'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="acronym" value="<?php echo $this->project->acronym; ?>"
				size="20" maxlength="50" /> <?php echo JHTML::_('tooltip',  JText::_( 'PROJECT_ACRONYM_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->project->acronym;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_REFERENCE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="reference" value="<?php echo $this->project->reference; ?>"
				size="50" maxlength="50" /> <?php echo JHTML::_('tooltip',  JText::_( 'PROJECT_REFERENCE_TOOLTIP' ) ); ?>
				<?php else:
				echo $this->project->reference;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_AWARDING_DATE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php JHTML::_('behavior.calendar'); ?>
			<input type="text" name="awarding_date" id="awarding_date"
				value="<?php echo $this->project->awarding_date; ?>" maxlength="10"
				size="10" /> <img class="calendar"
				src="templates/system/images/calendar.png" alt="calendar"
				onclick="return 	showCalendar('awarding_date', '%Y-%m-%d');" /> <?php else: 
				echo $this->project->awarding_date;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_START_DATE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php JHTML::_('behavior.calendar'); ?>
			<input type="text" name="start_date" id="start_date"
				value="<?php echo $this->project->start_date; ?>" maxlength="10"
				size="10" /> <img class="calendar"
				src="templates/system/images/calendar.png" alt="calendar"
				onclick="return 	showCalendar('start_date', '%Y-%m-%d');" /> <?php else: 
				echo $this->project->start_date;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_END_DATE'); ?>:</th>
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
			<th width="10%"><?php echo JText::_('PROJECT_IRB_CODE'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="irb_code"
				value="<?php echo $this->project->irb_code; ?>" size="30"
				maxlength="30" /> <?php else: 
				echo $this->project->irb_code;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_TGB'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="total_granted_budget"
				value="<?php echo $this->project->total_granted_budget; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo $this->project->total_granted_budget;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_TOTAL_BUDGET'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input class='required '
				type="text" name="total_budget"
				value="<?php echo $this->project->total_budget; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo $this->project->total_budget;
				endif; ?></td>
		</tr>
		<!--
Code update - 2010-11-17 - See ticket
Code update - 2014-12-04 - See ticket Carlos Barrabes ZVJ-808368
Después de consultarlo se muestra la información a todo el mundo
-->
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_TOTAL_BUDGET'); ?>:
			</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="overheads_total_budget"
				value="<?php echo $this->project->overheads_total_budget; ?>"
				size="10" maxlength="10" /> <?php else: 
				echo $this->project->overheads_total_budget;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BUDGET_YEAR_1'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_budget_year_1']; ?> <?php echo JText::_('PROJECT_BUDGET'); ?>
			<input type="text" name="budget_year_1"
				value="<?php echo $this->project->budget_year_1; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_budget_year_1 . " " . JText::_('PROJECT_BUDGET') . " " . $this->project->budget_year_1;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_YEAR_1'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_overheads_year_1']; ?> <?php echo JText::_('PROJECT_OVERHEAD'); ?>
			<input type="text" name="overheads_year_1"
				value="<?php echo $this->project->overheads_year_1; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_overheads_year_1 . " " . JText::_('PROJECT_OVERHEAD') . " " . $this->project->overheads_year_1;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BUDGET_YEAR_2'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_budget_year_2']; ?> <?php echo JText::_('PROJECT_BUDGET'); ?>
			<input type="text" name="budget_year_2"
				value="<?php echo $this->project->budget_year_2; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_budget_year_2 . " " . JText::_('PROJECT_BUDGET') . " " . $this->project->budget_year_2;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_YEAR_2'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_overheads_year_2']; ?> <?php echo JText::_('PROJECT_OVERHEAD'); ?>
			<input type="text" name="overheads_year_2"
				value="<?php echo $this->project->overheads_year_2; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_overheads_year_2 . " " . JText::_('PROJECT_OVERHEAD') . " " . $this->project->overheads_year_2;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BUDGET_YEAR_3'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_budget_year_3']; ?> <?php echo JText::_('PROJECT_BUDGET'); ?>
			<input type="text" name="budget_year_3"
				value="<?php echo $this->project->budget_year_3; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_budget_year_3 . " " . JText::_('PROJECT_BUDGET') . " " . $this->project->budget_year_3;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_YEAR_3'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_overheads_year_3']; ?> <?php echo JText::_('PROJECT_OVERHEAD'); ?>
			<input type="text" name="overheads_year_3"
				value="<?php echo $this->project->overheads_year_3; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_overheads_year_3 . " " . JText::_('PROJECT_OVERHEAD') . " " . $this->project->overheads_year_3;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BUDGET_YEAR_4'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_budget_year_4']; ?> <?php echo JText::_('PROJECT_BUDGET'); ?>
			<input type="text" name="budget_year_4"
				value="<?php echo $this->project->budget_year_4; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_budget_year_4 . " " . JText::_('PROJECT_BUDGET') . " " . $this->project->budget_year_4;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_YEAR_4'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_overheads_year_4']; ?> <?php echo JText::_('PROJECT_OVERHEAD'); ?>
			<input type="text" name="overheads_year_4"
				value="<?php echo $this->project->overheads_year_4; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_overheads_year_4 . " " . JText::_('PROJECT_OVERHEAD') . " " . $this->project->overheads_year_4;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BUDGET_YEAR_5'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_budget_year_5']; ?> <?php echo JText::_('PROJECT_BUDGET'); ?>
			<input type="text" name="budget_year_5"
				value="<?php echo $this->project->budget_year_5; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_budget_year_5 . " " . JText::_('PROJECT_BUDGET') . " " . $this->project->budget_year_5;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_YEAR_5'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_overheads_year_5']; ?> <?php echo JText::_('PROJECT_OVERHEAD'); ?>
			<input type="text" name="overheads_year_5"
				value="<?php echo $this->project->overheads_year_5; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_overheads_year_5 . " " . JText::_('PROJECT_OVERHEAD') . " " . $this->project->overheads_year_5;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_BUDGET_YEAR_6'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_budget_year_6']; ?> <?php echo JText::_('PROJECT_BUDGET'); ?>
			<input type="text" name="budget_year_6"
				value="<?php echo $this->project->budget_year_6; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_budget_year_6 . " " . JText::_('PROJECT_BUDGET') . " " . $this->project->budget_year_6;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_OVERHEAD_YEAR_6'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <?php echo JText::_('PROJECT_YEAR'); ?>
			<?php echo $this->lists['year_overheads_year_6']; ?> <?php echo JText::_('PROJECT_OVERHEAD'); ?>
			<input type="text" name="overheads_year_6"
				value="<?php echo $this->project->overheads_year_6; ?>" size="10"
				maxlength="10" /> <?php else: 
				echo JText::_('PROJECT_YEAR') . " " . $this->project->year_overheads_year_6 . " " . JText::_('PROJECT_OVERHEAD') . " " . $this->project->overheads_year_6;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_FUNDING_ENTITY'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['fundingentities'] : $this->project->funding_entity_description; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_FUNDING_ENTITY_SPECIFIC'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="funding_entity_specific"
				value="<?php echo $this->project->funding_entity_specific; ?>"
				size="50" maxlength="255" /> <?php else: ?> <?php echo $this->project->funding_entity_specific; ?>
				<?php endif; ?></td>
		</tr>
		
<!--  		
		<tr id='tr3_thin'>
			<th id='td3_thin'>
			<div id="cell_funding_1"><?php echo JText::_('PROJECT_FUNDING_ENTITY_SPECIFIC'); ?>:</div>
			</th>
			<td id='td3_thin'>
			<div id="cell_funding_2"><?php if ($this->rights == 'write'): ?> <input
				type="text" name="funding_entity_specific"
				value="<?php echo $this->project->funding_entity_specific; ?>"
				size="50" maxlength="255" /> <?php else: ?> <?php echo $this->project->funding_entity_specific; ?>
				<?php endif; ?>
			
			</td>
			</div>
		</tr>
-->		
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_GRANT_TYPE'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['granttypes'] : $this->project->grant_type_description; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_GRANT_TYPE_SPECIFIC'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input
				type="text" name="grant_type_specific"
				value="<?php echo $this->project->grant_type_specific; ?>"
				size="50" maxlength="255" /> <?php else: ?> <?php echo $this->project->grant_type_specific; ?>
				<?php endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_OWNER'); ?>: <?php echo ($this->rights == 'read')?"":"<span style='color: red;'>*</span>"; ?>
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['owners'] : $this->project->owner; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_CALL'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="call" value="<?php echo $this->project->call; ?>" size="60"
				maxlength="255" /> <?php else: 
				echo $this->project->call;
				endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_ACTION_TYPE'); ?>:
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['actiontypes'] : $this->project->action_type; ?>
			</td>
		</tr>
		<tr id='tr2_thin'>
			<th id='td_thin'>
			<div id="cell_role_1"><?php echo JText::_('PROJECT_ROLE'); ?>:</div>
			</th>
			<td id='td_thin'>
			<div id="cell_role_2"><?php echo ($this->rights == 'write') ? $this->lists['roles'] : $this->project->role; ?></div>
			</td>
		</tr>
		<tr id='tr1_thin'>
			<th id='td_thin'>
			<div id="cell_consortium_1">
			<div id="consortium_label"><?php echo ($this->project->role_id)?(JText::_('PROJECT_CONSORTIUM_1').':'):(JText::_('PROJECT_CONSORTIUM_2').':'); ?></div>
			</div>
			</th>
			<td id='td_thin'><?php if ($this->rights == 'write'): ?>
			<div id="cell_consortium_2"><input type="text" name="consortium"
				value="<?php echo $this->project->consortium; ?>" size="60"
				maxlength="250" /></div>
				<?php else: ?>
			<div id="cell_consortium_2"><?php echo $this->project->consortium; ?>
			</div>
			<?php endif; ?></td>
		</tr>
		<tr id='tr4_thin'>
			<th id='td_thin'>
			<div id="cell_coordinator_1"><?php echo JText::_('PROJECT_COORDINATOR'); ?>:</div>
			</th>
			<td id='td_thin'><?php if ($this->rights == 'write'): ?>
			<div id="cell_coordinator_2"><input type="text" name="coordinator"
				value="<?php echo $this->project->coordinator; ?>" size="60"
				maxlength="255" /></div>
				<?php else: ?>
			<div id="cell_coordinator_2"><?php echo $this->project->coordinator; ?>
			</div>
			<?php endif; ?></td>
		</tr>
		<tr class="sectiontableentry1">
			<th><?php echo JText::_('PROJECT_UNEIX'); ?>:
			</th>
			<td><?php echo ($this->rights == 'write') ? $this->lists['uneix'] : $this->project->uneix; ?>
			</td>
		</tr>
		<tr class="sectiontableentry1">
			<th width="10%"><?php echo JText::_('PROJECT_COMMENTS'); ?>:</th>
			<td><?php if ($this->rights == 'write'): ?> <input type="text"
				name="comments" value="<?php echo $this->project->comments; ?>" size="60"
				maxlength="255" /> <?php else: 
				echo $this->project->comments;
				endif; ?></td>
		</tr>

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

			<input type="hidden" name="id"
				value="<?php echo $this->project->id; ?>" /> <input type="hidden"
				name="option" value="com_science" /> <input type="hidden"
				name="controller" value="project" /> <input type="hidden"
				name="view" value="project" /> <input type="hidden" name="task"
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

