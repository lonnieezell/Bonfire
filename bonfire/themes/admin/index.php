<?php
	Assets::add_js(array(
			'plugins.js',
			Template::theme_url('js/jwerty.js'),
		),
		'external',
		true
	);
?>
<?php echo theme_view('_header'); ?>

<div class="container-fluid body">
	<?php echo Template::message(); ?>

	<?php echo Template::yield(); ?>
</div>

<?php echo theme_view('_footer'); ?>
