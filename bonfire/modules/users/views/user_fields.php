<?php /* /users/views/user_fields.php */

$currentMethod = $this->router->fetch_method();

$errorClass     = empty($errorClass) ? ' error' : $errorClass;
$controlClass   = empty($controlClass) ? 'span4' : $controlClass;
$registerClass  = $currentMethod == 'register' ? ' required' : '';
$editSettings   = $currentMethod == 'edit';

$defaultLanguage = isset($user->language) ? $user->language : strtolower(settings_item('language'));
$defaultTimezone = isset($user->timezone) ? $user->timezone : strtoupper(settings_item('site.default_user_timezone'));

?>
<div class="control-group<?php echo form_error('email') ? $errorClass : ''; ?>">
    <label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="text" id="email" name="email" value="<?php echo set_value('email', isset($user) ? $user->email : ''); ?>" />
        <span class="help-inline"><?php echo form_error('email'); ?></span>
    </div>
</div>
<div class="control-group<?php echo form_error('display_name') ? $errorClass : ''; ?>">
    <label class="control-label" for="display_name"><?php echo lang('bf_display_name'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="text" id="display_name" name="display_name" value="<?php echo set_value('display_name', isset($user) ? $user->display_name : ''); ?>" />
        <span class="help-inline"><?php echo form_error('display_name'); ?></span>
    </div>
</div>
<?php if (settings_item('auth.login_type') !== 'email' || settings_item('auth.use_usernames')) : ?>
<div class="control-group<?php echo form_error('username') ? $errorClass : ''; ?>">
    <label class="control-label required" for="username"><?php echo lang('bf_username'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="text" id="username" name="username" value="<?php echo set_value('username', isset($user) ? $user->username : ''); ?>" />
        <span class="help-inline"><?php echo form_error('username'); ?></span>
    </div>
</div>
<?php endif; ?>
<div class="control-group<?php echo form_error('password') ? $errorClass : ''; ?>">
    <label class="control-label<?php echo $registerClass; ?>" for="password"><?php echo lang('bf_password'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="password" id="password" name="password" value="" />
        <span class="help-inline"><?php echo form_error('password'); ?></span>
        <p class="help-block"><?php echo isset($password_hints) ? $password_hints : ''; ?></p>
    </div>
</div>
<div class="control-group<?php echo form_error('pass_confirm') ? $errorClass : ''; ?>">
    <label class="control-label<?php echo $registerClass; ?>" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
    <div class="controls">
        <input class="<?php echo $controlClass; ?>" type="password" id="pass_confirm" name="pass_confirm" value="" />
        <span class="help-inline"><?php echo form_error('pass_confirm'); ?></span>
    </div>
</div>
<?php if ($editSettings) : ?>
<div class="control-group<?php echo form_error('force_password_reset') ? $errorClass : ''; ?>">
    <div class="controls">
        <label class="checkbox" for="force_password_reset">
            <input type="checkbox" id="force_password_reset" name="force_password_reset" value="1" <?php echo set_checkbox('force_password_reset', empty($user->force_password_reset)); ?> />
            <?php echo lang('us_force_password_reset'); ?>
        </label>
    </div>
</div>
<?php
endif;

if (! empty($languages) && is_array($languages)) :
    if (count($languages) == 1) :
?>
<input type="hidden" id="language" name="language" value="<?php echo $languages[0]; ?>" />
<?php
    else :
?>
<div class="control-group<?php echo form_error('language') ? $errorClass : ''; ?>">
    <label class="control-label required" for="language"><?php echo lang('bf_language'); ?></label>
    <div class="controls">
        <select name="language" id="language" class="chzn-select <?php echo $controlClass; ?>">
            <?php foreach ($languages as $language) : ?>
            <option value="<?php e($language); ?>" <?php echo set_select('language', $language, $defaultLanguage == $language); ?>>
                <?php e(ucfirst($language)); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <span class="help-inline"><?php echo form_error('language'); ?></span>
    </div>
</div>
<?php
    endif;
endif;
?>
<div class="control-group<?php echo form_error('timezones') ? $errorClass : ''; ?>">
    <label class="control-label required" for="timezones"><?php echo lang('bf_timezone'); ?></label>
    <div class="controls">
        <?php
        echo timezone_menu(
            set_value('timezones', isset($user) ? $user->timezone : $defaultTimezone),
            $controlClass,
            'timezones',
            array('id' => 'timezones')
        );
        ?>
        <span class="help-inline"><?php echo form_error('timezones'); ?></span>
    </div>
</div>