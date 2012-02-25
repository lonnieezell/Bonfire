<section id="login">
	
	<div class="page-header">
		<h1><?php echo lang('us_edit_profile'); ?></h1>
	</div>

<?php if (auth_errors() || validation_errors()) : ?>
<div class="row-fluid">
	<div class="span2">
		&nbsp;
	</div>
	<div class="span8">
		<div class="alert alert-error fade in">
		  <a data-dismiss="alert" class="close">×</a>
			<?php echo auth_errors() . validation_errors(); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (isset($user) && $user->role_name == 'Banned') : ?>
<div class="row-fluid">
	<div class="span2">
		&nbsp;
	</div>
	<div class="span8">
		<div data-dismiss="alert" class="alert alert-error fade in">
		  <a class="close">×</a>
			<?php echo lang('us_banned_admin_note'); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span2">
		&nbsp;
	</div>
	<div class="span8">
		<div class="alert alert-info fade in">
		  <a data-dismiss="alert" class="close">×</a>
			<?php echo lang('bf_required_note'); ?>
		</div>
	</div>
</div>



<div class="row-fluid">
	<div class="span2">
		&nbsp;
	</div>
	<div class="span8">

<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<div class="control-group">
		<label class="control-label" for="login_value"><?php echo $this->settings_lib->item('auth.login_type') == 'both' ? 'Username/Email' : ucwords($this->settings_lib->item('auth.login_type')) ?></label>
		<div class="controls">
			<input class="span6" type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords($this->settings_lib->item('auth.login_type')) ?>" />		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="first_name"><?php echo lang('us_first_name'); ?></label>		
		<div class="controls">			
			<input class="span6" type="text" name="first_name" value="<?php echo isset($user) ? $user->first_name : set_value('first_name') ?>" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="last_name"><?php echo lang('us_last_name'); ?></label>		
		<div class="controls">			
			<input class="span6" type="text" name="last_name" value="<?php echo isset($user) ? $user->last_name : set_value('last_name') ?>" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>		
		<div class="controls">			
			<input class="span6" type="text" name="email" value="<?php echo isset($user) ? $user->email : set_value('email') ?>" />
		</div>
	</div>
		
	<?php if ( config_item('auth.login_type') !== 'email' OR config_item('auth.use_usernames')) : ?>
	<div class="control-group">
		<label class="control-label" for="username"><?php echo lang('bf_username'); ?></label>		
		<div class="controls">			
			<input class="span6" type="text" name="username" value="<?php echo isset($user) ? $user->username : set_value('username') ?>" />
		</div>
	</div>
	<?php endif; ?>

	<br />	

	<div class="control-group">
		<label class="control-label required" for="password"><?php echo lang('bf_password'); ?></label>		
		<div class="controls">			
			<input class="span6" type="password" id="password" name="password" value="" />		
		</div>
	</div>

	<div class="control-group">
		<label class="control-label required" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>		
		<div class="controls">			
			<input class="span6" type="password" id="pass_confirm" name="pass_confirm" value="" />
		</div>
	</div>
	
	<?php if ( ! config_item('auth.use_extended_profile')) :?>
	<fieldset>
		<legend><?php echo lang('us_address'); ?></legend>

		<div class="control-group">
			<label class="control-label" for="street_1"><?php echo lang('us_street_1'); ?></label>		
			<div class="controls">			
				<input type="text" name="street_1" class="span6" value="<?php echo isset($user) ? $user->street_1 : set_value('street_1') ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="street_2"><?php echo lang('us_street_2'); ?></label>		
			<div class="controls">			
				<input type="text" name="street_2" class="span6" value="<?php echo isset($user) ? $user->street_2 : set_value('street_2') ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="city"><?php echo lang('us_city'); ?></label>		
			<div class="controls">			
				<input type="text" name="city" class="span6" value="<?php echo isset($user) ? $user->city : set_value('city') ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="iso"><?php echo lang('us_country'); ?></label>		
			<div class="controls">			
				<?php echo country_select(isset($user) && !empty($user->country_iso) ? $user->country_iso : 'US', 'US'); ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="state_code"><?php echo lang('us_state'); ?></label>		
			<div class="controls">			
				<?php echo state_select(isset($user) ? $user->state_code : '', 'SC', isset($user) && !empty($user->country_iso) ? $user->country_iso : 'US'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="zipcode"><?php echo lang('us_zipcode'); ?></label>		
			<div class="controls">			
				<input type="text" name="zipcode" class="span6" value="<?php echo isset($user) ? $user->zipcode : set_value('zipcode') ?>" />
			</div>
		</div>

	</fieldset>
	<?php endif; ?>

	<div class="control-group">
		<label class="control-label" for="submit">&nbsp;</label>		
		<div class="controls">			
			<input class="btn btn-primary" type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " />
		</div>
	</div>
	
	<?php if (isset($user) && has_permission('Site.User.Manage')) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .'/settings/users/delete/'. $user->id); ?>" onclick="return confirm('<?php echo lang('us_delete_account_confirm'); ?>')"><?php echo lang('us_delete_account'); ?></a>
		
		<?php echo lang('us_delete_account_note'); ?>
	</div>
	<?php endif; ?>

<?php echo form_close(); ?>

	</div>
</div>
</section>