<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'CronJob Editor: ' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Task name' ); ?>:
				</label>
			</td>
			<td>
                            <input type="text" name="ttask" value="<?php echo JText::_($this->cron->task); ?>" />
			</td>
		</tr>
		
               <tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Task type' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_($this->cron->type); ?><br />
				<small>*To configure JCron to trigger a <b>Plugin</b> event you need to enter in the Command to run area the plugin group and the event
      name delimited by ".", like ("search.files")</small>
			</td>
		</tr>

                <tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Command to Run' ); ?>:
				</label>
			</td>
			<td>
                            <input type="text" name="file" value="<?php echo JText::_($this->cron->file); ?>" size="70" />
			</td>
		</tr>

                <tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Run at' ); ?>:
				</label>
			</td>
			<td>
                            <table>
                                <tr>
                                    <td><?php echo JText::_( 'Minute(s):' ); ?></td>
                                    <td><?php echo JText::_( 'Hour(s):' ); ?></td>
                                    <td><?php echo JText::_( 'Day(s):' ); ?></td>
                                    <td><?php echo JText::_( 'Months(s):' ); ?></td>
                                    <td><?php echo JText::_( 'Weekday(s):' ); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo JText::_($this->cron->minutes); ?></td>
                                    <td><?php echo JText::_($this->cron->hours); ?></td>
                                    <td><?php echo JText::_($this->cron->days); ?></td>
                                    <td><?php echo JText::_($this->cron->months); ?></td>
                                    <td><?php echo JText::_($this->cron->weekdays); ?></td>
                                </tr>
                            </table>
			</td>
		</tr>

                <tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'UNIX Crontab' ); ?>:
				</label>
			</td>
			<td>
                            <input type="text" name="crontab" id="crontab" value="<?php echo JText::_($this->cron->mhdmd); ?>" /> <?php echo JText::_("Use: "); ?><input type=checkbox name=c_crontab value='1'>
			</td>
		</tr>

                <tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Published' ); ?>:
				</label>
			</td>
			<td>
				<input type="checkbox" name="published" <?php echo $this->cron->published ? "checked" : ""; ?> />
			</td>
		</tr>

                 <tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Enable logs' ); ?>:
				</label>
			</td>
			<td>
				<input type="checkbox" name="ok" <?php echo $this->cron->ok ? "checked" : ""; ?> />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Last Run Time' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_($this->cron->ran_at); ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="greeting">
					<?php echo JText::_( 'Last Run Log Text' ); ?>:
				</label>
			</td>
			<td>
                            <textarea rows="10" cols="80" name="log_text_readonly" readonly ><?php echo JText::_($this->cron->log_text); ?></textarea>
			</td>
		</tr>
	</table>
    </fieldset>
</div>
<div class="clr"></div>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_jcron" />
<input type="hidden" name="id" value="<?php echo $this->cron->id; ?>" />
<input type="hidden" name="controller" value="jcronedit" />
<input type="hidden" name="task" value="" />

</form>
