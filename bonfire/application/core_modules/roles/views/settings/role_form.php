<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

	<div>
		<label><?php echo lang('role_name'); ?></label>
		<input type="text" name="role_name" class="medium" value="<?php echo isset($role) ? $role->role_name : '' ?>" />
	</div>
	
	<div style="vertical-align: top">
		<label><?php echo lang('bf_description'); ?></label>
		<textarea name="description" rows="3"><?php echo isset($role) ? $role->description : '' ?></textarea>
		<p class="small indent"><?php echo lang('role_max_desc_length'); ?></p>
	</div>
	
	<div>
		<label><?php echo lang('role_default_role'); ?>?</label>
		<input type="checkbox" name="default" value="1" <?php echo isset($role) && $role->default == 1 ? 'checked="checked"' : '' ?> />
		<p class="small" style="display: inline"><?php echo lang('role_default_note'); ?></p>
	</div>
	
	<!-- Permissions -->
	<fieldset>
		<legend><?php echo lang('role_permissions'); ?></legend>
		
		<p><?php echo lang('role_permissions_check_note'); ?></p>
	
		<?php echo modules::run('roles/settings/matrix'); ?>
	
	</fieldset>
	
	<div class="text-right">
		<br/>
		<input type="submit" name="submit" value="<?php echo lang('role_save_role'); ?>" /> or <?php echo anchor('admin/settings/roles', lang('bf_action_cancel')); ?>
	</div>

	<br/>

	<?php if (isset($role)) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url('admin/settings/roles/delete/'. $role->role_id); ?>" onclick="return confirm('Are you sure you want to delete this role?')"><?php echo lang('role_delete_role'); ?></a>
		
		<h3><?php echo lang('role_delete_role'); ?></h3>
		
		<p><?php echo lang('role_delete_note'); ?></p>
	</div>
	<?php endif; ?>
<?php echo form_close(); ?>