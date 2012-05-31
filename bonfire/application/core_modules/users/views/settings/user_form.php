<?php if (isset($user) && $user->banned) : ?>
<div class="alert alert-warning fade in">
	<h4 class="alert-heading"><?php echo lang('us_banned_admin_note'); ?></h4>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span8 offset2">
		<div class="alert alert-info fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<h4 class="alert-heading"><?php echo lang('bf_required_note'); ?></h4>
			<?php if (isset($password_hints) ) echo $password_hints; ?>
		</div>
	</div>
</div>

<div class="admin-box">

	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal" autocomplete="off"'); ?>

	<fieldset>
		<legend><?php echo lang('us_account_details') ?></legend>

		<div class="control-group <?php echo form_error('email') ? 'error' : '' ?>">
			<label for="email" class="control-label"><?php echo lang('bf_email') ?></label>
			<div class="controls">
				<input type="email" name="email" id="email" value="<?php echo isset($user) ? $user->email : set_value('email') ?>">
				<?php if (form_error('email')) echo '<span class="help-inline">'. form_error('email') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('username') ? 'error' : '' ?>">
			<label for="username" class="control-label"><?php echo lang('bf_username') ?></label>
			<div class="controls">
				<input type="text" name="username" id="username" value="<?php echo isset($user) ? $user->username : set_value('username') ?>">
				<?php if (form_error('username')) echo '<span class="help-inline">'. form_error('username') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('display_name') ? 'error' : '' ?>">
			<label for="display_name" class="control-label"><?php echo lang('bf_display_name') ?></label>
			<div class="controls">
				<input type="text" name="display_name" id="display_name" value="<?php echo isset($user) ? $user->display_name : set_value('display_name') ?>">
				<?php if (form_error('display_name')) echo '<span class="help-inline">'. form_error('display_name') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('password') ? 'error' : '' ?>">
			<label for="password" class="control-label"><?php echo lang('bf_password') ?></label>
			<div class="controls">
				<input type="password" id="password" name="password" value="">
				<?php if (form_error('password')) echo '<span class="help-inline">'. form_error('password') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('pass_confirm') ? 'error' : '' ?>">
			<label class="control-label" for="pass_confirm"><?php echo lang('bf_password_confirm') ?></label>
			<div class="controls">
				<input type="password" name="pass_confirm" id="pass_confirm" value="">
				<?php if (form_error('pass_confirm')) echo '<span class="help-inline">'. form_error('pass_confirm') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('language') ? 'error' : '' ?>">
			<label class="control-label" for="language"><?php echo lang('bf_language') ?></label>
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
			<label class="control-label" for="timezones"><?php echo lang('bf_timezone') ?></label>
			<div class="controls">
				<?php echo timezone_menu(set_value('timezones', isset($user) ? $user->timezone : $current_user->timezone)); ?>
				<?php if (form_error('timezones')) echo '<span class="help-inline">'. form_error('timezones') .'</span>'; ?>
			</div>
		</div>


		<?php if (isset($user) && has_permission('Bonfire.Roles.Manage') && has_permission('Permissions.'.$user->role_name.'.Manage') && isset($roles) ) :?>
		<fieldset>
			<legend><?php echo lang('us_role'); ?></legend>

			<div class="control-group">
				<label for="role_id" class="control-label"><?php echo lang('us_role'); ?></label>
				<div class="controls">
					<select name="role_id" id="role_id" class="chzn-select">
					<?php if (isset($roles) && is_array($roles) && count($roles)) : ?>
						<?php foreach ($roles as $role) : ?>

							<?php if (has_permission('Permissions.'. ucfirst($role->role_name) .'.Manage')) : ?>
							<?php
								// check if it should be the default
								$default_role = FALSE;
								if ((isset($user) && $user->role_id == $role->role_id) || (!isset($user) && $role->default == 1))
								{
									$default_role = TRUE;
								}
							?>
							<option value="<?php echo $role->role_id ?>" <?php echo set_select('role_id', $role->role_id, $default_role) ?>>
								<?php echo ucfirst($role->role_name) ?>
							</option>

							<?php endif; ?>

						<?php endforeach; ?>
					<?php endif; ?>
					</select>
				</div>
			</div>
		</fieldset>
		<?php endif; ?>

		<?php
			// Allow modules to render custom fields
			Events::trigger('render_user_form');
		?>


		<!-- Start of User Meta -->
		<?php
		foreach ($meta_fields as $field):

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
				if ( is_callable($form_method) )
				{
					echo $form_method($field['form_detail']['settings'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''), $field['label']);
				}


			endif;

		endforeach;
		?>

	<!-- End of User Meta -->


		<?php if (isset($user) && has_permission('Permissions.'. ucfirst($user->role_name).'.Manage') && $user->id != $this->auth->user_id() && ($user->banned || $user->deleted)) : ?>
		<fieldset>
			<legend><?php echo lang('us_account_status') ?></legend>

			<?php
			$field = 'activate';
			if ($user->active) :
					$field = 'de'.$field;
			endif; ?>
			<div class="control-group">
					<div class="controls">
							<label>
									<input type="checkbox" name="<?php echo $field; ?>" value="1">
									<?php echo lang('us_'.$field.'_note') ?>
							</label>
					</div>
			</div>

			<?php if ($user->deleted) : ?>
			<div class="control-group">
				<div class="controls">
					<label>
						<input type="checkbox" name="restore" value="1">
						<?php echo lang('us_restore_note') ?>
					</label>
				</div>
			</div>

			<?php elseif ($user->banned) :?>
			<div class="control-group">
				<div class="controls">
					<label>
						<input type="checkbox" name="unban" value="1">
						<?php echo lang('us_unban_note') ?>
					</label>
				</div>
			</div>
			<?php endif; ?>

		</fieldset>
		<?php endif; ?>


		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('bf_user') ?> " /> <?php echo lang('bf_or') ?>
			<?php echo anchor(SITE_AREA .'/settings/users', '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
		</div>

	<?php echo form_close(); ?>

</div>
