<?php
	Assets::add_css( array(
		css_path() . 'bootstrap.min.css',
		css_path() . 'bootstrap-responsive.min.css',
		'screen.css'
	));

	if (isset($shortcut_data) && is_array($shortcut_data['shortcut_keys'])) {
		Assets::add_js($this->load->view('ui/shortcut_keys', $shortcut_data, true), 'inline');
	}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php echo $this->settings_lib->item('site.title') ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php echo Assets::css(null, true); ?>

	<script src="<?php echo base_url() .'assets/js/head.min.js' ?>"></script>
	<script>
	head.feature("placeholder", function() {
		var inputElem = document.createElement('input');
		return new Boolean('placeholder' in inputElem);
	});
	</script>
</head>
<body class="desktop">
<!--[if lt IE 7]>
		<p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or
		<a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>
<![endif]-->


	<noscript>
		<p>Javascript is required to use Bonfire's admin.</p>
	</noscript>

		<div class="navbar navbar-fixed-top" id="topbar" >
				<div class="navbar-inner">
						<div class="container-fluid">
								<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
								</a>
								<h1><?php echo anchor( '/', $this->settings_lib->item('site.title'), 'class="brand"' ); ?></h1>

				<div class="nav-collapse">

				<?php if(isset($shortcut_data) && is_array($shortcut_data['shortcuts']) && is_array($shortcut_data['shortcut_keys']) && count($shortcut_data['shortcut_keys'])):?>
					<div class="nav pull-right">
					<div class="btn-group">
						<a class="dropdown-toggle dark btn" data-toggle="dropdown" href="#"><img src="<?php echo Template::theme_url('images/keyboard-icon.png') ?>" id="shortkeys_show" title="Keyboard Shortcuts" alt="Keyboard Shortcuts"/></a>
						<ul class="dropdown-menu toolbar-keys">
								<li>
										<div class="inner keys">
											<?php if (isset($shortcut_data) && is_array($shortcut_data['shortcut_keys'])): ?>
											<h4><?php echo lang('bf_keyboard_shortcuts') ?></h4>
											<ul>
											<?php foreach($shortcut_data['shortcut_keys'] as $key => $data): ?>
												<li><span><?php echo $data?></span> : <?php echo $shortcut_data['shortcuts'][$key]['description']; ?></li>
											<?php endforeach; ?>
											</ul>
											<?php else:?>
											<h4><?php echo lang('bf_keyboard_shortcuts_empty') ?></h4>
											<?php endif;?>
											<a href="<?php echo site_url(SITE_AREA.'/settings/ui');?>"><?php echo lang('bf_keyboard_shortcuts_edit');?></a>
										</div>
								</li>
						</ul>
					</div>
					</div>
					<?php endif;?>
				<div class="nav pull-right">
					<div class="btn-group">
						<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $current_user->id) ?>" id="tb_email" class="btn dark" title="<?php echo lang('bf_user_settings') ?>">
							<?php echo config_item('auth.use_usernames') ? (config_item('auth.use_own_names') ? $current_user->username : $current_user->username) : $current_user->email ?>
						</a>
						<a class="btn dropdown-toggle dark" data-toggle="dropdown" href="#"><span class="caret"></span></a>
						<ul class="dropdown-menu toolbar-profile">
								<li>
										<div class="inner">
											<div class="toolbar-profile-img">
												<?php echo gravatar_link($current_user->email, 96) ?>
											</div>

											<div class="toolbar-profile-info">
												<p><b><?php echo $current_user->display_name ?></b><br/>
													<?php e($current_user->email) ?>
												</p>

												<br/>
												<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $current_user->id) ?>">Profile</a>
												<a href="<?php echo site_url('logout'); ?>">Logout</a>
											</div>
										</div>
								</li>
						</ul>
					</div>
				</div>

			</div> <!-- END OF nav-collapse -->

				<?php echo Contexts::render_menu('both'); ?>
			</div><!-- /container -->
			<div style="clearfix"></div>
		</div><!-- /topbar-inner -->

	</div><!-- /topbar -->

 <div class="subnav navbar-fixed-top" >
				<div class="container-fluid">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
						</a>

			<div class="pull-right">
				<?php Template::block('sub_nav', ''); ?>
			</div>

			<?php if (isset($toolbar_title)) : ?>
				<h1><?php echo $toolbar_title ?></h1>
			<?php endif; ?>
	</div>
</div>
