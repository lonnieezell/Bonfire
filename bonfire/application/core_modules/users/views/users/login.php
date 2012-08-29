<?php
	$site_open = $this->settings_lib->item('auth.allow_register');
?>
<section id="login">
	<div class="page-header">
		<h1><?php echo lang('us_login'); ?></h1>
	</div>



<?php if ( !$site_open ) : ?>
<div class="row-fluid">
	<div class="span12">
		<div class="alert alert-danger fade in span6" >
		  <a data-dismiss="alert" class="close">&times;</a>
			<h4 class="alert-heading">Sorry this is invite only site.</h4>
		</div>
	</div>
</div>
<?php
	endif;
	if (auth_errors() || validation_errors()) :
?>
<div class="row-fluid">
	<div class="span12">
		<div class="alert alert-error fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo auth_errors() . validation_errors(); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span12">

<?php echo form_open('login', array('class' => "form-horizontal", 'autocomplete' => 'off')); ?>

	<div class="control-group <?php echo iif( form_error('login') , 'error') ;?>">
		<label class="control-label" for="login_value"><?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_login_type_both') : ucwords($this->settings_lib->item('auth.login_type')) ?></label>
		<div class="controls">
			<input class="span6" type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords($this->settings_lib->item('auth.login_type')) ?>" />
		</div>
	</div>

	<div class="control-group <?php echo iif( form_error('password') , 'error') ;?>">
		<label class="control-label" for="password"><?php echo lang('bf_password'); ?></label>
		<div class="controls">
			<input class="span6" type="password" name="password" id="password" value="" tabindex="2" placeholder="<?php echo lang('bf_password'); ?>" />
		</div>
	</div>

	<?php if ($this->settings_lib->item('auth.allow_remember')) : ?>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox" for="remember_me">
					<input type="checkbox" name="remember_me" id="remember_me" value="1" tabindex="3" />
					<span class="inline-help"><?php echo lang('us_remember_note'); ?></span>
				</label>
			</div>
		</div>
	<?php endif; ?>

	<div class="control-group">
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" id="submit" value="Let Me In" tabindex="5" />
		</div>
	</div>
<?php echo form_close(); ?>

	</div>
</div>

<?php // show for Email Activation (1) only
	if ($this->settings_lib->item('auth.user_activation_method') == 1) : ?>
<!-- Activation Block -->
<div class="row-fluid">
	<div class="span12">

		<p style="text-align: left" class="well">
			<?php echo lang('bf_login_activate_title'); ?><br />
			<?php
			$activate_str = str_replace('[ACCOUNT_ACTIVATE_URL]',anchor('/activate', lang('bf_activate')),lang('bf_login_activate_email'));
			$activate_str = str_replace('[ACTIVATE_RESEND_URL]',anchor('/resend_activation', lang('bf_activate_resend')),$activate_str);
			echo $activate_str; ?>
		</p>

	</div>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span12">

	<p style="text-align: center" class="well">
		<?php if ( $site_open ) : ?>
			<?php echo lang('us_no_account'); ?> <?php echo anchor('/register', lang('us_sign_up')); ?> &nbsp;&nbsp; &#8226; &nbsp;&nbsp;
		<?php endif; ?>

		<?php echo anchor('/forgot_password', lang('us_forgot_your_password')); ?>
	</p>

	</div>
</div>

</section>
