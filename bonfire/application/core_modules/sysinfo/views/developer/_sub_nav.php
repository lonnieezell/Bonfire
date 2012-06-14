<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/sysinfo', lang('si_s_system')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'modules' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/sysinfo/modules', lang('si_s_modules')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'php_info' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/sysinfo/php_info', lang('si_s_php')); ?>
	</li>
</ul>

