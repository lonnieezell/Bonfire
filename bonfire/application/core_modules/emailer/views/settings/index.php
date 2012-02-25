<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open(SITE_AREA .'/settings/emailer', 'class="form-horizontal"'); ?>
	
	<fieldset>
		<legend>General Settings</legend>
	
		<div class="control-group <?php echo form_error('sender_email') ? 'error' : '' ?>">
			<label for="sender_email"><?php echo lang('em_system_email'); ?></label>
			<div class="controls">
				<input type="email" name="sender_email" class="input-xlarge" value="<?php echo isset($sender_email) ? $sender_email : set_value('sender_email') ?>" />
				<?php if (form_error('sender_email')) echo '<span class="help-inline">'. form_error('sender_email') .'</span>'; ?>
				<p class="help-block"><?php echo lang('em_system_email_note'); ?></p>
			</div>
		</div>
	
		<div class="control-group">
			<label for="mailtype"><?php echo lang('em_email_type'); ?></label>
			<div class="controls">
				<select name="mailtype">
					<option value="text" <?php echo isset($mailtype) && $mailtype == 'text' ? 'selected="selected"' : ''; ?>>Text</option>
					<option value="html" <?php echo isset($mailtype) && $mailtype == 'html' ? 'selected="selected"' : ''; ?>>HTML</option>
				</select>
			</div>
		</div>
	
		<div class="control-group">
			<label for="protocol"><?php echo lang('em_email_server'); ?></label>
			<div class="controls">
				<select name="protocol" id="server_type">
					<option <?php echo isset($protocol) && $protocol == 'mail' ? 'selected="selected"' : ''; ?>>mail</option>
					<option <?php echo isset($protocol) && $protocol == 'sendmail' ? 'selected="selected"' : ''; ?>>sendmail</option>
					<option <?php echo isset($protocol) && $protocol == 'smtp' ? 'selected="selected"' : ''; ?>>SMTP</option>
				</select>
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
		<div id="sendmail" class="control-group" style="padding-top: 27px">
			<label for="mailpath">Sendmail <?php echo lang('em_location'); ?></label>
			<div class="controls">
				<input type="text" name="mailpath" class="input-xlarge" value="<?php echo isset($mailpath) ? $mailpath : '/usr/sbin/sendmail' ?>" />
			</div>
		</div>
	
		<!-- SMTP -->
		<div id="smtp" style="padding-top: 27px">
	
			<div class="control-group">
				<label for="smtp_host">SMTP <?php echo lang('em_server_address'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_host" class="input-xlarge" value="<?php echo isset($smtp_host) ? $smtp_host : set_value('smtp_host') ?>" />
				</div>
			</div>
	
			<div class="control-group">
				<label for="smtp_user">SMTP <?php echo lang('bf_username'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_user" class="input-xlarge" value="<?php echo isset($smtp_user) ? $smtp_user : set_value('smtp_user') ?>" />
				</div>
			</div>
	
			<div class="control-group">
				<label for="smtp_pass">SMTP <?php echo lang('bf_password'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_pass" class="input-xlarge" value="<?php echo isset($smtp_pass) ? $smtp_pass : set_value('smtp_pass') ?>" />
				</div>
			</div>
	
			<div class="control-group">
				<label for="smtp_port">SMTP <?php echo lang('em_port'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_port" class="input-xlarge" value="<?php echo isset($smtp_port) ? $smtp_port : set_value('smtp_port') ?>" />
				</div>
			</div>
	
			<div class="control-group">
				<label for="smptp_timeout">SMTP <?php echo lang('em_timeout_secs'); ?></label>
				<div class="controls">
					<input type="text" name="smtp_timeout" class="input-xlarge" value="<?php echo isset($smtp_timeout) ? $smtp_timeout : set_value('smtp_timeout') ?>" />
				</div>
			</div>
		</div>
	</fieldset>
	
	<div class="form-actions">
		<input type="submit" name="submit" class="btn primary" value="Save Settings" />
	</div>
	
	<?php echo form_close(); ?>
</div>

<!-- Test Settings -->
<div class="admin-box">
	<h3><?php echo lang('em_test_header'); ?></h3>
	
	<fieldset>
		<legend><?php echo lang('em_test_settings') ?></legend>
		
		<br/>
		<p class="intro"><?php echo lang('em_test_intro'); ?></p>
		
		<?php echo form_open(SITE_AREA .'/settings/emailer/test', array('class' => 'form-horizontal', 'id'=>'test-form')); ?>
		
			<br/>
			<div class="control-group">
				<label for="email"><?php echo lang('bf_email'); ?></label>
				<div class="controls">
					<input type="email" name="test_email" id="test-email" value="<?php echo config_item('site.system_email') ?>" />
					<input type="submit" name="submit" class="btn" value="<?php echo lang('em_test_button'); ?>" />
				</div>
			</div>
		</fieldset>
		
		<div id="test-ajax"></div>
	
	<?php echo form_close(); ?>
</div>