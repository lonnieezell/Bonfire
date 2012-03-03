<?php
$controller_name_lower = strtolower($controller_name);

$view = <<<END
<ul class="nav pills">
	<li <?php echo \$this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/{$controller_name_lower}/{$module_name_lower}') ?>"><?php echo lang('{$module_name_lower}_list'); ?></a>
	</li>
	<li <?php echo \$this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/{$controller_name_lower}/{$module_name_lower}/create') ?>"><?php echo lang('{$module_name_lower}_new'); ?></a>
	</li>
</ul>
END;

echo $view;