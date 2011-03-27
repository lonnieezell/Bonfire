<h2>Welcome</h2>

<p>Welcome to the Bonfire installation process! Just fill in the fields below, and before you know it you will be creating CodeIgniter 2.0 based web apps faster than ever.</p>

<?php if (isset($startup_errors) && !empty($startup_errors)) :?>

	<h2>Files/Folders Not Writeable</h2>
	
	<?php echo $startup_errors; ?>
	
	<p style="text-align: right; margin-top: 3em;"><?php echo anchor('installer.php', 'Reload Page'); ?></p>

<?php else : ?>
	<h2>Information Needed</h2>
	
	<p>Please provide the following information.</p>
	
	
	<?php if (validation_errors()) : ?>
	<div class="notification information">
		<p><?php echo validation_errors(); ?></p>
	</div>
	<?php endif; ?>
	
	<?php echo form_open(current_url()) ?>
	
		<div>
			<label>Site Title</label>
			<input type="text" name="site_title" id="site_title" placeholder="My Great Bonfire App" value="<?php echo set_value('site_title') ?>" />
		</div>
		
		<div>
			<label>Username</label>
			<input type="text" name="username" id="username" value="<?php echo set_value('username') ?>" />
		</div>
		
		<br />
		
		<div>
			<label>Password</label>
			<input type="password" name="password" id="password" value="" />
			<p class="small">Minimum length: 8 characters.</p>
		</div>
		
		<div>
			<label>Password (again)</label>
			<input type="password" name="pass_confirm" id="pass_confirm" value="" />
		</div>
		
		<br/>
		
		<div>
			<label>Your Email</label>
			<input type="email" name="email" id="email" placeholder="me@home.com" value="<?php echo set_value('email') ?>" />
			<p class="small">Please double-check your email before continuing.</p>
		</div>
		
		<div class="submits">
			<input type="submit" name="submit" id="submit" value="Install Bonfire" />
		</div>
	
	<?php echo form_close(); ?>
<?php endif; ?>