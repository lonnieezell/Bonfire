<?php if (empty($content)) : ?>

    <div class="alert">
        <span class="glyphicon glyphicon-book"></span>
        <?php echo lang('docs_not_found'); ?>
    </div>

<?php else: ?>

    <div class="page">
        <?php echo $content; ?>
    </div>

<?php endif; ?>