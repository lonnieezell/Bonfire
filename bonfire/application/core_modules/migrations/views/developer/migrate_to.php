<div class="notification attention">
	<p><?php echo lang('mig_migrate_note'); ?></p>
</div>

<!-- Migration Confirmation -->
<h2><?php echo lang('mig_migrate_to'); ?> <?php echo $latest_version ?>?</h2>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>

	<p>
		<?php echo lang('mig_choose_migration'); ?> 
		<select name="migration">
		<?php foreach ($migrations as $migration) :?>
			<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
		<?php endforeach; ?>
		</select>
	</p>

	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('mig_migrate_button'); ?>" /> or <?php echo anchor(SITE_AREA .'/developer/migrations', lang('bf_action_cancel')); ?>
	</div>
<?php echo form_close(); ?>