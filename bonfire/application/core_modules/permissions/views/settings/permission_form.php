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
	        <?php echo form_label(lang('permissions_name').'<span class="required">*</span>', 'name'); ?>
	    	<div class="controls">
	    	    <input id="name" type="text" name="name" maxlength="30" value="<?php echo set_value('name', isset($permissions['name']) ? $permissions['name'] : ''); ?>"  />
	    	    <span class="help-inline"><?php echo form_error('name'); ?></span>
			</div>
		</div>
	
		<div class="control-group <?php echo form_has_error('description') ? 'error' : ''; ?>">
	        <?php echo form_label(lang('permissions_description'), 'description'); ?>
	        <div class="controls">
		        <input id="description" type="text" name="description" maxlength="100" value="<?php echo set_value('description', isset($permissions['description']) ? $permissions['description'] : ''); ?>"  />
		        <span class="help-inline"><?php echo form_error('description'); ?></span>
			</div>
		</div>
	
		<div class="control-group">
	        <?php echo form_label(lang('permissions_status').'<span class="required">*</span>', 'status'); ?>
	        <?php // Change the values in this array to populate your dropdown as required ?>
	        <?php $options = array(
								  'active'		=> lang('permissions_active'),
								  'inactive'	=> lang('permissions_inactive'),
								  'deleted'		=> lang('permissions_deleted')
								); ?>
			<div class="controls">
		        <?php echo form_dropdown('status', $options, set_value('status'))?>
			</div>
		</div>

		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('permissions_save');?>" /> or <?php echo anchor(SITE_AREA .'/settings/permissions', lang('permissions_cancel'), 'class="btn btn-danger"'); ?>
		</div>

	</fieldset>
	
		<?php if (isset($permissions)) : ?>
		<div class="box delete rounded">
			<h3><?php echo lang('permissions_delete_record'); ?></h3>
	
			<p><?php echo lang('permissions_delete_warning'); ?></p>
	
			<a class="btn btn-danger" href="<?php echo site_url(SITE_AREA .'/settings/permissions/delete/'. $id); ?>" onclick="return confirm('<?php echo lang('permissions_delete_confirm'); ?>')"><i class="icon-trash icon-white">&nbsp;</i>&nbsp;<?php echo lang('permissions_delete_record'); ?></a>
		</div>
		<?php endif; ?>
	<?php echo form_close(); ?>

</div>