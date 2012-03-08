<section id="register">
	<div class="page-header">
		<h1><?php echo lang('us_login'); ?></h1>
	</div>

<?php if (auth_errors() || validation_errors()) : ?>
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

<?php echo form_open('login', 'class="form-horizontal"'); ?>


	<div class="control-group <?php echo iif( form_error('email') , 'error') ;?>">
		<label class="control-label" for="email"><?php echo lang('bf_email'); ?></label>
		<div class="controls">
		 <input class="span6" type="text" name="email" id="email"  value="<?php echo set_value('email'); ?>"  placeholder="email" />
		</div>
	</div>

	<?php if ( $this->settings_lib->item('auth.login_type') !== 'email' OR $this->settings_lib->item('auth.use_usernames') == 1): ?>

	<div class="control-group <?php echo iif( form_error('username') , 'error') ;?>">
		<label class="control-label" for="username"><?php echo lang('bf_username'); ?></label>
		<div class="controls">
		 <input class="span6" type="text" name="username" id="username" value="<?php echo set_value('username') ?>" placeholder="username" />
		</div>
	</div>

	<?php endif; ?>
	<br/>
	<?php if ($this->settings_lib->item('auth.use_own_names')) : ?>

		<div class="control-group <?php echo iif( form_error('first_name') , 'error') ;?>">
			<label class="control-label" for="first_name"><?php echo lang('us_first_name'); ?></label>
			<div class="controls">
				<input class="span6" type="text" id="first_name" name="first_name" value="<?php echo set_value('first_name') ?>" />
			</div>
		</div>

		<div class="control-group <?php echo iif( form_error('last_name') , 'error') ;?>">
			<label class="control-label" for="last_name"><?php echo lang('us_last_name'); ?></label>
			<div class="controls">
				<input class="span6" type="text" id="last_name" name="last_name" value="<?php echo set_value('last_name') ?>" />
			</div>
		</div>

	<?php endif; ?>

		<div class="control-group <?php echo iif( form_error('password') , 'error') ;?>">
			<label class="control-label" for="password"><?php echo lang('bf_password'); ?></label>
			<div class="controls">
				<input class="span6" type="password" name="password" id="password" value="" placeholder="password" />
				<p class="help-block"><?php echo lang('us_password_mins'); ?></p>
			</div>
		</div>

		<div class="control-group <?php echo iif( form_error('pass_confirm') , 'error') ;?>">
			<label class="control-label" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
			<div class="controls">
				<input class="span6" type="password" name="pass_confirm" id="pass_confirm" value="" placeholder="<?php echo lang('bf_password_confirm'); ?>" />
			</div>
		</div>

	<div class="control-group">
		<label class="control-label" for="submit">&nbsp;</label>
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" id="submit" value="<?php echo lang('us_register'); ?>"  />
		</div>
	</div>

<?php echo form_close(); ?>

<p style="text-align: center">
	<?php echo lang('us_already_registered'); ?> <?php echo anchor('/login', lang('bf_action_login')); ?>
</p>

	</div>
</div>
</section>
