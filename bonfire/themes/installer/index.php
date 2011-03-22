<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title>Bonfire Installer</title>

	<link rel="stylesheet" type="text/css" href="<?php echo Template::theme_url('screen.css') ?>" />

	<script src="/assets/js/jquery-1.5.min.js"></script>	
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
		<p>Powered by <strong>Bonfire <?php echo BONFIRE_VERSION ?></strong></p>
	</div>
</body>
</html>