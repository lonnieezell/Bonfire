<?php

$hasPermissionDeleteDate   = isset($hasPermissionDeleteDate) ? $hasPermissionDeleteDate : false;
$hasPermissionDeleteModule = isset($hasPermissionDeleteModule) ? $hasPermissionDeleteModule : false;
$hasPermissionDeleteUser   = isset($hasPermissionDeleteUser) ? $hasPermissionDeleteUser : false;

$activitiesReportsPage = SITE_AREA . '/reports/activities';
$activitiesReportsUrl = site_url($activitiesReportsPage);

?>
<style>
.row.icons {
    margin-bottom: 20px;
}
td.button-column {
    text-align: right;
}
td.button-column,
td.label-column,
td.button-column button {
    width: 20em;
}
</style>
<div class="row icons">
    <?php if ($hasPermissionViewOwn) : ?>
	<div class="column size1of4 media-box">
        <a href='<?php echo "{$activitiesReportsUrl}/{$pages['own']}"; ?>'>
			<img src="<?php echo Template::theme_url('images/activity-user.png'); ?>" alt='user icon' />
		</a>
        <p><strong><?php echo lang(str_replace('activity_', 'activities_', $pages['own'])); ?></strong><br />
            <span><?php echo lang(str_replace('activity_', 'activities_', "{$pages['own']}_description")); ?></span>
		</p>
	</div>
	<?php
    endif;
    if ($hasPermissionViewUser) :
    ?>
	<div class="column size1of4 media-box">
        <a href='<?php echo "{$activitiesReportsUrl}/{$pages['user']}"; ?>'>
			<img src="<?php echo Template::theme_url('images/customers.png'); ?>" alt='users icon' />
		</a>
        <p><strong><?php echo lang(str_replace('activity_', 'activities_', "{$pages['user']}s")); ?></strong><br />
            <span><?php echo lang(str_replace('activity_', 'activities_', "{$pages['user']}s_description")); ?></span>
		</p>
	</div>
	<?php
    endif;
    if ($hasPermissionViewModule) :
    ?>
	<div class="column size1of4 media-box">
        <a href='<?php echo "{$activitiesReportsUrl}/{$pages['module']}"; ?>'>
			<img src="<?php echo Template::theme_url('images/product.png'); ?>" alt='modules icon' />
		</a>
        <p><strong><?php echo lang(str_replace('activity_', 'activities_', "{$pages['module']}s")); ?></strong><br />
            <span><?php echo lang(str_replace('activity_', 'activities_', "{$pages['module']}_description")); ?></span>
		</p>
	</div>
	<?php
    endif;
    if ($hasPermissionViewDate) :
    ?>
	<div class="column size1of4 media-box">
        <a href='<?php echo "{$activitiesReportsUrl}/{$pages['date']}"; ?>'>
			<img src="<?php echo Template::theme_url('images/calendar.png'); ?>" alt='calendar icon' />
		</a>
        <p><strong><?php echo lang(str_replace('activity_', 'activities_', $pages['date'])); ?></strong><br />
            <span><?php echo lang(str_replace('activity_', 'activities_', "{$pages['date']}_description")); ?></span>
		</p>
	</div>
	<?php endif; ?>
</div>
<div class="row">
	<div class="column size1of2">
		<!-- Active Modules -->
		<div class="admin-box">
			<h3><?php echo lang('activities_top_modules'); ?></h3>
            <?php if (empty($top_modules) || ! is_array($top_modules)) : ?>
            <p><?php echo lang('activities_no_top_modules'); ?></p>
            <?php else : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang(str_replace('activity_', 'activities_', $pages['module'])); ?></th>
                        <th><?php echo lang('activities_logged'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_modules as $topModule) : ?>
                    <tr>
                        <td><strong><?php echo ucwords($topModule->module); ?></strong></td>
                        <td><?php echo $topModule->activity_count; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
		</div>
	</div>
	<div class="column size1of2 last-column">
		<div class="admin-box">
			<!-- Active Users -->
			<h3><?php echo lang('activities_top_users'); ?></h3>
            <?php if (empty($top_users) || ! is_array($top_users)) : ?>
            <p><?php echo lang('activities_no_top_users'); ?></p>
            <?php else : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang(str_replace('activity_', 'activities_', $pages['user'])); ?></th>
                        <th><?php echo lang('activities_logged'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_users as $topUser) : ?>
                    <tr>
                        <td><strong><?php e($topUser->username == '' ? lang('activities_username_not_found') : $topUser->username); ?></strong></td>
                        <td><?php echo $topUser->activity_count; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
		</div>
	</div>
</div>
<?php
if ($hasPermissionDeleteOwn
    || $hasPermissionDeleteUser
    || $hasPermissionDeleteModule
    || $hasPermissionDeleteDate
) :
?>
<div class="admin-box">
	<h3><?php echo lang('activities_cleanup'); ?></h3>
	<table class="table table-striped">
		<tbody>
            <?php if ($hasPermissionDeleteOwn) : ?>
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
            endif;
            if ($hasPermissionDeleteUser) :
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
            endif;

            if ($hasPermissionDeleteModule) :
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
            endif;

            if ($hasPermissionDeleteDate) :
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
			<?php endif; ?>
		</tbody>
	</table>
</div>
<?php
endif;
