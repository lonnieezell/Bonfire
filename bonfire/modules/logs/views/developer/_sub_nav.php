<?php

$checkSegment = $this->uri->segment(4);
$logsUrl = site_url(SITE_AREA . '/developer/logs');

?>
<ul class="nav nav-pills">
	<li<?php echo $checkSegment != 'settings' ? ' class="active"' : ''; ?>>
		<a href="<?php echo $logsUrl; ?>"><span class="fa fa-file-text"></span> <?php echo lang('logs_logs'); ?></a>
	</li>
	<li<?php echo $checkSegment == 'settings' ? ' class="active"' : ''; ?>>
		<a href='<?php echo "{$logsUrl}/settings"; ?>'><span class="fa fa-cogs"></span> <?php echo lang('logs_settings'); ?></a>
	</li>
</ul>