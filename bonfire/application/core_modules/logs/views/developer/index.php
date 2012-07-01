<?php if ($log_threshold == 0) : ?>
	<div class="alert alert-warning fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php e(lang('logs_not_enabled')); ?>
	</div>
<?php endif; ?>

<p class="intro"><?php echo lang('logs_intro'); ?></p>

<?php if (isset($logs) && is_array($logs) && count($logs) && count($logs) > 1) : ?>

<div class="admin-box">
	<h3><?php echo $toolbar_title; ?></h3>
	<?php echo form_open(); ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th style="width: 15em;"><?php echo lang('logs_date'); ?></th>
				<th><?php echo lang('logs_file'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?php echo lang('bf_with_selected'); ?>:
					<input type="submit" name="submit" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>"  onclick="return confirm('<?php echo lang('logs_delete_confirm'); ?>')"/>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($logs as $log) :?>
			<?php if ($log != 'index.html') : ?>
			<tr>
				<td class="column-check">
					<input type="checkbox" value="<?php echo $log; ?>" name="checked[]" />
				</td>
				<td>
					<a href="<?php echo site_url(SITE_AREA .'/developer/logs/view/'. $log); ?>">
						<b><?php echo date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $log)))); ?></b></td>
					</a>
				<td><?php echo $log; ?></td>
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
		<h3><?php echo lang('logs_delete_files'); ?></h3>

		<br/>

		<div class="alert alert-warning fade in">
			<a class="close" data-dismiss="alert">&times;</a>
			<?php echo lang('logs_delete_note'); ?>
		</div>

		<div class="form-actions">
			<a class="btn btn-danger" href="<?php echo site_url(SITE_AREA .'/developer/logs/purge/'); ?>" onclick="return confirm('<?php echo lang('logs_delete_all_confirm'); ?>')"><i class="icon-white icon-trash">&nbsp;</i>&nbsp;<?php echo lang('logs_action_delete_files'); ?></a>
		</div>
	</div>
<?php else : ?>

	<div class="alert alert-info fade in notification ">
		<a class="close" data-dismiss="alert">&times;</a>
		<p><?php echo lang('logs_no_logs'); ?></p>
	</div>
<?php endif; ?>