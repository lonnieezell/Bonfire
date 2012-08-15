<?php if (!isset($current_user->email)) : ?>

<div class="modal hide" id="modal-login">
	<div class="modal-header">
		<h3><?php echo lang('bf_action_login') ?></h3>
	</div>

	<?php $this->load->helper('form'); ?>
		<?php echo form_open(site_url('login'), array('class' => "form-horizontal", 'autocomplete' => 'off', 'style' => "margin: 0;")); ?>

	<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="modal-login-value">
					<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_login_type_both') : ucwords($this->settings_lib->item('auth.login_type')) ?>
				</label>
				<div class="controls">
					<input type="text" name="login" id="modal-login-value" class="input-xlarge" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords($this->settings_lib->item('auth.login_type')) ?>" value="">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="modal-login-password"><?php echo lang('bf_password') ?></label>
				<div class="controls">
					<input type="password" name="password" id="modal-login-password" class="input-xlarge" placeholder="Password" value="">
				</div>
			</div>

			<?php if ($this->settings_lib->item('auth.allow_remember')) : ?>
			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="modal-login-remember">
						<input type="checkbox" name="remember" id="modal-login-remember" value="1" checked>
						<?php echo lang('bf_remember_me') ?>
					</label>
				</div>
			</div>
			<?php endif; ?>

		<div class="control-group">
			<div class="controls">
				<input type="submit" name="submit" id="log-me-in" value="<?php echo lang('bf_action_login') ?>" class="btn btn-primary">
				<a class="btn" data-dismiss="modal"><?php echo lang('bf_action_cancel'); ?></a>
			</div>
		</div>
	</div>

	<div class="modal-footer">
		<p style="text-align: center">
			<?php if ($this->settings_lib->item('auth.allow_register')) : ?>
				No account? <a href="<?php echo site_url('register') ?>"><?php echo lang('bf_action_register'); ?>!</a> |
			<?php endif; ?>

			<?php echo anchor('/forgot_password', lang('bf_forgot_password')); ?>
		</p>
	</div>

	<?php echo form_close(); ?>
</div>

<?php endif; ?>