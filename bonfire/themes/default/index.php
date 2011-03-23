<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title><?php echo $page_title .' @ '. config_item('site.site_title'); ?></title>

	<?php Assets::css(); ?>
</head>
<body>

	<div class="page">
	
		<!-- Header -->
		<div class="head text-right">
			<h1>Bonfire</h1>
		</div>
		
		<div class="main">
			<?php echo Template::message(); ?>
			<?php echo Template::yield(); ?>

		</div>	<!-- /main -->
	</div>	<!-- /page -->
	
	<div class="foot">
		<p>Powered by <a href="http://cibonfire.com" target="_blank">Bonfire <?php echo BONFIRE_VERSION ?></a></p>
	</div>
</body>
</html>