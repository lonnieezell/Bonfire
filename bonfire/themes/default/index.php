<?php
	// Setup our default assets to load.
	Assets::add_js( array(
		base_url() .'assets/js/jquery-1.5.min.js',
	));
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title><?php echo config_item('site.title'); ?></title>
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

	<?php echo Assets::css(); ?>
	
	<?php echo Assets::external_js('head.min.js'); ?>
</head>
<body>

	<div class="page">
	
		<!-- Header -->
		<div class="head text-right">
			<h1>Bonfire</h1>
		</div>
		
		<div class="main">
			<?php echo Template::message(); ?>
			<?php echo isset($content) ? $content : Template::yield(); ?>

		</div>	<!-- /main -->
	</div>	<!-- /page -->
	
	<div class="foot">
		<?php if (ENVIRONMENT == 'development') :?>
			<p style="float: right; margin-right: 80px;">Page rendered in {elapsed_time} seconds, using {memory_usage}.</p>
		<?php endif; ?>
		
		<p>Powered by <a href="http://cibonfire.com" target="_blank">Bonfire <?php echo BONFIRE_VERSION ?></a></p>
	</div>
	
	<div id="debug"></div>
	
	<script>
		head.js(<?php echo Assets::external_js(null, true) ?>);
		head.js(<?php echo Assets::module_js(true) ?>);
	</script>
	<?php echo Assets::inline_js(); ?>
</body>
</html>