<h2><?php echo lang('us_reset_password'); ?></h2>
	
<?php if (auth_errors() || validation_errors()) : ?>
<div class="notification error">
	<?php echo auth_errors() . validation_errors(); ?>
</div>
<?php endif; ?>

<p><?php echo lang('us_reset_note'); ?></p>

<?php echo form_open($this->uri->uri_string()); ?>
	<label for="email"><?php echo lang('bf_email'); ?></label>
	<input type="email" name="email" id="email"  value="<?php echo set_value('email'); ?>"  />		

<div class="submits">
	<input type="submit" name="submit" id="submit" value="Send Password"  />
</div>

<?php echo form_close(); ?>