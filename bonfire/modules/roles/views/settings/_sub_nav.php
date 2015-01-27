<?php

$testSegment = $this->uri->segment(4);
$rolesUrl = site_url(SITE_AREA . '/settings/roles');

?>
<ul class="nav nav-pills">
	<li<?php echo $testSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo $rolesUrl; ?>"><span class="fa fa-flag"></span> <?php echo lang('role_roles'); ?></a>
	</li>
	<?php if (has_permission('Bonfire.Roles.Add')) : ?>
	<li<?php echo $testSegment == 'create' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$rolesUrl}/create"; ?>' id='create_new'><span class="fa fa-plus"></span> <?php echo lang('role_new_role'); ?></a>
	</li>
	<?php endif;?>
	<li<?php echo $testSegment == 'permission_matrix' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$rolesUrl}/permission_matrix"; ?>'><span class="fa fa-flag-checkered"></span> <?php echo lang('matrix_header'); ?></a>
	</li>
</ul>