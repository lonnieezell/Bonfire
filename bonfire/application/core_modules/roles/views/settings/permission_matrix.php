
<div class="view split-view">
	
	<!-- Role List -->
	<div class="view">
		
		<div class="panel-header list-search">
			<a href="<?php echo site_url(SITE_AREA .'/settings/roles/permission_matrix'); ?>"><?php echo lang('matrix_header');?></a>
			<?php render_search_box(); ?>
		</div>
		
	<?php if (isset($roles) && is_array($roles) && count($roles)) : ?>
		<div class="scrollable">
			<div class="list-view" id="role-list">
				<?php foreach ($roles as $role) : ?>
					<div class="list-item with-icon" data-id="<?php echo $role->role_id ?>">
						<img src="<?php echo Template::theme_url('images/customers.png') ?>" />
					
						<p>
							<b><?php echo $role->role_name ?></b><br/>
							<span class="small"><?php echo $role->description ?></span>
						</p>
					</div>
				<?php endforeach; ?>
			</div>	<!-- /list-view -->
		</div>
	
	<?php else: ?>
	
	<div class="notification attention">
		<p><?php echo lang('role_no_roles'); ?> <?php echo anchor(SITE_AREA .'/settings/roles/create', lang('role_create_button'), array("class" => "ajaxify")) ?></p>
	</div>
	
	<?php endif; ?>
	</div>
	
	<!-- Role Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
		
		<h2><?php echo lang('matrix_header');?></h2>
		
		<table id="permission_table">
			<thead>
				<tr>
					<td id="permission_table_result" class="notification information" colspan="<?php echo count($matrix_roles) + 1; ?>"><?php echo lang('matrix_note');?></td>
				</tr>
				<tr>
					<th><?php echo lang('matrix_permission');?></th>
					<?php foreach($matrix_roles as $matrix_role ) : ?>
						<?php $matrix_role = (array)$matrix_role; ?>
						<?php if (has_permission('Permissions.'.$matrix_role['role_name'].'.Manage')) : ?>
							<th><?php echo $matrix_role['role_name']; ?></th>
						<?php endif; ?>
						<?php $cols[] = array('role_id' => $matrix_role['role_id'], 'role_name' => $matrix_role['role_name']); ?>
						<?php endforeach; ?>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach($matrix_permissions as $matrix_perm ) : ?>
				<?php $matrix_perm = (array)$matrix_perm; ?>
				<?php if (has_permission($matrix_perm['name'])) : ?>
				<tr title="<?php echo $matrix_perm['name']; ?>">
					<td><?php echo $matrix_perm['name']; ?></td>
					<?php
					for($i=0;$i<count($cols);$i++) :
						if (has_permission('Permissions.'.$cols[$i]['role_name'].'.Manage')) : 
							$checkbox_value = $cols[$i]['role_id'].','.$matrix_perm['permission_id'];
							$checked = in_array($checkbox_value, $matrix_role_permissions) ? ' checked="checked"' : '';
					 ?>
						<td title="<?php echo $cols[$i]['role_name']; ?>"><input type="checkbox" value="<?php echo $checkbox_value; ?>"<?php echo $checked; ?> title="<?php echo lang('matrix_role');?>: <?php echo $cols[$i]['role_name']; ?>, <?php echo lang('matrix_permission');?>: <?php echo $matrix_perm['name']; ?>" /></td>
						<?php endif; ?>
					<?php endfor; ?>			
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>