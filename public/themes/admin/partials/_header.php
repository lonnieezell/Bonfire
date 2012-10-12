<?php
	Assets::add_css( array(
		'bootstrap.css',
		'bootstrap-responsive.css',
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

	<meta name="robots" content="noindex" />
	<?php echo Assets::css(null, true); ?>

	<script src="<?php echo Template::theme_url('js/modernizr-2.5.3.js'); ?>"></script>
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


					<?php if(isset($shortcut_data) && is_array($shortcut_data['shortcuts']) && is_array($shortcut_data['shortcut_keys']) && count($shortcut_data['shortcut_keys'])):?>
					<!-- Shortcut Menu -->
					<div class="nav pull-right" id="shortcuts">
					<div class="btn-group">
						<a class="dropdown-toggle light btn" data-toggle="dropdown" href="#"><img src="<?php echo Template::theme_url('images/keyboard-icon.png') ?>" id="shortkeys_show" title="Keyboard Shortcuts" alt="Keyboard Shortcuts"/></a>
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

					<div class="nav-collapse in collapse">
						<!-- User Menu -->
						<div class="nav pull-right" id="user-menu">
							<div class="btn-group">
								<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit') ?>" id="tb_email" class="btn dark" title="<?php echo lang('bf_user_settings') ?>">
									<?php echo (isset($current_user->display_name) && !empty($current_user->display_name)) ? $current_user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $current_user->username : $current_user->email); ?>
								</a>
								<!-- Change **light** to **dark** to match colors -->
								<a class="btn dropdown-toggle light" data-toggle="dropdown" href="#"><span class="caret"></span></a>
								<ul class="dropdown-menu toolbar-profile">
									<li>
										<div class="inner">
											<div class="toolbar-profile-img">
												<?php echo gravatar_link($current_user->email, 96, null, $current_user->display_name) ?>
											</div>

											<div class="toolbar-profile-info">
												<p><b><?php echo $current_user->display_name ?></b><br/>
													<?php e($current_user->email) ?>
													<br/>
												</p>
												<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit') ?>"><?php echo lang('bf_user_settings')?></a>
												<a href="<?php echo site_url('logout'); ?>"><?php echo lang('bf_action_logout')?></a>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>

						<?php echo Contexts::render_menu('text', 'normal'); ?>
					</div> <!-- END OF nav-collapse -->

			</div><!-- /container -->
			<div style="clearfix"></div>
		</div><!-- /topbar-inner -->

	</div><!-- /topbar -->

 <div class="subnav navbar-fixed-top" >
	<div class="container-fluid">

		<?php if (isset($toolbar_title)) : ?>
			<h1><?php echo $toolbar_title ?></h1>
		<?php endif; ?>

		<div class="pull-right" id="sub-menu">
			<?php Template::block('sub_nav', ''); ?>
		</div>
	</div>
</div>

<!-- Ajax Loader Image/Overlay -->
<div id="loader">
	<div class="box">
		<img src="<?php echo Template::theme_url('images/ajax_loader.gif')?>" />
	</div>
</div>

<!-- End Ajax Loader Image/Overlay -->
