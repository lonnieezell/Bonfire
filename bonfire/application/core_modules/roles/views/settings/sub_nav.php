<div class="subnav rounded-5">
	<a href="<?php echo site_url('admin/settings/roles') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> >Roles</a>
	<a href="<?php echo site_url('admin/settings/roles/create') ?>" <?php echo $this->uri->segment(4) == 'create' ? 'class="current"' : '' ?> >New Role</a>
</div>