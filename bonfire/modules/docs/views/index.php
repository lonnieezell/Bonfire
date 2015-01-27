<?php if (empty($content)) : ?>

    <div class="alert">
        <span class="fa fa-book"></span>
        <?php echo lang('docs_not_found'); ?>
    </div>

<?php else: ?>

    <div class="page">
        <?php echo $content; ?>
    </div>

<?php endif; ?>