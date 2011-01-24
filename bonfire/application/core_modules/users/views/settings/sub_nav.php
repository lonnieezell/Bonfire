<div class="subnav rounded-5">
	<a href="<?php echo site_url('admin/settings/users') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> >Users</a>
	<a href="<?php echo site_url('admin/settings/users/create') ?>" <?php echo $this->uri->segment(4) == 'create' ? 'class="current"' : '' ?> >New User</a>
</div>