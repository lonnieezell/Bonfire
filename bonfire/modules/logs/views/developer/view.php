<div class="admin-box">
    <h3><span><?php echo lang('logs_viewing'); ?></span> <?php echo $log_file_pretty; ?></h3>
    <?php if (empty($log_content)) : ?>
    <div class="alert alert-warning fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <?php echo lang('logs_not_found'); ?>
    </div>
    <?php else : ?>
    <span class='form-horizontal'>
        <div class='control-group'>
            <label for='filter' class='control-label'><?php echo lang('logs_filter_label'); ?></label>
            <div class='controls'>
                <select id="filter">
                    <option value="all"><?php echo lang('logs_show_all_entries'); ?></option>
                    <option value="error"><?php echo lang('logs_show_errors'); ?></option>
                </select>
            </div>
        </div>
    </span>
    <div id="log">
        <?php
        foreach ($log_content as $row) :
            // Log files start with PHP guard header
            if (strpos($row, '<?php') === 0) {
                continue;
            }

            // Log files usually contain an empty row after the guard header,
            // and any whitespace around the entry doesn't need to be output
            $row = trim($row);
            if (empty($row)) {
                continue;
            }

            $class = 'log-entry';
            if (strpos($row, 'ERROR') !== false) {
                $class .= ' alert-error';
            } elseif (strpos($row, 'DEBUG') !== false) {
                $class .= ' alert-warning';
            }
        ?>
        <div class="<?php echo $class; ?>"><?php e($row); ?></div>
        <?php endforeach; ?>
    </div>
<?php if ($canDelete) : ?>
</div>
<div class="admin-box">
    <h3><?php echo lang('logs_delete1_button') ?></h3>
    <?php echo form_open(site_url(SITE_AREA . '/developer/logs'), array('class' => 'form-horizontal')); ?>
        <div class="alert alert-warning fade in">
            <a class="close" data-dismiss="alert">&times;</a>
            <?php echo lang('logs_delete1_note'); ?>
        </div>
        <fieldset class="form-actions">
            <input type="hidden" name="checked[]" value="<?php e($log_file); ?>" />
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('<?php e(js_escape(lang('logs_delete_confirm'))) ?>')"><span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('logs_delete1_button'); ?></button>
        </fieldset>
    <?php
        echo form_close();
    endif;
endif;
?>
</div>