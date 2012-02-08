<?php
	Assets::add_css( array(
		'bootstrap.min.css',
		'bootstrap-responsive.min.css',
		'screen.css'
	));
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php echo config_item('site.title') ?></title>
    
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

	<noscript>
		<p>Javascript is required to use Bonfire's admin.</p>
	</noscript>

	<div class="navbar navbar-fluid navbar-fixed" id="topbar" data-dropdown="dropdown">
		<div class="navbar-inner">
			<div class="fluid-container">
				<h1 class="branding"><?php e(config_item('site.title')); ?></h1>
			
				<div class="nav pull-right">
					<div class="btn-group">
						<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $current_user->id) ?>" id="tb_email" class="btn dark" title="<?php echo lang('bf_user_settings') ?>">
							<?php echo config_item('auth.use_usernames') ? (config_item('auth.use_own_names') ? $current_user->username : $current_user->username) : $current_user->email ?>
						</a>
						<a class="btn dropdown-toggle dark" data-toggle="dropdown" href="#"><span class="caret"></span></a>
						<ul class="dropdown-menu toolbar-profile">
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
						</ul>
					</div>
				</div>
				<?php echo Contexts::render_menu('both'); ?>
			</div><!-- /container -->
			<div style="clearfix"></div>
		</div><!-- /topbar-inner -->
		
	</div><!-- /topbar -->
	
	<div class="subnav navbar-fixed">
		<div class="fluid-container">
		
			<div class="pull-right">
				<?php Template::block('sub_nav', ''); ?>
			</div>
			
			<?php if (isset($toolbar_title)) : ?>
				<h1><?php echo $toolbar_title ?></h1>
			<?php endif; ?>
		</div>
	</div>