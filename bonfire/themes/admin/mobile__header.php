<?php
	Assets::add_js('mobile');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php e(settings_item('site.title')) ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php echo Assets::css(null, 'screen', true); ?>

    <script src="<?php echo js_path(); ?>modernizr-2.5.3.js"></script>
	<script type="text/javascript">
	(function(doc) {
		// Fix viewport scaling when changing device orientation.
		var addEvent = 'addEventListener',
		    type = 'gesturestart',
		    qsa = 'querySelectorAll',
		    scales = [1, 1],
		    meta = qsa in doc ? doc[qsa]('meta[name=viewport]') : [];

		function fix() {
			meta.content = 'width=device-width,minimum-scale=' + scales[0] + ',maximum-scale=' + scales[1];
			doc.removeEventListener(type, fix, true);
		}

		if ((meta = meta[meta.length - 1]) && addEvent in doc) {
			fix();
			scales = [.25, 1.6];
			doc[addEvent](type, fix, true);
		}

	}(document));
	</script>
</head>
<body class="mobile">

	<noscript>
		<p>Javascript is required to use Bonfire's admin.</p>
	</noscript>

	<div class="topbar" id="topbar" data-dropdown="dropdown">
		<div class="topbar-inner">
			<div class="container">
				<div class="nav secondary-nav">
					<a href="<?php echo site_url('logout'); ?>" style="float: right">Logout</a>

					<h1><?php e(settings_item('site.title')); ?></h1>

					<div class="clearfix"></div>
				</div>
				<?php echo Contexts::render_menu('both', true); ?>
			</div><!-- /container -->
			<div style="clearfix"></div>
			<?php echo Contexts::render_mobile_navs(); ?>
		</div><!-- /topbar-inner -->

	</div><!-- /topbar -->

	<div id="nav-bar">
		<div class="container">
			<?php if (isset($toolbar_title)) : ?>
				<h1><?php echo $toolbar_title ?></h1>
			<?php endif; ?>

			<?php Template::block('sub_nav', ''); ?>
		</div>
	</div>
