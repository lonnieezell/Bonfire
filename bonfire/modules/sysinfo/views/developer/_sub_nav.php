<?php

$checkSegment = $this->uri->segment(4);
$baseUrl = site_url(SITE_AREA . '/developer/sysinfo');

?>
<ul class="nav nav-pills">
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo $baseUrl; ?>"><?php echo lang('sysinfo_system'); ?></a>
	</li>
	<li<?php echo $checkSegment == 'modules' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$baseUrl}/modules"; ?>'><?php echo lang('sysinfo_modules'); ?></a>
	</li>
	<li<?php echo $checkSegment == 'php_info' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$baseUrl}/php_info"; ?>'><?php echo lang('sysinfo_php'); ?></a>
	</li>
</ul>