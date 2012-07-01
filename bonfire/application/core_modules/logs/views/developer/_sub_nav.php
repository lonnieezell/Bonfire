<ul class="nav nav-pills">
	<li  <?php echo $this->uri->segment(4) != 'settings' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/logs') ?>"><?php echo lang('logs_s_logs'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'settings' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/logs/settings') ?>"><?php echo lang('logs_s_settings'); ?></a>
	</li>
</ul>