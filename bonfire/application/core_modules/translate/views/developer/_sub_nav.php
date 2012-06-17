<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/translate', lang('tr_s_translate')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'export' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/translate/export', lang('tr_s_export')); ?>
	</li>
</ul>