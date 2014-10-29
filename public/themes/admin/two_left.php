<?php

Assets::add_js(array(
                    Template::theme_url('js/bootstrap.min.js'),
                    Template::theme_url('js/jwerty.js')
               ),
               'external',
               true
);

echo theme_view('header');

?>
<div class="body">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <?php Template::block('sidebar'); ?>
            </div>
            <div class="span10">
                <?php
                echo Template::message();
                echo isset($content) ? $content : Template::content();
                ?>
            </div>
        </div>
    </div>
</div>
<?php echo theme_view('footer'); ?>