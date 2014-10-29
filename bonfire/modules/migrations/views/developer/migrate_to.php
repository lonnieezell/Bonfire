<div class="alert alert-info fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<p><?php echo lang('migrations_migrate_note'); ?></p>
</div>
<!-- Migration Confirmation -->
<h2><?php echo lang('migrations_migrate_to'); ?> <?php echo $latest_version; ?>?</h2>
<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
	<label class='control-label' for='migration'><?php echo lang('migrations_choose_migration'); ?></label>
	<select name="migration" id='migration'>
		<?php foreach ($migrations as $migration) :?>
		<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
		<?php endforeach; ?>
	</select>
	<fieldset class="submits">
		<input class="btn btn-primary" type="submit" name="migrate" value="<?php echo lang('migrations_migrate_button'); ?>" />
        <?php echo ' ' . lang('bf_or') . ' ' . anchor(SITE_AREA . '/developer/migrations', '<span class="icon-refresh icon-white"></span>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-danger"'); ?>
	</fieldset>
<?php echo form_close(); ?>