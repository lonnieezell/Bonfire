<?php

$testSegment = $this->uri->segment(4);
$translateUrl = site_url(SITE_AREA . '/developer/translate');

?>
<ul class="nav nav-pills">
	<li<?php echo $testSegment == '' ? ' class="active"' : '' ?>>
		<a href="<?php echo $translateUrl; ?>"><?php echo lang('tr_translate'); ?></a>
	</li>
	<li<?php echo $testSegment == 'export' ? ' class="active"' : '' ?>>
		<a href="<?php echo "{$translateUrl}/export"; ?>"><?php echo lang('tr_export_short'); ?></a>
	</li>
</ul>