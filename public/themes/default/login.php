<?php echo theme_view('parts/_header'); ?>

<div class="container body"> <!-- Start of Main Container -->

<?php

	echo Template::message();
	echo isset($content) ? $content : Template::yield();
?>

<?php echo theme_view('parts/_footer'); ?>
