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
	
	<!-- Pages -->
	<fieldset>
		<legend><?php echo lang('bf_pages') ?></legend>
		
		<div>
			<label><?php echo lang('bf_enable_rte') ?></label>
			<input type="checkbox" name="default_rich_text" value="1" <?php echo config_item('pages.default_rich_text') == 1 ?  'checked="checked"' : '' ?> />
		</div>
		
		<div>
			<label><?php echo lang('bf_rte_type') ?></label>
			<select name="rte">
				<option value="html" <?php echo config_item('pages.rte') == 'html' ? 'selected="selected"' : '' ?>>HTML</option>
				<option value="markdown" <?php echo config_item('pages.rte') == 'markdown' ? 'selected="selected"' : '' ?>>Markdown</option>
				<option value="textile" <?php echo config_item('pages.rte') == 'textile' ? 'selected="selected"' : '' ?>>Textile</option>
				<option value="tinymce" <?php echo config_item('pages.rte') == 'tinymce' ? 'selected="selected"' : '' ?>>TinyMCE</option>
			</select>
		</div>
		
		<div>
			<label><?php echo lang('bf_searchable_default') ?></label>
			<input type="checkbox" name="default_searchable" value="1" <?php echo config_item('pages.default_searchable') == 1 ?  'checked="checked"' : '' ?> />
		</div>
		
		<div>
			<label><?php echo lang('bf_cacheable_default') ?></label>
			<input type="checkbox" name="default_cacheable" value="1" <?php echo config_item('pages.default_cacheable') == '1' ?  'checked="checked"' : '' ?> />
		</div>
		
		<div>
			<label><?php echo lang('bf_track_hits') ?></label>
			<input type="checkbox" name="track_hits" value="1" <?php echo config_item('pages.track_hits') == '1' ?  'checked="checked"' : '' ?> />
		</div>
	</fieldset>
	
	<div class="submits">
		<input type="submit" name="submit" value="Save Settings" />
	</div>

<?php echo form_close(); ?>