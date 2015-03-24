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
		<?php echo $this->escape( JText::_( 'GMAIL_TOP') ); ?>
	</div>
<?php endif; ?>

<?php echo JText::_( 'GMAIL_TOP_INTRO' ); ?>
<br><br>

<form action="<?php echo $this->action; ?>" method="post" name="josForm" id="josForm" onSubmit="return myValidate(this);">
<table width="100%" class="table">
<tbody>
<tr class="sectiontableentry1">
	<th width="5%">
		<?php echo JText::_( 'NAME' ); ?>: <span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required' type="text" name="name" value="<?php echo $this->account->name; ?>" size="20" maxlength="50"/>
		<?php echo JHTML::_('tooltip',  JText::_( 'NAME_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_( 'SURNAME' ); ?>: <span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required' type="text" name="surname" value="<?php echo $this->account->surname; ?>" size="20" maxlength="50"/>
		<?php echo JHTML::_('tooltip',  JText::_( 'SURNAME_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_( 'USERNAME' ); ?>: <span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required' type="text" name="username" value="<?php echo $this->account->username; ?>" size="20" maxlength="50"/>
		<?php echo JHTML::_('tooltip',  JText::_( 'USERNAME_TOOLTIP' ) ); ?>
	</td>
</tr>
<tr class="sectiontableentry1">
	<th>
		<?php echo JText::_( 'PASSWORD' ); ?>: <span style='color: red;'>*</span>
	</th>
	<td>
		<input class='required' type="text" name="password" value="<?php echo $this->account->password; ?>" size="20" maxlength="50"/>
	</td>
</tr>
<tr class="sectiontableentry1">
	<td align="left">
		<fieldset class="input">
			<table><tr><td>
			<button class="validate" name="create" value="true" type="submit"><?php echo JText::_('Create') ?></button>
			</td><td>
			<button onclick="window.history.go(-1);return false;"><?php echo JText::_('Cancel') ?></button>
			</td></tr></table>
		</fieldset>
	</td>
</tr>

<input type="hidden" name="option" value="com_irbtools" />
<input type="hidden" name="controller" value="gmail" />
<input type="hidden" name="task" value="createaccount" />
<?php echo JHTML::_( 'form.token' ); ?>
</tbody>
</table>
</form>
