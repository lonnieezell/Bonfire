<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/roles') ?>"><?php echo lang('role_roles'); ?></a>
	</li>
	<?php if(has_permission('Bonfire.Roles.Add')):?>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/roles/create') ?>" id="create_new"><?php echo lang('role_new_role'); ?></a>
	</li>
	<?php endif;?>
	<li <?php echo $this->uri->segment(4) == 'permission_matrix' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/roles/permission_matrix') ?>"><?php echo lang('matrix_header'); ?></a>
	</li>
</ul>

