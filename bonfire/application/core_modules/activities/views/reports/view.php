	<?php  if (count($select_options) > 2): // one for all, one for the only choice = 2 ?>
	<div class="box select admin-box">
		<h3><?php echo lang('activity_filter_head'); ?></h3>
		<?php

			echo form_open(SITE_AREA . '/reports/activities/' . $vars['which'], 'class="form-horizontal constrained ajax-form"');
			$form_help = '<span class="help-inline">' . sprintf(lang('activity_filter_note'),($vars['view_which'] == ucwords(lang('activity_date')) ? 'from before':'only for'),strtolower($vars['view_which'])) . '</span>';
			$form_data = array('name' => 'activity_select', 'id' => 'activity_select', 'class' => 'span3' );
			echo form_dropdown($form_data, $select_options, $filter, lang('activity_filter_head') , '' , $form_help);
			//echo form_dropdown("activity_select", $select_options, $filter,array('id' => 'activity_select', 'class' => 'span4' ) );
			unset ( $form_data, $form_help);
		?>
		<div class="form-actions">
			<?php
			echo form_submit('submit', lang('activity_submit'), 'class="btn btn-primary"');
			echo form_close();
			?>
		</div>

	</div>

	<br/>
	<?php endif; ?>

	<h2><?php echo sprintf(lang('activity_view'),($vars['view_which'] == ucwords(lang('activity_date')) ? $vars['view_which'] . ' before' : $vars['view_which']),$vars['name']); ?></h2>

	<?php if (!isset($activity_content) || empty($activity_content)) : ?>
	<div class="alert alert-error fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<h4 class="alert-heading"><?php echo lang('activity_not_found'); ?></h4>
	</div>
	<?php else : ?>

	<div id="user_activities">
		<table class="table table-striped table-bordered" id="flex_table">
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
					<td><i class="icon-user">&nbsp;</i>&nbsp;<?php echo $activity->username; ?></td>
					<td><?php echo $activity->activity; ?></td>
					<td><?php echo $activity->module; ?></td>
					<td><?php echo date('M j, Y g:i A', strtotime($activity->created)); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<?php echo $this->pagination->create_links(); ?>
	<?php endif; ?>
