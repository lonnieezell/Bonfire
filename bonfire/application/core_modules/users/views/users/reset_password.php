		<div class="page-header">
				<h1>Reset Your Password</h1>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<div class="alert alert-info fade in">
						<a data-dismiss="alert" class="close">&times;</a>
						<h4 class="alert-heading">Enter your new password below to reset your password.</h4>
				</div>
			</div>
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

<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<input type="hidden" name="user_id" value="<?php echo $user->id ?>" />

	<div class="control-group <?php echo iif( form_error('password') , 'error') ;?>">
		<label class="control-label" for="password"><?php echo lang('bf_password'); ?></label>
		<div class="controls">
			<input class="span6" type="password" name="password" id="password" value="" placeholder="Password...." />
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
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" id="submit" value="Save New Password"  />
		</div>
	</div>

<?php echo form_close(); ?>

	</div>
</div>
