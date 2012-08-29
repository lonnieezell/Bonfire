<?php if ($log_threshold == 0) : ?>
	<div class="alert alert-warning fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php e(lang('log_not_enabled')); ?>
	</div>
<?php endif; ?>

<p class="intro"><?php e(lang('log_intro'))  ?></p>

<?php if (isset($logs) && is_array($logs) && count($logs) && count($logs) > 1) : ?>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>
	<?php echo form_open(); ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th style="width: 15em;"><?php e(lang('log_date')) ?></th>
				<th><?php e(lang('log_file')) ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?php echo lang('bf_with_selected'); ?>:
					<input type="submit" name="action_delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete') ?>"  onclick="return confirm('<?php echo lang('logs_delete_confirm'); ?>')"/>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($logs as $log) :?>
			<?php if ($log != 'index.html') : ?>
			<tr>
				<td class="column-check">
					<input type="checkbox" value="<?php e($log) ?>" name="checked[]" />
				</td>
				<td>
					<a href="<?php e(site_url(SITE_AREA .'/developer/logs/view/'. $log)) ?>">
						<b><?php e(date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $log)))) ); ?></b>
					</a>
				</td>
				<td><?php e($log) ?></td>
			</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</tbody>
	</table>

	</form>

	<?php echo $this->pagination->create_links(); ?>
</div>

	<!-- Purge? -->
	<div class="admin-box">
		<h3><?php echo lang('log_delete_button'); ?></h3>
		<br/>

		<?php echo form_open(); ?>
		<div class="alert alert-warning fade in">
			<a class="close" data-dismiss="alert">&times;</a>
			<?php echo lang('log_delete_note'); ?>
		</div>

		<div class="form-actions">
			<button type="submit" name="action_delete_all" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete all log files?')"><i class="icon-white icon-trash">&nbsp;</i>&nbsp;<?php echo lang('log_delete_button'); ?></button>
		</div>
		<?php echo form_close(); ?>
	</div>
<?php else : ?>

	<div class="alert alert-info fade in notification ">
		<a class="close" data-dismiss="alert">&times;</a>
		<p><?php echo lang('log_no_logs'); ?></p>
	</div>
<?php endif; ?>



