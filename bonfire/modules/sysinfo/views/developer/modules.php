<div class="admin-box">
	<h3><?php echo lang('si_installed_mods'); ?></h3>

	<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo lang('sys_mod_name'); ?></th>
					<th><?php echo lang('sys_mod_ver'); ?></th>
					<th><?php echo lang('sys_mod_desc'); ?></th>
					<th><?php echo lang('sys_mod_author'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($modules as $module => $config) : ?>
				<tr>
					<td><?php echo $config['name'] ?></td>
					<td><?php echo isset($config['version']) ? $config['version'] : '---'; ?></td>
					<td><?php echo isset($config['description']) ? $config['description'] : '---'; ?></td>
					<td><?php echo isset($config['author']) ? $config['author'] : '---'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>