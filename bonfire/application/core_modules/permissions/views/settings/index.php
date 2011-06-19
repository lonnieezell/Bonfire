
<div class="view split-view">
	
	<!-- Role List -->
	<div class="view">
	
	<?php if (isset($records) && is_array($records) && count($records)) : ?>
		<div class="scrollable">
			<div class="list-view" id="role-list">
				<?php foreach ($records as $record) : ?>
					<?php $record = (array)$record;?>
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
		<p><?php echo lang('permissions_no_records'); ?> <?php echo anchor('admin/settings/permissions/create', lang('permissions_create_new'), array("class" => "ajaxify")) ?></p>
	</div>
	
	<?php endif; ?>
	</div>
	<!-- Role Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
				
			<div class="box create rounded">
				<a class="button good ajaxify" href="<?php echo site_url('/admin/settings/permissions/create'); ?>"><?php echo lang('permissions_create_new_button');?></a>

				<h3><?php echo lang('permissions_create_new');?></h3>

				<p><?php echo lang('permissions_create_message'); ?></p>
			</div>
			<br />
				<?php if (isset($records) && is_array($records) && count($records)) : ?>
				
					<h2>Permissions</h2>
	<table>
		<thead>
		<th>Name</th>
		<th>Description</th>
		<th>Status</th><th><?php echo lang('permissions_actions'); ?></th>
		</thead>
		<tbody>
<?php
foreach ($records as $record) : ?>
<?php $record = (array)$record;?>
			<tr>
<?php
	foreach($record as $field => $value)
	{
		if($field != "permission_id") {
?>
				<td><?php echo $value;?></td>

<?php
		}
	}
?>
				<td><?php echo anchor('admin/settings/permissions/edit/'. $record['permission_id'], 'Edit', 'class="ajaxify"') ?></td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
				<?php endif; ?>
				
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>
