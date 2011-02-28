<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title><?php echo config_item('site.title') ?></title>
	
	<?php Assets::css(); ?>
	
	<script src="<?php echo base_url() .'assets/js/head.min.js'; ?>"></script>
</head>
<body>

	<div class="body">
		
		<h1 id="site-title"><?php echo config_item('site.title') ?></h1>

		<?php echo Template::message(); ?>
		
		<div class="main">