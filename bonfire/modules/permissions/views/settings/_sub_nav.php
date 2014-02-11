<?php

$testSegment = $this->uri->segment(4);
$settingsUrl = site_url(SITE_AREA . '/settings');

?>
<ul class="nav nav-pills">
	<li<?php echo $testSegment == '' ? ' class="active"' : '' ?>>
		<a href='<?php echo "{$settingsUrl}/permissions"; ?>'><?php echo lang('bf_action_list'); ?></a>
	</li>
	<li<?php echo $testSegment == 'create' ? ' class="active"' : '' ?>>
		<a href='<?php echo "{$settingsUrl}/permissions/create"; ?>' id="create_new"><?php echo lang('bf_action_create'); ?></a>
	</li>
	<li>
		<a href='<?php echo "{$settingsUrl}/roles/permission_matrix"; ?>'><?php echo lang('permissions_matrix'); ?></a>
	</li>
</ul>