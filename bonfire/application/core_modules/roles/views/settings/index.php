<div class="v-split">
	
	<!-- Role List -->
	<div class="vertical-panel">
	
	<?php if (isset($roles) && is_array($roles) && count($roles)) : ?>
		<div class="scrollable">
			<div class="list-view" id="role-list">
				<?php foreach ($roles as $role) : ?>
					<div class="list-item" data-id="<?php echo $role->role_id ?>">
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
		<p>There aren't any roles in the system. <?php echo anchor('admin/settings/roles/create', 'Create a new role.') ?></p>
	</div>
	
	<?php endif; ?>
	</div>
	
	<!-- Role Editor -->
	<div id="content">
		<div class="scrollable" id="ajax-content">
			<div class="inner">
				
				<div class="box create rounded">
					<a class="button good ajaxify" href="<?php echo site_url('admin/settings/roles/create'); ?>">Create New Role</a>
				
					<h3>Create A New Role</h3>
					
					<p>Every user needs a role. Make sure you have all that you need.</p>
				</div>	
				
				
				<br/>
				
				<?php if (isset($role_counts) && is_array($role_counts) && count($role_counts)) : ?>
				
					<h2>Account Distribution</h2>
					
					<table cellspacing="0">
						<thead>
							<tr>
								<th>Account Type</th>
								<th class="text-center"># Users</th>
								<th class="text-center">% Users</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($roles as $role) : ?>
							<tr>
								<td><?php echo anchor('admin/settings/roles/edit/'. $role->role_id, $role->role_name, 'class="ajaxify"') ?></td>
								<td align="center"><?php
										$count = 0; 
										foreach ($role_counts as $r)
										{
											if ($role->role_name == $r->role_name)
											{
												$count = $r->count;
											}						
										}
										
										echo $count;
									?>
								</td>
								<td align="center"><?php echo $count ? (($count / $total_users) * 100) .'%' : '--'; ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				
				<?php endif; ?>
				
			</div>	<!-- /inner -->
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>
