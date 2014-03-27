<?php /* /bonfire/modules/users/views/user_fields.php */

$currentMethod = $this->router->fetch_method();

$errorClass     = empty($errorClass) ? ' error' : $errorClass;
$controlClass   = empty($controlClass) ? 'col-md-6' : $controlClass;
$registerClass  = $currentMethod == 'register' ? ' required' : '';
$editSettings   = $currentMethod == 'edit';

$defaultLanguage = isset($user->language) ? $user->language : strtolower(settings_item('language'));
$defaultTimezone = isset($current_user) ? $current_user->timezone : strtoupper(settings_item('site.default_user_timezone'));

?>
<div class="form-group<?php echo iif(form_error('display_name'), $errorClass); ?>">
    <label class="control-label" for="display_name"><?php echo lang('bf_display_name'); ?></label>
        <div class="controls <?php echo $controlClass; ?>">
        <input class="form-control" type="text" id="display_name" name="display_name" value="<?php echo set_value('display_name', isset($user) ? $user->display_name : ''); ?>" />
        <span class="help-inline"><?php echo form_error('display_name'); ?></span>
    </div>
</div>
<div class="form-group<?php echo iif(form_error('email'), $errorClass); ?>">
    <label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>
    <div class="controls <?php echo $controlClass; ?>">
        <input class="form-control" type="text" id="email" name="email" value="<?php echo set_value('email', isset($user) ? $user->email : ''); ?>" />
        <span class="help-inline"><?php echo form_error('email'); ?></span>
    </div>
</div>
<?php if (settings_item('auth.login_type') !== 'email' OR settings_item('auth.use_usernames')) : ?>
<div class="form-group<?php echo iif(form_error('username'), $errorClass); ?>">
    <label class="control-label required" for="username"><?php echo lang('bf_username'); ?></label>
        <div class="controls <?php echo $controlClass; ?>">
        <input class="form-control" type="text" id="username" name="username" value="<?php echo set_value('username', isset($user) ? $user->username : ''); ?>" />
        <span class="help-inline"><?php echo form_error('username'); ?></span>
    </div>
</div>
<?php endif; ?>
<div class="form-group<?php echo iif(form_error('password'), $errorClass); ?>">
    <label class="control-label<?php echo $registerClass; ?>" for="password"><?php echo lang('bf_password'); ?></label>
        <div class="controls <?php echo $controlClass; ?>">
        <input class="form-control" type="password" id="password" name="password" value="" />
        <span class="help-inline"><?php echo form_error('password'); ?></span>
        <p class="help-block"><?php if (isset($password_hints) ) { echo $password_hints; } ?></p>
    </div>
</div>
<div class="form-group<?php echo iif(form_error('pass_confirm'), $errorClass); ?>">
    <label class="control-label<?php echo $registerClass; ?>" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
        <div class="controls <?php echo $controlClass; ?>">
        <input class="form-control" type="password" id="pass_confirm" name="pass_confirm" value="" />
        <span class="help-inline"><?php echo form_error('pass_confirm'); ?></span>
    </div>
</div>
<?php if ($editSettings) : ?>
<div class="clearfix"></div>
<div class="form-group<?php echo iif(form_error('force_password_reset'), $errorClass); ?>" style="border-bottom:0;">
    <div class="col-md-offset-2 col-md-6" style="margin-left: 15px;">
      <div class="checkbox">
        <label>
          <input type="checkbox" id="force_password_reset" name="force_password_reset" value="1" <?php echo set_checkbox('force_password_reset', empty($user->force_password_reset)); ?> > 
          <?php echo lang('us_force_password_reset'); ?>
        </label>
      </div>
    </div>
</div>
<div class="clearfix"></div>

<?php endif;

if (isset($languages) && is_array($languages) && count($languages)) :
    if(count($languages) == 1):
?>
<input type="hidden" id="language" name="language" value="<?php echo $languages[0]; ?>" />
<?php
    else :
?>
<div class="form-group<?php echo iif(form_error('language'), $errorClass); ?>">
    <label class="control-label required" for="language"><?php echo lang('bf_language'); ?></label>
    <div class="controls <?php echo $controlClass; ?>">
        <select name="language" id="language" class="form-control chzn-select">
            <?php foreach ($languages as $language) : ?>
            <option value="<?php e($language); ?>" <?php echo set_select('language', $language, $defaultLanguage == $language ? true : false); ?>>
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
<div class="form-group<?php echo iif(form_error('timezone'), $errorClass); ?>">
    <label class="control-label required" for="timezones"><?php echo lang('bf_timezone'); ?></label>
     <div class="controls <?php echo $controlClass; ?>">
        <?php echo timezone_menu(set_value('timezones', isset($user) ? $user->timezone : $defaultTimezone), 'form-control' ); ?>
        <span class="help-inline"><?php echo form_error('timezones'); ?></span>
    </div>
</div>