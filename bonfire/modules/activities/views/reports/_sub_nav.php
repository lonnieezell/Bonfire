<?php

$has_permission_view_date = isset($has_permission_view_date) ? $has_permission_view_date : has_permission('Activities.Date.View');
$has_permission_view_module = isset($has_permission_view_module) ? $has_permission_view_module : has_permission('Activities.Module.View');
$has_permission_view_own = isset($has_permission_view_own) ? $has_permission_view_own : has_permission('Activities.Own.View');
$has_permission_view_user = isset($has_permission_view_user) ? $has_permission_view_user : has_permission('Activities.User.View');

$checkSegment = $this->uri->segment(4);
$activitiesReportsUrl = site_url(SITE_AREA . '/reports/activities');
$pageUser   = 'activity_user';
$pageModule = 'activity_module';
$pageDate   = 'activity_date';

?>
<ul class="nav nav-pills">
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo $activitiesReportsUrl; ?>"><?php echo lang('activities_home'); ?></a>
	</li>
    <?php if ($has_permission_view_user || $has_permission_view_own) : ?>
	<li<?php echo $checkSegment == $pageUser || $checkSegment == 'activity_own' ? ' class="active"' : ''; ?>>
		<a href="<?php echo "{$activitiesReportsUrl}/{$pageUser}"; ?>"><?php echo lang(str_replace('activity_', 'activities_', $pageUser)); ?></a>
	</li>
    <?php
    endif;
    if ($has_permission_view_module) :
    ?>
	<li<?php echo $checkSegment == $pageModule ? ' class="active"' : ''; ?>>
		<a href="<?php echo "{$activitiesReportsUrl}/{$pageModule}"; ?>"><?php echo lang(str_replace('activity_', 'activities_', $pageModule)); ?></a>
	</li>
    <?php
    endif;
    if ($has_permission_view_date) :
    ?>
	<li<?php echo $checkSegment == $pageDate ? ' class="active"' : ''; ?>>
		<a href="<?php echo "{$activitiesReportsUrl}/{$pageDate}"; ?>"><?php echo lang(str_replace('activity_', 'activities_', $pageDate)); ?></a>
	</li>
    <?php endif; ?>
</ul>