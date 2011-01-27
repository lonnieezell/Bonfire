<div class="subnav rounded-5">
	<a href="<?php echo site_url('admin/content/pages') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> >Pages</a>
	<a href="<?php echo site_url('admin/content/pages/create') ?>" <?php echo $this->uri->segment(4) == 'create' ? 'class="current"' : '' ?> >New Page</a>
</div>