<?php if (isset($content)) : ?>
    <div class="page">
        <?php echo $content; ?>
    </div>
<?php else: ?>
    <div class="alert">
        Unable to find the docs you were looking for.
    </div>
<?php endif; ?>