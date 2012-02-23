<?php echo theme_view('header'); ?>

	<div class="login-container body">
	LOGIN
		<?php echo Template::message(); ?>
		<?php echo isset($content) ? $content : Template::yield(); ?>

	</div>
<?php echo theme_view('footer'); ?>