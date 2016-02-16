<?php

$errorClass = ' has-error';
$controlClass = 'form-control';
$fieldData = array(
    'errorClass'    => $errorClass,
    'controlClass'  => $controlClass,
);


if (isset($password_hints)) {
    $fieldData['password_hints'] = $password_hints;
}

// For the settings form, $renderPayload should not be set to $current_user or
// $this->auth->user(), as it can't be assumed that $current_user is the same as
// the user being edited.
$renderPayload = null;
if (isset($current_user)) {
    $fieldData['current_user'] = $current_user;
}
if (isset($user)) {
    $fieldData['user'] = $user;
    $renderPayload = $user;
}

if (validation_errors()) :
?>
<div class='alert alert-danger'>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

if (isset($user) && $user->banned) :
?>
<div class="alert alert-warning fade in">
    <h4 class="alert-heading"><?php echo lang('us_banned_admin_note'); ?></h4>
</div>
<?php
endif;

if (isset($password_hints)) :
?>
<div class="alert alert-info fade in">
    <a data-dismiss="alert" class="close">&times;</a>
    <?php echo $password_hints; ?>
</div>
<?php
endif;

echo form_open($this->uri->uri_string(), array('autocomplete' => 'off'));
?>
    <fieldset>
        <legend><?php echo lang('us_account_details'); ?></legend>
        <?php Template::block('user_fields', 'user_fields', $fieldData); ?>
    </fieldset>
    <?php
    $canManageUser = false;
    if (! isset($user)) {
        $canManageUser = true;
    } elseif ($this->auth->has_permission('Permissions.' . ucfirst($user->role_name) . '.Manage')) {
        $canManageUser = true;
    }
    if ($canManageUser && $this->auth->has_permission('Bonfire.Roles.Manage')) :
    ?>
    <fieldset>
        <legend><?php echo lang('us_role'); ?></legend>
        <div class="form-group">
            <label for="role_id"><?php echo lang('us_role'); ?></label>
                <select name="role_id" id="role_id" class="chzn-select <?php echo $controlClass; ?>">
                    <?php
                    if (! empty($roles) && is_array($roles)) :
                        foreach ($roles as $role) :
                            if ($this->auth->has_permission('Permissions.' . ucfirst($role->role_name) . '.Manage')) :
                                // The selected role is the role assigned to the
                                // user or the site's default role.
                                $selectedRole = isset($user) ? ($user->role_id == $role->role_id)
                                    : ($role->default == 1);
                    ?>
                    <option value="<?php echo $role->role_id; ?>" <?php echo set_select('role_id', $role->role_id, $selectedRole); ?>>
                        <?php e(ucfirst($role->role_name)); ?>
                    </option>
                    <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>
        </div>
    </fieldset>
    <?php endif; ?>
    <fieldset>
        <?php
        // Allow modules to render custom fields.
        Events::trigger('render_user_form', $renderPayload);
        ?>
        <!-- Start of User Meta -->
        <?php $this->load->view('users/user_meta');?>
        <!-- End of User Meta -->
    </fieldset>
    <?php
    if (isset($user)
        && $this->auth->has_permission('Permissions.' . ucfirst($user->role_name) . '.Manage')
        && $user->id != $this->auth->user_id()
        && ($user->banned || $user->deleted)
    ) :
        $field = ($user->active ? 'de' : '') . 'activate';
    ?>
    <fieldset>
        <legend><?php echo lang('us_account_status'); ?></legend>
        <div class="form-group">
v            <div class="checkbox">
                <label for="<?php echo $field; ?>">
                    <input type="checkbox" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="1" />
                    <?php echo lang("us_{$field}_note"); ?>
                </label>
            </div>
        </div>
        <?php if ($user->deleted) : ?>
        <div class="form-group">
            <div class="checkbox">
                <label for="restore">
                    <input type="checkbox" name="restore" id="restore" value="1" />
                    <?php echo lang('us_restore_note'); ?>
                </label>
            </div>
        </div>
        <?php elseif ($user->banned) : ?>
        <div class="form-group">
            <div class="checkbox">
                <label for="unban">
                    <input type="checkbox" name="unban" id="unban" value="1" />
                    <?php echo lang('us_unban_note'); ?>
                </label>
            </div>
        </div>
        <?php endif; ?>
    </fieldset>
    <?php endif; ?>
    <fieldset class="form-actions">
        <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save') . ' ' . lang('bf_user'); ?>" />
        <?php echo lang('bf_or') . ' ' . anchor(SITE_AREA . '/settings/users', lang('bf_action_cancel')); ?>
    </fieldset>
<?php
echo form_close();
