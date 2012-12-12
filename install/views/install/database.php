<?php $this->load->view('header'); ?>

	<?php $this->load->view('install/menu'); ?>

	<?php if (validation_errors()) : ?>
	<div class="notification information">
		<p><?php echo validation_errors(); ?></p>
	</div>
	<?php endif; ?>

	<h2><?php echo lang('in_environment'); ?></h2>
	
	<?php echo lang('in_environment_note'); ?>
	
	
	<?php echo form_open(current_url(), array('id' => 'db-form') ) ?>
	
		<div>
			<label for="environment"><?php echo lang('in_environment'); ?></label>
			<select name="environment" id="environment">
				<option value="development" <?php echo set_select('environment', 'development', TRUE); ?>><?php echo lang('in_environment_dev'); ?></option>
				<option value="testing" <?php echo set_select('environment', 'testing'); ?>><?php echo lang('in_environment_test'); ?></option>
				<option value="production" <?php echo set_select('environment', 'production'); ?>><?php echo lang('in_environment_prod'); ?></option>
			</select>
		</div>


	<h2><?php echo lang('in_db_settings'); ?></h2>
	
		<div>
			<label for="driver"><?php echo lang('in_db_driver') ?></label>
			<select name="driver" id="driver">
			<?php foreach ($drivers as $driver) : ?>
				<option><?php echo $driver ?></option>
			<?php endforeach; ?>
			</select>
		</div>
		
		<div>
			<label for="hostname"><?php echo lang('in_host'); ?></label>
			<input type="text" name="hostname" id="hostname" value="<?php echo set_value('hostname', 'localhost') ?>" class="db_check" />
		</div>
		
		<div>
			<label for="port"><?php echo lang('in_port'); ?></label>
			<input type="text" name="port" id="port" value="<?php echo set_value('port', '3306') ?>" style="width: 5em" class="db_check" />
		</div>
		
		<div>
			<label for="username"><?php echo lang('bf_username'); ?></label>
			<input type="text" name="username" id="username" value="<?php echo set_value('username') ?>" class="db_check" />
		</div>
		
		<div>
			<label for="password"><?php echo lang('bf_password'); ?></label>
			<input type="password" name="password" id="password" value="" class="db_check" />
		</div>
		
		<div>
			<label for="database"><?php echo lang('in_database'); ?></label>
			<input type="text" name="database" id="database" value="<?php echo set_value('database', 'bonfire_dev') ?>" />
		</div>
		
		<div>
			<label for="db_prefix"><?php echo lang('in_prefix'); ?></label>
			<input type="text" name="db_prefix" id="db_prefix" value="<?php echo set_value('db_prefix', 'bf_'); ?>" />
		</div>
		
		<div id="confirm_db"></div>
		
		<div class="form-actions">
			<input type="submit" name="install_db" id="submit" value="<?php echo lang('in_continue'); ?>" />
		</div>
		
	
	<?php echo form_close(); ?>


<?php $this->load->view('footer'); ?>
