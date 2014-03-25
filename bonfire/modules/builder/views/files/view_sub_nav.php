<?php

$controller_name_lower = strtolower($controller_name);
$ucModuleName = preg_replace("/[ -]/", "_", ucfirst($module_name));
$ucControllerName = ucfirst($controller_name);

$createPermission = "{$ucModuleName}.{$ucControllerName}.Create";

//------------------------------------------------------------------------------
// Output the view
//------------------------------------------------------------------------------
echo "<?php

\$checkSegment = \$this->uri->segment(4);
\$areaUrl = SITE_AREA . '/{$controller_name_lower}/{$module_name_lower}';

?>
<ul class='nav nav-pills'>
	<li<?php echo \$checkSegment == '' ? ' class=\"active\"' : ''; ?>>
		<a href=\"<?php echo site_url(\$areaUrl); ?>\" id='list'>
            <?php echo lang('{$module_name_lower}_list'); ?>
        </a>
	</li>
	<?php if (\$this->auth->has_permission('{$createPermission}')) : ?>
	<li<?php echo \$checkSegment == 'create' ? ' class=\"active\"' : ''; ?>>
		<a href=\"<?php echo site_url(\$areaUrl . '/create'); ?>\" id='create_new'>
            <?php echo lang('{$module_name_lower}_new'); ?>
        </a>
	</li>
	<?php endif; ?>
</ul>";