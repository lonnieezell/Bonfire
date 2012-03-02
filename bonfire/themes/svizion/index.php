<?php
	echo theme_view('parts/_header');
?>
 <div class="container-fluid"> <!-- Start of Main Container -->

<?php

	echo Template::message();
	echo isset($content) ? $content : Template::yield();

	echo theme_view('parts/_footer');
?>
