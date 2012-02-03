<?php 
	Assets::add_js(array(
			'jquery-1.6.4.min.js',
			'plugins.js'
		), 
		'external',
		true
	);
?>
<?php echo theme_view('_header'); ?>

<div class="fluid-container body">
	<?php echo Template::message(); ?>	

	<?php echo Template::yield(); ?>
</div>
	
<?php echo theme_view('_footer'); ?>