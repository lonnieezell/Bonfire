<?php if (validation_errors()) : ?>
<div class="alert alert-block alert-error fade in">
  <a class="close" data-dismiss="alert">&times;</a>
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#main-settings" data-toggle="tab">Main Settings</a>
			</li>
			<li>
				<a href="#security" data-toggle="tab">Security Settings</a>
			</li>

			<?php if (has_permission('Site.Developer.View')) : ?>
			<li>
				<a href="#developer" data-toggle="tab">Developer Settings</a>
			</li>
			<?php endif; ?>
		</ul>

		<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

		<!-- Start of Main Settings Tab Pane -->
		<div class="tab-pane active" id="main-settings">

		<fieldset>
			<legend><?php echo lang('bf_site_information') ?></legend>

			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('bf_site_name') ?></label>
				<div class="controls">
					<input type="text" name="title" id="title" class="span6" value="<?php echo set_value('site.title', isset($settings['site.title']) ? $settings['site.title'] : '') ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="system_email"><?php echo lang('bf_site_email') ?></label>
				<div class="controls">
					<input type="text" name="system_email" id="system_email" class="span4" value="<?php echo set_value('site.system_email', isset($settings['site.system_email']) ? $settings['site.system_email'] : '') ?>" />
					<p class="help-inline"><?php echo lang('bf_site_email_help') ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="status"><?php echo lang('bf_site_status') ?></label>
				<div class="controls">
					<select name="status" id="status">
						<option value="1" <?php echo isset($settings) && $settings['site.status'] == 1 ? 'selected="selected"' : set_select('site.status', '1') ?>><?php echo lang('bf_online') ?></option>
						<option value="0" <?php echo isset($settings) && $settings['site.status'] == 0 ? 'selected="selected"' : set_select('site.status', '1') ?>><?php echo lang('bf_offline') ?></option>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="list_limit"><?php echo lang('bf_top_number') ?></label>
				<div class="controls">
					<input type="text" name="list_limit" id="list_limit" value="<?php echo set_value('list_limit', isset($settings['site.list_limit']) ? $settings['site.list_limit'] : '')  ?>" class="span1" />
					<p class="help-inline"><?php echo lang('bf_top_number_help') ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="languages"><?php echo lang('bf_language') ?></label>
				<div class="controls">
					<select name="languages[]" id="languages" multiple="multiple">
					<?php if (is_array($languages) && count($languages)):?>
						<?php foreach($languages as $language):?>
							<?php $selected = in_array($language, $selected_languages) ? TRUE : FALSE;?>
						<option value="<?php e($language);?>" <?php echo set_select('languages', $language, $selected) ?>><?php e(ucfirst($language)) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
					<p class="help-inline"><?php echo lang('bf_language_help') ?></p>
				</div>
			</div>

		</fieldset>

		</div>

		<!-- Start of Security Settings Tab Pane -->
		<div class="tab-pane" id="security">

		<fieldset>
			<legend><?php echo lang('bf_security') ?></legend>

			<div class="control-group">
				<div class="controls">
					<label for="allow_register">
						<input type="checkbox" name="allow_register" id="allow_register" value="1" <?php echo $settings['auth.allow_register'] == 1 ? 'checked="checked"' : set_checkbox('auth.allow_register', 1); ?> />
						<span><?php echo lang('bf_allow_register') ?></span>
					</label>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="user_activation_method"><?php echo lang('bf_activate_method') ?></label>
				<div class="controls">
					<select name="user_activation_method" id="user_activation_method">
						<option value="0" <?php echo $settings['auth.user_activation_method'] == 0 ? 'selected="selected"' : ''; ?>><?php echo lang('bf_activate_none') ?></option>
						<option value="1" <?php echo $settings['auth.user_activation_method'] == 1 ? 'selected="selected"' : ''; ?>><?php echo lang('bf_activate_email') ?></option>
						<option value="2" <?php echo $settings['auth.user_activation_method'] == 2 ? 'selected="selected"' : ''; ?>><?php echo lang('bf_activate_admin') ?></option>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="login_type"><?php echo lang('bf_login_type') ?></label>
				<div class="controls">
					<select name="login_type" id="login_type">
						<option value="email" <?php echo $settings['auth.login_type'] == 'email' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_email') ?></option>
						<option value="username" <?php echo $settings['auth.login_type'] == 'username' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_username') ?></option>
						<option value="both" <?php echo $settings['auth.login_type'] == 'both' ? 'selected="selected"' : ''; ?>><?php echo lang('bf_login_type_both') ?></option>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" id="use_usernames_label"><?php echo lang('bf_use_usernames') ?></label>
				<div class="controls" aria-labelledby="use_usernames_label" role="group">
					<label class="radio" for="use_username">
						<input type="radio" id="use_username" name="use_usernames" value="1" <?php echo $settings['auth.use_usernames'] == 1 ? 'checked="checked"' : set_radio('auth.use_usernames', 1); ?> />
						<span><?php echo lang('bf_username') ?></span>
					</label>
					<label class="radio" for="use_email">
						<input type="radio" id="use_email" name="use_usernames" value="0" <?php echo $settings['auth.use_usernames'] == 0 ? 'checked="checked"' : set_radio('auth.use_usernames', 0); ?> />
						<span><?php echo lang('bf_email') ?></span>
					</label>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo lang('bf_display_name'); ?></label>
				<div class="controls">
					<label class="checkbox" for="allow_name_change">
						<input type="checkbox" name="allow_name_change" id="allow_name_change" <?php echo isset($settings['auth.allow_name_change']) && $settings['auth.allow_name_change'] == 1 ? 'checked="checked"' : set_checkbox('auth.allow_remember', 1); ?> >
						<?php echo lang('set_allow_name_change_note'); ?>
					</label>

					<div id="name-change-settings" style="<?php if (!$settings['auth.allow_name_change']) echo 'display: none'; ?>">
						<input type="text" name="name_change_frequency" style="width: 2em;" value="<?php echo $settings['auth.name_change_frequency'] ?>">
						<?php echo lang('set_name_change_frequency') ?>

						<input type="text" name="name_change_limit" style="width: 2em;" value="<?php echo $settings['auth.name_change_limit'] ?>">
						<?php echo lang('set_days') ?>
					</div>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="allow_remember">
						<input type="checkbox" name="allow_remember" id="allow_remember" value="1" <?php echo $settings['auth.allow_remember'] == 1 ? 'checked="checked"' : set_checkbox('auth.allow_remember', 1); ?> />
						<span><?php echo lang('bf_allow_remember') ?></span>
					</label>
				</div>
			</div>

			<div class="control-group" id="remember-length" style="<?php if (!$settings['auth.allow_remember']) echo 'display: none'; ?>">
				<label class="control-label" for="remember_length"><?php echo lang('bf_remember_time') ?></label>
				<div class="controls">
					<select name="remember_length" id="remember_length">
						<option value="604800"  <?php echo $settings['auth.remember_length'] == '604800' ?  'selected="selected"' : '' ?>>1 <?php echo lang('bf_week') ?></option>
						<option value="1209600" <?php echo $settings['auth.remember_length'] == '1209600' ? 'selected="selected"' : '' ?>>2 <?php echo lang('bf_weeks') ?></option>
						<option value="1814400" <?php echo $settings['auth.remember_length']== '1814400' ? 'selected="selected"' : '' ?>>3 <?php echo lang('bf_weeks') ?></option>
						<option value="2592000" <?php echo $settings['auth.remember_length'] == '2592000' ? 'selected="selected"' : '' ?>>30 <?php echo lang('bf_days') ?></option>
					</select>
				</div>
			</div>

			<div class="control-group" id="password-strength">
				<label class="control-label" for="password_min_length"><?php echo lang('bf_password_strength') ?></label>
				<div class="controls">
					<input type="text" name="password_min_length" id="password_min_length" value="<?php echo set_value('password_min_length', isset($settings['auth.password_min_length']) ? $settings['auth.password_min_length'] : '') ?>" class="span1" />
					<p class="help-inline"><?php echo lang('bf_password_length_help') ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Password Options</label>
				<div class="controls">
					<label class="checkbox" for="password_force_numbers">
						<input type="checkbox" name="password_force_numbers" id="password_force_numbers" value="1" <?php echo set_checkbox('password_force_numbers', 1, isset($settings['auth.password_force_numbers']) && $settings['auth.password_force_numbers'] == 1 ? TRUE : FALSE); ?> />
						<?php echo lang('bf_password_force_numbers') ?>
					</label>
					<label class="checkbox" for="password_force_symbols">
						<input type="checkbox" name="password_force_symbols" id="password_force_symbols" value="1" <?php echo set_checkbox('password_force_symbols', 1, isset($settings['auth.password_force_symbols']) && $settings['auth.password_force_symbols'] == 1 ? TRUE : FALSE); ?> />
						<?php echo lang('bf_password_force_symbols') ?>
					</label>
					<label class="checkbox" for="password_force_mixed_case">
						<input type="checkbox" name="password_force_mixed_case" id="password_force_mixed_case" value="1" <?php echo set_checkbox('password_force_mixed_case', 1, isset($settings['auth.password_force_mixed_case']) && $settings['auth.password_force_mixed_case'] == 1 ? TRUE : FALSE); ?> />
						<?php echo lang('bf_password_force_mixed_case') ?>
					</label>
                    <label class="checkbox" for="password_show_labels">
                        <input type="checkbox" name="password_show_labels" id="password_show_labels" value="1" <?php echo set_checkbox('password_show_labels', 1, isset($settings['auth.password_show_labels']) && $settings['auth.password_show_labels'] == 1 ? TRUE : FALSE); ?> />
                        <?php echo lang('bf_password_show_labels') ?>
                    </label>
				</div>
			</div>

		</fieldset>
	</div>

	<?php if (has_permission('Site.Developer.View')) : ?>

	<!-- Start of Developer Settings Tab Pane -->
	<div class="tab-pane" id="developer">
		<!-- Developer Settings -->
		<fieldset>
			<legend>Developer</legend>

			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="show_profiler">
						<input type="checkbox" name="show_profiler" id="show_profiler" value="1" <?php echo  $settings['site.show_profiler'] == 1 ? 'checked="checked"' : set_checkbox('auth.use_extended_profile', 1); ?> />
						<span><?php echo lang('bf_show_profiler') ?></span>
					</label>
					<label class="checkbox" for="show_front_profiler">
						<input type="checkbox" name="show_front_profiler" id="show_front_profiler" value="1" <?php echo  $settings['site.show_front_profiler'] == 1 ? 'checked="checked"' : set_checkbox('site.show_front_profiler', 1); ?> />
						<span><?php echo lang('bf_show_front_profiler') ?></span>
					</label>
					<label class="checkbox" for="do_check">
						<input type="checkbox" name="do_check" id="do_check" value="1" <?php echo $settings['updates.do_check'] == 1 ? 'checked="checked"' : set_checkbox('updates.do_check', 1); ?> />
						<span><?php echo lang('bf_do_check') ?></span>
						<p class="help-block"><?php echo lang('bf_do_check_edge') ?></p>
					</label>
					<label class="checkbox" for="bleeding_edge">
						<input type="checkbox" name="bleeding_edge" id="bleeding_edge" value="1" <?php echo $settings['updates.bleeding_edge'] == 1 ? 'checked="checked"' : set_checkbox('updates.bleeding_edge', 1); ?> />
						<span><?php echo lang('bf_update_show_edge') ?></span>
						<p class="help-block"><?php echo lang('bf_update_info_edge') ?></p>
					</label>
				</div>
			</div>

		</fieldset>
	</div>
	<!-- End of Developer Tab Options Pane -->
	<?php endif; ?>

	</div>
</div>

	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('bf_context_settings') ?>" />
	</div>

	<?php echo form_close(); ?>
</div> <!-- /admin-box -->
