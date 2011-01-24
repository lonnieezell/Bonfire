<div class="subnav rounded-5">
	<a href="<?php echo site_url('admin/developer/database') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> >Maintenance</a>
	<a href="<?php echo site_url('admin/developer/database/backups') ?>" <?php echo $this->uri->segment(4) == 'backups' ? 'class="current"' : '' ?> >Backups</a>
</div>