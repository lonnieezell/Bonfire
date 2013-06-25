<?php if (isset($content) && !empty($content)) : ?>
    <div class="page">
        <?php echo $content; ?>
        <?php var_dump($content); ?>
    </div>
<?php else: ?>
    <div class="alert">
        Unable to find the docs you were looking for.
    </div>
<?php endif; ?>