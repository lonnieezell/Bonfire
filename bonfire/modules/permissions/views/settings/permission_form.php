<div class="admin-box">
    <?php echo form_open($this->uri->uri_string()); ?>
    <fieldset>
        <legend><?php echo lang('permissions_details') ?></legend>

        <div class="form-group<?php echo form_error('name') ? ' has-error' : ''; ?>">
            <label for="name"><?php echo lang('permissions_name'); ?></label>
            <input id="name" type="text" name="name" maxlength="30" class="form-control"
                   value="<?php echo set_value('name', isset($permissions->name) ? $permissions->name : ''); ?>"/>
            <span class="help-block"><?php echo form_error('name'); ?></span>
        </div>
        <div class="form-group<?php echo form_error('description') ? ' has-error' : ''; ?>">
            <label for="description"><?php echo lang('permissions_description'); ?></label>
            <input id="description" type="text" name="description" maxlength="100" class="form-control"
                   value="<?php echo set_value('description', isset($permissions->description) ? $permissions->description : ''); ?>"/>
            <span class="help-block"><?php echo form_error('description'); ?></span>
        </div>
        <div class="form-group">
            <label for="status"><?php echo lang('permissions_status'); ?></label>
            <select name="status" id="status" class="form-control">
                <option
                    value="active" <?php echo set_select('status', 'active', isset($permissions->status) && $permissions->status == 'active'); ?>><?php echo lang('permissions_active'); ?></option>
                <option
                    value="inactive" <?php echo set_select('status', 'inactive', isset($permissions->status) && $permissions->status == 'inactive'); ?>><?php echo lang('permissions_inactive'); ?></option>
            </select>
        </div>
    </fieldset>
    <fieldset class='form-actions'>
        <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('permissions_save'); ?>"/>
        <?php
        echo lang('bf_or') . ' ' . anchor(SITE_AREA . '/settings/permissions', lang('bf_action_cancel'));
        ?>
    </fieldset>
    <?php echo form_close(); ?>
</div>