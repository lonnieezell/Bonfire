<?php echo form_open($this->uri->uri_string()); ?>

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

	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?> <?php echo lang('bf_files'); ?>" />
		<?php echo lang('bf_or'); ?>	<a href="<?php echo site_url(SITE_AREA .'/developer/database/backups') ?>"><?php echo lang('bf_action_cancel'); ?></a></p>
	</div>

<?php echo form_close(); ?>
