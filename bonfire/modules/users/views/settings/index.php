<div class="admin-box">
	<h3><?php echo lang('bf_users') ?></h3>

	<div class="well shallow-well">
		<span class="filter-link-list">
			<?php /* If there's a current filter, we need to replace the caption with a clear button. */ ?>
			<?php if ($filter_type=='first_letter'): ?>
				<a href="<?php echo $index_url; ?>" class="btn btn-small btn-primary"><?php echo lang('bf_clear') ?></a>
			<?php else: ?>
				<?php e(lang('us_filter_first_letter')) ?>
			<?php endif; ?>

			<?php $letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'); ?>
			<?php foreach ($letters as $letter): ?>
				<a href="<?php echo $index_url . 'first_letter-' . $letter; ?>"><?php echo $letter; ?></a>
			<?php endforeach; ?>
		</span>
	</div>

	<ul class="nav nav-tabs" >
		<li <?php echo $filter_type=='all' ? 'class="active"' : ''; ?>><a href="<?php echo $index_url; ?>"><?php echo lang('us_tab_all'); ?></a></li>
		<li <?php echo $filter_type=='inactive' ? 'class="active"' : ''; ?>><a href="<?php echo $index_url .'inactive/'; ?>"><?php echo lang('us_tab_inactive'); ?></a></li>
		<li <?php echo $filter_type=='banned' ? 'class="active"' : ''; ?>><a href="<?php echo $index_url .'banned/'; ?>"><?php echo lang('us_tab_banned'); ?></a></li>
		<li <?php echo $filter_type=='deleted' ? 'class="active"' : ''; ?>><a href="<?php echo $index_url .'deleted/'; ?>"><?php echo lang('us_tab_deleted'); ?></a></li>
		<li class="<?php echo $filter_type=='role_id' ? 'active ' : ''; ?>dropdown">
			<a href="#" class="drodown-toggle" data-toggle="dropdown">
				<?php echo lang('us_tab_roles'); ?> <?php echo isset($filter_role) ? ": $filter_role" : ''; ?>
				<b class="caret light-caret"></b>
			</a>
			<ul class="dropdown-menu">
			<?php foreach ($roles as $role) : ?>
				<li>
					<a href="<?php echo $index_url . 'role_id-' . $role->role_id; ?>">
						<?php echo $role->role_name; ?>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</li>
	</ul>

	<?php echo form_open(); ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th style="width: 3em"><?php echo lang('bf_id'); ?></th>
				<th><?php echo lang('bf_username'); ?></th>
				<th><?php echo lang('bf_display_name'); ?></th>
				<th><?php echo lang('bf_email'); ?></th>
				<th><?php echo lang('us_role'); ?></th>
				<th style="width: 11em"><?php echo lang('us_last_login'); ?></th>
				<th style="width: 10em"><?php echo lang('us_status'); ?></th>
			</tr>
		</thead>
		<?php if (isset($users) && is_array($users) && count($users)) : ?>
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo lang('bf_with_selected') ?>
					<?php if($filter_type == 'deleted'):?>
					<input type="submit" name="restore" class="btn" value="<?php echo lang('bf_action_restore') ?>">
					<input type="submit" name="purge" class="btn btn-danger" value="<?php echo lang('bf_action_purge') ?>" onclick="return confirm('<?php e(js_escape(lang('us_purge_del_confirm'))); ?>')">
					<?php else: ?>
					<input type="submit" name="activate" class="btn" value="<?php echo lang('bf_action_activate') ?>">
					<input type="submit" name="deactivate" class="btn" value="<?php echo lang('bf_action_deactivate') ?>">
					<input type="submit" name="ban" class="btn" value="<?php echo lang('bf_action_ban') ?>">
					<input type="submit" name="delete" class="btn btn-danger" id="delete-me" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php e(js_escape(lang('us_delete_account_confirm'))); ?>')">
					<?php endif;?>
				</td>
			</tr>
		</tfoot>
		<?php endif; ?>
		<tbody>

			<?php if (isset($users) && is_array($users) && count($users)) : ?>
				<?php foreach ($users as $user) : ?>
				<tr>
					<td>
						<input type="checkbox" name="checked[]" value="<?php echo $user->id ?>" />
					</td>
					<td><?php echo $user->id ?></td>
					<td>
						<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $user->id); ?>"><?php echo $user->username; ?></a>
						<?php if ($user->banned) echo '<span class="label label-warning">Banned</span>'; ?>
					</td>
					<td><?php echo $user->display_name ?></td>
					<td>
						<a href="mailto:<?php echo $user->email ?>"><?php echo $user->email ?></a>
					</td>
					<td>
						<?php echo $roles[$user->role_id]->role_name ?>
					</td>
					<td>
						<?php
							if ($user->last_login != '0000-00-00 00:00:00')
							{
								echo date('M j, y g:i A', strtotime($user->last_login));
							}
							else
							{
								echo '---';
							}
						?>
					</td>
					<td>
						<?php
						$class = '';
						switch ($user->active)
						{
							case 1:
								$class = " label-success";
								break;
							case 0:
							default:
								$class = " label-warning";
								break;

						}
						?>
						<span class="label<?php echo($class); ?>">
							<?php
							if ($user->active == 1)
							{
								echo(lang('us_active'));
							}
							else
							{
								echo(lang('us_inactive'));
							}
							?>
						</span>
					</td>
				</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="8"><?php echo lang('us_no_users'); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?php echo form_close(); ?>

	<?php echo $this->pagination->create_links(); ?>

</div>
