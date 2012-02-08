<?php echo theme_view('header'); ?>

	<?php echo Template::message(); ?>
	<?php echo isset($content) ? $content : Template::yield(); ?>

<?php echo theme_view('footer'); ?>