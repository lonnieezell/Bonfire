<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>

	<div>
		<label><?php echo lang('bf_site_name') ?></label>
		<input type="text" name="title" value="<?php echo isset($settings['site.title']) ? $settings['site.title'] : set_value('site.title') ?>" />
	</div>
	
	<div>
		<label><?php echo lang('bf_site_email') ?></label>
		<input type="text" name="system_email" value="<?php echo isset($settings['site.system_email']) ? $settings['site.system_email'] : set_value('site.system_email') ?>" />
		<p class="small indent"><?php echo lang('bf_site_email_help') ?></p>
	</div>
	
	<div>
		<label><?php echo lang('bf_site_status') ?></label>
		<select name="status">
			<option value="1" <?php echo isset($settings) && $settings['site.status'] == 1 ? 'selected="selected"' : set_select('site.status', '1') ?>><?php echo lang('bf_online') ?></option>
			<option value="0" <?php echo isset($settings) && $settings['site.status'] == 0 ? 'selected="selected"' : set_select('site.status', '1') ?>><?php echo lang('bf_offline') ?></option>
		</select>
	</div>
	
	<div>
		<label><?php echo lang('bf_top_number') ?></label>
		<input type="text" name="list_limit" value="<?php echo isset($settings['site.list_limit']) ? $settings['site.list_limit'] : set_value('site.list_limit') ?>" class="tiny" />
		<p class="small indent"><?php echo lang('bf_top_number_help') ?></p>
	</div>
	
	<fieldset>
		<legend><?php echo lang('bf_security') ?></legend>
		
		<div>
			<label><?php echo lang('bf_allow_register') ?></label>
			<input type="checkbox" name="allow_register" id="allow_register" value="1" <?php echo config_item('auth.allow_register') == 1 ? 'checked="checked"' : set_checkbox('auth.allow_register', 1); ?> />
		</div>
		
		<div>
			<label><?php echo lang('bf_login_type') ?></label>
			<select name="login_type">
				<option value="email" <?php echo config_item('auth.login_type') == 'email' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_email') ?></option>
				<option value="username" <?php echo config_item('auth.login_type') == 'username' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_username') ?></option>
				<option value="both" <?php echo config_item('auth.login_type') == 'both' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_both') ?></option>
			</select>
		</div>
		
		<div>
			<label><?php echo lang('bf_use_usernames') ?></label>
			<input type="checkbox" name="use_usernames" id="use_usernames" value="1" <?php echo config_item('auth.use_usernames') == 1 ? 'checked="checked"' : set_checkbox('auth.use_usernames', 1); ?> />
		</div>
		
		<div>
			<label><?php echo lang('bf_allow_remember') ?></label>
			<input type="checkbox" name="allow_remember" id="allow_remember" value="1" <?php echo config_item('auth.allow_remember') == 1 ? 'checked="checked"' : set_checkbox('auth.allow_remember', 1); ?> />
		</div>
		
		<div>
			<label><?php echo lang('bf_remember_time') ?></label>
			<select name="remember_length" id="remember_length">
				<option value="604800"  <?php echo config_item('auth.remember_length') == '604800' ?  'selected="selected"' : '' ?>>1 <?php echo lang('bf_week') ?></option>
				<option value="1209600" <?php echo config_item('auth.remember_length') == '1209600' ? 'selected="selected"' : '' ?>>2 <?php echo lang('bf_weeks') ?></option>
				<option value="1814400" <?php echo config_item('auth.remember_length') == '1814400' ? 'selected="selected"' : '' ?>>3 <?php echo lang('bf_weeks') ?></option>
				<option value="2592000" <?php echo config_item('auth.remember_length') == '2592000' ? 'selected="selected"' : '' ?>>30 <?php echo lang('bf_days') ?></option>
			</select>
		</div>
	
	</fieldset>
	
	<?php if ($this->auth->has_permission('Site.Developer.View')) : ?>
	<!-- Developer Settings -->
	<fieldset>
		<legend>Developer</legend>
		
		<div>
			<label><?php echo lang('bf_update_show_edge') ?></label>
			<input type="checkbox" name="update_check" value="1" <?php echo config_item('updates.bleeding_edge') == 1 ? 'checked="checked"' : set_checkbox('updates.bleeding_edge', 1); ?> />
			<p class="small" style="display: inline">Leave unchecked to only check for new tagged updates. Check to see any new commits to the official repository.</p>
		</div>
	</fieldset>
	<?php endif; ?>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') .' '. lang('bf_context_settings') ?>" />
	</div>

<?php echo form_close(); ?>