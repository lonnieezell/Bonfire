<h2><?php echo lang('us_login'); ?></h2>

<?php if (auth_errors() || validation_errors()) : ?>
<div class="notification error">
	<?php echo auth_errors() . validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open('login'); ?>

	<label for="login_value"><?php echo config_item('auth.login_type') == 'both' ? 'Username/Email' : ucwords(config_item('auth.login_type')) ?></label>
	<input type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo config_item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords(config_item('auth.login_type')) ?>" />

	<label for="password"><?php echo lang('bf_password'); ?></label>
	<input type="password" name="password" id="password" value="" tabindex="2" placeholder="<?php echo lang('bf_password'); ?>" />
	
	<?php if (config_item('auth.allow_remember')) : ?>
	<div class="small indent">
		<input type="checkbox" name="remember_me" id="remember_me" value="1" tabindex="3" /> 		
		<label for="remember_me" class="remember"><?php echo lang('us_remember_note'); ?></label>
	</div>
	<?php endif; ?>

	<div class="submits">
		<input type="submit" name="submit" id="submit" value="Let Me In" tabindex="5" />	
	</div>

<?php echo form_close(); ?>
		
<p style="text-align: center">
	<?php if (config_item('auth.allow_register')) : ?>
		<?php echo lang('us_no_account'); ?> <?php echo anchor('/register', lang('us_sign_up')); ?> &nbsp;&nbsp; &#8226; &nbsp;&nbsp;
	<?php endif; ?>

	<?php echo anchor('/forgot_password', lang('us_forgot_your_password')); ?>
</p>
	
		