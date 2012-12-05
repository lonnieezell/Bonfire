<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/permissions') ?>"><?php echo lang('bf_action_list'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/permissions/create') ?>" id="create_new"><?php echo lang('bf_action_create'); ?></a>
	</li>
	<li>
		<a href="<?php echo site_url(SITE_AREA .'/settings/roles/permission_matrix') ?>" ><?php echo lang('permissions_matrix'); ?></a>
	</li>
</ul>
