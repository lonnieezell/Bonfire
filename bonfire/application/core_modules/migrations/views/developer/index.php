<br/>
<p><?php echo lang('mig_intro'); ?></p>

<?php if ($this->config->item('migrations_enabled') === false) :?>

	<div class="notification attention">
		<p><?php echo lang('mig_not_enabled'); ?></p>
	</div>

<?php else : ?>

	<!-- Application Migrations -->
	<h2><?php echo lang('mig_app_migrations'); ?></h2>
		
	<div class="notification information">
		<p><?php echo lang('mig_installed_version'); ?> <b><?php echo $installed_version; ?></b> / 
		<?php echo lang('mig_latest_version'); ?> <b><?php echo $latest_version ?></b></p>
	</div>
		
	<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
		<input type="hidden" name="core_only" value="0" />
	
		<?php if (count($app_migrations)) : ?>
		<p>
			<?php echo lang('mig_choose_migration'); ?> 
			<select name="migration">
			<?php foreach ($app_migrations as $migration) :?>
				<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
			<?php endforeach; ?>
			</select>
		</p>
	
		<div class="submits">
			<input type="submit" name="submit" value="<?php echo lang('mig_migrate_button'); ?>" /> or <?php echo anchor('admin/developer/migrations', lang('bf_action_cancel')); ?>
		</div>
		<?php else: ?>
			<p><?php echo lang('mig_no_migrations') ?></p>
		<?php endif; ?>
	<?php echo form_close(); ?>
	
	
	<!-- Core Migrations -->
	<h2><?php echo lang('mig_core_migrations'); ?></h2>
		
	<div class="notification information">
		<p><?php echo lang('mig_installed_version'); ?> <b><?php echo $core_installed_version; ?></b> / 
		<?php echo lang('mig_latest_version'); ?> <b><?php echo $core_latest_version ?></b></p>
	</div>
	
	<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
		<input type="hidden" name="core_only" value="1" />
	
		<?php if (count($core_migrations)) : ?>
		<p>
			<?php echo lang('mig_choose_migration'); ?> 
			<select name="migration">
			<?php foreach ($core_migrations as $migration) :?>
				<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
			<?php endforeach; ?>
			</select>
		</p>
	
		<div class="submits">
			<input type="submit" name="submit" value="<?php echo lang('mig_migrate_button'); ?>" /> or <?php echo anchor('admin/developer/migrations', lang('bf_action_cancel')); ?>
		</div>
		<?php else: ?>
			<p><?php echo lang('mig_no_migrations') ?></p>
		<?php endif; ?>
	<?php echo form_close(); ?>

<?php endif; ?>