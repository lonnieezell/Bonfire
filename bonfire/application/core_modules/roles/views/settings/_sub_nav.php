<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/roles', lang('roles_s_roles')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/roles/create', lang('roles_s_new_role'), ' id="create_new"'); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'permission_matrix' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/roles/permission_matrix', lang('roles_s_matrix')); ?>
	</li>
</ul>