<div class="modal hide" id="modal-login">
	<div class="modal-header">
		<h3>Sign In</h3>
	</div>
	
	<?php $this->load->helper('form'); ?>
		<?php echo form_open(site_url('login'), 'class="form-horizontal" style="margin: 0;"'); ?>
	
	<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="login">Email</label>
				<div class="controls">
					<input type="text" name="login" class="input-xlarge" placeholder="me@myemail.com" value="">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<input type="password" name="password" class="input-xlarge" value="">
				</div>
			</div>
			
			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="remember">
						<input type="checkbox" name="remember" value="1" checked>
						Remember me					
					</label>
				</div>
			</div>
		
			<?php if ($this->settings_lib->item('auth.allow_register')) : ?>
				<hr>
			
				<p style="text-align: center">No account? <a href="<?php echo site_url('register') ?>">Sign Up!</a></p>
			<?php endif; ?>
	</div>
	
	<div class="modal-footer">
		<input type="submit" name="submit" id="log-me-in" class="btn btn-primary">Sign In</a>
		<a class="btn" data-dismiss="modal">Cancel</a>
	</div>
	
	<?php echo form_close(); ?>
</div>