<div class="admin-box">
	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            <legend><?php echo lang('permissions_details') ?></legend>

            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?>">
                <label for="name" class="control-label"><?php echo lang('permissions_name'); ?></label>
                <div class="controls">
                    <input id="name" type="text" name="name" class="input-large" maxlength="30" value="<?php echo set_value('name', isset($permissions->name) ? $permissions->name : ''); ?>" />
                    <span class="help-inline"><?php echo form_error('name'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('description') ? ' error' : ''; ?>">
                <label for="description" class="control-label"><?php echo lang('permissions_description'); ?></label>
                <div class="controls">
                    <input id="description" type="text" name="description" maxlength="100" value="<?php echo set_value('description', isset($permissions->description) ? $permissions->description : ''); ?>" />
                    <span class="help-inline"><?php echo form_error('description'); ?></span>
                </div>
            </div>
            <div class="control-group">
                <label for="status" class="control-label"><?php echo lang('permissions_status'); ?></label>
                <div class="controls">
                    <select name="status" id="status">
                        <option value="active" <?php echo set_select('status', 'active', isset($permissions->status) && $permissions->status == 'active'); ?>><?php echo lang('permissions_active'); ?></option>
                        <option value="inactive" <?php echo set_select('status', 'inactive', isset($permissions->status) && $permissions->status == 'inactive'); ?>><?php echo lang('permissions_inactive'); ?></option>
                    </select>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('permissions_save'); ?>" />
            <?php
            echo lang('bf_or') . ' ' . anchor(SITE_AREA . '/settings/permissions', lang('bf_action_cancel'));
            ?>
        </fieldset>
	<?php echo form_close(); ?>
</div>