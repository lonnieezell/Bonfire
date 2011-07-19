<div class="subnav rounded-5">
	<a href="<?php echo site_url(SITE_AREA .'/settings/roles') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> ><?php echo lang('role_roles'); ?></a>
	<a href="<?php echo site_url(SITE_AREA .'/settings/roles/create') ?>" <?php echo $this->uri->segment(4) == 'create' ? 'class="current"' : '' ?> ><?php echo lang('role_new_role'); ?></a>
</div>