<div id="sub-nav" class="button-group">
	<a href="<?php echo site_url('admin/settings/emailer') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> >Settings</a>
	<a href="<?php echo site_url('admin/settings/emailer/template') ?>" <?php echo $this->uri->segment(4) == 'template' ? 'class="current"' : '' ?> >Template</a>
</div>