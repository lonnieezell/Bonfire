<p class="intro">Creates and sets up a new Context.</p>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>
	
	<?php if (validation_errors()) :?>
	<div class="alert alert-error">
		<?php echo validation_errors(); ?>
	</div>
	<?php endif; ?>
	
	<?php echo form_open(current_url(), 'class="form-horizontal"'); ?>
	
		<div class="control-group">
			<label for="context_name" class="control-label">Context Name</label>
			<div class="controls">
				<input type="text" name="context_name" class="input-large" value="<?php echo settings_item('context_name'); ?>" />
				<p class="help-inline">Cannot contain spaces.</p>
			</div>
		</div>
		
		<?php if (isset($roles) && is_array($roles) && count($roles)) :?>
		
			<div class="control-group">
				<label class="control-label">Allow for Roles:</label>
				<div class="controls">
				<?php foreach ($roles as $role) : ?>
					<label class="checkbox">
						<input type="checkbox" name="roles[]" value="<?php echo $role->role_id ?>" <?php echo set_checkbox('roles[]', $role->role_id) ?> /> <?php echo $role->role_name; ?>
					</label>
				<?php endforeach; ?>
				</div>
			</div>
		
		<?php endif; ?>
		<!-- TODO Add this in later.
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="migrate" checked="checked" /> Create an Application Migration?
				</label>
			</div>
		</div>
		-->
		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="Create It" /> or 
			<a href="<?php echo site_url(SITE_AREA .'/developer/builder') ?>">Cancel</a>
		</div>
	
	<?php echo form_close(); ?>
</div>