<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

	<div>
		<label>Role Name</label>
		<input type="text" name="role_name" class="medium" value="<?php echo isset($role) ? $role->role_name : '' ?>" />
	</div>
	
	<div style="vertical-align: top">
		<label>Description</label>
		<textarea name="description" rows="3"><?php echo isset($role) ? $role->description : '' ?></textarea>
		<p class="small indent">Max. 255 characters.</p>
	</div>
	
	<div>
		<label>Default Role?</label>
		<input type="checkbox" name="default" value="1" <?php echo isset($role) && $role->default == 1 ? 'checked="checked"' : '' ?> />
		<p class="small" style="display: inline">Check if this role should be assigned to all new users.</p>
	</div>
	
	<!-- Permissions -->
	<fieldset>
		<legend>Permissions</legend>
		
		<p>Check all permissions that apply to this Role.</p>
	
		<?php echo modules::run('roles/settings/matrix'); ?>
	
	</fieldset>
	
	<div class="text-right">
		<br/>
		<input type="submit" name="submit" value="Save Role" /> or <?php echo anchor('admin/settings/roles', 'Cancel'); ?>
	</div>

	<br/>

	<?php if (isset($role)) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url('admin/settings/roles/delete/'. $role->role_id); ?>" onclick="return confirm('Are you sure you want to delete this role?')">Delete this Role</a>
		
		<h3>Delete this Role</h3>
		
		<p>Deleting this role will convert all users that are currently assigned it to the site's default role.</p>
	</div>
	<?php endif; ?>
<?php echo form_close(); ?>