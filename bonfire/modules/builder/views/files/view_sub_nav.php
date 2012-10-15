<?php
$controller_name_lower = strtolower($controller_name);

$view = <<<END
<ul class="nav nav-pills">
	<li <?php echo \$this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/{$controller_name_lower}/{$module_name_lower}') ?>" id="list"><?php echo lang('{$module_name_lower}_list'); ?></a>
	</li>
	<?php if (\$this->auth->has_permission('{create_permission}')) : ?>
	<li <?php echo \$this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/{$controller_name_lower}/{$module_name_lower}/create') ?>" id="create_new"><?php echo lang('{$module_name_lower}_new'); ?></a>
	</li>
	<?php endif; ?>
</ul>
END;

$view = str_replace('{create_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Create', $view);

echo $view;
