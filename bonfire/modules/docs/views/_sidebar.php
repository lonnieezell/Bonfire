<?php

//$docsDir = empty($data['docsDir']) ? 'docs' : $data['docsDir'];
//$docsExt = empty($data['docsExt']) ? '.md' : $data['docsExt'];

?>
<ul class="nav" id="toc">
    <?php if (isset($docs) && is_array($docs) && count($docs)) : ?>

        <?php foreach ($docs as $file => $name) : ?>
            <?php if (is_array($name)) : ?>
            <li class='parent'>
                <div class="nav-header"><?php echo $file; ?></div>
                <ul class="nav">
                    <?php foreach ($name as $line => $namer) : ?>
                    <li><?php echo anchor($docsDir . '/' . str_replace($docsExt, '', $line), $namer); ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php else : ?>
                <li><?php echo anchor($docsDir . '/' . str_replace($docsExt, '', $file), $name); ?></li>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php else: ?>
        <p style="text-align: center">No help items found.</p>
    <?php endif; ?>
</ul>