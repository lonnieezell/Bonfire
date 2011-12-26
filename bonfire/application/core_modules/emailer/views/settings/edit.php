<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open(SITE_AREA .'/settings/emailer/edit/'.$profile->profile_id, 'class="constrained ajax-form"'); ?>
	
	<br/>

	<div>
		<label for="profile_name"><?php echo "Profile Name"; ?></label>
		<input type="text" name="profile_name" class="medium" value="<?php echo isset($profile->profile_name) ? $profile->profile_name : set_value('profile_name') ?>" />
		<p class="small indent"><?php echo lang('em_system_email_note'); ?></p>
	</div>
	

	<div>
		<label for="sender_email"><?php echo lang('em_system_email'); ?></label>
		<input type="email" name="sender_email" class="medium" value="<?php echo isset($profile->sender_email) ? $profile->sender_email : set_value('sender_email') ?>" />
		<p class="small indent"><?php echo lang('em_system_email_note'); ?></p>
	</div>

	<div>
		<label for="sender_name"><?php echo "Sender Name"; ?></label>
		<input type="text" name="sender_name" class="medium" value="<?php echo isset($profile->sender_name) ? $profile->sender_name : set_value('sender_name') ?>" />
		<p class="small indent"><?php echo "Leave empty to use site title"; ?></p>
	</div>

	
	<div>
		<label for="mailtype"><?php echo lang('em_email_type'); ?></label>
		<select name="mailtype">
			<option value="text" <?php echo isset($profile->mailtype) && $profile->mailtype == 'text' ? 'selected="selected"' : ''; ?>>Text</option>
			<option value="html" <?php echo isset($profile->mailtype) && $profile->mailtype == 'html' ? 'selected="selected"' : ''; ?>>HTML</option>
		</select>
	</div>
	
	<div>
		<label for="protocol"><?php echo lang('em_email_server'); ?></label>
		<select name="protocol" id="server_type">
			<option <?php echo isset($profile->protocol) && $profile->protocol == 'mail' ? 'selected="selected"' : ''; ?>>mail</option>
			<option <?php echo isset($profile->protocol) && $profile->protocol == 'sendmail' ? 'selected="selected"' : ''; ?>>sendmail</option>
			<option <?php echo isset($profile->protocol) && $profile->protocol == 'smtp' ? 'selected="selected"' : ''; ?>>SMTP</option>
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
		<input type="text" name="mailpath" class="medium" value="<?php echo isset($profile->mailpath) ? $profile->mailpath : '/usr/sbin/sendmail' ?>" />
	</div>
	
	<!-- SMTP -->
	<div id="smtp">
		<label for="smtp_host">SMTP <?php echo lang('em_server_address'); ?></label>
		<input type="text" name="smtp_host" class="medium" value="<?php echo isset($profile->smtp_host) ? $profile->smtp_host : set_value('smtp_host') ?>" />
		<br/>
		<label for="smtp_user">SMTP <?php echo lang('bf_username'); ?></label>
		<input type="text" name="smtp_user" class="medium" value="<?php echo isset($profile->smtp_user) ? $profile->smtp_user : set_value('smtp_user') ?>" />
		<br/>
		<label for="smtp_pass">SMTP <?php echo lang('bf_password'); ?></label>
		<input type="text" name="smtp_pass" class="medium" value="<?php echo isset($profile->smtp_pass) ? $profile->smtp_pass : set_value('smtp_pass') ?>" />
		<br/>
		<label for="smtp_port">SMTP <?php echo lang('em_port'); ?></label>
		<input type="text" name="smtp_port" class="medium" value="<?php echo isset($profile->smtp_port) ? $profile->smtp_port : set_value('smtp_port') ?>" />
		<br/>
		<label for="smptp_timeout">SMTP <?php echo lang('em_timeout_secs'); ?></label>
		<input type="text" name="smtp_timeout" class="medium" value="<?php echo isset($profile->smtp_timeout) ? $profile->smtp_timeout : set_value('smtp_timeout') ?>" />
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
		<input type="email" name="test_email" id="test-email" value="<?php echo $this->settings_lib->item('site.system_email') ?>" /> 
        <input type="hidden" name="profile_id" value="<?php echo $profile->profile_id ?>" />
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
