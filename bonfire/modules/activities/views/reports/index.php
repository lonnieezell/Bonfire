<?php

$has_permission_view_date = isset($has_permission_view_date) ? $has_permission_view_date : has_permission('Activities.Date.View');
$has_permission_view_module = isset($has_permission_view_module) ? $has_permission_view_module : has_permission('Activities.Module.View');
$has_permission_view_own = isset($has_permission_view_own) ? $has_permission_view_own : has_permission('Activities.Own.View');
$has_permission_view_user = isset($has_permission_view_user) ? $has_permission_view_user : has_permission('Activities.User.View');

$activitiesReportsPage = SITE_AREA . '/reports/activities';
$activitiesReportsUrl = site_url($activitiesReportsPage);
$pageUser = 'activity_user';
$pageModule = 'activity_module';
$pageDate = 'activity_date';
$pageOwn = 'activity_own';

?>
<style>
.row.icons {
    margin-bottom: 20px;
}
td.button-column {
    width: 15em;
    text-align: right;
}
td.label-column {
    width: 15em;
}
</style>
<div class="row icons">
	<?php if ($has_permission_view_own) : ?>
	<div class="column size1of4 media-box">
		<a href='<?php echo "{$activitiesReportsUrl}/{$pageOwn}"; ?>'>
			<img src="<?php echo Template::theme_url('images/activity-user.png'); ?>" alt='user icon' />
		</a>
		<p><strong><?php echo lang(str_replace('activity_', 'activities_', $pageOwn)); ?></strong><br />
            <span><?php echo lang(str_replace('activity_', 'activities_', "{$pageOwn}_description")); ?></span>
		</p>
	</div>
	<?php
    endif;
    if ($has_permission_view_user) :
    ?>
	<div class="column size1of4 media-box">
		<a href='<?php echo "{$activitiesReportsUrl}/{$pageUser}"; ?>'>
			<img src="<?php echo Template::theme_url('images/customers.png'); ?>" alt='users icon' />
		</a>
		<p><strong><?php echo lang(str_replace('activity_', 'activities_', "{$pageUser}s")); ?></strong><br />
            <span><?php echo lang(str_replace('activity_', 'activities_', "{$pageUser}s_description")); ?></span>
		</p>
	</div>
	<?php
    endif;
    if ($has_permission_view_module) :
    ?>
	<div class="column size1of4 media-box">
		<a href='<?php echo "{$activitiesReportsUrl}/{$pageModule}"; ?>'>
			<img src="<?php echo Template::theme_url('images/product.png'); ?>" alt='modules icon' />
		</a>
		<p><strong><?php echo lang(str_replace('activity_', 'activities_', "{$pageModule}s")); ?></strong><br />
    		<span><?php echo lang(str_replace('activity_', 'activities_', "{$pageModule}_description")); ?></span>
		</p>
	</div>
	<?php
    endif;
    if ($has_permission_view_date) :
    ?>
	<div class="column size1of4 media-box">
		<a href='<?php echo "{$activitiesReportsUrl}/{$pageDate}"; ?>'>
			<img src="<?php echo Template::theme_url('images/calendar.png'); ?>" alt='calendar icon' />
		</a>
		<p><strong><?php echo lang(str_replace('activity_', 'activities_', $pageDate)); ?></strong><br />
    		<span><?php echo lang(str_replace('activity_', 'activities_', "{$pageDate}_description")); ?></span>
		</p>
	</div>
	<?php endif; ?>
