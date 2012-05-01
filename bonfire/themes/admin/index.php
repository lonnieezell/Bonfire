<?php
	Assets::add_js( array(
		                 Template::theme_url('js/bootstrap.min.js'),
		                 Template::theme_url('js/jwerty.js')) ,
		                 'external',
		                 true
		                 );
?>
<?php echo theme_view('partials/_header'); ?>

<div class="container-fluid body">
        <?php echo Template::message(); ?>

        <?php echo Template::yield(); ?>
</div>

<?php echo theme_view('partials/_footer'); ?>
