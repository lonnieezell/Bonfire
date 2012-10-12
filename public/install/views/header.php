<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />

	<title><?php echo config_item('site.title'); ?></title>

	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

	<?php if(lang('bf_language_direction') == 'rtl'): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() .'views/screen-rtl.css'; ?>" />
	<?php else: ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() .'views/screen.css'; ?>" />
	<?php endif; ?>
</head>
<body>

		<!-- Header -->
		<div class="head text-right">
			<div class="inner">
				<h1><img src="<?php echo site_url('views/images/bonfire_logo.png') ?>" /></h1>
			</div>
		</div>

		<div class="main">

			<?php if (isset($message)) :?>
			<?php echo $message; ?>
			<?php endif; ?>
