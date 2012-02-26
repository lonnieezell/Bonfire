<div class="page-header">
  <h1>Welcome to Bonfire.</h1>
</div>

<p>Ready to pour some kerosene on the logs and start a Bonfire?</p>

<p>If you're new to Bonfire, but familiar with <a href="http://www.codeigniter.com" target="_blank">CodeIgniter</a>, then you should be up an running within the system in no time. If you're new to CodeIgniter, make sure you read through and understand the latest <a href="http://codeigniter.com/user_guide/" target="_blank">CodeIgniter User Guide</a> before diving into Bonfire. Your headaches will thank you.</p>

<br/>

<div class="well">
<p>If you would like to edit this page you'll find it located at:</p>

<div class="notification information">
	<p><code>bonfire/application/views/home/index.php</code></p>
</div>

<p>The corresponding controller for this page is found at:</p>

<div class="notification information">
	<p><code>bonfire/application/controllers/home.php</code></p>
</div>

</div>

<p>If you are new to Bonfire, you should start by reading the <?php echo anchor('/docs', '<i class="icon-info-sign icon-white"></i>docs', ' class="btn btn-info btn-small" ') ?>.</p>

<?php
	// acessing our userdata cookie
	$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
	$logged_in = isset ($cookie['logged_in']);
	unset ($cookie);

	if ($logged_in) : ?>

	<div class="notification attention" style="text-align: center">
		<?php echo anchor(SITE_AREA, 'Dive into Bonfire\'s Springboard'); ?>
	</div>

<?php else :?>

	<p style="text-align: center">
		<?php echo anchor('/login', '<i class="icon-lock icon-white"></i>Login', ' class="btn btn-primary btn-large" '); ?>
	</p>

<?php endif;?>
