<?php

//$docsDir = empty($data['docsDir']) ? 'docs' : $data['docsDir'];
//$docsExt = empty($data['docsExt']) ? '.md' : $data['docsExt'];

?>
<div id="toc">
    <?php if ( ! empty($app_docs) && count($app_docs)) : ?>
    <!-- Application Specific Docs -->
    <h3><?php e(lang('docs_title_application')); ?></h3>
    <ul class='toc'>
        <?php
        foreach ($app_docs as $file => $name) :
            if (is_array($name)) :
        ?>
        <li class='parent'>
            <h4><?php echo $file; ?></h4>
            <ul>
                <?php foreach ($name as $line => $namer) : ?>
                <li><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $line)), $namer); ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php else : ?>
        <li><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $file)), $name); ?></li>
        <?php
            endif;
        endforeach;
        ?>
    </ul>
    <?php
    endif; // Application Specific Docs

    if ( ! empty($bf_docs) && count($bf_docs)) :
    ?>
    <!-- Bonfire Specific Docs -->
    <h3><?php e(lang('docs_title_bonfire')); ?></h3>
    <ul class="toc">
        <?php
        foreach ($bf_docs as $file => $name) :
            if (is_array($name)) :
        ?>
        <li class="parent">
            <h4><?php echo $file; ?></h4>
            <ul>
                <?php foreach ($name as $line => $namer) : ?>
                <li><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $line)), $namer); ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php else : ?>
        <li><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $file)), $name); ?></li>
        <?php
            endif;
        endforeach;
        ?>
    </ul>
    <?php
    endif; // Bonfire Specific Docs

    if ( ! empty($module_docs) && count($module_docs)) :
    ?>
    <!-- Module Specific Docs -->
    <h3><?php e(lang('docs_title_modules')); ?></h3>
    <ul class="toc">
        <?php
        foreach ($module_docs as $module => $mod_files) :
            if (count($mod_files)) :
        ?>
        <li class="parent">
            <h4><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $module)), ucwords(str_replace('_', ' ', $module))); ?></h4>
            <ul>
                <?php foreach ($mod_files as $fileName => $title) : ?>
                <li><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $fileName)), ucwords($title)); ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php else : ?>
        <li class='parent'><h4><?php echo anchor(site_url($docsDir . '/' . str_replace($docsExt, '', $module)), ucwords(str_replace('_', ' ', $module))); ?></h4></li>
        <?php
            endif;
        endforeach;
        ?>
    </ul>
    <?php endif; ?>
</div>