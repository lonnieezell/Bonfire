<?php $this->load->view('header'); ?>

<?php echo lang('in_intro'); ?>

<?php if (isset($startup_errors) && !empty($startup_errors)) :?>

	<h2><?php echo lang('in_not_writeable_heading'); ?></h2>
	
	<?php echo $startup_errors; ?>
	
	<p style="text-align: right; margin-top: 3em;"><?php echo anchor('/', lang('in_reload')); ?></p>

<?php else : ?>
	<h2><?php echo lang('in_db_settings'); ?></h2>
	
	<?php echo lang('in_db_settings_note'); ?>
	
	
	<?php if (validation_errors()) : ?>
	<div class="notification information">
		<p><?php echo validation_errors(); ?></p>
	</div>
	<?php endif; ?>
	
	<?php echo form_open(uri_string(), array('id' => 'db-form') ) ?>
	
		<div>
			<label for="environment"><?php echo lang('in_environment'); ?></label>
			<select name="environment">
				<option value="development" <?php echo set_select('environment', 'development', TRUE); ?>><?php echo lang('in_env_dev'); ?></option>
				<option value="testing" <?php echo set_select('environment', 'testing'); ?>><?php echo lang('in_env_test'); ?></option>
				<option value="production" <?php echo set_select('environment', 'production'); ?>><?php echo lang('in_env_prod'); ?></option>
			</select>
		</div>
		
		<div>
			<label for="hostname"><?php echo lang('in_host'); ?></label>
			<input type="text" name="hostname" value="<?php echo set_value('hostname', 'localhost') ?>" />
		</div>
		
		<div>
			<label for="username"><?php echo lang('bf_username'); ?></label>
			<input type="text" name="username" value="<?php echo set_value('username') ?>" />
		</div>
		
		<div>
			<label for="password"><?php echo lang('bf_password'); ?></label>
			<input type="password" name="password" id="password" value="" />
		</div>
		
		<div>
			<label for="database"><?php echo lang('in_database'); ?></label>
			<input type="text" name="database" id="database" value="<?php echo set_value('database', 'bonfire_dev') ?>" />
		</div>
		
		<div>
			<label for="db_prefix"><?php echo lang('in_prefix'); ?></label>
			<input type="text" name="db_prefix" value="<?php echo set_value('db_prefix', 'bf_'); ?>" />
		</div>
		
		<div class="submits">
			<input type="submit" name="submit" id="submit" value="<?php echo lang('in_test_db'); ?>" />
		</div>
	
	<?php echo form_close(); ?>
<?php endif; ?>

<?php $this->load->view('footer'); ?>
