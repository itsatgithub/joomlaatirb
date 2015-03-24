<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if($type == 'logout') : ?>
<?php if ($params->get('greeting')) : ?>
	<li><a href="<?php echo JRoute::_("index.php?option=com_user&task=logout&return=".base64_encode('index.php'));?>" class="logout-switch"><?php echo JText::sprintf( 'HINAME', $user->get('name') ); ?>
<?php endif; ?>
<?php echo JText::_( 'BUTTON_LOGOUT'); ?></a></li>
<?php else : ?>
	<li>
		<a class="login-switch" href="<?php echo JRoute::_('index.php?option=com_user&view=login');?>" onclick="this.blur();showBox('ja-login','mod_login_username',this);return false;" title="<?php JText::_('Login');?>">Login</a>
	
	<!--LOFIN FORM content-->
	<div id="ja-login" style="width:450px;">
	<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
        <?php JHTML::_('script', 'openid.js'); ?>
    <?php endif; ?>
    <form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="login" >
        <?php echo $params->get('pretext'); ?>
    
                <label for="mod_login_username" class="ja-login-user">
                    <span><?php echo JText::_('Username') ?>: </span>
                    <input name="username" id="mod_login_username" type="text" class="inputbox" alt="username" size="20" />
                </label>
    
                <label for="mod_login_password" class="ja-login-password">
                    <span><?php echo JText::_('Password') ?>: </span>
                    <input type="password" id="mod_login_password" name="passwd" class="inputbox" size="20" alt="password" />
                </label>
    
                <label for="mod_login_remember">
                    <input type="hidden" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" />
                </label>
                <input type="submit" name="Submit" class="button" value="Login" />
    			<br />
                <div class="ja-login-links clearfix">
                <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
                <?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
                <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
                <?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
                <?php 
                    $usersConfig = &JComponentHelper::getParams( 'com_users' );
                    if ($usersConfig->get('allowUserRegistration')) : ?>
                    <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
                        <?php echo JText::_('REGISTER'); ?></a>
                <?php endif; ?>
                </div>
        <?php echo $params->get('posttext'); ?>
    
        <input type="hidden" name="option" value="com_user" />
        <input type="hidden" name="task" value="login" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
        <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    </div>
		
	</li>
	<?php 
				$option = JRequest::getCmd('option');
				$task = JRequest::getCmd('task');
				if($option!='com_user' && $task != 'register') { ?>
	<li>
		<a class="register-switch" href="<?php echo JRoute::_("index.php?option=com_user&task=register");?>" onclick="this.blur();showBox('ja-register','namemsg',this);return false;" >
			<?php echo JText::_('REGISTER');?>
		</a>
		<!--LOFIN FORM content-->
		<script type="text/javascript" src="<?php echo JURI::base();?>media/system/js/validate.js"></script>
		<div id="ja-register" style="width:370px;">
				<script type="text/javascript">
				<!--
					Window.onDomReady(function(){
						document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
					});
				// -->
				</script>
				
				<?php
					if(isset($this->message)){
						$this->display('message');
					}
				?>
				
				<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">				
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
				<tr>
					<td width="30%" height="40">
						<label id="namemsg" for="name">
							<?php echo JText::_( 'Name' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="name" id="name" size="40" value="" class="inputbox required" maxlength="50" /> *
					</td>
				</tr>
				<tr>
					<td height="40">
						<label id="usernamemsg" for="username">
							<?php echo JText::_( 'Username' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" id="username" name="username" size="40" value="" class="inputbox required validate-username" maxlength="25" /> *
					</td>
				</tr>
				<tr>
					<td height="40">
						<label id="emailmsg" for="email">
							<?php echo JText::_( 'Email' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" id="email" name="email" size="40" value="" class="inputbox required validate-email" maxlength="100" /> *
					</td>
				</tr>
				<tr>
					<td height="40">
						<label id="pwmsg" for="password">
							<?php echo JText::_( 'Password' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox required validate-password" type="password" id="password" name="password" size="40" value="" /> *
					</td>
				</tr>
				<tr>
					<td height="40">
						<label id="pw2msg" for="password2">
							<?php echo JText::_( 'Verify Password' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox required validate-passverify" type="password" id="password2" name="password2" size="40" value="" /> *
					</td>
				</tr>
				<tr>
					<td colspan="2" height="40">
						<?php echo "Fields marked with an asterisk (*) are required."; ?>
					</td>
				</tr>
				</table>
					<button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
					<input type="hidden" name="task" value="register_save" />
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="gid" value="0" />
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>
		</div>
	</li>
	<?php } ?>
		<!--LOFIN FORM content-->
<?php endif; ?>