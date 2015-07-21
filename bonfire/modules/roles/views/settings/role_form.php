<?php

if (validation_errors()) :
?>
<div class="alert alert-error fade in">
    <a class="close" data-dismiss="alert">&times;</a>
    <?php echo validation_errors(); ?>
</div>
<?php endif; ?>
<div class="admin-box">
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            <legend><?php echo lang('role_details'); ?></legend>
            <input type='hidden' name='role_id' value="<?php echo set_value('role_id', isset($role) ? $role->role_id : ''); ?>" />
            <div class="control-group<?php echo form_error('role_name') ? ' error' : ''; ?>">
                <label class="control-label" for="role_name"><?php echo lang('role_name'); ?></label>
                <div class="controls">
                    <input type="text" name="role_name" id="role_name" class="input-xlarge" value="<?php echo set_value('role_name', isset($role) ? $role->role_name : ''); ?>" />
                    <span class="help-inline"><?php echo form_error('role_name'); ?></span>
                </div>
            </div>
            <div class="description control-group<?php echo form_error('description') ? ' error' : ''; ?>">
                <label class="control-label" for="description"><?php echo lang('bf_description'); ?></label>
                <div class="controls">
                    <textarea name="description" id="description" rows="3" class="input-xlarge"><?php echo set_value('description', isset($role) ? $role->description : ''); ?></textarea>
                    <span class="help-inline"><?php echo form_error('description') ? form_error('description') : lang('role_max_desc_length'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('login_destination') ? ' error' : ''; ?>">
                <label class="control-label" for="login_destination"><?php echo lang('role_login_destination'); ?></label>
                <div class="controls">
                    <input type="text" name="login_destination" id="login_destination" class="input-xlarge" value="<?php echo set_value('login_destination', isset($role) ? $role->login_destination : ''); ?>" />
                    <span class="help-inline"><?php
                        echo form_error('login_destination') ? form_error('login_destination') . '<br />' : '';
                        echo lang('role_destination_note');
                    ?></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="default_context"><?php echo lang('role_default_context'); ?></label>
                <div class="controls">
                    <select name="default_context" id="default_context">
                        <?php
                        if (! empty($contexts) && is_array($contexts)) :
                            foreach ($contexts as $context) :
                        ?>
                        <option value="<?php echo $context;?>" <?php echo set_select('default_context', $context, isset($role) && $role->default_context == $context); ?>><?php echo ucfirst($context); ?></option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <span class="help-inline"><?php
                        echo form_error('default_context') ? form_error('default_context') . '<br />' : '';
                        echo lang('role_default_context_note');
                    ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('default') ? ' error' : ''; ?>">
                <label class="control-label" for="default"><?php echo lang('role_default_role'); ?></label>
                <div class="controls">
                    <label class="checkbox" for="default">
                        <input type="checkbox" name="default" id="default" value="1" <?php echo set_checkbox('default', 1, isset($role) && $role->default == 1); ?> />
                        <?php echo lang('role_default_note'); ?>
                    </label>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" id="can_delete_label"><?php echo lang('role_can_delete_role'); ?></label>
                <div class="controls" aria-labelledby="can_delete_label" role="group">
                    <label class="radio" for="can_delete_yes">
                        <input type="radio" name="can_delete" id="can_delete_yes" value="1" <?php echo set_radio('can_delete', 1, isset($role) && $role->can_delete == 1); ?> />
                        <?php echo lang('bf_yes'); ?>
                    </label>
                    <label class="radio" for="can_delete_no">
                        <input type="radio" name="can_delete" id="can_delete_no" value="0" <?php echo set_radio('can_delete', 0, isset($role) && $role->can_delete == 0); ?> />
                        <?php echo lang('bf_no'); ?>
                    </label>
                    <span class="help-inline"><?php echo lang('role_can_delete_note'); ?></span>
                </div>
            </div>
        </fieldset>
        <!-- Permissions -->
        <?php if (has_permission('Bonfire.Permissions.Manage')) : ?>
        <fieldset>
            <legend><?php echo lang('role_permissions'); ?></legend>
            <p class="intro"><?php echo lang('role_permissions_check_note'); ?></p>
            <?php echo Modules::run('roles/settings/matrix'); ?>
        </fieldset>
        <?php endif; ?>
        <fieldset class="form-actions">
            <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('role_save_role'); ?>" />
            <?php
            echo lang('bf_or') . ' ' . anchor(SITE_AREA . '/settings/roles', lang('bf_action_cancel'));
            if (isset($role)
                && $role->can_delete == 1
                && has_permission('Bonfire.Roles.Delete')
            ) :
            ?>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('<?php e(js_escape(lang('role_delete_confirm') . ' ' . lang('role_delete_note'))); ?>')"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('role_delete_role'); ?></button>
            <?php endif;?>
        </fieldset>
    <?php echo form_close(); ?>
</div>