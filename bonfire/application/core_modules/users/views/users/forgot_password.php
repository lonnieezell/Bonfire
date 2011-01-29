<div class="box">
	<div class="header">
		<h1>Reset Password</h1>
	</div>
	
	<?php if (auth_errors()) : ?>
	<div class="notification error">
		<?php echo auth_errors(); ?>
	</div>
	<?php endif; ?>
	
	<br/>
	<p>Enter your email and we'll send a temporary password to you.</p>
	
	<?php echo form_open('forgot_password'); ?>
	<div class="login" style="width: 50%;">
		<label>Email</label>
		<input type="email" name="email" id="email"  value="<?php echo set_value('email'); ?>"  />		
	</div>
	
	<div>
		<br />
		<input type="submit" name="submit" id="submit" value="Reset Password"  />
	</div>
	
	
	<?php echo form_close(); ?>
</div>