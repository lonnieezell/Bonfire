<style>
#flex_table { margin: 0; }
#flex_table th { font-weight: bold; }
#flex_table th.sorting_desc, #flex_table th.sorting_asc { background-color: #F5F5F5;}
</style>

<div class="scrollable" id="ajax-content">
	<?php  if (count($select_options) > 2): // one for all, one for the only choice = 2 ?>
	<div class="box select rounded">
		<h3><?php echo lang('activity_filter_head'); ?></h3>
		<?php echo form_open(SITE_AREA . '/reports/activities/' . $vars['which'], 'class="constrained ajax-form"'); ?>
		<?php echo sprintf(lang('activity_filter_note'),($vars['view_which'] == ucwords(lang('activity_date')) ? 'from before':'only for'),strtolower($vars['view_which'])); ?>
		<?php echo form_dropdown("activity_select", $select_options, '','id="activity_select"'); ?>
		<?php echo form_submit('submit', lang('activity_submit')); ?>
		<?php echo form_close(); ?>
	</div>
	<?php endif; ?>

	<h2><?php echo sprintf(lang('activity_view'),($vars['view_which'] == ucwords(lang('activity_date')) ? $vars['view_which'] . ' before' : $vars['view_which']),$vars['name']); ?></h2>
	
	<?php if (!isset($activity_content) || empty($activity_content)) : ?>
	<div class="notification attention">
		<p><?php echo lang('activity_not_found'); ?></p>
	</div>
	<?php else : ?>
	
	<div id="user_activities">
		<table id="flex_table">
			<thead>
				<tr>
					<th><?php echo lang('activity_user'); ?></th>
					<th><?php echo lang('activity_activity'); ?></th>
					<th><?php echo lang('activity_module'); ?></th>
					<th><?php echo lang('activity_when'); ?></th>
				</tr>
			</thead>
			
			<tfoot></tfoot>
			
			<tbody>
				<?php foreach ($activity_content as $activity) : ?>
				<tr>
					<td><?php echo $activity->username; ?></td>
					<td><?php echo $activity->activity; ?></td>
					<td><?php echo $activity->module; ?></td>
					<td><?php echo date('M j, Y g:i A', strtotime($activity->created)); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php endif; ?>	
</div>