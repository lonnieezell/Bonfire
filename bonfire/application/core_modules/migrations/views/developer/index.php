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
		<p><?php echo lang('mig_db_not_current'); ?></p>
	</div>
	<?php endif; ?>

<?php endif; ?>



<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>

	<p><br/>
		<?php echo lang('mig_choose_migration'); ?> 
		<select name="migration">
		<?php foreach ($migrations as $migration) :?>
			<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
		<?php endforeach; ?>
		</select>
	</p>

	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('mig_migrate_button'); ?>" /> or <?php echo anchor('admin/developer/migrations', lang('bf_action_cancel')); ?>
	</div>
<?php echo form_close(); ?>

