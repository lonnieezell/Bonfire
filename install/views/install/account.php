<?php $this->load->view('header'); ?>

	<?php $this->load->view('install/menu'); ?>

    <?php if (validation_errors()) : ?>
	<div class="notification information">
		<p><?php echo validation_errors(); ?></p>
	</div>
	<?php endif; ?>
    
	<h2><?php echo lang('in_site_title') ?></h2>
	
	<?php echo form_open(current_url()) ?>
	
		<div>
			<label for="site_title"><?php echo lang('in_site_title'); ?></label>
			<input type="text" name="site_title" id="site_title" value="<?php echo set_value('site_title', 'Bonfire Site') ?>" />
		</div>

	<?php echo lang('in_account_heading'); ?>
	  
	  	<div>
			<label for="email"><?php echo lang('in_email'); ?></label>
			<input type="email" name="email" id="email" placeholder="me@home.com" value="<?php echo set_value('email') ?>" />
			<p class="small"><?php echo lang('in_email_note'); ?></p>
		</div>
		
		<div>
			<label for="username"><?php echo lang('bf_username'); ?></label>
			<input type="text" name="username" id="username" value="<?php echo set_value('username', 'admin') ?>" />
		</div>
		
		<br />
		
		<div>
			<label for="password"><?php echo lang('bf_password'); ?></label>
			<input type="password" name="password" id="password" value="" />
			<p class="small"><?php echo lang('in_password_note'); ?></p>
		</div>
		
		<div>
			<label for="pass_confirm"><?php echo lang('in_password_again'); ?></label>
			<input type="password" name="pass_confirm" id="pass_confirm" value="" />
		</div>
		
		
		<div class="submits">
			<input type="submit" name="submit" id="submit" value="<?php echo lang('in_install_button'); ?>" />
		</div>
	
	<?php echo form_close(); ?>

<?php $this->load->view('footer'); ?>