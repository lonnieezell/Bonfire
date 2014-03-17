<div class="box select admin-box">
    <?php echo form_open(SITE_AREA . "/reports/activities/{$vars['which']}", 'class="form-horizontal constrained"'); ?>
        <fieldset>
            <legend><?php echo lang('activities_filter_head'); ?></legend>
            <?php
            $form_help = '<span class="help-inline">' . sprintf(lang('activities_filter_note'), ($vars['view_which'] == ucwords(lang('activities_date')) ? lang('activities_filter_from_before') : lang('activities_filter_only_for')), strtolower($vars['view_which'])) . '</span>';
            $form_data = array('name' => "{$vars['which']}_select", 'id' => "{$vars['which']}_select", 'class' => 'span3');
            echo form_dropdown($form_data, $select_options, $filter, lang('activities_filter_head'), '' , $form_help);
            unset($form_data, $form_help);
            ?>
        </fieldset>
        <fieldset class="form-actions">
            <?php
            echo form_submit('filter', lang('activities_filter'), 'class="btn btn-primary"');
            if ($vars['which'] == 'activity_own' && has_permission('Activities.Own.Delete')) :
            ?>
            <button type="submit" name="delete" class="btn btn-danger" id="delete-activity_own"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_own_delete'); ?></button>
            <?php elseif ($vars['which'] == 'activity_user' && has_permission('Activities.User.Delete')) : ?>
            <button type="submit" name="delete" class="btn btn-danger" id="delete-activity_user"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_user_delete'); ?></button>
            <?php elseif ($vars['which'] == 'activity_module' && has_permission('Activities.Module.Delete')) : ?>
            <button type="submit" name="delete" class="btn btn-danger" id="delete-activity_module"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_module_delete'); ?></button>
            <?php elseif ($vars['which'] == 'activity_date' && has_permission('Activities.Date.Delete')) : ?>
            <button type="submit" name="delete" class="btn btn-danger" id="delete-activity_date"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('activities_date_delete'); ?></button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>
<h2><?php
    echo sprintf(
            lang('activities_view'),
            $vars['view_which'] == ucwords(lang('activities_date')) ? sprintf(lang('activities_view_before'), $vars['view_which']) : $vars['view_which'],
            $vars['name']
    ); ?></h2>
<?php if (empty($activity_content)) : ?>
<div class="alert alert-error fade in">
    <a class="close" data-dismiss="alert">&times;</a>
    <h4 class="alert-heading"><?php echo lang('activities_not_found'); ?></h4>
</div>
<?php else : ?>
<div id="user_activities">
    <table class="table table-striped table-bordered" id="flex_table">
        <thead>
            <tr>
                <th><?php echo lang('activities_user'); ?></th>
                <th><?php echo lang('activities_activity'); ?></th>
                <th><?php echo lang('activities_module'); ?></th>
                <th><?php echo lang('activities_when'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activity_content as $activity) : ?>
            <tr>
                <td><span class="icon-user"></span>&nbsp;<?php e($activity->username); ?></td>
                <td><?php echo $activity->activity; ?></td>
                <td><?php echo $activity->module; ?></td>
                <td><?php echo date('M j, Y g:i A', strtotime($activity->created)); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
    echo $this->pagination->create_links();
endif;
?>