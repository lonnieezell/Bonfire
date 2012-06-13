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
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo lang('us_banned_admin_note'); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span8 offset2">
		<div class="alert alert-info fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<h4 class="alert-heading"><?php echo lang('bf_required_note'); ?></h4>
			<?php if (isset($password_hints)):?>
				<?php echo $password_hints; ?>
			<?php endif;?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">

<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal" autocomplete="off"'); ?>

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
		<label class="control-label required" for="username"><?php echo lang('bf_username'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="username" value="<?php echo isset($user) ? $user->username : set_value('username') ?>" />
		</div>
	</div>
	<?php endif; ?>

	<br />

	<div class="control-group <?php echo iif( form_error('password') , 'error') ;?>">
		<label class="control-label" for="password"><?php echo lang('bf_password'); ?></label>
		<div class="controls">
			<input class="span6" type="password" id="password" name="password" value="" />
		</div>
	</div>

	<div class="control-group <?php echo iif( form_error('pass_confirm') , 'error') ;?>">
		<label class="control-label" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
		<div class="controls">
			<input class="span6" type="password" id="pass_confirm" name="pass_confirm" value="" />
		</div>
	</div>

		<div class="control-group <?php echo form_error('language') ? 'error' : '' ?>">
			<label class="control-label required" for="language"><?php echo lang('bf_language') ?></label>
			<div class="controls">
				<select name="language" id="language" class="chzn-select">
				<?php if (isset($languages) && is_array($languages) && count($languages)) : ?>
					<?php foreach ($languages as $language) : ?>
						<option value="<?php echo $language ?>" <?php echo set_select('language', $language, isset($user->language) && $user->language == $language ? TRUE : FALSE) ?>>
							<?php echo ucfirst($language) ?>
						</option>

					<?php endforeach; ?>
				<?php endif; ?>
				</select>
				<?php if (form_error('language')) echo '<span class="help-inline">'. form_error('language') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('timezone') ? 'error' : '' ?>">
			<label class="control-label required" for="timezones"><?php echo lang('bf_timezone') ?></label>
			<div class="controls">
				<?php echo timezone_menu(set_value('timezones', isset($user) ? $user->timezone : $current_user->timezone)); ?>
				<?php if (form_error('timezones')) echo '<span class="help-inline">'. form_error('timezones') .'</span>'; ?>
			</div>
		</div>

		<?php
			// Allow modules to render custom fields
			Events::trigger('render_user_form', $user );
		?>

		<!-- Start User Meta -->
		<?php
		foreach ($meta_fields as $field):

			if (!(isset($field['frontend']) && $field['frontend'] === TRUE)):

				if ($field['form_detail']['type'] == 'dropdown'):

					echo form_dropdown($field['form_detail']['settings'], $field['form_detail']['options'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''));


				elseif ($field['form_detail']['type'] == 'state_select' && is_callable('state_select')) : ?>

					<div class="control-group <?php echo iif( form_error($field['name']) , 'error'); ?>">
						<label class="control-label" for="<?= $field['name'] ?>"><?php echo lang('user_meta_state'); ?></label>
						<div class="controls">

							<?php echo state_select(set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : 'SC'), 'SC', 'US', $field['name'], 'span6 chzn-select'); ?>

						</div>
					</div>

				<?php elseif ($field['form_detail']['type'] == 'country_select' && is_callable('country_select')) : ?>

					<div class="control-group <?php echo iif( form_error('country') , 'error'); ?>">
						<label class="control-label" for="country"><?php echo lang('user_meta_country'); ?></label>
						<div class="controls">

							<?php echo country_select(set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : 'US'), 'US', 'country', 'span6 chzn-select'); ?>

						</div>
					</div>

				<?php else:


					$form_method = 'form_' . $field['form_detail']['type'];
					if (is_callable($form_method))
					{
						echo $form_method($field['form_detail']['settings'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''), $field['label']);
					}


				endif;
			endif;

		endforeach;
		?>

	<!-- End of User Meta -->

	<!-- Start of Form Actions -->
	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('bf_user') ?> " /> <?php echo lang('bf_or') ?>
		<?php echo anchor('/', '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>

		<?php if (isset($user) && has_permission('Site.User.Manage')) : ?>
		<a class="btn btn-danger" id="delete-me" href="<?php echo site_url(SITE_AREA .'/settings/users/delete/'. $user->id); ?>" onclick="return confirm('<?php echo lang('us_delete_account_confirm'); ?>')"><i class="icon-trash icon-white">&nbsp;</i><?php echo lang('us_delete_account'); ?></a>

		<?php echo lang('us_delete_account_note'); ?>
		<?php endif; ?>
	</div>
	<!-- End of Form Actions -->

<?php echo form_close(); ?>

	</div>
</div>
</section>
