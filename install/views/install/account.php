<?php $this->load->view('header'); ?>

<?php echo lang('in_intro'); ?>

<?php if (isset($startup_errors) && !empty($startup_errors)) :?>

	<h2><?php echo lang('in_not_writeable_heading'); ?></h2>
	
	<?php echo $startup_errors; ?>
	
	<p style="text-align: right; margin-top: 3em;"><?php echo anchor('install', lang('in_reload_page')); ?></p>

<?php else : ?>

	<?php if (isset($curl_error) && !empty($curl_error)) :?>
	
	<?php echo lang('in_curl_disabled'); ?>
	
	<?php endif; ?>
    

	<?php echo lang('in_account_heading'); ?>
	  
	
	<?php if (validation_errors()) : ?>
	<div class="notification information">
		<p><?php echo validation_errors(); ?></p>
	</div>
	<?php endif; ?>
	
	<?php echo form_open(current_url()) ?>
	
		<div>
			<?php echo form_simple_label('site_title', lang('in_site_title')); ?>
			<input type="text" name="site_title" id="site_title" placeholder="Ma magnifique application Bonfire" value="<?php echo set_value('site_title', config_item('site.title')) ?>" />
		</div>
		
		<div>
			<?php echo form_simple_label('username', lang('in_username')); ?>
			<input type="text" name="username" id="username" value="<?php echo set_value('username') ?>" />
		</div>
		
		<br />
		
		<div>
			<?php echo form_simple_label('password', lang('in_password')); ?>
			<input type="password" name="password" id="password" value="" />
			<p class="small"><?php echo lang('in_password_note'); ?></p>
		</div>
		
		<div>
			<?php echo form_simple_label('pass_confirm', lang('in_password_again')); ?>
			<input type="password" name="pass_confirm" id="pass_confirm" value="" />
		</div>
		
		<br/>
		
		<div>
			<?php echo form_simple_label('email', lang('in_email')); ?>
			<input type="email" name="email" id="email" placeholder="moi@maison.com" value="<?php echo set_value('email') ?>" />
			<p class="small"><?php echo lang('in_email_note'); ?></p>
		</div>
		
		<div class="submits">
			<input type="submit" name="submit" id="submit" value="<?php echo lang('in_install_button'); ?>" />
		</div>
	
	<?php echo form_close(); ?>
<?php endif; ?>

<?php $this->load->view('footer'); ?>