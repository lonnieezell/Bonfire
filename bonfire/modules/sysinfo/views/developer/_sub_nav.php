<?php

$checkSegment = $this->uri->segment(4);
$baseUrl = site_url(SITE_AREA . '/developer/sysinfo');

?>
<ul class="nav nav-pills">
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo $baseUrl; ?>"><span class="fa fa-server"></span> <?php echo lang('sysinfo_system'); ?></a>
	</li>
	<li<?php echo $checkSegment == 'modules' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$baseUrl}/modules"; ?>'><span class="fa fa-puzzle-piece"></span> <?php echo lang('sysinfo_modules'); ?></a>
	</li>
	<li<?php echo $checkSegment == 'php_info' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$baseUrl}/php_info"; ?>'><span class="fa fa-code"></span> <?php echo lang('sysinfo_php'); ?></a>
	</li>
</ul>