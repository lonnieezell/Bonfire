<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>
<h2><em>&lsquo;<?php echo $server_type ?>&rsquo;</em>  -  <?php echo lang('db_database_settings'); ?> </h2>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"') ?>
			
	<input type="hidden" name="server_type" value="<?php echo $server_type ?>" />
	
	<div>
		<label for="hostname"><?php echo lang('db_hostname'); ?></label>
		<input type="text" name="hostname" id="hostname" value="<?php echo isset($db_settings['default']['hostname']) ? $db_settings['default']['hostname'] : ''; ?>" />
	</div>
	<div>
		<label for="database"><?php echo lang('db_dbname'); ?></label>
		<input type="text" name="database" id="database" value="<?php echo isset($db_settings['default']['database']) ? $db_settings['default']['database'] : ''; ?>" />
	</div>
	<div>
		<label for="username"><?php echo lang('bf_username'); ?></label>
		<input type="text" name="username" id="username" value="<?php echo isset($db_settings['default']['username']) ? $db_settings['default']['username'] : ''; ?>" />
	</div>
	<div>
		<label for="password"><?php echo lang('bf_password'); ?></label>
		<input type="text" name="password" id="password" value="<?php echo isset($db_settings['default']['password']) ? $db_settings['default']['password'] : ''; ?>" />
	</div>
	
	<fieldset class="collapsible small">
		<legend><?php echo lang('db_advanced_options'); ?></legend>
		
		<div>
			<label for="pconnect"><?php echo lang('db_persistant_connect'); ?>?</label>
			<select name="pconnect">
				<option value="TRUE" <?php echo isset($db_settings['default']['pconnect']) && $db_settings['default']['pconnect'] == TRUE ? 'selected="selected"' : ''; ?>><?php echo lang('bf_yes'); ?></option>
				<option value="FALSE" <?php echo isset($db_settings['default']['pconnect']) && $db_settings['default']['pconnect'] == FALSE ? 'selected="selected"' : ''; ?>><?php echo lang('bf_no'); ?></option>
			</select>
		</div>
		
		<div>
			<label for="db_debug"><?php echo lang('db_display_errors'); ?>?</label>
			<select name="db_debug">
				<option value="TRUE" <?php echo isset($db_settings['default']['db_debug']) && $db_settings['default']['db_debug'] == TRUE ? 'selected="selected"' : ''; ?>><?php echo lang('bf_yes'); ?></option>
				<option value="FALSE" <?php echo isset($db_settings['default']['db_debug']) && $db_settings['default']['db_debug'] == FALSE ? 'selected="selected"' : ''; ?>><?php echo lang('bf_no'); ?></option>
			</select>
		</div>
		
		<div>
			<label for="cache_on"><?php echo lang('db_enable_caching'); ?>?</label>
			<select name="cache_on">
				<option value="TRUE" <?php echo isset($db_settings['default']['cache_on']) && $db_settings['default']['cache_on'] == TRUE ? 'selected="selected"' : ''; ?>><?php echo lang('bf_yes'); ?></option>
				<option value="FALSE" <?php echo isset($db_settings['default']['cache_on']) && $db_settings['default']['cache_on'] == FALSE ? 'selected="selected"' : ''; ?>><?php echo lang('bf_no'); ?></option>
			</select>
		</div>
		
		<div>
			<label for="cachedir"><?php echo lang('db_cache_dir'); ?></label>
			<input type="text" name="cachedir" id="cachedir" value="<?php echo isset($db_settings['default']['cachedir']) ? $db_settings['default']['cachedir'] : ''; ?>" />
		</div>
		
		<div>
			<label for="dbprefix"><?php echo lang('db_prefix'); ?></label>
			<input type="text" name="dbprefix" id="dbprefix" value="<?php echo isset($db_settings['default']['dbprefix']) ? $db_settings['default']['dbprefix'] : ''; ?>" style="width: 100px" />
		</div>
	</fieldset>
		

	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?>" />
	</div>

<?php echo form_close(); ?>
