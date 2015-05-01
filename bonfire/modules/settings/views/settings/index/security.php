<fieldset>
    <legend><?php echo lang('bf_security'); ?></legend>
    <div class="control-group<?php echo form_error('allow_register') ? $errorClass : ''; ?>">
        <div class="controls">
            <label class='checkbox' for="allow_register">
                <input type="checkbox" name="allow_register" id="allow_register" value="1" <?php echo set_checkbox('auth.allow_register', 1, isset($settings['auth.allow_register']) && $settings['auth.allow_register'] == 1); ?> />
                <?php echo lang('bf_allow_register'); ?>
            </label>
            <span class='help-inline'><?php echo form_error('allow_register'); ?></span>
        </div>
    </div>
    <div class="control-group<?php echo form_error('user_activation_method') ? $errorClass : ''; ?>">
        <label class="control-label" for="user_activation_method"><?php echo lang('bf_activate_method'); ?></label>
        <div class="controls">
            <select name="user_activation_method" id="user_activation_method">
                <option value="0" <?php echo set_select('auth.user_activation_method', 0, isset($settings['auth.user_activation_method']) && $settings['auth.user_activation_method'] == 0); ?>><?php echo lang('bf_activate_none'); ?></option>
                <option value="1" <?php echo set_select('auth.user_activation_method', 1, isset($settings['auth.user_activation_method']) && $settings['auth.user_activation_method'] == 1); ?>><?php echo lang('bf_activate_email'); ?></option>
                <option value="2" <?php echo set_select('auth.user_activation_method', 2, isset($settings['auth.user_activation_method']) && $settings['auth.user_activation_method'] == 2); ?>><?php echo lang('bf_activate_admin'); ?></option>
            </select>
            <span class='help-inline'><?php echo form_error('user_activation_method'); ?></span>
        </div>
    </div>
    <div class="control-group<?php echo form_error('login_type') ? $errorClass : ''; ?>">
        <label class="control-label" for="login_type"><?php echo lang('bf_login_type') ?></label>
        <div class="controls">
            <select name="login_type" id="login_type">
                <option value="email" <?php echo set_select('auth.login_type', 'email', isset($settings['auth.login_type']) && $settings['auth.login_type'] == 'email'); ?>><?php echo lang('bf_login_type_email'); ?></option>
                <option value="username" <?php echo set_select('auth.login_type', 'username', isset($settings['auth.login_type']) && $settings['auth.login_type'] == 'username'); ?>><?php echo lang('bf_login_type_username'); ?></option>
                <option value="both" <?php echo set_select('auth.login_type', 'both', isset($settings['auth.login_type']) && $settings['auth.login_type'] == 'both'); ?>><?php echo lang('bf_login_type_both'); ?></option>
            </select>
            <span class='help-inline'><?php echo form_error('login_type'); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" id="use_usernames_label"><?php echo lang('bf_use_usernames'); ?></label>
        <div class="controls" aria-labelledby="use_usernames_label" role="group">
            <label class="radio" for="use_username">
                <input type="radio" id="use_username" name="use_usernames" value="1" <?php echo set_radio('auth.use_usernames', 1, isset($settings['auth.use_usernames']) && $settings['auth.use_usernames'] == 1); ?> />
                <?php echo lang('bf_username'); ?>
            </label>
            <label class="radio" for="use_email">
                <input type="radio" id="use_email" name="use_usernames" value="0" <?php echo set_radio('auth.use_usernames', 0, isset($settings['auth.use_usernames']) && $settings['auth.use_usernames'] == 0); ?> />
                <?php echo lang('bf_email'); ?>
            </label>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" id='allow_name_change_label'><?php echo lang('bf_display_name'); ?></label>
        <div class="controls" aria-labelledby='allow_name_change_label' role='group'>
            <label class="checkbox" for="allow_name_change">
                <input type="checkbox" name="allow_name_change" id="allow_name_change" <?php echo set_checkbox('auth.allow_remember', 1, isset($settings['auth.allow_name_change']) && $settings['auth.allow_name_change'] == 1); ?> />
                <?php echo lang('set_allow_name_change_note'); ?>
            </label>
            <div id="name-change-settings"<?php echo $settings['auth.allow_name_change'] ? '' : ' style="display:none"'; ?>>
                <input type="text" name="name_change_frequency" value="<?php echo set_value('auth.name_change_frequency', isset($settings['auth.name_change_frequency']) ? $settings['auth.name_change_frequency'] : ''); ?>" />
                <?php echo lang('set_name_change_frequency'); ?>
                <input type="text" name="name_change_limit" value="<?php echo set_value('auth.name_change_limit', isset($settings['auth.name_change_limit']) ? $settings['auth.name_change_limit'] : ''); ?>" />
                <?php echo lang('set_days'); ?>
            </div>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox" for="allow_remember">
                <input type="checkbox" name="allow_remember" id="allow_remember" value="1" <?php echo set_checkbox('auth.allow_remember', 1, isset($settings['auth.allow_remember']) && $settings['auth.allow_remember'] == 1); ?> />
                <?php echo lang('bf_allow_remember'); ?>
            </label>
        </div>
    </div>
    <div class="control-group<?php echo form_error('remember_length') ? $errorClass : ''; ?>" id="remember-length"<?php echo $settings['auth.allow_remember'] ? '' : ' style="display:none"'; ?>>
        <label class="control-label" for="remember_length"><?php echo lang('bf_remember_time'); ?></label>
        <div class="controls">
            <select name="remember_length" id="remember_length">
                <option value="604800"  <?php echo set_select('auth.remember_length', '604800', isset($settings['auth.remember_length']) && $settings['auth.remember_length'] == '604800'); ?>>1 <?php echo lang('bf_week'); ?></option>
                <option value="1209600" <?php echo set_select('auth.remember_length', '1209600', isset($settings['auth.remember_length']) && $settings['auth.remember_length'] == '1209600'); ?>>2 <?php echo lang('bf_weeks'); ?></option>
                <option value="1814400" <?php echo set_select('auth.remember_length', '1814400', isset($settings['auth.remember_length']) && $settings['auth.remember_length'] == '1814400'); ?>>3 <?php echo lang('bf_weeks'); ?></option>
                <option value="2592000" <?php echo set_select('auth.remember_length', '2592000', isset($settings['auth.remember_length']) && $settings['auth.remember_length'] == '2592000'); ?>>30 <?php echo lang('bf_days'); ?></option>
            </select>
            <span class='help-inline'><?php echo form_error('remember_length'); ?></span>
        </div>
    </div>
    <div class="control-group<?php echo form_error('password_min_length') ? $errorClass : ''; ?>" id="password-strength">
        <label class="control-label" for="password_min_length"><?php echo lang('bf_password_strength'); ?></label>
        <div class="controls">
            <input type="text" name="password_min_length" id="password_min_length" value="<?php echo set_value('password_min_length', isset($settings['auth.password_min_length']) ? $settings['auth.password_min_length'] : ''); ?>" class="span1" />
            <span class="help-inline"><?php echo (form_error('password_min_length') ? form_error('password_min_length') . '<br />' : '') . lang('bf_password_length_help'); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" id='password_options_label'><?php echo lang('set_option_password'); ?></label>
        <div class="controls" aria-labelledby='password_options_label' role='group'>
            <label class="checkbox" for="password_force_numbers">
                <input type="checkbox" name="password_force_numbers" id="password_force_numbers" value="1" <?php echo set_checkbox('password_force_numbers', 1, isset($settings['auth.password_force_numbers']) && $settings['auth.password_force_numbers'] == 1); ?> />
                <?php echo lang('bf_password_force_numbers'); ?>
            </label>
            <label class="checkbox" for="password_force_symbols">
                <input type="checkbox" name="password_force_symbols" id="password_force_symbols" value="1" <?php echo set_checkbox('password_force_symbols', 1, isset($settings['auth.password_force_symbols']) && $settings['auth.password_force_symbols'] == 1); ?> />
                <?php echo lang('bf_password_force_symbols'); ?>
            </label>
            <label class="checkbox" for="password_force_mixed_case">
                <input type="checkbox" name="password_force_mixed_case" id="password_force_mixed_case" value="1" <?php echo set_checkbox('password_force_mixed_case', 1, isset($settings['auth.password_force_mixed_case']) && $settings['auth.password_force_mixed_case'] == 1); ?> />
                <?php echo lang('bf_password_force_mixed_case'); ?>
            </label>
            <label class="checkbox" for="password_show_labels">
                <input type="checkbox" name="password_show_labels" id="password_show_labels" value="1" <?php echo set_checkbox('password_show_labels', 1, isset($settings['auth.password_show_labels']) && $settings['auth.password_show_labels'] == 1); ?> />
                <?php echo lang('bf_password_show_labels'); ?>
            </label>
        </div>
    </div>
    <div class="control-group<?php echo form_error('password_iterations') ? $errorClass : ''; ?>">
        <label for="password_iterations" class="control-label"><?php echo lang('set_password_iterations'); ?></label>
        <div class="controls">
            <select name="password_iterations" id='password_iterations'>
                <option <?php echo set_select('password_iterations', 2, isset($settings['password_iterations']) && $settings['password_iterations'] == 2) ?>>2</option>
                <option <?php echo set_select('password_iterations', 4, isset($settings['password_iterations']) && $settings['password_iterations'] == 4) ?>>4</option>
                <option <?php echo set_select('password_iterations', 8, isset($settings['password_iterations']) && $settings['password_iterations'] == 8) ?>>8</option>
                <option <?php echo set_select('password_iterations', 16, isset($settings['password_iterations']) && $settings['password_iterations'] == 16) ?>>16</option>
                <option <?php echo set_select('password_iterations', 31, isset($settings['password_iterations']) && $settings['password_iterations'] == 31) ?>>31</option>
            </select>
            <span class="help-inline"><?php echo (form_error('password_iterations') ? form_error('password_iterations') . '<br />' : '') . lang('bf_password_iterations_note'); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="force_pass_reset"><?php echo lang('set_force_reset'); ?></label>
        <div class="controls">
            <a href="<?php echo site_url(SITE_AREA . '/settings/users/force_password_reset_all'); ?>" class="btn btn-danger" onclick="return confirm('<?php echo lang('set_password_reset_confirm'); ?>');"><?php echo lang('set_reset'); ?></a>
            <span class="help-inline"><?php echo lang('set_reset_note'); ?></span>
        </div>
    </div>
</fieldset>