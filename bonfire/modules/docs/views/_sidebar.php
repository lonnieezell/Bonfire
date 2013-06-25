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
            <?php if (is_array($name)) : ?>
                <li class="parent"><h4><?php echo $file; ?></h4>
                    <ul>
                    <?php foreach ($name as $line => $namer) : ?>
                        <li><a href="<?php echo site_url('docs/'. str_replace('.md', '', $line)) ?>"><?php echo $namer ?></a></li>
                    <?php endforeach; ?>
                    </li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?php echo site_url('docs/'. str_replace('.md', '', $file)) ?>"><?php echo $name ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Module Specific Docs -->
    <?php if (isset($module_docs)) :?>
        <h3>Modules</h3>

        <ul class="toc">
        <?php foreach ($module_docs as $module => $mod_files) : ?>
            <li><a href="<?php echo site_url('docs/'. $module) ?>"><?php echo ucwords(str_replace('_', ' ', $module)) ?></a></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>