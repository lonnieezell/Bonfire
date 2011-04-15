<div class="box delete rounded">

<?php echo form_open($this->uri->uri_string()); ?>

	<button type="submit" name="submit" class="button"><?php echo lang('bf_action_delete'); ?> <?php echo lang('bf_files'); ?></button> 
	<span class="cancel"><?php echo lang('bf_or'); ?>	<a href="/admin/developer/database/backups"><?php echo lang('bf_action_cancel'); ?></a></span>

	<h3><?php echo lang('bf_action_delete'); ?> <?php echo lang('db_backup'); ?> <?php echo lang('bf_file'); ?><?php echo count($files) > 1 ? 's' : ''; ?></h3>
		
		<?php if (isset($files) && is_array($files) && count($files) > 0) : ?>
			<?php foreach ($files as $file) : ?>
				<input type="hidden" name="files[]" value="<?php echo $file ?>" />
			<?php endforeach; ?>
		
		
			<p><b><?php echo lang('db_backup_delete_confirm'); ?></b></p>
			
			<ul>
			<?php foreach($files as $file) : ?>
				<li><?php echo $file ?></li>
			<?php endforeach; ?>
			</ul>
		
		<?php endif; ?>
		
	<?php echo form_close(); ?>
</div>
