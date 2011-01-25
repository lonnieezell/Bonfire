<h1>Login</h1>

<?php if (auth_errors()) : ?>
<div class="notification error">
	<?php echo auth_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open('login'); ?>
<div class="login row">
	<div class="column size1of2">
		<label>Email</label>
		<input type="email" name="email" id="email"  value="<?php echo set_value('email'); ?>" tabindex="1" />
		
		<br /><br /><input type="checkbox" name="remember_me" value="1" tabindex="3" /> Remember me for two weeks
	</div>
	
	<div class="column size1of2">
		<label>Password</label>
		<input type="password" name="password" id="password" value="" tabindex="2" />
		
		<br /><br /><?php echo anchor('/forgot_password', 'I forgot my password.'); ?>
	</div>
</div>

<div class="text-right">
	<br />
	<input type="submit" name="submit" id="submit" value="Log Me In" tabindex="5" />
</div>


<?php echo form_close(); ?>