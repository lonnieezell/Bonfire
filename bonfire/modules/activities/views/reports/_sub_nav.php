<?php

$checkSegment = $this->uri->segment(4);
$activitiesReportsUrl = site_url(SITE_AREA . '/reports/activities');
$pageUser   = 'activity_user';
$pageModule = 'activity_module';
$pageDate   = 'activity_date';

?>
<ul class="nav nav-pills">
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
        <?php echo anchor($activitiesReportsUrl, lang('activities_home')); ?>
	</li>
    <?php if ($hasPermissionViewUser || $hasPermissionViewOwn) : ?>
	<li<?php echo $checkSegment == $pageUser || $checkSegment == 'activity_own' ? ' class="active"' : ''; ?>>
		<?php echo anchor("{$activitiesReportsUrl}/{$pageUser}", lang(str_replace('activity_', 'activities_', $pageUser))); ?>
	</li>
    <?php
    endif;
    if ($hasPermissionViewModule) :
    ?>
	<li<?php echo $checkSegment == $pageModule ? ' class="active"' : ''; ?>>
		<?php echo anchor("{$activitiesReportsUrl}/{$pageModule}", lang(str_replace('activity_', 'activities_', $pageModule))); ?>
	</li>
    <?php
    endif;
    if ($hasPermissionViewDate) :
    ?>
	<li<?php echo $checkSegment == $pageDate ? ' class="active"' : ''; ?>>
		<?php echo anchor("{$activitiesReportsUrl}/{$pageDate}", lang(str_replace('activity_', 'activities_', $pageDate))); ?>
	</li>
    <?php endif; ?>
</ul>