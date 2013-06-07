<style>
th.date { width: 15em; }
td.date a { font-weight: bold; }
</style>
<?php if ($log_threshold == 0) : ?>
<div class="alert alert-warning fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<?php e(lang('log_not_enabled')); ?>
</div>
<?php
endif;

if (isset($logs) && is_array($logs) && count($logs) && count($logs) > 1) :
?>
<div class="admin-box">
	<?php echo form_open(); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<th class='date'><?php e(lang('log_date')); ?></th>
					<th><?php e(lang('log_file')); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">
						<?php echo lang('bf_with_selected'); ?>:
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('logs_delete_confirm'))); ?>')" />
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				foreach ($logs as $log) :
					if ($log != 'index.html') :
				?>
				<tr>
					<td class="column-check">
						<input type="checkbox" value="<?php e($log); ?>" name="checked[]" />
					</td>
					<td class='date'>
						<a href="<?php e(site_url(SITE_AREA . '/developer/logs/view/' . $log)); ?>">
							<?php e(date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $log))))); ?>
						</a>
					</td>
					<td><?php e($log); ?></td>
				</tr>
				<?php
					endif;
				endforeach;
				?>
			</tbody>
		</table>
	<?php
		echo form_close();

		echo $this->pagination->create_links();
	?>
</div>
<!-- Purge? -->
<div class="admin-box">
	<h3><?php echo lang('log_delete_button'); ?></h3>
	<?php echo form_open(); ?>
		<div class="alert alert-warning fade in">
			<a class="close" data-dismiss="alert">&times;</a>
			<?php echo lang('log_delete_note'); ?>
		</div>

		<div class="form-actions">
			<button type="submit" name="delete_all" class="btn btn-danger" onclick="return confirm('<?php e(js_escape(lang('logs_delete_all_confirm'))); ?>')">
				<span class="icon-white icon-trash"></span>&nbsp;<?php echo lang('log_delete_button'); ?>
			</button>
		</div>
	<?php echo form_close(); ?>
</div>
<?php else : ?>
<div class="alert alert-info fade in notification">
	<a class="close" data-dismiss="alert">&times;</a>
	<p><?php echo lang('log_no_logs'); ?></p>
</div>
<?php endif; ?>