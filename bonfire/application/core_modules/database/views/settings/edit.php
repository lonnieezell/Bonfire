<h2>&lsquo;<?php echo $server_type ?>&rsquo; Database Settings</h2>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"') ?>
			
	<input type="hidden" name="server_type" value="<?php echo $server_type ?>" />
	
	<div>
		<label for="hostname">Hostname</label>
		<input type="text" name="hostname" id="hostname" value="<?= isset($db_settings['hostname']) ? $db_settings['hostname'] : ''; ?>" />
	</div>
	<div>
		<label for="database">Database Name</label>
		<input type="text" name="database" id="database" value="<?= isset($db_settings['database']) ? $db_settings['database'] : ''; ?>" />
	</div>
	<div>
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?= isset($db_settings['username']) ? $db_settings['username'] : ''; ?>" />
	</div>
	<div>
		<label for="password">Password</label>
		<input type="text" name="password" id="password" value="<?= isset($db_settings['password']) ? $db_settings['password'] : ''; ?>" />
	</div>
	
	<fieldset class="collapsible small">
		<legend>Advanced Options</legend>
		
		<div>
			<label for="pconnect">Persistant Connection?</label>
			<select name="pconnect">
				<option value="TRUE" <?php echo isset($db_settings['pconnect']) && $db_settings['pconnect'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
				<option value="FALSE" <?php echo isset($db_settings['pconnect']) && $db_settings['pconnect'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
			</select>
		</div>
		
		<div>
			<label for="db_debug">Display Database Errors?</label>
			<select name="db_debug">
				<option value="TRUE" <?php echo isset($db_settings['db_debug']) && $db_settings['db_debug'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
				<option value="FALSE" <?php echo isset($db_settings['db_debug']) && $db_settings['db_debug'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
			</select>
		</div>
		
		<div>
			<label for="cache_on">Enable Query Caching?</label>
			<select name="cache_on">
				<option value="TRUE" <?php echo isset($db_settings['cache_on']) && $db_settings['cache_on'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
				<option value="FALSE" <?php echo isset($db_settings['cache_on']) && $db_settings['cache_on'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
			</select>
		</div>
		
		<div>
			<label for="cachedir">Cache Directory</label>
			<input type="text" name="cachedir" id="cachedir" value="<?= isset($db_settings['cachedir']) ? $db_settings['cachedir'] : ''; ?>" />
		</div>
		
		<div>
			<label for="dbprefix">Prefix</label>
			<input type="text" name="dbprefix" id="dbprefix" value="<?= isset($db_settings['dbprefix']) ? $db_settings['dbprefix'] : ''; ?>" style="width: 100px" />
		</div>
	</fieldset>
		

	<div class="submits">
		<input type="submit" name="submit" value="Save Settings" />
	</div>

<?php echo form_close(); ?>
