<?php

Assets::add_js(array('bootstrap.min.js', 'jwerty.js'), 'external', true);

echo theme_view('header');

?>
<?= Template::message() != '' ? '<div class="container">'.Template::message().'</div>':'';?>
<div class="container well">
	<?= isset($content) ? $content : Template::content(); ?>
</div>
<?php echo theme_view('footer'); ?>