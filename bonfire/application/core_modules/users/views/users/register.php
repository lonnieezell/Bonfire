	<div class="box">
	
		<div class="header">
			<h1>My Name Is...</h1>
		</div>
		
		<?php if (auth_errors() || validation_errors()) : ?>
		<div class="notification error">
			<?php echo auth_errors(); ?>
		</div>
		<?php endif; ?>
		
		<?php echo form_open($this->uri->uri_string()); ?>
		
			<label>Email</label>
			<input type="text" name="email" id="email"  value="<?php echo set_value('email'); ?>"  placeholder="email" />
			
			<?php if (config_item('auth.use_usernames') == 1) : ?>
			<label>Username</label>
			<input type="text" name="username" id="username" value="<?php echo set_value('username') ?>" placeholder="username" />
	
			<?php endif; ?>
			<br/>
	
			<label>Password</label>
			<input type="password" name="password" id="password" value="" placeholder="password" />
			<p class="small">Minimum 8 characters.</p>
			
			<label>Password (again)</label>
			<input type="password" name="pass_confirm" id="pass_confirm" value="" placeholder="password (again)" />
		
			<br/>
			<input type="submit" name="submit" id="submit" value="Register"  />	
		
		<?php echo form_close(); ?>
		
	</div>	<!-- /login -->
	
		