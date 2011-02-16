<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title>Crust.Me Administration</title>
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() .'assets/css/front.css' ?>" />
	
	<script src="<?php echo base_url() .'assets/js/head.min.js'; ?>"></script>
</head>
<body>

	<div class="body">
	
		<!-- Header -->
		<div class="head text-right">
			<?php if ($this->auth->is_logged_in()) : ?>
				<?php echo anchor('logout','Logout'); ?>
			<?php else : ?>
				<?php echo anchor('login','Login'); ?>
			<?php endif; ?>
		</div>

		<?php echo Template::message(); ?>
		
		<div class="main">