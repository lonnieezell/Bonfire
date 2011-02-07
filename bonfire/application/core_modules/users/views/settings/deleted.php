<h2>Deleted Users</h2>

<div class="text-right">
	<?php echo anchor('admin/settings/users', 'Return to User Management'); ?>
</div>

<?php if (isset($users) && is_array($users) && count($users)) : ?>

	<table cellspacing="0">
		<thead>
			<tr>
				<th style="width: 3em"></th>
				<th style="width: 33%">Email</th>
				<th style="width: 33%">Username</th>
				<th class="text-right">Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user) : ?>
			<tr>
				<td></td>
				<td><?php echo $user->email ?></td>
				<td><?php echo $user->username ? $user->username : '--' ?></td>
				<td class="text-right">
					<?php echo anchor('admin/settings/users/purge/'. $user->id, 'Purge', 'class="ajaxify"') ?> | 
					<?php echo anchor('admin/settings/users/restore/'. $user->id, 'Restore', 'class="ajaxify"') ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<br/><br/>

	<div class="box delete rounded">
		<a class="button delete ajaxify" href="<?php echo site_url('admin/settings/users/create'); ?>">Purge Deleted Accounts</a>
	
		<h3>Purge Deleted Accounts</h3>
		
		<p>Purging deleted accounts is a permanent action. There is no going back, so please make sure.</p>
	</div>	

<?php else : ?>
<div class="notification information">
	<p>There are not any deleted users in the database. <?php echo anchor('admin/settings/users', 'Go Back') ?></p>
</div>
<?php endif; ?>