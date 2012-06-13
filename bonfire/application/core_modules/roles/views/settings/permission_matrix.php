<script type="text/javascript">
	window.g_permission 	= '<?php e(lang('matrix_permission')); ?>';
	window.g_role			= '<?php e(lang('matrix_role')); ?>';
	window.g_url			= '<?php echo site_url(SITE_AREA .'/settings/roles/matrix_update') ?>';
</script>

<div id="permission_table_result" class="alert alert-info fade in">
	<a class="close" data-dismiss="alert">&times;</a>		
	<?php echo lang('matrix_note');?>
</div>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<table class="table table-striped" id="permission_table">
		<thead>
			<tr>
				<th><?php echo lang('matrix_permission');?></th>
				<?php foreach($matrix_roles as $matrix_role ) : ?>
					<?php $matrix_role = (array)$matrix_role; ?>
					<?php if (has_permission('Permissions.'.$matrix_role['role_name'].'.Manage')) : ?>
						<th  class="text-center"><?php echo $matrix_role['role_name']; ?></th>
					<?php endif; ?>
					<?php $cols[] = array('role_id' => $matrix_role['role_id'], 'role_name' => $matrix_role['role_name']); ?>
				<?php endforeach; ?>
			</tr>
		</thead>
	
		<tbody>
		<?php foreach($matrix_permissions as $matrix_perm ) : ?>
			<?php $matrix_perm = (array)$matrix_perm; ?>
			
			<?php if (has_permission($matrix_perm['name']) || $current_user->role_id == 1): //Admin?>
			<tr title="<?php echo $matrix_perm['name']; ?>">
				<td><?php echo $matrix_perm['name']; ?></td>
				<?php
				for($i=0;$i<count($cols);$i++) :
					if (has_permission('Permissions.'.$cols[$i]['role_name'].'.Manage')) :
						$checkbox_value = $cols[$i]['role_id'].','.$matrix_perm['permission_id'];
						$checked = in_array($checkbox_value, $matrix_role_permissions) ? ' checked="checked"' : '';
				 ?>
					<td class="text-center" title="<?php echo $cols[$i]['role_name']; ?>">
						<input type="checkbox" value="<?php echo $checkbox_value; ?>"<?php echo $checked; ?> title="<?php echo lang('matrix_role');?>: <?php echo $cols[$i]['role_name']; ?>, <?php echo lang('matrix_permission');?>: <?php echo $matrix_perm['name']; ?>" />
					</td>
					<?php endif; ?>
				<?php endfor; ?>
			</tr>
			<?php endif; ?>
			
		<?php endforeach; ?>
		</tbody>
	</table>

</div>
