<p class="intro"><?php e(lang('permissions_intro')) ?></p>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>
	<?php echo form_open($this->uri->uri_string()); ?>

		<table class="table table-striped">
			<thead>
				<tr>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<th><?php echo lang('permissions_id'); ?></th>
					<th><?php echo lang('permissions_name'); ?></th>
					<th><?php echo lang('permissions_description'); ?></th>
					<th><?php echo lang('permissions_status'); ?></th>
				</tr>
			</thead>

			<tfoot>
				<?php if (isset($results) && is_array($results) && count($results)) : ?>
				<tr>
					<td colspan="5">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="delete" class="btn btn-danger" id="delete-me" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php echo lang('permissions_delete_confirm'); ?>')">
					</td>
				</tr>
				<?php endif;?>
			</tfoot>

			<tbody>
	<?php if (isset($results) && is_array($results) && count($results)) : ?>

			<?php foreach ($results as $record) : ?>
			<?php 	$record = (array)$record; ?>
				<tr>
					<td>
						<input type="checkbox" name="checked[]" value="<?php echo $record['permission_id'] ?>" />
					</td>
					<td><?php echo $record['permission_id'] ?></td>
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
			</tbody>
		</table>
	</form>

	<?php echo $this->pagination->create_links(); ?>

</div>


