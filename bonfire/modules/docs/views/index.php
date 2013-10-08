<?php

if (empty($content)) {
    $divClass = 'alert';
    $content = lang('docs_not_found');
} else {
    $divClass = 'page';
}

?>
<div class="<?php echo $divClass; ?>">
    <?php echo $content; ?>
</div>