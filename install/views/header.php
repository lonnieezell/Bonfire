<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />

	<title><?php echo config_item('site.title'); ?></title>

	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

	<?php if(lang('bf_language_direction') == 'rtl'): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/screen-rtl.css'); ?>" />
	<?php else: ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/screen.css'); ?>" />
	<?php endif; ?>
	
	<script src="<?php echo base_url('js/jquery-1.7.2.min.js') ?>"></script>
	<script src="<?php echo base_url('js/install.js') ?>"></script>
	<script>base_url = '<?php echo base_url(); ?>';</script>
</head>
<body>

		<!-- Header -->
		<div class="head text-right">
			<div class="inner">
				<h1><img src="<?php echo base_url('images/bonfire_logo.png') ?>" /></h1>
			</div>
		</div>

		<div class="main">

			<?php if (isset($message)) :?>
			<?php echo $message; ?>
			<?php endif; ?>
