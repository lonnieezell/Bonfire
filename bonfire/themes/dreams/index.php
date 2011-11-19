<?php
	// Setup our default assets to load.
	Assets::add_js( array(
		Template::theme_url('js/jquery-1.6.4.min.js'),
		Template::theme_url('js/jquery.form.js'),
		Template::theme_url('js/ui.js')
	),
	'external',
	true);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php echo config_item('site.title') ?></title>
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	
	<?php echo Assets::css(null, 'screen', true); ?>
	
	<!-- Fix the mobile Safari auto-zoom bug -->
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<script src="<?php echo base_url() .'assets/js/head.min.js' ?>"></script>
	<script>
	head.feature("placeholder", function() {
		var inputElem = document.createElement('input');
		return new Boolean('placeholder' in inputElem);
	});
	</script>
</head>
<body>

	<noscript>
		<p>Javascript is required to use Bonfire's admin.</p>
	</noscript>
	
	<div id="message">
		<?php echo Template::message(); ?>
	</div>
	
	<div id="header">
		<!-- Nav Bar -->
		<div id="toolbar">
			<div id="toolbar-right">
				<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $this->auth->user_id()) ?>" id="tb_email" title="<?php echo lang('bf_user_settings') ?>"><?php echo config_item('auth.use_usernames') ? (config_item('auth.use_own_names') ? $this->auth->user_name() : $this->auth->username()) : $this->auth->email() ?></a>
				<a href="<?php echo site_url('logout') ?>" id="tb_logout" title="Logout">Logout</a>
			</div>
		
			<h1><a href="<?php echo site_url(); ?>" target="_blank"><?php echo config_item('site.title') ?></a></h1>
			
			<div id="toolbar-left">
				<?php echo context_nav() ?>
			</div>	<!-- /toolbar-left -->
		</div>
		
		<?php echo modules::run('subnav/subnav/index', $this->uri->segment(2)); ?>
	
		<div id="nav-bar">
			<?php if (isset($toolbar_title)) : ?>
				<h1><?php echo $toolbar_title ?></h1>
			<?php endif; ?>
			
			<?php Template::block('sub_nav', ''); ?>
		</div>
	</div> <!-- /header -->

	<div class="content-main <?php echo isset(Template::$blocks['nav_bottom']) ? 'with-bottom-bar' : '' ?>">
			<?php echo Template::yield(); ?>
	</div>
	
	<?php Template::block('nav_bottom', ''); ?>
	
	<div id="loader">
		<div class="box">
			<img src="<?php echo Template::theme_url()?>images/ajax_loader.gif" />
		</div>
	</div>
	
	<div id="debug"><!-- Stores the Profiler Results --></div>
	
	<script>
		head.js(<?php echo Assets::external_js(null, true) ?>);
		head.js(<?php echo Assets::module_js(true) ?>);
	</script>
	<?php echo Assets::inline_js(); ?>

</body>
</html>