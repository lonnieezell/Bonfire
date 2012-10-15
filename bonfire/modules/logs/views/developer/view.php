<div class="admin-box">
<h3><span style="font-weight: normal">Viewing:</span> <?php echo $log_file_pretty; ?></h3>

<?php if (!isset($log_content) || empty($log_content)) : ?>
	<div class="alert alert-warning fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php echo lang('log_not_found'); ?>
	</div>
	<?php else : ?>

	<br/>

	<p>View &nbsp;&nbsp;
		<select id="filter">
			<option value="all"><?php echo lang('log_show_all_entries'); ?></option>
			<option value="error"><?php echo lang('log_show_errors'); ?></option>
		</select>
	</p>

	<div id="log">
		<?php foreach ($log_content as $row) : ?>
		<?php
		// Log files start with PHP guard header
		// (apparently we don't trust .htaccess)
		if (strpos($row, '<?php') === 0)
		{
			continue;
		}

		$class = '';

		if (strpos($row, 'ERROR') !== false)
		{
			$class="alert-error";
		} else
			if (strpos($row, 'DEBUG') !== false)
			{
				$class="alert-warning";
			}
		?>
		<div style="border-bottom: 1px solid #999; padding: 5px 18px; color: #222;" <?php echo 'class="'. $class .'"' ?>>
			<?php e($row); ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>

	<?php if (has_permission('Bonfire.Logs.Manage')) : ?>
	<!-- Purge? -->
	<div class="admin-box">
		<h3><?php echo lang('log_delete1_button') ?></h3>
		<br/>

		<?php echo form_open(SITE_AREA .'/developer/logs'); ?>
		<div class="alert alert-warning fade in">
			<a class="close" data-dismiss="alert">&times;</a>
			<?php echo sprintf(lang('log_delete1_note'),$log_file_pretty); ?>
		</div>

		<div class="form-actions">
			<input type="hidden" name="checked[]" value="<?php e($log_file); ?>" />

			<button type="submit" name="action_delete" class="btn btn-danger" onclick="return confirm('<?php echo lang('log_delete_confirm') ?>')"><i class="icon-trash icon-white">&nbsp;</i>&nbsp;<?php echo lang('log_delete1_button'); ?></button>
		</div>
		<?php echo form_close(); ?>
	</div>
	<?php endif; ?>

<?php endif; ?>
