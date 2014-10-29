<style>
.filter-link-list a.active { text-decoration: underline; cursor: default; }
.user-table th.id { width: 3em; }
.user-table th.last-login { width: 11em; }
.user-table th.status { width: 10em; }
</style>
<div class="well shallow-well">
	<span class="filter-link-list">
		<?php
        // If there's a current filter, add a clear button.
        if ($filter_type == 'first_letter') {
			echo anchor($index_url, lang('bf_clear'), array('class' => 'btn btn-small btn-primary'));
        }
			e(lang('us_filter_first_letter'));

        $filterLetter = ! empty($first_letter);

		$letters = range('A', 'Z');
        foreach ($letters as $letter) {
            echo anchor(
                "{$index_url}first_letter-{$letter}",
                $letter,
                array('class' => $filterLetter && $letter == $first_letter ? 'active' : '')
            ) . PHP_EOL;
        }
		?>
	</span>
</div>
<ul class="nav nav-tabs">
	<li<?php echo $filter_type == 'all' ? ' class="active"' : ''; ?>><?php echo anchor($index_url, lang('us_tab_all')); ?></li>
    <li<?php echo $filter_type == 'inactive' ? ' class="active"' : ''; ?>><?php echo anchor("{$index_url}inactive/", lang('us_tab_inactive')); ?></li>
    <li<?php echo $filter_type == 'banned' ? ' class="active"' : ''; ?>><?php echo anchor("{$index_url}banned/", lang('us_tab_banned')); ?></li>
    <li<?php echo $filter_type == 'deleted' ? ' class="active"' : ''; ?>><?php echo anchor("{$index_url}deleted/", lang('us_tab_deleted')); ?></li>
	<li class="<?php echo $filter_type == 'role_id' ? 'active ' : ''; ?>dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<?php
			echo lang('us_tab_roles');
            echo isset($filter_role) ? ": {$filter_role}" : '';
			?>
			<span class="caret light-caret"></span>
		</a>
		<ul class="dropdown-menu">
			<?php foreach ($roles as $role) : ?>
            <li><?php echo anchor("{$index_url}role_id-{$role->role_id}", $role->role_name); ?></li>
			<?php endforeach; ?>
		</ul>
	</li>
</ul>
<?php if (empty($users) || ! is_array($users)) : ?>
<p><?php echo lang('us_no_users'); ?></p>
<?php
else :
    $numColumns = 8;
    echo form_open();
?>
    <table class="table table-striped user-table">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th class='id'><?php echo lang('bf_id'); ?></th>
				<th><?php echo lang('bf_username'); ?></th>
				<th><?php echo lang('bf_display_name'); ?></th>
				<th><?php echo lang('bf_email'); ?></th>
				<th><?php echo lang('us_role'); ?></th>
				<th class='last-login'><?php echo lang('us_last_login'); ?></th>
				<th class='status'><?php echo lang('us_status'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
                <td colspan="<?php echo $numColumns; ?>">
					<?php
					echo lang('bf_with_selected');

					if ($filter_type == 'deleted') :
					?>
					<input type="submit" name="restore" class="btn" value="<?php echo lang('bf_action_restore'); ?>" />
					<input type="submit" name="purge" class="btn btn-danger" value="<?php echo lang('bf_action_purge'); ?>" onclick="return confirm('<?php e(js_escape(lang('us_purge_del_confirm'))); ?>')" />
					<?php else : ?>
					<input type="submit" name="activate" class="btn" value="<?php echo lang('bf_action_activate'); ?>" />
					<input type="submit" name="deactivate" class="btn" value="<?php echo lang('bf_action_deactivate'); ?>" />
					<input type="submit" name="ban" class="btn" value="<?php echo lang('bf_action_ban'); ?>" />
					<input type="submit" name="delete" class="btn btn-danger" id="delete-me" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('us_delete_account_confirm'))); ?>')" />
					<?php endif;?>
				</td>
			</tr>
		</tfoot>
		<tbody>
            <?php foreach ($users as $user) : ?>
			<tr>
				<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $user->id; ?>" /></td>
				<td class='id'><?php echo $user->id; ?></td>
				<td><?php
                    echo anchor(site_url(SITE_AREA . "/settings/users/edit/{$user->id}"), $user->username);

					if ($user->banned) :
					?>
                    <span class="label label-warning"><?php echo lang('us_tab_banned'); ?></span>
					<?php endif; ?>
				</td>
				<td><?php echo $user->display_name; ?></td>
				<td><?php echo $user->email ? mailto($user->email) : ''; ?></td>
				<td><?php echo $roles[$user->role_id]->role_name; ?></td>
				<td class='last-login'><?php echo $user->last_login != '0000-00-00 00:00:00' ? date('M j, y g:i A', strtotime($user->last_login)) : '---'; ?></td>
				<td class='status'>
					<?php if ($user->active) : ?>
					<span class="label label-success"><?php echo lang('us_active'); ?></span>
					<?php else : ?>
					<span class="label label-warning"><?php echo lang('us_inactive'); ?></span>
					<?php endif; ?>
				</td>
			</tr>
            <?php endforeach; ?>
		</tbody>
	</table>
<?php
    echo form_close();
    echo $this->pagination->create_links();
endif;
