<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open(SITE_AREA .'/settings/emailer', 'class="constrained"'); ?>
	
	<br/>
	
	<div>
		<label for="sender_email"><?php echo lang('em_system_email'); ?></label>
		<input type="email" name="sender_email" class="medium" value="<?php echo isset($sender_email) ? $sender_email : set_value('sender_email') ?>" />
		<p class="small indent"><?php echo lang('em_system_email_note'); ?></p>
	</div>
	
	<div>
		<label for="mailtype"><?php echo lang('em_email_type'); ?></label>
		<select name="mailtype">
			<option value="text" <?php echo isset($mailtype) && $mailtype == 'text' ? 'selected="selected"' : ''; ?>>Text</option>
			<option value="html" <?php echo isset($mailtype) && $mailtype == 'html' ? 'selected="selected"' : ''; ?>>HTML</option>
		</select>
	</div>
	
	<div>
		<label for="protocol"><?php echo lang('em_email_server'); ?></label>
		<select name="protocol" id="server_type">
			<option <?php echo isset($protocol) && $protocol == 'mail' ? 'selected="selected"' : ''; ?>>mail</option>
			<option <?php echo isset($protocol) && $protocol == 'sendmail' ? 'selected="selected"' : ''; ?>>sendmail</option>
			<option <?php echo isset($protocol) && $protocol == 'smtp' ? 'selected="selected"' : ''; ?>>SMTP</option>
		</select>
	</div>
	
<fieldset>
	<legend><?php echo lang('em_settings'); ?></legend>
	<!-- PHP Mail -->
	<div id="mail">
		<p class="text-center"><?php echo lang('em_settings_note'); ?></p>
	</div>

	<!-- Sendmail -->
	<div id="sendmail">
		<label for="mailpath">Sendmail <?php echo lang('em_location'); ?></label>
		<input type="text" name="mailpath" class="medium" value="<?php echo isset($mailpath) ? $mailpath : '/usr/sbin/sendmail' ?>" />
	</div>
	
	<!-- SMTP -->
	<div id="smtp">
		<label for="smtp_host">SMTP <?php echo lang('em_server_address'); ?></label>
		<input type="text" name="smtp_host" class="medium" value="<?php echo isset($smtp_host) ? $smtp_host : set_value('smtp_host') ?>" />
		<br/>
		<label for="smtp_user">SMTP <?php echo lang('bf_username'); ?></label>
		<input type="text" name="smtp_user" class="medium" value="<?php echo isset($smtp_user) ? $smtp_user : set_value('smtp_user') ?>" />
		<br/>
		<label for="smtp_pass">SMTP <?php echo lang('bf_password'); ?></label>
		<input type="text" name="smtp_pass" class="medium" value="<?php echo isset($smtp_pass) ? $smtp_pass : set_value('smtp_pass') ?>" />
		<br/>
		<label for="smtp_port">SMTP <?php echo lang('em_port'); ?></label>
		<input type="text" name="smtp_port" class="medium" value="<?php echo isset($smtp_port) ? $smtp_port : set_value('smtp_port') ?>" />
		<br/>
		<label for="smptp_timeout">SMTP <?php echo lang('em_timeout_secs'); ?></label>
		<input type="text" name="smtp_timeout" class="medium" value="<?php echo isset($smtp_timeout) ? $smtp_timeout : set_value('smtp_timeout') ?>" />
	</div>
</fieldset>

<div class="submits">
	<input type="submit" name="submit" value="Save Settings" />
</div>

<?php echo form_close(); ?>

<!-- Test Settings -->
<h3><?php echo lang('em_test_header'); ?></h3>

<p><?php echo lang('em_test_intro'); ?></p>

<?php echo form_open(SITE_AREA .'/settings/emailer/test', array('class' => 'ajax-form', 'id'=>'test-form')); ?>
	
	<div>
		<label for="email"><?php echo lang('bf_email'); ?></label>
		<input type="email" name="test_email" id="test-email" value="<?php echo config_item('site.system_email') ?>" /> 
		<input type="submit" name="submit" value="<?php echo lang('em_test_button'); ?>" />
	</div>
	
	<div id="test-ajax"></div>

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

	// Email Test
	$('#test-form').submit(function(e){
		e.preventDefault();
		
		var email	= $('#test-email').val();
		var url		= $(this).attr('action');
		
		$('#test-ajax').load(
			url,
			{
				email: email,
				url: url
			}
		);
	});
});
</script>