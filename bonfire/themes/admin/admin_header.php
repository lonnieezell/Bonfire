<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title>Bonfire</title>
	
	<link rel="stylesheet" type="text/css" href="<?php echo Template::theme_url('screen.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo Template::theme_url('ui.css') ?>" />
	
	<script src="<?php echo base_url() .'assets/js/head.min.js' ?>"></script>
</head>
<body>

	<div id="toolbar">
		<div id="toolbar-right">
			<a href="<?php echo site_url('logout') ?>" id="tb_logout" title="Logout">Logout</a>
		</div>
	
		<?php if (isset($toolbar_title)) : ?>
			<h1><?php echo $toolbar_title ?></h1>
		<?php endif; ?>
		
		<div id="toolbar-left">
			<?php if ($this->auth->has_permission('Site.Content.View')) :?>
				<a href="<?php echo site_url('admin/content') ?>" <?php echo check_class('content') ?> id="tb_content" title="Content">Content</a>
			<?php endif; ?>
			<?php if ($this->auth->has_permission('Site.Statistics.View')) :?>
				<a href="<?php echo site_url('admin/stats') ?>" <?php echo check_class('stats') ?> id="tb_stats" title="Statistics">Statistics</a>
			<?php endif; ?>
			<?php if ($this->auth->has_permission('Site.Appearance.View')) :?>
				<a href="<?php echo site_url('admin/appearance') ?>" <?php echo check_class('appearance') ?> id="tb_appearance" title="Appearance">Appearance</a>
			<?php endif; ?>
			<?php if ($this->auth->has_permission('Site.Settings.View')) :?>
				<a href="<?php echo site_url('admin/settings') ?>" <?php echo check_class('settings') ?> id="tb_settings" title="Settings">Settings</a>
			<?php endif; ?>
			<?php if ($this->auth->has_permission('Site.Developer.View')) :?>
				<a href="<?php echo site_url('admin/developer') ?>" <?php echo check_class('developer') ?> id="tb_developer" title="Developer Tools">Developer Tools</a>
			<?php endif; ?>
		</div>	<!-- /toolbar-left -->
	</div>

	<div class="body">
	
		<div class="leftCol">
			<?php echo modules::run('sidebar/sidebar/index', $this->uri->segment(2)); ?>
		</div>
		
		<div class="main">
			<?php echo Template::message(); ?>