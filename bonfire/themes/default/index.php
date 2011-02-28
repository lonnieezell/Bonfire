<?php echo theme_view('_header'); ?>

<?php echo isset($body_content) ? $body_content : Template::yield(); ?>

<?php echo theme_view('_footer'); ?>