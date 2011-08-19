
<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php // Change the css classes to suit your needs    
	if( isset($permissions) ) {
		$permissions = (array)$permissions;
	}
	$id = isset($permissions['permission_id']) ? "/".$permissions['permission_id'] : '';
?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

<div>
        <?php echo form_label(lang('permissions_name').'<span class="required">*</span>', 'name'); ?>
        <input id="name" type="text" name="name" maxlength="30" value="<?php echo set_value('name', isset($permissions['name']) ? $permissions['name'] : ''); ?>"  />
</div>

<div>
        <?php echo form_label(lang('permissions_description'), 'description'); ?>
        <input id="description" type="text" name="description" maxlength="100" value="<?php echo set_value('description', isset($permissions['description']) ? $permissions['description'] : ''); ?>"  />
</div>

<div>
        <?php echo form_label(lang('permissions_status').'<span class="required">*</span>', 'status'); ?>
        <?php // Change the values in this array to populate your dropdown as required ?>
        <?php $options = array(
							  'active'		=> lang('permissions_active'),
							  'inactive'	=> lang('permissions_inactive'),
							  'deleted'		=> lang('permissions_deleted')
							); ?>

        <?php echo form_dropdown('status', $options, set_value('status'))?>
</div>                                             
                        

	<div class="text-right">
		<br/>
		<input type="submit" name="submit" value="<?php echo lang('permissions_save');?>" /> or <?php echo anchor(SITE_AREA .'/settings/permissions', lang('permissions_cancel')); ?>
	</div>

	<?php if (isset($permissions)) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .'/settings/permissions/delete/'. $id); ?>" onclick="return confirm('<?php echo lang('permissions_delete_confirm'); ?>')"><?php echo lang('permissions_delete_record'); ?></a>
		
		<h3><?php echo lang('permissions_delete_record'); ?></h3>
		
		<div><?php echo lang('permissions_delete_warning'); ?></div>
	</div>
	<?php endif; ?>
<?php echo form_close(); ?>
