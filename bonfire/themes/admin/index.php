<?php
	// Setup our default assets to load.
	Assets::add_js( array(
		base_url() .'assets/js/jquery-1.5.min.js',
		Template::theme_url('js/jquery.form.js'),
		Template::theme_url('js/jquery.ajaxify.min.js'),
		Template::theme_url('js/ui.js')
	));
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php echo config_item('site.title') ?></title>
	
	<?php Assets::css(null, 'screen', true); ?>
	
	<!-- Fix the mobile Safari auto-zoom bug -->
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<script src="<?php echo base_url() .'assets/js/head.min.js' ?>"></script>
</head>
<body>

	<noscript>
		<p>Javascript is required to use Bonfire's admin.</p>
	</noscript>
	
	<div id="message">
		<?php echo Template::message(); ?>
	</div>
	
	<!-- Nav Bar -->
	<div id="toolbar">
		<div id="toolbar-right">
			<a href="<?php echo site_url('admin/settings/users/edit/'. $this->auth->user_id()) ?>" id="tb_email"><?php echo $this->auth->email() ?></a>
			<a href="<?php echo site_url('logout') ?>" id="tb_logout" title="Logout">Logout</a>
		</div>
	
		<h1><?php echo config_item('site.title') ?></h1>
		
		<div id="toolbar-left">
			<?php if (has_permission('Site.Content.View')) :?>
				<a href="<?php echo site_url('admin/content') ?>" <?php echo check_class('content') ?> id="tb_content" title="Content">Content</a>
			<?php endif; ?>
			<?php if (has_permission('Site.Statistics.View')) :?>
				<a href="<?php echo site_url('admin/stats') ?>" <?php echo check_class('stats') ?> id="tb_stats" title="Statistics">Statistics</a>
			<?php endif; ?>
			<?php if (has_permission('Site.Appearance.View')) :?>
				<a href="<?php echo site_url('admin/appearance') ?>" <?php echo check_class('appearance') ?> id="tb_appearance" title="Appearance">Appearance</a>
			<?php endif; ?>
			<?php if (has_permission('Site.Settings.View')) :?>
				<a href="<?php echo site_url('admin/settings') ?>" <?php echo check_class('settings') ?> id="tb_settings" title="Settings">Settings</a>
			<?php endif; ?>
			<?php if (has_permission('Site.Developer.View')) :?>
				<a href="<?php echo site_url('admin/developer') ?>" <?php echo check_class('developer') ?> id="tb_developer" title="Developer Tools">Developer Tools</a>
			<?php endif; ?>
		</div>	<!-- /toolbar-left -->
	</div>
	
	<?php echo modules::run('subnav/subnav/index', $this->uri->segment(2)); ?>

	<div id="nav-bar">
		<?php if (isset($toolbar_title)) : ?>
			<h1><?php echo $toolbar_title ?></h1>
		<?php endif; ?>
		
		<?php Template::block('sub_nav', ''); ?>
	</div>

	<div class="content-main <?php echo isset(Template::$blocks['nav_bottom']) ? 'with-bottom-bar' : '' ?>">
			<?php echo Template::yield(); ?>
	</div>
	
	<?php Template::block('nav_bottom', ''); ?>
	
	<script>
		head.js(<?php echo Assets::external_js(null, true) ?>);
	</script>
	<?php echo Assets::inline_js(); ?>

</body>
</html>