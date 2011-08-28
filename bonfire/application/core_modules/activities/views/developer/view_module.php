<div class="view split-view">
	<!-- Module List -->
	<div class="view">	
		
		<div class="panel-header list-search">			
			<strong>Users</strong> <?php render_search_box(); ?>
		</div>
		<?php if (isset($modules) && is_array($modules)) : ?>
		
		<div class="scrollable">
			<div class="list-view" id="module-list">
			<?php foreach ($modules as $module) : ?>
				<div class="list-item with-icon" data-id="<?php echo $module; ?>">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					<p>
						<b><?php echo $module; ?></b><br/>
						<span><?php echo $module; ?></span>
					</p>
				</div>
			<?php endforeach; ?>
			</div>	<!-- /list -->
		</div>
		
		<?php else : ?>
		
			<div class="notification information">
				<p><?php echo lang('no_users'); ?></p>
			</div>
		
		<?php endif; ?>
	</div>	<!-- /module-list -->
	
	<div class="scrollable" id="ajax-content">
		<h2><?php echo sprintf(lang('activity_view_module'), $activity_module['name']); ?></h2>
		
		<?php if (!isset($activity_content) || empty($activity_content)) : ?>
		<div class="notification attention">
			<p><?php echo lang('activity_not_found'); ?></p>
		</div>
		<?php else : ?>
		
		<div id="module_activities">
			<table>
				<thead>
					<tr>
						<th><?php echo lang('activity_user'); ?></th>
						<th><?php echo lang('activity_activity'); ?></th>
						<th><?php echo lang('activity_when'); ?></th>
					</tr>
				</thead>
				
				<tfoot></tfoot>
				
				<tbody>
					<?php foreach ($activity_content as $activity) : ?>
					<tr>
						<td><?php echo $activity->username; ?></td>
						<td><?php echo $activity->activity; ?></td>
						<td><?php echo $activity->created_on; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>