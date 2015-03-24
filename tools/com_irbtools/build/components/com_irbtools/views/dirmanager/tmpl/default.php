<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

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
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape( JText::_( 'DIRMANAGER_TOP') ); ?>
	</div>
<?php endif; ?>

<?php echo JText::_( 'DIRMANAGER_TOP_INTRO' ); ?>
<br><br>

<form action="<?php echo $this->action; ?>" method="post" name="josForm" id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
<tbody>
<tr class="sectiontableentry1">
	<th width="5%">
		<?php echo JText::_( 'DIRMANAGER_EMAIL' ); ?>: <span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required' type="text" name="email" value="<?php echo $this->account->email; ?>" size="50" maxlength="250"/>
		<?php echo JHTML::_('tooltip',  JText::_( 'DIRMANAGER_EMAIL_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_( 'DIRMANAGER_FECHABAJAEMAIL' ); ?>: <span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required' type="text" name="date" value="<?php echo $this->account->fechabajamail; ?>" size="10" maxlength="10"/>
		<?php echo JHTML::_('tooltip',  JText::_( 'DIRMANAGER_FECHABAJAEMAIL_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<td align="left">
		<fieldset class="input">
			<table><tr><td>
			<button class="validate" name="create" value="true" type="submit"><?php echo JText::_('Update') ?></button>
			</td><td>
			<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
			</td></tr></table>
		</fieldset>
	</td>
</tr>
</tbody>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_irbtools" />
<input type="hidden" name="controller" value="dirmanager" />
<input type="hidden" name="task" value="updateaccount" />
</form>
