<p class="intro"><?php e(lang('permissions_intro')) ?></p>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo $this->dataset->table_open(); ?>
	
	<?php if (isset($results) && is_array($results) && count($results)) : ?>
	
			<?php foreach ($results as $record) : ?>
			<?php 	$record = (array)$record; ?>
			<tr>
				<td>
					<input type="checkbox" name="checked[]" value="<?php echo $record['permission_id'] ?>" />
				</td>
				<td><?php e($record['permission_id']) ?></td>
				<td>
					<a href="<?php echo site_url(SITE_AREA .'/settings/permissions/edit/'. $record['permission_id']) ?>">
						<?php echo $record['name'] ?>
					</a>
				</td>
				<td><?php e($record['description']) ?></td>
				<td><?php e(ucfirst($record['status'])) ?></td>
			</tr>
			<?php endforeach; ?>
	
	<?php else: ?>
	
		<tr>
			<td colspan="6">No permissions found.</td>
		</tr>
	
	<?php endif; ?>
	
	<?php echo $this->dataset->table_close(); ?>

</div>


