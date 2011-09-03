<style>
.list-view .permission_set {cursor: pointer; border-bottom: 1px solid #CCCCCC; background: #F5F5F5; vertical-align: middle; }
.list-view .permission_set img { height: 10px; width: 10px; float: right; margin: 14px 14px 0 0; }
</style>


<div class="view split-view">
	
	<!-- Role List -->
	<div class="view">
	
	<?php if (isset($records) && is_array($records) && count($records)) : ?>
	
		<div class="scrollable">
			<div class="list-view" id="role-list">
				<?php foreach ($records as $record) : ?>
					<?php $record = (array)$record; ?>
					<?php $permission_pieces = explode('.',$record['name']);
					if ($permission_pieces[0] != $permission_header) : ?>
					<h4 class="permission_set pointer">
						<img src="<?php echo Template::theme_url('images/plus.png') ?>" />	
						<?php echo $permission_pieces[0];?>				
					</h4>
					<?php $permission_header = $permission_pieces[0]; ?>
					<?php endif; ?>
					
					<div class="list-item" data-id="<?php echo $record['permission_id']; ?>">
						<p>
							<b><?php echo $record['name']; ?></b><br/>
							<span class="small"><?php echo $record['description']; ?></span>
						</p>
					</div>
				<?php endforeach; ?>
			</div>	<!-- /list-view -->
		</div>
	
	<?php else: ?>
	
		<div class="notification attention">
			<p><?php echo lang('permissions_no_records'); ?> <?php echo anchor(SITE_AREA .'/settings/permissions/create', lang('permissions_create_new'), array("class" => "ajaxify")) ?></p>
		</div>
		<?php endif; ?>
		
	</div>
	
	<!-- Role Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
				
			<div class="box create rounded">
				<a class="button good ajaxify" href="<?php echo site_url(SITE_AREA .'/settings/permissions/create'); ?>"><?php echo lang('permissions_create_new_button');?></a>

				<h3><?php echo lang('permissions_create_new');?></h3>

				<p><?php echo lang('permissions_create_message'); ?></p>
			</div>
			<br />
			<?php if (isset($records) && is_array($records) && count($records)) : ?>
				
			<h2><?php echo ucwords(plural(lang('permissions_permission')));?></h2>
			<table>
				<thead>
				<th><?php echo lang('permissions_name');?></th>
				<th><?php echo lang('permissions_description');?></th>
				<th><?php echo lang('permissions_status');?></th>
				<th><?php echo lang('permissions_actions'); ?></th>
				</thead>
				<tbody>
					<?php foreach ($records as $record) : ?>
					<?php 	$record = (array)$record; ?>
					<tr><?php foreach($record as $field => $value): ?>
							<?php if ($field != "permission_id") : ?>
								<td><?php echo $value;?></td>
							<?php endif; ?>
						<?php endforeach; ?>
							
						<td><?php echo anchor(SITE_AREA .'/settings/permissions/edit/'. $record['permission_id'], 'Edit', 'class="ajaxify"') ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
				
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>
