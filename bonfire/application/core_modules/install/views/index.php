<h2>Welcome</h2>

<p>Welcome to the Bonfire installation process! Just fill in the fields below, and before you know it you will be creating CodeIgniter 2.0 based web apps faster than ever.</p>

<?php if (isset($startup_errors) && !empty($startup_errors)) :?>

	<h2>Files/Folders Not Writeable</h2>
	
	<?php echo $startup_errors; ?>
	
	<p style="text-align: right; margin-top: 3em;"><?php echo anchor('/install', 'Reload Page'); ?></p>

<?php else : ?>
	<h2>Database Settings</h2>
	
	<p>Please fill out the database information below.</p> 
	
	<p class="small">These settings will be saved to both the main <b>config/database.php</b> file and to the development environment (found at <b>config/development/database.php)</b>. </p>
	
	
	<?php if (validation_errors()) : ?>
	<div class="notification information">
		<p><?php echo validation_errors(); ?></p>
	</div>
	<?php endif; ?>
	
	<?php echo form_open(site_url('install'), array('id' => 'db-form') ) ?>
	
		<div>
			<label>Host</label>
			<input type="text" name="hostname" value="<?php echo set_value('hostname', 'localhost') ?>" />
		</div>
		
		<div>
			<label>Username</label>
			<input type="text" name="username" value="<?php echo set_value('username') ?>" />
		</div>
		
		<div>
			<label>Password</label>
			<input type="password" name="password" id="password" value="" />
		</div>
		
		<div>
			<label>Database</label>
			<input type="text" name="database" id="database" value="<?php echo set_value('database', 'bonfire_dev') ?>" />
		</div>
		
		<div>
			<label>Prefix</label>
			<input type="text" name="db_prefix" value="<?php echo set_value('db_prefix', 'bf_'); ?>" />
		</div>
		
		<div class="submits">
			<input type="submit" name="submit" id="submit" value="Test Database" />
		</div>
	
	<?php echo form_close(); ?>
<?php endif; ?>