<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/sysinfo') ?>"><?php echo lang('si_system'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'modules' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/sysinfo/modules') ?>"><?php echo lang('si_modules'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'php_info' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/sysinfo/php_info') ?>"><?php echo lang('si_php'); ?></a>
	</li>
</ul>

