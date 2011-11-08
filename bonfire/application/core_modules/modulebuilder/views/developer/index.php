<div class="view split-view">
	
	<!-- Role List -->
	<div class="view">	
	<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>
		<div class="scrollable">
			<div class="list-view" id="module-list">
				<?php foreach ($modules as $module => $config) : ?>
					<div class="list-item with-icon" data-id="<?php echo $config['name'] ?>">
						<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
						<p>
							<b><?php echo $config['name'] ?></b><br/>
							<span class="small"><?php echo isset($config['description']) ? $config['description'] : lang('mb_generic_description'); ?></span>
						</p>
					</div>
				<?php endforeach; ?>
			</div>	<!-- /list-view -->
		</div>
	
	<?php else: ?>
	
		<div class="notification attention">
			<p><?php echo lang('mb_no_modules'); ?></p>
		</div>
	
	<?php endif; ?>
	</div>
	
	<!-- Role Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
				
				<div class="box create rounded">
					<?php if ($writeable): ?>
					<a class="button good" href="<?php echo site_url(SITE_AREA .'/developer/modulebuilder/create'); ?>"><?php echo lang('mb_create_button'); ?></a>
					<?php endif;?>
					
					<h3><?php echo ucwords(lang('mb_create_button')); ?></h3>
					
					<p><?php echo lang('mb_create_note'); ?></p>
				</div>	
				
					<?php if (!$writeable): ?>
					<div class="notification error">
						<p><?php echo lang('mb_not_writeable_note'); ?></p>
					</div>
					<?php endif;?>
				
				<br/>

				<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>
				
					<h2><?php echo lang('mb_installed_head'); ?></h2>
				
					<table>
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
								<td><?php echo anchor(SITE_AREA .'/developer/modulebuilder/delete/'. $config['name'], lang('mb_delete'),'class="confirm_delete" title="'.$config['name'].'"') ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
				
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>
