<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

<?php if (isset($backups) && is_array($backups) && count($backups) > 0) : ?>
	<?php echo form_open($this->uri->uri_string(), array('style' => 'padding: 0')); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th id="cb" class="column-check" style="width: 2em">
						<input class="check-all" type="checkbox" />
					</th>
					<th><?php echo lang('db_filename'); ?></th>
					<th style="width: 6.5em"><?php echo lang('bf_size'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">
						<?php echo lang('bf_with_selected'); ?>
						<button type="submit" name="submit" class="btn btn-danger" onclick="return confirm('<?php echo lang('db_backup_delete_confirm'); ?>')"><?php echo lang('bf_action_delete'); ?></button>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($backups as $file => $atts) :?>
				<tr class="hover-toggle">
					<td class="column-check">
						<input type="checkbox" value="<?php echo $file ?>" name="checked[]" />
					</td>
					<td>
						<?php echo $file ?>
						<div class="hover-item small">
							<?php echo anchor(SITE_AREA . '/developer/database/get_backup/' . $file, lang('bf_action_download'), ' title="' . lang('bf_download_file') . '"'); ?> | 
							<?php echo anchor(SITE_AREA . '/developer/database/restore/' . $file, lang('bf_action_restore'), ' title="' . lang('bf_restore_file') . '"'); ?>
						</div>
					</td>
					<td><?php echo round($atts['size'] / 1024 , 3) ?> KB</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		</form>

	<?php else : ?>
		<div class="notification attention">
			<p><?php echo lang('db_no_backups'); ?></p>
		</div>
	<?php endif; ?>
</div>