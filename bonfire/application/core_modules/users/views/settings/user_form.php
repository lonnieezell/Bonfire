<?php if (isset($user) && $user->banned) : ?>
<div class="alert alert-warning fade in">
	<h4 class="alert-heading"><?php echo lang('us_banned_admin_note'); ?></h4>
</div>
<?php endif; ?>

<div class="admin-box">

	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<fieldset>
		<legend><?php echo lang('us_account_details') ?></legend>

		<div class="control-group <?php echo form_error('email') ? 'error' : '' ?>">
			<label for="email" class="control-label"><?php echo lang('bf_email') ?></label>
			<div class="controls">
				<input type="email" name="email" value="<?php echo isset($user) ? $user->email : set_value('email') ?>">
				<?php if (form_error('email')) echo '<span class="help-inline">'. form_error('email') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('username') ? 'error' : '' ?>">
			<label for="username" class="control-label"><?php echo lang('bf_username') ?></label>
			<div class="controls">
				<input type="text" name="username" value="<?php echo isset($user) ? $user->username : set_value('username') ?>">
				<?php if (form_error('username')) echo '<span class="help-inline">'. form_error('username') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('display_name') ? 'error' : '' ?>">
			<label for="display_name" class="control-label"><?php echo lang('bf_display_name') ?></label>
			<div class="controls">
				<input type="text" name="display_name" value="<?php echo isset($user) ? $user->display_name : set_value('display_name') ?>">
				<?php if (form_error('display_name')) echo '<span class="help-inline">'. form_error('display_name') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('password') ? 'error' : '' ?>">
			<label for="username" class="control-label"><?php echo lang('bf_password') ?></label>
			<div class="controls">
				<input type="password" name="password" value="">
				<?php if (form_error('password')) echo '<span class="help-inline">'. form_error('password') .'</span>'; ?>
				<span class="help-inline" id='strength' style="display:none;">
					<span class="label span1"><i class="strength-icon icon-white"></i> <span class="txt"></span></span>
				</span>
			</div>
		</div>

		<div class="control-group <?php echo form_error('pass_confirm') ? 'error' : '' ?>">
			<label class="control-label" for="username"><?php echo lang('bf_password_confirm') ?></label>
			<div class="controls">
				<input type="password" name="pass_confirm" value="">
				<span class="help-inline" id='match' style="display:none;margin-left:5px;">
					<span class="label span1"><i class="match-icon icon-white"></i> <span class="txt"></span></span>
				</span>
				<?php if (form_error('pass_confirm')) echo '<span class="help-inline">'. form_error('pass_confirm') .'</span>'; ?>
			</div>
		</div>

		<?php if (has_permission('Bonfire.Roles.Manage')) :?>
		<fieldset>
			<legend><?php echo lang('us_role'); ?></legend>

			<div class="control-group">
				<label for="role_id" class="control-label"><?php echo lang('us_role'); ?></label>
				<div class="controls">
					<select name="role_id">
					<?php if (isset($roles) && is_array($roles) && count($roles)) : ?>
						<?php foreach ($roles as $role) : ?>

							<?php if (has_permission('Permissions.'. ucfirst($role->role_name) .'.Manage')) : ?>

							<option value="<?php echo $role->role_id ?>" <?php echo isset($user) && $user->role_id == $role->role_id ? 'selected="selected"' : '' ?> <?php echo !isset($user) && $role->default == 1 ? 'selected="selected"' : ''; ?>>
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


		<?php if (isset($user) && has_permission('Permissions.'. ucfirst($user->role_name).'.Manage') && $user->id != $this->auth->user_id() && ($user->banned || $user->deleted)) : ?>
		<fieldset>
			<legend><?php echo lang('us_account_status') ?></legend>

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
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('bf_user') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings/users', lang('bf_action_cancel')); ?>
		</div>

	<?php echo form_close(); ?>

</div>
