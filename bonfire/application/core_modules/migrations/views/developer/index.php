<br/>
<p><?php echo lang('mig_intro'); ?></p>

<?php if ($this->config->item('migrations_enabled') === false) :?>

	<div class="notification attention">
		<p><?php echo lang('mig_not_enabled'); ?></p>
	</div>

<?php else : ?>

	<br />
	<p><b><?php echo lang('mig_installed_version'); ?></b> <?php echo $installed_version; ?></p>
	<p><b><?php echo lang('mig_latest_version'); ?></b> <?php echo $latest_version ?></p>
	
	<br />
	<?php if ($latest_version > $installed_version) : ?>
	<div class="notification attention">
		<p><?php echo lang('mig_db_not_current'); ?> <?php echo anchor('admin/developer/migrations/migrate_to/'. $latest_version, lang('mig_install_latest')); ?></p>
	</div>
	<?php endif; ?>

<?php endif; ?>

