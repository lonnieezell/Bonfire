<?php if (isset($domains) && is_array($domains) && count($domains)) : ?>

	<?php foreach ($domains as $domain_name => $fields) : ?>
	<table class="matrix table table-striped">
		<thead>
			<tr>
				<th style="width: 250px">
					<b style="color: #222">
						<?php echo (array_key_exists(strtolower($domain_name), $modules_descriptions)) ? $modules_descriptions[strtolower($domain_name)]['display_name'] : $domain_name; ?>
					</b>
				</th>
				<?php $index = 0; ?>
				<?php foreach ($fields['actions'] as $action) : ?>
					<th class="text-center" cellIndex="<?php echo $index; ?>">
						<a href="#"><?php echo lang('roles_matrix_action_'.strtolower($action)); ?></a>
					</th>
					<?php ++$index; ?>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($fields as $field_name => $field_actions) : ?>
				<?php if ($field_name != 'actions') : ?>
				<tr>
					<td class="matrix-title">
						<b>
							<?php if (isset($field_actions['Description'])) : ?>
								<a href="#" title="<?php echo $field_actions['Description']['title']; ?>">
								<?php echo $field_actions['Description']['display_name']; ?>
								</a>
							<?php else: ?>
								<a href="#">
								<?php if (in_array(strtolower($field_name), $this->config->item('contexts'))) : ?>
									<?php echo lang('bf_context_'.strtolower($field_name)); ?>
								<?php else: ?>
									<?php echo (lang('roles_matrix_'.strtolower($domain_name).'_'.strtolower($field_name))) ? lang('roles_matrix_'.strtolower($domain_name).'_'.strtolower($field_name)) : $field_name; ?>
								<?php endif; ?>
								</a>
							<?php endif; ?>
						</b>
					</td>
					<?php foreach ($fields['actions'] as $action) : ?>
						<td class="text-center">
							<?php if (array_key_exists($action, $field_actions)) : ?>
								<?php
									$perm_name = $domain_name .'.'. $field_name .'.'. $action;
								?>
								<input type="checkbox" name="role_permissions[]" class="" value="<?php echo $domains[$domain_name][$field_name][$action]['perm_id']; ?>"
								<?php
									if (isset($domains[$domain_name][$field_name][$action]['value']) && $domains[$domain_name][$field_name][$action]['value'] == 1)
									{
										echo 'checked="checked"';
									}
								?>
								/>
							<?php else: ?>
								<span class="help-inline small"><?php echo lang('roles_not_used'); ?></span>
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