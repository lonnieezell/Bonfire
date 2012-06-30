<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/emailer') ?>"><?php echo lang('em_s_settings'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'template' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/emailer/template') ?>"><?php echo lang('em_s_template'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'queue' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/emailer/queue') ?>"><?php echo lang('em_s_queue'); ?></a>
	</li>
</ul>