<section id="profile">

	<div class="page-header">
		<h1><?php echo lang('us_edit_profile'); ?></h1>
	</div>

<?php if (auth_errors() || validation_errors()) : ?>
<div class="row-fluid">
	<div class="span8 offset2">
		<div class="alert alert-error fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo auth_errors() . validation_errors(); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (isset($user) && $user->role_name == 'Banned') : ?>
<div class="row-fluid">
	<div class="span8 offset2">
		<div data-dismiss="alert" class="alert alert-error fade in">
		  <a class="close">&times;</a>
			<?php echo lang('us_banned_admin_note'); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span8 offset2">
		<div class="alert alert-info fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo lang('bf_required_note'); ?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span8 offset2">

<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<div class="control-group <?php echo iif( form_error('display_name') , 'error') ;?>">
		<label class="control-label" for="display_name"><?php echo lang('bf_display_name'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="display_name" value="<?php echo isset($user) ? $user->display_name : set_value('display_name') ?>" />
		</div>
	</div>

	<div class="control-group <?php echo iif( form_error('email') , 'error') ;?>">
		<label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="email" value="<?php echo isset($user) ? $user->email : set_value('email') ?>" />
		</div>
	</div>

	<?php if ( config_item('auth.login_type') !== 'email' OR config_item('auth.use_usernames')) : ?>
	<div class="control-group <?php echo iif( form_error('username') , 'error') ;?>">
		<label class="control-label" for="username"><?php echo lang('bf_username'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="username" value="<?php echo isset($user) ? $user->username : set_value('username') ?>" />
		</div>
	</div>
	<?php endif; ?>

	<br />

	<div class="control-group <?php echo iif( form_error('password') , 'error') ;?>">
		<label class="control-label required" for="password"><?php echo lang('bf_password'); ?></label>
		<div class="controls">
			<input class="span6" type="password" id="password" name="password" value="" />
			<span class="help-inline" id='strength' style="display:none;">
				<span class="label span1"><i class="strength-icon icon-white"></i> <span class="txt"></span></span>
			</span>
		</div>
	</div>

	<div class="control-group <?php echo iif( form_error('pass_confirm') , 'error') ;?>">
		<label class="control-label required" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
		<div class="controls">
			<input class="span6" type="password" id="pass_confirm" name="pass_confirm" value="" />
			<span class="help-inline" id='match' style="display:none;margin-left:5px;">
				<span class="label span1"><i class="match-icon icon-white"></i> <span class="txt"></span></span>
			</span>
		</div>
	</div>


		<?php
			// Allow modules to render custom fields
			Events::trigger('render_user_form', $user );
		?>


	<div class="control-group">
		<label class="control-label" for="submit">&nbsp;</label>
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " />
		</div>
	</div>

	<?php if (isset($user) && has_permission('Site.User.Manage')) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .'/settings/users/delete/'. $user->id); ?>" onclick="return confirm('<?php echo lang('us_delete_account_confirm'); ?>')"><i class="icon-trash icon-white">&nbsp;</i><?php echo lang('us_delete_account'); ?></a>

		<?php echo lang('us_delete_account_note'); ?>
	</div>
	<?php endif; ?>

<?php echo form_close(); ?>

	</div>
</div>
</section>
