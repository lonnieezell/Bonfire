<div class="admin-box">
<h3><span style="font-weight: normal"><?php echo lang('logs_viewing'); ?></span> <?php echo $log_file_pretty; ?></h3>

<?php if (!isset($log_content) || empty($log_content)) : ?>
	<div class="alert alert-warning fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php echo lang('logs_not_found'); ?>
	</div>
	<?php else : ?>

	<br/>

	<p><?php echo lang('logs_view'); ?>&nbsp;&nbsp;
		<select id="filter">
			<option value="all"><?php echo lang('logs_show_all_entries'); ?></option>
			<option value="error"><?php echo lang('logs_show_errors'); ?></option>
		</select>
	</p>

	<div id="log">
		<?php foreach ($log_content as $row) : ?>
		<?php
		$class = '';

		if (strpos($row, 'ERROR') !== FALSE)
		{
			$class="alert-error";
		} else
			if (strpos($row, 'DEBUG') !== FALSE)
			{
				$class="alert-warning";
			}
		?>
		<div style="border-bottom: 1px solid #999; padding: 5px 18px; color: #222;" <?php echo 'class="'. $class .'"'; ?>>
			<?php echo $row; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>

	<?php if (has_permission('Bonfire.Logs.Manage')) : ?>
	<!-- Purge? -->
	<div class="admin-box">
		<h3><?php echo lang('logs_delete_file'); ?></h3>

		<br/>

		<div class="alert alert-warning fade in">
			<a class="close" data-dismiss="alert">&times;</a>
			<?php echo sprintf(lang('logs_delete_note'),$log_file_pretty); ?>
		</div>

		<div class="form-actions">
			<a class="btn btn-danger" href="<?php echo site_url(SITE_AREA .'/developer/logs/purge/'.$log_file); ?>" onclick="return confirm('<?php echo lang('logs_delete_confirm'); ?>')"><i class="icon-trash icon-white">&nbsp;</i>&nbsp;<?php echo lang('logs_action_delete_this_file'); ?></a>
		</div>
	</div>
	<?php endif; ?>

<?php endif; ?>