</div>
<div class="row">
	<div class="column size1of2">
		<!-- Active Modules -->
		<div class="admin-box">
			<h3><?php echo lang('activities_top_modules'); ?></h3>
			<?php if (isset($top_modules) && is_array($top_modules) && count($top_modules)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang(str_replace('activity_', 'activities_', $pageModule)); ?></th>
                        <th><?php echo lang('activities_logged'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_modules as $top_module) : ?>
                    <tr>
                        <td><strong><?php echo ucwords($top_module->module); ?></strong></td>
                        <td><?php echo $top_module->activity_count; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
			<?php
            else :
                echo lang('activities_no_top_modules');
			endif;
            ?>
		</div>
	</div>
	<div class="column size1of2 last-column">
		<div class="admin-box">
			<!-- Active Users -->
			<h3><?php echo lang('activities_top_users'); ?></h3>
			<?php if (isset($top_users) && is_array($top_users) && count($top_users)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang(str_replace('activity_', 'activities_', $pageUser)); ?></th>
                        <th><?php echo lang('activities_logged'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_users as $top_user) : ?>
                    <tr>
                        <td><strong><?php e($top_user->username == '' ? 'Not found' : $top_user->username); ?></strong></td>
                        <td><?php echo $top_user->activity_count; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
			<?php
            else :
                echo lang('activities_no_top_users');
            endif;
            ?>
		</div>
	</div>
</div>
<div class="admin-box">
	<h3><?php echo lang('activities_cleanup'); ?></h3>
	<?php $empty_table = true; ?>
	<table class="table table-striped">
		<tbody>
			<?php if (has_permission('Activities.Own.Delete')) : ?>
            <tr>
                <?php echo form_open("{$activitiesReportsPage}/delete", array('id' => 'activity_own_form', 'class' => 'form-inline')); ?>
                    <td class='label-column'><label for="activity_own_select"><?php echo lang('activities_delete_own_note'); ?></label></td>
                    <td>
                        <input type="hidden" name="action" value="activity_own" />
                        <select name="which" id="activity_own_select">
                            <option value="<?php echo $current_user->id; ?>"><?php e($current_user->username); ?></option>
                        </select>
                    </td>
                    <td class='button-column'>
                        <button type="button" class="btn btn-danger" id="delete-activity_own"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_own_delete'); ?></button>
                    </td>
                <?php echo form_close(); ?>
            </tr>
            <?php
                $empty_table = false;
            endif;
            if (has_permission('Activities.User.Delete')) :
            ?>
            <tr>
                <?php echo form_open("{$activitiesReportsPage}/delete", array('id' => 'activity_user_form', 'class' => 'form-inline')); ?>
                    <td class='label-column'><label for="activity_user_select"><?php echo lang('activities_delete_user_note'); ?></label></td>
                    <td>
                        <input type="hidden" name="action" value="activity_user" />
                        <select name="which" id="activity_user_select">
                            <option value="all"><?php echo lang('activities_all_users'); ?></option>
                            <?php foreach ($users as $au) : ?>
                            <option value="<?php echo $au->id; ?>"><?php e($au->username); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class='button-column'>
                        <button type="button" class="btn btn-danger" id="delete-activity_user"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_user_delete'); ?></button>
                    </td>
                <?php echo form_close(); ?>
            </tr>
			<?php
                $empty_table = false;
            endif;
            if (has_permission('Activities.Module.Delete')) :
            ?>
			<tr>
                <?php echo form_open("{$activitiesReportsPage}/delete", array('id' => 'activity_module_form', 'class' => 'form-inline')); ?>
                    <td class='label-column'><label for="activity_module_select"><?php echo lang('activities_delete_module_note'); ?></label></td>
                    <td>
                        <input type="hidden" name="action" value="activity_module" />
                        <select name="which" id="activity_module_select">
                            <option value="all"><?php echo lang('activities_all_modules'); ?></option>
                            <option value="core"><?php echo lang('activities_core'); ?></option>
                            <?php foreach ($modules as $mod) : ?>
                            <option value="<?php echo $mod; ?>"><?php echo $mod; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class='button-column'>
                        <button type="button" class="btn btn-danger" id="delete-activity_module"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_module_delete'); ?></button>
                    </td>
                <?php echo form_close(); ?>
			</tr>
			<?php
                $empty_table = false;
            endif;
            if (has_permission('Activities.Date.Delete')) :
            ?>
			<tr>
                <?php echo form_open("{$activitiesReportsPage}/delete", array('id' => 'activity_date_form', 'class' => 'form-inline')); ?>
                    <td class='label-column'><label for="activity_date_select"><?php echo lang('activities_delete_date_note'); ?></label></td>
                    <td>
                        <input type="hidden" name="action" value="activity_date" />
                        <select name="which" id="activity_date_select">
                            <option value="all"><?php echo lang('activities_all_dates'); ?></option>
                            <?php foreach ($activities as $activity) : ?>
                            <option value="<?php echo $activity->activity_id; ?>"><?php echo $activity->created_on; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class='button-column'>
                        <button type="button" class="btn btn-danger" id="delete-activity_date"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_date_delete'); ?></button>
                    </td>
                <?php echo form_close(); ?>
			</tr>
			<?php
                $empty_table = false;
            endif;

            if ($empty_table) :
            ?>
			<tr>
				<td colspan="3"><?php echo lang('activities_none_found'); ?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>