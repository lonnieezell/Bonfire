<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title><?php echo $page_title ?> @ <?php echo $this->config->item('site.title') ?></title>
	
	<link rel="stylesheet" type="text/css" href="<?php echo Template::theme_url('screen.css') ?>" />
	
	<script src="<?php echo base_url() .'assets/js/head.min.js' ?>" ></script>
</head>
<body>

	<div class="head">
		<?php echo anchor('', '&laquo; Back to '. $this->config->item('site.title')); ?>
	</div>