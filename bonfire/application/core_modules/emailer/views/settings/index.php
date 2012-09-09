<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open(SITE_AREA .'/settings/emailer', 'class="form-horizontal"'); ?>

	<fieldset>
		<legend>General Settings</legend>

		<div class="control-group <?php echo form_error('sender_email') ? 'error' : '' ?>">
			<label class="control-label" for="sender_email"><?php echo lang('em_system_email'); ?></label>
			<div class="controls">
				<input type="email" name="sender_email" id="sender_email" class="input-xlarge" value="<?php echo set_value('sender_email', isset($sender_email) ? $sender_email : '')  ?>" />
				<?php if (form_error('sender_email')) echo '<span class="help-inline">'. form_error('sender_email') .'</span>'; ?>
				<p class="help-block"><?php echo lang('em_system_email_note'); ?></p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="mailtype"><?php echo lang('em_email_type'); ?></label>
			<div class="controls">
				<select name="mailtype" id="mailtype">
					<option value="text" <?php echo isset($mailtype) && $mailtype == 'text' ? 'selected="selected"' : ''; ?>>Text</option>
					<option value="html" <?php echo isset($mailtype) && $mailtype == 'html' ? 'selected="selected"' : ''; ?>>HTML</option>
				</select>
			</div>
		</div>

		<div class="control-group <?php echo form_error('protocol') ? 'error' : ''; ?>">
			<label class="control-label" for="server_type"><?php echo lang('em_email_server'); ?></label>
			<div class="controls">
				<select name="protocol" id="server_type">
					<option <?php echo set_select('protocol', 'mail', isset($protocol) && $protocol == 'mail' ? TRUE : FALSE); ?>>mail</option>
					<option <?php echo set_select('protocol', 'sendmail', isset($protocol) && $protocol == 'sendmail' ? TRUE : FALSE); ?>>sendmail</option>
					<option value="smtp" <?php echo set_select('protocol', 'smtp', isset($protocol) && $protocol == 'smtp' ? TRUE : FALSE); ?>>SMTP</option>
				</select>
	    	    <span class="help-inline"><?php echo form_error('protocol'); ?></span>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend><?php echo lang('em_settings'); ?></legend>
		<!-- PHP Mail -->
		<div id="mail" class="control-group">
			<p class="intro"><?php echo lang('em_settings_note'); ?></p>
		</div>

		<!-- Sendmail -->
		<div id="sendmail" class="control-group <?php echo form_error('mailpath') ? 'error' : ''; ?>" style="padding-top: 27px">
			<label  class="control-label" for="mailpath">Sendmail <?php echo lang('em_location'); ?></label>
			<div class="controls">
				<input type="text" name="mailpath" id="mailpath" class="input-xlarge" value="<?php echo set_value('mailpath', isset($mailpath) ? $mailpath : '/usr/sbin/sendmail') ?>" />
				<span class="help-inline"><?php echo form_error('mailpath'); ?></span>
			</div>
		</div>

		<!-- SMTP -->
		<div id="smtp" style="padding-top: 27px">

			<div class="control-group <?php echo form_error('smtp_host') ? 'error' : ''; ?>">
				<label class="control-label" for="smtp_host">SMTP <?php echo lang('em_server_address'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_host" id="smtp_host" class="input-xlarge" value="<?php echo set_value('smtp_host', isset($smtp_host) ? $smtp_host : '') ?>" />
		    	    <span class="help-inline"><?php echo form_error('smtp_host'); ?></span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="smtp_user">SMTP <?php echo lang('bf_username'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_user" id="smtp_user" class="input-xlarge" value="<?php echo set_value('smtp_user', isset($smtp_user) ? $smtp_user : '') ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="smtp_pass">SMTP <?php echo lang('bf_password'); ?></label>
				<div class="controls">
					<input type="password" name="smtp_pass" id="smtp_pass" class="input-xlarge" value="<?php echo set_value('smtp_pass', isset($smtp_pass) ? $smtp_pass : '') ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="smtp_port">SMTP <?php echo lang('em_port'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_port" id="smtp_port" class="input-xlarge" value="<?php echo set_value('smtp_port', isset($smtp_port) ? $smtp_port : 25) ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="smtp_timeout">SMTP <?php echo lang('em_timeout_secs'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_timeout" id="smtp_timeout" class="input-xlarge" value="<?php echo set_value('smtp_timeout', isset($smtp_timeout) ? $smtp_timeout : '') ?>" />
				</div>
			</div>
		</div>
	</fieldset>

	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="Save Settings" />
	</div>

	<?php echo form_close(); ?>
</div>

<!-- Test Settings -->
<div class="admin-box">
	<h3><?php echo lang('em_test_header'); ?></h3>

	<?php echo form_open(SITE_AREA .'/settings/emailer/test', array('class' => 'form-horizontal', 'id'=>'test-form')); ?>
		<fieldset>
			<legend><?php echo lang('em_test_settings') ?></legend>

			<br/>
			<p class="intro"><?php echo lang('em_test_intro'); ?></p>

			<br/>
			<div class="control-group">
				<label class="control-label" for="test-email"><?php echo lang('bf_email'); ?></label>
				<div class="controls">
					<input type="email" name="email" id="test-email" value="<?php echo set_value('test_email', settings_item('site.system_email')) ?>" />
					<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('em_test_button'); ?>" />
				</div>
			</div>
		</fieldset>

		<div id="test-ajax"></div>

	<?php echo form_close(); ?>
</div>
