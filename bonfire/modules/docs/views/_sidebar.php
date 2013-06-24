<div id="toc">

    <!-- Application Specific Docs -->
    <?php if (isset($app_docs) && count($app_docs)) : ?>
        <h3><?php e(config_item('docs.app_title')) ?></h3>
    <?php endif; ?>

    <!-- Bonfire Specific Docs -->
    <?php if (isset($bf_docs) && count($bf_docs)) :?>
        <h3><?php e(config_item('docs.bf_title')) ?></h3>

        <ul class="toc">
        <?php foreach ($bf_docs as $file => $name) : ?>
            <li><a href="<?php echo site_url('docs/'. str_replace('.md', '', $file)) ?>"><?php echo $name ?></a></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Module Specific Docs -->
    <?php if (isset($module_docs)) :?>
        <h3>Modules</h3>
    <?php endif; ?>

</div>