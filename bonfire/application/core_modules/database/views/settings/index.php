<?php echo form_open(site_url('admin/settings/database'), 'class="constrained"') ?>

	<div class="notification information">
		<p>You are currently running on the <b><?php echo ENVIRONMENT ?></b> server.</p>
	</div>

	<div class="tabs">
	
		<ul>
			<li><a href="#dev-tab">Development</a></li>
			<li><a href="#test-tab">Staging</a></li>
			<li><a href="#prod-tab">Production</a></li>
		</ul>
		
		<!-- Development -->
		<div id="dev-tab">
			
			<div>
				<label for="dev[hostname]">Hostname</label>
				<input type="text" name="dev[hostname]" id="dev[hostname]" value="<?= isset($settings['dev']['hostname']) ? $settings['dev']['hostname'] : ''; ?>" />
			</div>
			<div>
				<label for="dev[database]">Database Name</label>
				<input type="text" name="dev[database]" id="dev[database]" value="<?= isset($settings['dev']['database']) ? $settings['dev']['database'] : ''; ?>" />
			</div>
			<div>
				<label for="dev[username]">Username</label>
				<input type="text" name="dev[username]" id="dev[username]" value="<?= isset($settings['dev']['username']) ? $settings['dev']['username'] : ''; ?>" />
			</div>
			<div>
				<label for="dev[password]">Password</label>
				<input type="text" name="dev[password]" id="dev[password]" value="<?= isset($settings['dev']['password']) ? $settings['dev']['password'] : ''; ?>" />
			</div>
			
			<fieldset class="collapsible small">
				<legend>Advanced Options</legend>
				
				<div>
					<label for="dev[pconnect]">Persistant Connection?</label>
					<select name="dev[pconnect]">
						<option value="TRUE" <?php echo isset($settings['dev']['pconnect']) && $settings['dev']['pconnect'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['dev']['pconnect']) && $settings['dev']['pconnect'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="dev[db_debug]">Display Database Errors?</label>
					<select name="dev[db_debug]">
						<option value="TRUE" <?php echo isset($settings['dev']['db_debug']) && $settings['dev']['db_debug'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['dev']['db_debug']) && $settings['dev']['db_debug'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="dev[cache_on]">Enable Query Caching?</label>
					<select name="dev[cache_on]">
						<option value="TRUE" <?php echo isset($settings['dev']['cache_on']) && $settings['dev']['cache_on'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['dev']['cache_on']) && $settings['dev']['cache_on'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="dev[cachedir]">Cache Directory</label>
					<input type="text" name="dev[cachedir]" id="dev[cachedir]" value="<?= isset($settings['dev']['cachedir']) ? $settings['dev']['cachedir'] : ''; ?>" />
				</div>
				
				<div>
					<label for="dev[dbprefix]">Prefix</label>
					<input type="text" name="dev[dbprefix]" id="dev[dbprevix]" value="<?= isset($settings['dev']['dbprefix']) ? $settings['dev']['dbprefix'] : ''; ?>" style="width: 100px" />
				</div>
			</fieldset>
			
		</div>
		
		<!-- Staging -->
		<div id="test-tab">
			<div>
				<label for="test[hostname]">Hostname</label>
				<input type="text" name="test[hostname]" id="test[hostname]" value="<?= isset($settings['test']['hostname']) ? $settings['test']['hostname'] : ''; ?>" />
			</div>
			<div>
				<label for="test[database]">Database Name</label>
				<input type="text" name="test[database]" id="test[database]" value="<?= isset($settings['test']['username']) ? $settings['test']['database'] : ''; ?>" />
			</div>
			<div>
				<label for="test[username]">Username</label>
				<input type="text" name="test[username]" id="test[username]" value="<?= isset($settings['test']['username']) ? $settings['test']['username'] : ''; ?>" />
			</div>
			<div>
				<label for="test[password]">Password</label>
				<input type="text" name="test[password]" id="test[password]" value="<?= isset($settings['test']['password']) ? $settings['test']['password'] : ''; ?>" />
			</div>
			
			<fieldset class="collapsible small">
				<legend>Advanced Options</legend>
				
				<div>
					<label for="test[pconnect]">Persistant Connection?</label>
					<select name="test[pconnect]">
						<option value="TRUE" <?php echo isset($settings['test']['pconnect']) && $settings['test']['pconnect'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['test']['pconnect']) && $settings['test']['pconnect'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="test[db_debug]">Display Database Errors?</label>
					<select name="test[db_debug]">
						<option value="TRUE" <?php echo isset($settings['test']['db_debug']) && $settings['test']['db_debug'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['test']['db_debug']) && $settings['test']['db_debug'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="test[cache_on]">Enable Query Caching?</label>
					<select name="test[cache_on]">
						<option value="TRUE" <?php echo isset($settings['test']['cache_on']) && $settings['test']['cache_on'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['test']['cache_on']) && $settings['test']['cache_on'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="test[cachedir]">Cache Directory</label>
					<input type="text" name="test[cachedir]" id="test[cachedir]" value="<?= isset($settings['test']['cachedir']) ? $settings['test']['cachedir'] : ''; ?>" />
				</div>
				
				<div>
					<label for="test[dbprefix]">Prefix</label>
					<input type="text" name="test[dbprefix]" id="test[dbprevix]" value="<?= isset($settings['test']['dbprefix']) ? $settings['test']['dbprefix'] : ''; ?>" style="width: 100px" />
				</div>
			</fieldset>
		</div>
		
		<!-- Production -->
		<div id="prod-tab">
			<div>
				<label for="prod[hostname]">Hostname</label>
				<input type="text" name="prod[hostname]" id="prod[hostname]" value="<?= isset($settings['prod']['hostname']) ? $settings['prod']['hostname'] : ''; ?>" />
			</div>
			<div>
				<label for="prod[database]">Database Name</label>
				<input type="text" name="prod[database]" id="prod[database]" value="<?= isset($settings['prod']['database']) ? $settings['prod']['database'] : ''; ?>" />
			</div>
			<div>
				<label for="prod[username]">Username</label>
				<input type="text" name="prod[username]" id="prod[username]" value="<?= isset($settings['prod']['username']) ? $settings['prod']['username'] : ''; ?>" />
			</div>
			<div>
				<label for="prod[password]">Password</label>
				<input type="text" name="prod[password]" id="prod[password]" value="<?= isset($settings['prod']['password']) ? $settings['prod']['password'] : ''; ?>" />
			</div>
			
			<fieldset class="collapsible small">
				<legend>Advanced Options</legend>
				
				<div>
					<label for="prod[pconnect]">Persistant Connection?</label>
					<select name="prod[pconnect]">
						<option value="TRUE" <?php echo isset($settings['prod']['pconnect']) && $settings['prod']['pconnect'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['prod']['pconnect']) && $settings['prod']['pconnect'] == FALSE ? 'selected="selected"' : ''; ?>No</option>
					</select>
				</div>
				
				<div>
					<label for="prod[db_debug]">Display Database Errors?</label>
					<select name="prod[db_debug]">
						<option value="TRUE" <?php echo isset($settings['prod']['db_debug']) && $settings['prod']['db_debug'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['prod']['db_debug']) && $settings['prod']['db_debug'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="prod[cache_on]">Enable Query Caching?</label>
					<select name="prod[cache_on]">
						<option value="TRUE" <?php echo isset($settings['prod']['cache_on']) && $settings['prod']['cache_on'] == TRUE ? 'selected="selected"' : ''; ?>>Yes</option>
						<option value="FALSE" <?php echo isset($settings['prod']['cache_on']) && $settings['prod']['cache_on'] == FALSE ? 'selected="selected"' : ''; ?>>No</option>
					</select>
				</div>
				
				<div>
					<label for="prod[cachedir]">Cache Directory</label>
					<input type="text" name="prod[cachedir]" id="prod[cachedir]" value="<?= isset($settings['prod']['cachedir']) ? $settings['prod']['cachedir'] : ''; ?>" />
				</div>
				<div>
					<label for="prod[dbprefix]">Prefix</label>
					<input type="text" name="prod[dbprefix]" id="prod[dbprevix]" value="<?= isset($settings['prod']['dbprefix']) ? $settings['prod']['dbprefix'] : ''; ?>" style="width: 100px" />
				</div>
			</fieldset>
		</div>
	
	</div>	<!-- /tabs -->

	<div class="spacer text-right">
		<input type="submit" name="submit" value="Save Settings" />
	</div>

<?php echo form_close(); ?>

<script>
head.ready(function(){
	$('.tabs').tabs();
});
</script>