<h1>Retrieve Password</h1>

<?php if (auth_errors()) : ?>
<div class="notification error">
	<?php echo auth_errors(); ?>
</div>
<?php endif; ?>

<p>If you forgot your password, we will build a new one and email it to you.<br/>You can change the password once you login again.</p>

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