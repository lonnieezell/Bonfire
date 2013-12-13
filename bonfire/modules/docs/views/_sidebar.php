<?php

//$docsDir = empty($data['docsDir']) ? 'docs' : $data['docsDir'];
//$docsExt = empty($data['docsExt']) ? '.md' : $data['docsExt'];

?>
<div id="toc">
<ul class="nav">
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

    <?php if ( ! empty($module_docs) && count($module_docs)) : ?>
        <li class="parent"><div class="nav-header"><?php e(lang('docs_title_modules')); ?></div></li>
        <!-- Module Specific Docs -->
            <?php foreach ($module_docs as $module => $mod_files) : ?>
                <?php if (count($mod_files)) : ?>
                    <li class="parent">
                            <?php foreach ($mod_files as $fileName => $title) : ?>
                                <?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $fileName)), ucwords($title)); ?>
                            <?php endforeach; ?>
                    </li>
                <?php else : ?>
                    <li class='parent'><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $module)), ucwords(str_replace('_', ' ', $module))); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
    <?php endif; ?>
</ul>

</div>