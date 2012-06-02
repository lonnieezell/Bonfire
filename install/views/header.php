<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />

	<title><?php echo config_item('site.title'); ?></title>

	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

	<?php if(lang('bf_language_direction') == 'rtl'): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() .'assets/css/screen-rtl.css'; ?>" />
	<?php else: ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() .'assets/css/screen.css'; ?>" />
	<?php endif; ?>
</head>
<body>

		<!-- Header -->
		<div class="head text-right">
			<div class="inner">
				<h1><img src="<?php echo site_url('views/images/bonfire_logo.png') ?>" /></h1>
			</div>
			<ul id="flags">
			<?php foreach($languages as $language): ?>
				<li>
					<a href="<?php echo base_url('change/'.$language); ?>" title="<?php echo $language; ?>">
						<img src="<?php echo base_url(); ?>assets/images/flags/<?php echo $language; ?>.png" alt="<?php echo $language; ?>" height="32" />
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>

		<div class="main">

			<?php if (isset($error)) :?>
			<div class="notification error">
				<p><?php echo $error; ?></p>
			</div>
			<?php endif; ?>

			<?php if (isset($attention)) :?>
			<div class="notification attention">
				<p><?php echo $attention; ?></p>
			</div>
			<?php endif; ?>

			<?php if (isset($success)) :?>
			<div class="notification success">
				<p><?php echo $success; ?></p>
			</div>
			<?php endif; ?>