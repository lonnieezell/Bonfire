<?php if ($log_threshold == 0) : ?>
<div class="alert alert-warning fade in">
    <a class="close" data-dismiss="alert">&times;</a>
    <?php echo lang('logs_not_enabled'); ?>
</div>
<?php endif; ?>
<div class="admin-box">
	<?php echo form_open(site_url(SITE_AREA . '/developer/logs/enable'), 'class="form-horizontal"'); ?>
        <fieldset>
            <div class="control-group">
                <label for="log_threshold" class="control-label"><?php echo lang('logs_the_following'); ?></label>
                <div class="controls">
                    <select name="log_threshold" id="log_threshold">
                        <option value="0" <?php echo set_select('log_threshold', 0, $log_threshold == 0); ?>><?php echo lang('logs_what_0'); ?></option>
                        <option value="1" <?php echo set_select('log_threshold', 1, $log_threshold == 1); ?>><?php echo lang('logs_what_1'); ?></option>
                        <option value="2" <?php echo set_select('log_threshold', 2, $log_threshold == 2); ?>><?php echo lang('logs_what_2'); ?></option>
                        <option value="3" <?php echo set_select('log_threshold', 3, $log_threshold == 3); ?>><?php echo lang('logs_what_3'); ?></option>
                        <option value="4" <?php echo set_select('log_threshold', 4, $log_threshold == 4); ?>><?php echo lang('logs_what_4'); ?></option>
                    </select>
                    <p class="help-block"><?php echo lang('logs_what_note'); ?></p>
                </div>
            </div>
        </fieldset>
        <div class="alert alert-info fade in">
            <a class="close" data-dismiss="alert">&times;</a>
            <?php echo lang('logs_big_file_note'); ?>
        </div>
        <fieldset class="form-actions">
            <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('logs_save_button'); ?>" />
        </fieldset>
    <?php echo form_close(); ?>
</div>