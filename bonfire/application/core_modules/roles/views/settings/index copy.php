<?php echo $this->load->view('settings/sub_nav', null, true); ?>

<br/>
<p>Every user in your site is assigned to at least one role. Roles determine what the users are allowed to do.</p>

<?php if (isset($roles) && is_array($roles) && count($roles)) : ?>
<?php echo form_open('admin/settings/roles/do_action', 'id="action_form"'); ?>
	<table cellspacing="0">
		<thead>
			<tr>
				<th style="width: 2em">
					<input type="checkbox" name="select_all" id="select_all" />
				</th>
				<th>Role</th>
				<th>Description</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					With Selected: 
					<select name="action" id="action-select">
						<option value="0">-----</option>
						<option>Delete</option>
						<option>Set Default</option>
					</select>
					
					<input type="submit" name="submit" value="Apply" style="width: auto;" />
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($roles as $role) : ?>
			<tr>
				<td>
				<?php if ($role->can_delete == 1) : ?>
					<input type="checkbox" name="actionable[]" value="<?php echo $role->role_id ?>" />
				<?php endif; ?>
				</td>
				<td>
					<?php echo anchor('admin/settings/roles/edit/'. $role->role_id, $role->role_name) ?>
					<?php echo $role->default == 1 ? '<span style="color: green">(default)</span>' : ''; ?>
				</td>
				<td><?php echo $role->description ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo form_close(); ?>

<?php else: ?>

<div class="notification attention">
	<p>There aren't any roles in the system. <?php echo anchor('admin/settings/roles/create', 'Create a new role.') ?></p>
</div>

<?php endif; ?>

<script>
head.ready(function() {
	// Select All Toggle
	$('#select_all').click(function() {
		var is_checked = $(this).attr('checked');
	
		$('#action_form input[type=checkbox]').attr('checked', is_checked);
	});
});
</script>
