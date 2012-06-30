<?php if (!isset($current_user->email)) : ?>

<div class="modal hide" id="modal-login">
	<div class="modal-header">
		<h3><?php echo lang('us_login'); ?></h3>
	</div>
	
	<?php $this->load->helper('form'); ?>
		<?php echo form_open(site_url('login'), 'class="form-horizontal" style="margin: 0;"'); ?>
	
	<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="login">
					<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('us_username') .'/'. lang('us_email') : lang('us_' . $this->settings_lib->item('auth.login_type')); ?>
				</label>
				<div class="controls">
					<input type="text" name="login" class="input-xlarge" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('us_username') .'/'. lang('us_email') : lang('us_' . $this->settings_lib->item('auth.login_type')); ?>" value="">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="password"><?php echo lang('us_password'); ?></label>
				<div class="controls">
					<input type="password" name="password" class="input-xlarge" placeholder="<?php echo lang('us_password'); ?>" value="">
				</div>
			</div>
			
			<?php if ($this->settings_lib->item('auth.allow_remember')) : ?>
			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="remember">
						<input type="checkbox" name="remember" value="1" checked>
						<?php echo lang('us_remember_me'); ?>				
					</label>
				</div>
			</div>
			<?php endif; ?>
		
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="submit" id="log-me-in" value="<?php echo lang('us_action_sign_in') ?>" class="btn btn-primary"> <?php echo lang('bf_or'); ?> 
				<a class="btn" data-dismiss="modal"><?php echo lang('bf_action_cancel'); ?></a>
			</div>
		</div>
	</div>
	
	<div class="modal-footer">
			<?php if ($this->settings_lib->item('auth.allow_register')) : ?>
				<p style="text-align: center">
					<?php echo lang('us_no_account'); ?> <?php echo anchor('/register', lang('us_sign_up')); ?>&nbsp;|&nbsp;
					<?php echo anchor('/forgot_password', lang('us_forgot_password')); ?>
				</p>
			<?php endif; ?>
	</div>
	
	<?php echo form_close(); ?>
</div>

<?php endif; ?>