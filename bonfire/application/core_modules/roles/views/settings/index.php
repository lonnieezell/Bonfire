
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
				
				<div class="box create rounded">
					<a class="button good ajaxify" href="<?php echo site_url(SITE_AREA .'/settings/roles/create'); ?>"><?php echo lang('role_create_button'); ?></a>
				
					<h3><?php echo ucwords(lang('role_create_button')); ?></h3>
					
					<p><?php echo lang('role_create_note'); ?></p>
				</div>	
				
				
				<br/>
				
				<?php if (isset($role_counts) && is_array($role_counts) && count($role_counts)) : ?>
				
					<h2><?php echo lang('role_distribution'); ?></h2>
					
					<table cellspacing="0">
						<thead>
							<tr>
								<th><?php echo lang('role_account_type'); ?></th>
								<th class="text-center"># <?php echo lang('bf_users'); ?></th>
								<th class="text-center">% <?php echo lang('bf_users'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($roles as $role) : ?>
							<tr>
								<td><?php echo anchor(SITE_AREA .'/settings/roles/edit/'. $role->role_id, $role->role_name, 'class="ajaxify"') ?></td>
								<td class="text-center"><?php
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
								<td class="text-center"><?php echo $count && $total_users ? number_format(($count / $total_users) * 100, 2) .'%' : '--'; ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				
				<?php endif; ?>
				
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>
