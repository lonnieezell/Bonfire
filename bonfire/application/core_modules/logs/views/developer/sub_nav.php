<div class="subnav rounded-5">
	<a href="<?php echo site_url('admin/developer/logs') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> >Logs</a>
	<a href="<?php echo site_url('admin/developer/logs/enable') ?>" <?php echo $this->uri->segment(4) == 'enable' ? 'class="current"' : '' ?> >Threshold</a>
</div>