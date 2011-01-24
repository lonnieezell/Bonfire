<?php echo $this->load->view('settings/sub_nav', null, true); ?>

<?php echo form_open('admin/settings/users', 'style="padding-bottom: 0"'); ?>

	<div>
		<p>Show only users with role 
		
			<select name="filter_by_role_id">
				<option></option>
			<?php foreach ($roles as $role) : ?>
				<option value="<?php echo $role->role_id ?>" <?php echo isset($filter) && $filter == $role->role_id ? 'selected="selected"' : '' ?>><?php echo $role->role_name ?></option>
			<?php endforeach; ?>
			</select>
			
			<input type="submit" name="filter_submit" value="Go" />
		
		</p>
	</div>
<?php echo form_close(); ?>

<?php if (isset($users) && is_array($users)) : ?>
<?php echo form_open('admin/settings/users/do_action', 'id="action_form"'); ?>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th style="width: 2em">
					<input type="checkbox" name="select_all" id="select_all" />
				</th>
				<th>Name</th>
				<th>Email</th>
				<th>Role</th>
				<th style="width: 6em">Last Login</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					With Selected: 
					<select name="action">
						<option value="0">-----</option>
						<option>Delete</option>
						<option>Ban</option>
					</select>
					
					<input type="submit" name="submit" value="Go" style="width: auto;" />
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($users as $user) : ?>
			<tr>
				<td>
					<input type="checkbox" name="actionable[]" value="<?php echo $user->id ?>" />
				</td>
				<td><?php echo !empty($user->first_name) ? $user->first_name .' '. $user->last_name : ' -- '; ?></td>
				<td><?php echo anchor('admin/settings/users/edit/'. $user->id, $user->email) ?></td>
				<td>
					<?php 
					foreach ($roles as $role)
					{
						if ($role->role_id == $user->role_id)
						{
							echo $role->role_name;
							break;
						}
					}
					?>
				</td>
				<td>
					<?php echo isset($user->last_login) && $user->last_login != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($user->last_login)) : 'N/A' ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo form_close(); ?>
	
	<?php echo $this->pagination->create_links(); ?>

<?php else : ?>

	<div class="notification information">
		<p>No users found.</p>
	</div>

<?php endif; ?>

<script>
head.ready(function() {
	$('#select_all').click(function() {
		var is_checked = $(this).attr('checked');
	
		$('#action_form input[type=checkbox]').attr('checked', is_checked);
	});
});
</script>