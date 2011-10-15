<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>

	<div>
		<label for="title"><?php echo lang('bf_site_name') ?></label>
		<input type="text" name="title" value="<?php echo isset($settings['site.title']) ? $settings['site.title'] : set_value('site.title') ?>" />
	</div>
	
	<div>
		<label for="system_email"><?php echo lang('bf_site_email') ?></label>
		<input type="text" name="system_email" value="<?php echo isset($settings['site.system_email']) ? $settings['site.system_email'] : set_value('site.system_email') ?>" />
		<p class="small indent"><?php echo lang('bf_site_email_help') ?></p>
	</div>
	
	<div>
		<label for="status"><?php echo lang('bf_site_status') ?></label>
		<select name="status">
			<option value="1" <?php echo isset($settings) && $settings['site.status'] == 1 ? 'selected="selected"' : set_select('site.status', '1') ?>><?php echo lang('bf_online') ?></option>
			<option value="0" <?php echo isset($settings) && $settings['site.status'] == 0 ? 'selected="selected"' : set_select('site.status', '1') ?>><?php echo lang('bf_offline') ?></option>
		</select>
	</div>
	
	<div>
		<label for="list_limit"><?php echo lang('bf_top_number') ?></label>
		<input type="text" name="list_limit" value="<?php echo isset($settings['site.list_limit']) ? $settings['site.list_limit'] : set_value('site.list_limit') ?>" class="tiny" />
		<p class="small indent"><?php echo lang('bf_top_number_help') ?></p>
	</div>
	
	<fieldset>
		<legend><?php echo lang('bf_security') ?></legend>
		
		<div>
			<label for="allow_register"><?php echo lang('bf_allow_register') ?></label>
			<input type="checkbox" name="allow_register" id="allow_register" value="1" <?php echo config_item('auth.allow_register') == 1 ? 'checked="checked"' : set_checkbox('auth.allow_register', 1); ?> />
		</div>
		
		<div>
			<label for="login_type"><?php echo lang('bf_login_type') ?></label>
			<select name="login_type">
				<option value="email" <?php echo config_item('auth.login_type') == 'email' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_email') ?></option>
				<option value="username" <?php echo config_item('auth.login_type') == 'username' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_username') ?></option>
				<option value="both" <?php echo config_item('auth.login_type') == 'both' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_both') ?></option>
			</select>
		</div>
		
		<div>
			<label><?php echo lang('bf_use_usernames') ?></label>
			<label style="display: inline" class="text-left"><?php echo lang('bf_username') ?>
				<input type="radio" name="use_usernames" id="use_usernames" value="1" <?php echo config_item('auth.use_usernames') == 1 ? 'checked="checked"' : set_radio('auth.use_usernames', 1); ?> />
			</label>
			<label style="display: inline" class="text-left"><?php echo lang('bf_email') ?>
				<input type="radio" name="use_usernames" id="use_usernames" value="0" <?php echo config_item('auth.use_usernames') == 0 ? 'checked="checked"' : set_radio('auth.use_usernames', 0); ?> />
			</label>
			<label style="display: inline" class="text-left"><?php echo lang('bf_use_own_name') ?>
				<input type="checkbox" name="use_own_names" id="use_own_names" value="1" <?php echo config_item('auth.use_own_names') == 1 ? 'checked="checked"' : set_checkbox('auth.use_own_names', 2); ?> />
			</label>
		</div>
		
		<div>
			<label for="allow_remember"><?php echo lang('bf_allow_remember') ?></label>
			<input type="checkbox" name="allow_remember" id="allow_remember" value="1" <?php echo config_item('auth.allow_remember') == 1 ? 'checked="checked"' : set_checkbox('auth.allow_remember', 1); ?> />
		</div>
		
		<div>
			<label for="remember_length"><?php echo lang('bf_remember_time') ?></label>
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
			<label for="show_profiler"><?php echo lang('bf_show_profiler') ?></label>
			<input type="checkbox" name="show_profiler" value="1" <?php echo config_item('site.show_profiler') == 1 ? 'checked="checked"' : set_checkbox('site.show_profiler', 1); ?> />
		</div>
		<div>
			<label for="show_front_profiler"><?php echo lang('bf_show_front_profiler') ?></label>
			<input type="checkbox" name="show_front_profiler" value="1" <?php echo config_item('site.show_front_profiler') == 1 ? 'checked="checked"' : set_checkbox('site.show_front_profiler', 1); ?> />
		</div>
		
		<div>
			<label for="do_check"><?php echo lang('bf_do_check') ?></label>
			<input type="checkbox" name="do_check" value="1" <?php echo config_item('updates.do_check') == 1 ? 'checked="checked"' : set_checkbox('updates.do_check', 1); ?> />
			<p class="small" style="display: inline"><?php echo lang('bf_do_check_edge') ?></p>
		</div>
		
		<div>
			<label for="bleeding_edge"><?php echo lang('bf_update_show_edge') ?></label>
			<input type="checkbox" name="bleeding_edge" value="1" <?php echo config_item('updates.bleeding_edge') == 1 ? 'checked="checked"' : set_checkbox('updates.bleeding_edge', 1); ?> />
			<p class="small" style="display: inline"><?php echo lang('bf_update_info_edge') ?></p>
		</div>		
		<!--
		<div>
			<label for="use_ext_profile"><?php echo lang('bf_ext_profile_show') ?></label>
			<input type="checkbox" name="use_ext_profile" value="1" <?php echo config_item('auth.use_extended_profile') == 1 ? 'checked="checked"' : set_checkbox('auth.use_extended_profile', 1); ?> />
			<p class="small" style="display: inline"><?php echo lang('bf_ext_profile_info') ?></p>
		</div>
		-->
	</fieldset>
	<?php endif; ?>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') .' '. lang('bf_context_settings') ?>" />
	</div>

<?php echo form_close(); ?>