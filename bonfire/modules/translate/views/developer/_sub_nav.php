<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/translate') ?>"><?php echo lang('tr_translate'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'export' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/translate/export') ?>"><?php echo lang('tr_export_short'); ?></a>
	</li>
</ul>