
<div class="container">

	<div class="hero-unit">
		<h1>Welcome to Bonfire.</h1>

		<p>Kickstart your CodeIgniter applications.</p>
	</div>

<p>If you're new to Bonfire, but familiar with <a href="http://www.codeigniter.com" target="_blank">CodeIgniter</a>, then you should be up an running within the system in no time.</p>

<p>If you're new to CodeIgniter, make sure you read through and understand the latest <a href="http://codeigniter.com/user_guide/" target="_blank">CodeIgniter User Guide</a> before diving into Bonfire. Your headaches will thank you.</p>


<p>If you are new to Bonfire, you should start by reading the <?php echo anchor('http://cibonfire.com/learn', 'docs', 'target="_blank"') ?>.</p>

<?php if (isset($current_user->email)) : ?>

	<div class="alert alert-info" style="text-align: center">
		<?php echo anchor(SITE_AREA, "Dive into Bonfire's Springboard"); ?>
	</div>

<?php else :?>

	<p style="text-align: center">
		<?php echo anchor('/login', '<i class="icon-lock icon-white"></i> '. lang('bf_action_login'), ' class="btn btn-primary btn-large" '); ?>
	</p>

<?php endif;?>



</div>
