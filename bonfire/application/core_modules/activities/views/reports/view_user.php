<div class="scrollable" id="ajax-content">
	<h2><?php echo sprintf(lang('activity_view_user'),$activity_user->username); ?></h2>
	
	<?php if (!isset($activity_content) || empty($activity_content)) : ?>
	<div class="notification attention">
		<p><?php echo lang('activity_not_found'); ?></p>
	</div>
	<?php else : ?>
	
	<div id="user_activities">
		<table>
			<thead>
				<tr>
					<th><?php echo lang('activity_module'); ?></th>
					<th><?php echo lang('activity_activity'); ?></th>
					<th><?php echo lang('activity_when'); ?></th>
				</tr>
			</thead>
			
			<tfoot></tfoot>
			
			<tbody>
				<?php foreach ($activity_content as $activity) : ?>
				<tr>
					<td><?php echo $activity->module; ?></td>
					<td><?php echo $activity->activity; ?></td>
					<td><?php echo $activity->created_on; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php endif; ?>
</div>