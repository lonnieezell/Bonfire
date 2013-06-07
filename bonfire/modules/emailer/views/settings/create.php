<?php

$validation_errors = validation_errors();
$num_users_columns = 7;

?>
<style>
#email_content { width: 90%; }
</style>
<?php if ($validation_errors) : ?>
<div class="alert alert-block alert-error fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<h4 class="alert-heading">Please fix the following errors:</h4>
	<?php echo $validation_errors; ?>
</div>
<?php endif; ?>
<div class="admin-box">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Subject:</td>
					<td><input type="text" size="50" name="email_subject" id="email_subject" value="<?php if (isset($email_subject)) { e($email_subject); } ?>"></td>
				</tr>
				<tr>
					<td>Content:</td>
					<td>
						<textarea name="email_content" id="email_content" rows="15"><?php
							if (isset($email_content)) { e($email_content); }
						?></textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php echo lang('bf_users') ?></h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<th style="width: 3em"><?php echo lang('bf_id'); ?></th>
					<th><?php echo lang('bf_username'); ?></th>
					<th><?php echo lang('bf_display_name'); ?></th>
					<th><?php echo lang('bf_email'); ?></th>
					<th style="width: 11em"><?php echo lang('us_last_login'); ?></th>
					<th style="width: 10em"><?php echo lang('us_status'); ?></th>
				</tr>
			</thead>
			<?php
			if (isset($users) && is_array($users) && count($users)) :
				$has_checked = isset($checked) && is_array($checked) && count($checked);

			?>
			<tfoot>
				<tr>
					<td colspan="<?php echo $num_users_columns; ?>">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="create" class="btn btn-primary" value="<?php echo lang('em_create_email') ?>">
						<?php echo anchor(SITE_AREA . '/settings/emailer/queue', lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($users as $user) : ?>
				<tr>
					<td class='column-check'>
						<input type="checkbox" name="checked[]" value="<?php echo $user->id; ?>"<?php echo $has_checked && in_array($user->id, $checked) ? ' checked="checked"' : ''; ?> />
					</td>
					<td><?php echo $user->id; ?></td>
					<td>
						<a href="<?php echo site_url(SITE_AREA . '/settings/users/edit/' . $user->id); ?>"><?php echo $user->username; ?></a>
						<?php echo $user->banned ? '<span class="label label-warning">Banned</span>' : ''; ?>
					</td>
					<td><?php echo $user->display_name; ?></td>
					<td><?php echo empty($user->email) ? '' : mailto($user->email); ?></td>
					<td><?php echo $user->last_login != '0000-00-00 00:00:00' ? date('M j, y g:i A', strtotime($user->last_login)) : '---'; ?></td>
					<td>
					<?php if ($user->active) : ?>
						<span class="label label-success"><?php echo lang('us_active'); ?></span>
					<?php else : ?>
						<span class="label label-warning"><?php echo lang('us_inactive'); ?></span>
					<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<?php else: ?>
			<tbody>
				<tr>
					<td colspan="<?php echo $num_users_columns; ?>">No users found that match your selection.</td>
				</tr>
			</tbody>
			<?php endif; ?>
		</table>
	<?php echo form_close(); ?>
</div>