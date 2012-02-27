<?php if (!$writeable): ?>
	<div class="alert alert-error">
		<p><?php echo lang('mb_not_writeable_note'); ?></p>
	</div>
<?php endif;?>

<br/>

<div class="admin-box">
	<h3><?php echo lang('mb_exist_modules') ?></h3>

<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo lang('mb_table_name'); ?></th>
				<th><?php echo lang('mb_table_version'); ?></th>
				<th><?php echo lang('mb_table_description'); ?></th>
				<th><?php echo lang('mb_table_author'); ?></th>
				<th><?php echo lang('mb_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($modules as $module => $config) : ?>
			<tr>
				<td><?php echo $config['name'] ?></td>
				<td><?php echo isset($config['version']) ? $config['version'] : '---'; ?></td>
				<td><?php echo isset($config['description']) ? $config['description'] : '---'; ?></td>
				<td><?php echo isset($config['author']) ? $config['author'] : '---'; ?></td>
				<td>
					<a href="<?php echo site_url(SITE_AREA .'/developer/modulebuilder/delete/'. preg_replace("/[ -]/", "_", $config['name'])); ?>" onclick="return confirm('Really delete this module and all of its files?');">
						<?php echo lang('mb_delete') ?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<div class="alert alert-warning">
		<p><?php e(lang('mb_no_modules')); ?> <a href="<?php echo site_url(SITE_AREA .'/developer/modulebuilder/create') ?>"><?php e(lang('mb_create_link')); ?></a></p>
	</div>

<?php endif; ?>

</div>
