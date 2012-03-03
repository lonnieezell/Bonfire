<?php
	// Setup our default assets to load.
	Assets::add_js( array(
		base_url() .'assets/js/jquery-1.7.1.min.js',
	));
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title><?php echo config_item('site.title'); ?></title>
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

	<?php echo Assets::css(); ?>
</head>
<body>

	<div class="page">
	
		<!-- Header -->
		<div class="head text-right">
			<h1>Bonfire</h1>
		</div>

		<div class="main">
			<?php  
				// acessing our userdata cookie
				$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
				$logged_in = isset ($cookie['logged_in']);
				unset ($cookie);

				if ($logged_in) : ?>
			<div class="profile">
				<a href="<?php echo site_url();?>">Home</a> | 
				<a href="<?php echo site_url('users/profile');?>">Edit Profile</a> | 
				<a href="<?php echo site_url('logout');?>">Logout</a>
			</div>
			<?php endif;?>