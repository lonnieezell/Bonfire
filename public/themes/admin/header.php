<?php

Assets::add_css(array(
    'bootstrap.css',
    'bootstrap-responsive.css',
    'font-awesome.min.css',
));

if (isset($shortcut_data) && is_array($shortcut_data['shortcut_keys'])) {
    Assets::add_js($this->load->view('ui/shortcut_keys', $shortcut_data, true), 'inline');
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php
        echo isset($toolbar_title) ? "{$toolbar_title} : " : '';
        e($this->settings_lib->item('site.title'));
    ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex" />
    <?php
    /* Modernizr is loaded before CSS so CSS can utilize its features */
    ?>
	<script src="<?php echo Template::theme_url('js/modernizr-2.5.3.js'); ?>"></script>
	<?php echo Assets::css(null, true); ?>
</head>
<body class="desktop">
    <!--[if lt IE 7]>
    <p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or
    <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>
    <![endif]-->
	<noscript>
		<p>Javascript is required to use Bonfire's admin.</p>
	</noscript>
    <div class="navbar navbar-static-top navbar-inverse" id="topbar" >
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <?php echo anchor('/', html_escape($this->settings_lib->item('site.title')), 'class="brand"'); ?>
                <div class="nav-collapse in collapse">
                    <!-- User Menu -->
                    <div class="nav pull-right" id="user-menu">
                        
                    <div class="btn-group">
                        <a href="<?php echo site_url(SITE_AREA . '/settings/users/edit'); ?>" id="tb_email" class="btn" title="<?php echo lang('bf_user_settings'); ?>">
                            <i class="fa fa-user"></i>
                            <?php
                            $userDisplayName = isset($current_user->display_name) && ! empty($current_user->display_name) ? $current_user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $current_user->username : $current_user->email);
                            echo $userDisplayName;
                            ?>
                        </a>

                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                        <ul class="dropdown-menu pull-right">
                            <li class="disabled text-center"><a href="#"><?php echo gravatar_link($current_user->email, 96, null, $userDisplayName); ?></a></li>
                            <li class="disabled"><a href="mailto:<?php e($current_user->email); ?>"><strong><?php echo $userDisplayName; ?></strong><br /><?php e($current_user->email); ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo site_url(SITE_AREA . '/settings/users/edit'); ?>"><?php echo lang('bf_user_settings'); ?></a></li>
                            <li><a href="<?php echo site_url('logout'); ?>"><?php echo lang('bf_action_logout'); ?></a></li>
                        </ul>
                    </div>
                        <?php if (isset($shortcut_data) && is_array($shortcut_data['shortcuts'])
                            && is_array($shortcut_data['shortcut_keys']) && count($shortcut_data['shortcut_keys'])
                           ) :
                        ?>
                        <!-- Shortcut Menu -->
                        <div class="btn-group">
                            <a class="dropdown-toggle btn" data-toggle="dropdown" href="#"><i class="fa fa-keyboard-o"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li class="disabled"><a href="#"><?php echo lang('bf_keyboard_shortcuts'); ?></a></li>
                                <?php foreach ($shortcut_data['shortcut_keys'] as $key => $data) : ?>
                                <li class="disabled"><a href="#"><strong><?php e($data); ?></strong> : <?php echo $shortcut_data['shortcuts'][$key]['description']; ?></a></li>
                            <?php endforeach; ?>
                            <?php if ( has_permission('Bonfire.UI.View') && has_permission('Bonfire.UI.Manage') ): ?>
                            <li><a href="<?php echo site_url(SITE_AREA . '/settings/ui'); ?>"><?php echo lang('bf_keyboard_shortcuts_edit'); ?></a></li>
                        <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif;?>
                    </div>
                    <?php echo Contexts::render_menu('text', 'normal'); ?>
                </div><!-- /.nav-collapse -->
			</div><!-- /container -->
			<div class="clearfix"></div>
		</div><!-- /.navbar-inner -->
	</div><!-- /.navbar -->
    <div class="subnav">
        <div class="container">
            <div class="pull-right" id="sub-menu">
                <?php Template::block('sub_nav', ''); ?>
            </div>
            <?php if (isset($toolbar_title)) : ?>
                <h1><?php echo $toolbar_title; ?></h1>
            <?php endif; ?>
        </div>
    </div>
