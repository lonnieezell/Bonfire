<h2>Welcome</h2>

<p>Welcome to the Bonfire installation process! Just fill in the fields below, and before you know it you will be creating CodeIgniter 2.0 based web apps faster than ever.</p>

<h2>Information Needed</h2>

<p>Please provide the following information.</p>


<?php if (validation_errors()) : ?>
<div class="notification information">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open(site_url('installer.php')) ?>

	<div>
		<label>Site Title</label>
		<input type="text" name="site_title" id="site_title" placeholder="My Great Bonfire App" value="" />
	</div>
	
	<div>
		<label>Username</label>
		<input type="text" name="username" id="username" value="" />
	</div>
	
	<br />
	
	<div>
		<label>Password</label>
		<input type="password" name="password" id="password" value="" />
	</div>
	
	<div>
		<label>Password (again)</label>
		<input type="password" name="pass_confirm" id="pass_confirm" value="" />
	</div>
	
	<br/>
	
	<div>
		<label>Your Email</label>
		<input type="text" name="username" id="username" placeholder="me@home.com" value="" />
		<p class="small">Please double-check your password before continuing.</p>
	</div>
	
	<div class="submits">
		<input type="submit" name="submit" id="submit" value="Install Bonfire" />
	</div>

<?php echo form_close(); ?>