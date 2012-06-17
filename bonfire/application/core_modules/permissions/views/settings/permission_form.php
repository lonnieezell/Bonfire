<?php // Change the css classes to suit your needs
	if( isset($permissions) ) {
		$permissions = (array)$permissions;
	}
	$id = isset($permissions['permission_id']) ? "/".$permissions['permission_id'] : '';
?>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
	<fieldset>
		<legend><?php echo lang('permissions_details') ?></legend>
	
		<div class="control-group <?php echo form_has_error('name') ? 'error' : ''; ?>">
			<?php echo form_simple_label('name', lang('bf_name'), TRUE); ?>
	    	<div class="controls">
	    	    <input id="name" type="text" name="name" class="input-large" maxlength="30" value="<?php echo set_value('name', isset($permissions['name']) ? $permissions['name'] : ''); ?>"  />
	    	    <span class="help-inline"><?php echo form_error('name'); ?></span>
			</div>
		</div>
	
		<div class="control-group <?php echo form_has_error('description') ? 'error' : ''; ?>">
			<?php echo form_simple_label('description', lang('bf_description')); ?>
	        <div class="controls">
		        <input id="description" type="text" name="description" maxlength="100" value="<?php echo set_value('description', isset($permissions['description']) ? $permissions['description'] : ''); ?>"  />
		        <span class="help-inline"><?php echo form_error('description'); ?></span>
			</div>
		</div>
	
		<div class="control-group">
			<?php echo form_simple_label('status', lang('bf_status'), TRUE); ?>
			<div class="controls">
				<select name="status">
					<option value="active" <?php echo set_select('status', lang('permissions_active')) ?>><?php echo lang('permissions_active') ?></option>
					<option value="inactive" <?php echo set_select('status', lang('permissions_inactive')) ?>><?php echo lang('permissions_inactive') ?></option>
				</select>
			</div>
		</div>

		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save_changes');?>" /> <?php echo lang('bf_or') ?> 
			<?php echo anchor(SITE_AREA .'/settings/permissions', '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
		</div>

	</fieldset>
	
	</div>
	<?php echo form_close(); ?>

</div>