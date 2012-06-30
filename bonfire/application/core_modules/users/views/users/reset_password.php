		<div class="page-header">
				<h1><?php echo lang('us_reset_password'); ?></h1>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<div class="alert alert-info fade in">
						<a data-dismiss="alert" class="close">&times;</a>
						<h4 class="alert-heading"><?php echo lang('us_reset_password_note'); ?></h4>
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
		<label class="control-label" for="password"><?php echo lang('us_password'); ?></label>
		<div class="controls">
			<input class="span6" type="password" name="password" id="password" value="" placeholder="<?php echo lang('us_password'); ?>" />
			<p class="help-block"><?php echo $password_mins; ?></p>
		</div>
	</div>

	<div class="control-group <?php echo iif( form_error('pass_confirm') , 'error') ;?>">
		<label class="control-label" for="pass_confirm"><?php echo lang('us_password_confirm'); ?></label>
		<div class="controls">
			<input class="span6" type="password" name="pass_confirm" id="pass_confirm" value="" placeholder="<?php echo lang('us_password_confirm'); ?>" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="submit">&nbsp;</label>
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" id="submit" value="<?php echo lang('us_save_password'); ?>"  />
		</div>
	</div>

<?php echo form_close(); ?>

	</div>
</div>