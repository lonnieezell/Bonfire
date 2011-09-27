<h2>Reset Your Password</h2>

<p>Enter your new password below to reset your password.</p>

<?php if (auth_errors() || validation_errors()) : ?>
<div class="notification error">
	<?php echo auth_errors() . validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open(current_url()) ?>
	<input type="hidden" name="user_id" value="<?php echo $user->id ?>" />

	<label for="password"><?php echo lang('bf_password'); ?></label>
	<input type="text" name="password" placeholder="Password..." />
	<p class="small"><?php echo lang('us_password_mins'); ?></p>
	
	<label for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
	<input type="text" name="pass_confirm" placeholder="Again..." />
	
	<div class="submits">
		<input type="submit" name="submit" value="Save New Password" />
	</div>

<?php echo form_close(); ?>