<?php if ( ! $writable) : ?>
<div class="alert alert-error">
	<p><?php echo lang('mb_not_writable_note'); ?></p>
</div>
<?php endif;?>
<div class="admin-box">
	<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo lang('mb_table_name'); ?></th>
				<th><?php echo lang('mb_table_version'); ?></th>
				<th><?php echo lang('mb_table_description'); ?></th>
				<th><?php echo lang('mb_table_author'); ?></th>
				<th><?php echo lang('bf_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($modules as $module => $config) : ?>
			<tr>
				<td><?php echo $config['name']; ?></td>
				<td><?php e(isset($config['version']) ? $config['version'] : '---'); ?></td>
				<td><?php e(isset($config['description']) ? $config['description'] : '---'); ?></td>
				<td><?php e(isset($config['author']) ? $config['author'] : '---'); ?></td>
				<td>
					<?php echo form_open(SITE_AREA . '/developer/builder/delete'); ?>
						<input type="hidden" name="module" value="<?php echo preg_replace("/[ -]/", "_", $config['name']); ?>">
						<input type="submit" class="btn btn-danger" onclick="return confirm('<?php e(js_escape(lang('mb_delete_confirm'))) ?>');" value="<?php e(lang('bf_action_delete')) ?>" />
					<?php echo form_close(); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<div class="alert alert-warning">
		<p><?php e(lang('mb_no_modules')); ?> <a href="<?php echo site_url(SITE_AREA .'/developer/builder/create_module') ?>"><?php e(lang('mb_create_link')); ?></a></p>
	</div>
	<?php endif; ?>
</div>