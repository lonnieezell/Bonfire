<?php if (isset($domains) && is_array($domains) && count($domains)) : ?>

	<?php foreach ($domains as $domain_name => $fields) : ?>
	<table class="matrix table table-striped">
		<thead>
			<tr>
				<th style="width: 150px"><b style="color: #222"><?php echo $domain_name ?></b></th>
				<?php foreach ($fields['actions'] as $action) : ?>
					<th class="text-center">
						<a href="#"><?php echo $action ?></a>
					</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($fields as $field_name => $field_actions) : ?>
				<?php if ($field_name != 'actions') : ?>
				<tr>
					<td class="matrix-title"><b><a href="#"><?php echo $field_name ?></a></b></td>
					<?php foreach ($fields['actions'] as $action) : ?>
						<td class="text-center">
							<?php if (array_key_exists($action, $field_actions)) : ?>
								<?php
									$perm_name = $domain_name .'.'. $field_name .'.'. $action;
								?>
								<input type="checkbox" name="role_permissions[]" class="" value="<?php echo $domains[$domain_name][$field_name][$action]['perm_id'] ?>"
								<?php
									if (isset($domains[$domain_name][$field_name][$action]['value']) && $domains[$domain_name][$field_name][$action]['value'] == 1)
									{
										echo 'checked="checked"';
									}
								?>
								/>
							<?php else: ?>
								<span class="help-inline small"><?php echo lang('role_not_used') ?></span>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<br/>
	<?php endforeach; ?>

	<?php else: ?>

	<div class="notification attention"><?php echo $authentication_failed; ?></div>

<?php endif; ?>
