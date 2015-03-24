<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('stylesheet', 'table.css', 'components/com_irbtoolsl/assets/'); ?>

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
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php echo $this->escape( JText::_( 'EXCEPTION_TOP_FORM') ); ?></div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post" name="josForm" id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
<tbody>
<tr class="sectiontableentry1">
	<th width="15%">
		<?php echo JText::_('EXCEPTION_COMMAND'); ?>:<span style='color: red;'>*</span>
	</th>
	<td>
		<?php echo $this->lists['commands'];; ?>
		<?php echo JHTML::_('tooltip',  JText::_('EXCEPTION_COMMAND_TOOLTIP') ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_IRBPEOPLE_USER_ID'); ?>:<span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required ' type="text" name="irbpeople_user_id" 
			value="<?php echo $this->exception->irbpeople_user_id; ?>" size="5" maxlength="5" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_IRBPEOPLE_USER_ID_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_NAME'); ?>:
	</th>
	<td>
		<input type="text" name="name" value="<?php echo $this->exception->name; ?>" size="50" maxlength="255" /> 
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_SURNAME1'); ?>:
	</th>
	<td>
		<input type="text" name="surname1" value="<?php echo $this->exception->surname1; ?>" size="50" maxlength="255" /> 
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_SURNAME2'); ?>:
	</th>
	<td>
		<input type="text" name="surname2" value="<?php echo $this->exception->surname2; ?>" size="50" maxlength="255" /> 
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_GENDER'); ?>:
	</th>
	<td>
		<input type="text" name="gender" value="<?php echo $this->exception->gender; ?>" size="50" maxlength="255" /> 
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_DEPARTMENT'); ?>:
	</th>
	<td>
		<input type="text" name="department" value="<?php echo $this->exception->department; ?>" size="50" maxlength="255" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_DEPARTMENT_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_UNIT'); ?>:
	</th>
	<td>
		<input type="text" name="unit" value="<?php echo $this->exception->unit; ?>" size="50" maxlength="255" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_UNIT_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_RESEARCH_GROUP'); ?>:
	</th>
	<td>
		<input type="text" name="research_group" value="<?php echo $this->exception->research_group; ?>" size="50" maxlength="255" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_RESEARCH_GROUP_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_EMAIL'); ?>:
	</th>
	<td>
		<input type="text" name="email" value="<?php echo $this->exception->email; ?>" size="50" maxlength="255" /> 
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_PHONE'); ?>:
	</th>
	<td>
		<input type="text" name="phone" value="<?php echo $this->exception->phone; ?>" size="50" maxlength="255" /> 
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_POSITION'); ?>:
	</th>
	<td>
		<input type="text" name="position" value="<?php echo $this->exception->position; ?>" size="50" maxlength="255" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_POSITION_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_LOCATION'); ?>:
	</th>
	<td>
		<input type="text" name="location" value="<?php echo $this->exception->location; ?>" size="50" maxlength="255" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_LOCATION_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_('EXCEPTION_SECOND_AFFILIATION'); ?>:
	</th>
	<td>
		<input type="text" name="second_affiliation" value="<?php echo $this->exception->second_affiliation; ?>" size="50" maxlength="255" /> 
		<?php echo JHTML::_('tooltip',  JText::_( 'EXCEPTION_SECOND_AFFILIATION_TOOLTIP' ) ); ?>
	</td>
</tr>

<tr class="sectiontableentry1">
	<td align="left">
		<fieldset class="input">
		<table>
		<tr>
			<td>
			<button class="validate" name="save" value="true" type="submit"><?php echo JText::_('Save') ?></button>
			</td>
			<td>
			<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
			</td>
		</tr>
		</table>
		</fieldset>
	</td>
	<td align="right">
	</td>
</tr>
</tbody>
</table>
<input type="hidden" name="id" value="<?php echo $this->exception->id; ?>" />
<input type="hidden" name="option" value="com_irbtools" />
<input type="hidden" name="controller" value="exception" />
<input type="hidden" name="view" value="exception" />
<input type="hidden" name="task" value="save_data" />
<input type="hidden" name="check" value="post" /> <?php echo JHTML::_( 'form.token' ); ?>
</form>
<br>
<span style='color: red;'>* <?php echo JText::_('COMPULSORY_FIELDS') ?></span>
<br>
<?php if($this->exception->modified): ?>
	<font color="Gray"><i><?php echo JText::sprintf( 'LAST_UPDATED', $this->exception->modified ); ?></i></font>
<?php endif; ?>
