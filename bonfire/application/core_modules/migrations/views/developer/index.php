<br/>
<p>Migrations help you keep your database up to date and synced between development and production servers by providing a simple way to keep 'version control' on your database.</p>

<?php if ($this->config->item('migrations_enabled') === false) :?>

	<div class="notification attention">
		<p>Migrations are not enabled.</p>
	</div>

<?php else : ?>

	<br />
	<p><b>Installed Version:</b> <?php echo $installed_version; ?></p>
	<p><b>Latest Available Version:</b> <?php echo $latest_version ?></p>
	
	<br />
	<?php if ($latest_version > $installed_version) : ?>
	<div class="notification attention">
		<p>Your database is not up to date. <?php echo anchor('admin/developer/migrations/migrate_to/'. $latest_version, 'Install Latest Version'); ?></p>
	</div>
	<?php endif; ?>

<?php endif; ?>

