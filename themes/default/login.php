<?php echo theme_view('header'); ?>
<style>body { background: #f5f5f5; }</style>
<div class="container"><!-- Start of Main Container -->
    <?php
    echo isset($content) ? $content : Template::content();

    echo theme_view('footer', array('show' => false));
?>