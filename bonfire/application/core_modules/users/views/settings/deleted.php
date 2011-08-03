<h2><?php echo lang('us_deleted_users'); ?></h2>

<div class="text-right">
	<?php echo anchor(SITE_AREA .'/settings/users', 'Return to User Management'); ?>
</div>

<?php if (isset($users) && is_array($users) && count($users)) : ?>

	<table cellspacing="0">
		<thead>
			<tr>
				<th style="width: 3em"></th>
				<th style="width: 33%"><?php echo lang('bf_email'); ?></th>
				<th style="width: 33%"><?php echo lang('bf_username'); ?></th>
				<th class="text-right"><?php echo lang('bf_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user) : ?>
			<tr>
				<td></td>
				<td><?php echo $user->email ?></td>
				<td><?php echo $user->username ? $user->username : '--' ?></td>
				<td class="text-right">
					<?php echo anchor(SITE_AREA .'/settings/users/purge/'. $user->id, lang('bf_action_purge'), 'class="ajaxify"') ?> | 
					<?php echo anchor(SITE_AREA .'/settings/users/restore/'. $user->id, lang('bf_action_restore'), 'class="ajaxify"') ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<br/><br/>

	<div class="box delete rounded">
		<a class="button delete ajaxify" href="<?php echo site_url(SITE_AREA .'/settings/users/purge'); ?>"><?php echo lang('us_purge_del_accounts'); ?></a>
	
		<?php echo lang('us_purge_del_note'); ?>
	</div>	

<?php else : ?>
<div class="notification information">
	<p><?php echo lang('us_no_deleted'); ?> <?php echo anchor(SITE_AREA .'/settings/users', lang('bf_go_back')) ?></p>
</div>
<?php endif; ?>