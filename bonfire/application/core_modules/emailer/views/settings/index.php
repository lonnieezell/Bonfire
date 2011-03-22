<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open('admin/settings/emailer', 'class="constrained"'); ?>
	
	<br/>
	
	<div>
		<label>System Email Address</label>
		<input type="email" name="sender_email" class="medium" value="<?php echo isset($sender_email) ? $sender_email : set_value('sender_email') ?>" />
		<p class="small indent">The email that all system-generated emails are sent from.</p>
	</div>
	
	<div>
		<label>Email Server</label>
		<select name="protocol" id="server_type">
			<option <?php echo isset($protocol) && $protocol == 'mail' ? 'selected="selected"' : ''; ?>>mail</option>
			<option <?php echo isset($protocol) && $protocol == 'sendmail' ? 'selected="selected"' : ''; ?>>sendmail</option>
			<option <?php echo isset($protocol) && $protocol == 'smtp' ? 'selected="selected"' : ''; ?>>SMTP</option>
		</select>
	</div>
	
<fieldset>
	<legend>Email Settings</legend>
	<!-- PHP Mail -->
	<div id="mail">
		<p class="text-center"><b>Mail</b> uses the standard PHP mail function, so no settings are necessary.</p>
	</div>

	<!-- Sendmail -->
	<div id="sendmail">
		<label>Sendmail location</label>
		<input type="text" name="mailpath" class="medium" value="<?php echo isset($mailpath) ? $mailpath : '/usr/sbin/sendmail' ?>" />
	</div>
	
	<!-- SMTP -->
	<div id="smtp">
		<label>SMTP Server Address</label>
		<input type="text" name="smtp_host" class="medium" value="<?php echo isset($smtp_host) ? $smtp_host : set_value('smtp_host') ?>" />
		<br/>
		<label>SMTP Username</label>
		<input type="text" name="smtp_user" class="medium" value="<?php echo isset($smtp_user) ? $smtp_user : set_value('smtp_user') ?>" />
		<br/>
		<label>SMTP Password</label>
		<input type="text" name="smtp_pass" class="medium" value="<?php echo isset($smtp_pass) ? $smtp_pass : set_value('smtp_pass') ?>" />
		<br/>
		<label>SMTP Port</label>
		<input type="text" name="smtp_port" class="medium" value="<?php echo isset($smtp_port) ? $smtp_port : set_value('smtp_port') ?>" />
		<br/>
		<label>SMTP Timeout (seconds)</label>
		<input type="text" name="smtp_timeout" class="medium" value="<?php echo isset($smtp_timeout) ? $smtp_timeout : set_value('smtp_timeout') ?>" />
	</div>
</fieldset>

<div class="submits">
	<input type="submit" name="submit" value="Save Settings" />
</div>

<?php echo form_close(); ?>

<script>
head.ready(function(){
	// Server Settings
	$('#server_type').change(function(){
		// First, hide everything
		$('#mail, #sendmail, #smtp').css('display', 'none');
		
		switch ($(this).val())
		{
			case 'mail':
				$('#mail').css('display', 'block');
				break;
			case 'sendmail':
				$('#sendmail').css('display', 'block');
				break;
			case 'SMTP':
				$('#smtp').css('display', 'block');
				break;
		}
	});
	
	// since js is active, hide the server settings
	$('#server_type').trigger('change');

});
</script>