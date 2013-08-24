<?php echo theme_view('_header'); ?>
<style>body { background: #f5f5f5; }</style>

<div class="container"> <!-- Start of Main Container -->

    <?php
        echo isset($content) ? $content : Template::yieldContent();
    ?>

<?php echo theme_view('_footer', array('show' => false)); ?>