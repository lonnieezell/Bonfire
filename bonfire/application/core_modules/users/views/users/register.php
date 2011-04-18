<h2>My Name Is...</h2>

<?php if (auth_errors() || validation_errors()) : ?>
<div class="notification error">
	<?php echo auth_errors() . validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string()); ?>

	<label for="email">Email</label>
	<input type="text" name="email" id="email"  value="<?php echo set_value('email'); ?>"  placeholder="email" />
	
	<?php if (config_item('auth.use_usernames') == 1) : ?>
	<label for="username">Username</label>
	<input type="text" name="username" id="username" value="<?php echo set_value('username') ?>" placeholder="username" />

	<?php endif; ?>
	<br/>

	<label for="password">Password</label>
	<input type="password" name="password" id="password" value="" placeholder="password" />
	<p class="small">Minimum 8 characters.</p>
	
	<label for="pass_confirm">Password (again)</label>
	<input type="password" name="pass_confirm" id="pass_confirm" value="" placeholder="password (again)" />

	<div class="submits">
		<input type="submit" name="submit" id="submit" value="Register"  />	
	</div>

<?php echo form_close(); ?>

<p style="text-align: center">
	Already registered? <?php echo anchor('/login', 'Login'); ?>
</p>
	
		