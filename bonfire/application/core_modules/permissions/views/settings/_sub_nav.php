<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/permissions', lang('permissions_s_permissions')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/permissions/create', lang('permissions_s_new_permission'), ' id="create_new"'); ?>
	</li>
	<li>
		<?php echo anchor(SITE_AREA . '/settings/roles/permission_matrix', lang('permissions_s_matrix')); ?>
	</li>
</ul>