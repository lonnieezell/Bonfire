<div id="login">

	<div class="header">
		<h1>My Name Is...</h1>
	</div>
	
	<?php if (auth_errors()) : ?>
	<div class="notification error">
		<?php echo auth_errors(); ?>
	</div>
	<?php endif; ?>
	
	<?php echo form_open('login'); ?>
	
		<label><?php echo config_item('auth.login_type') == 'both' ? 'Username/Email' : ucwords(config_item('auth.login_type')) ?></label>
		<input type="text" name="login" id="login_value"  value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo config_item('auth.login_type') == 'both' ? 'Username/Email' : ucwords(config_item('auth.login_type')) ?>" />

		<label>Password</label>
		<input type="password" name="password" id="password" value="" tabindex="2" placeholder="password" />
		
		<?php if ($this->config->item('auth.allow_remember')) : ?>
		<div class="small">
			<input type="checkbox" name="remember_me" value="1" tabindex="3" /> Remember me for two weeks
		</div>
		<?php endif; ?>
	
		<input type="submit" name="submit" id="submit" value="Let Me In" tabindex="5" />	
	
	<?php echo form_close(); ?>
	
	<div class="footer"></div>

</div>