<div id="sub-nav" class="button-group">
	<a href="<?php echo site_url(SITE_AREA .'/developer/database') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> ><?php echo lang('db_maintenance'); ?></a>
	<a href="<?php echo site_url(SITE_AREA .'/developer/database/backups') ?>" <?php echo $this->uri->segment(4) == 'backups' ? 'class="current"' : '' ?> ><?php echo lang('db_backups'); ?></a>
</div>
