<p class="intro"><?php echo lang('translate_export_note'); ?></p>
<div class='admin-box'>
    <?php echo form_open(current_url(), ''); ?>
    <fieldset>
        <div class="form-group">
            <label for="export_lang"><?php echo lang('translate_language'); ?></label>
            <select name="export_lang" class="form-control" id="export_lang">
                <?php foreach ($languages as $lang) : ?>
                    <option
                        value="<?php e($lang); ?>" <?php echo isset($trans_lang) && $trans_lang == $lang ? 'selected="selected"' : '' ?>><?php e(ucfirst($lang)); ?></option>
                <?php endforeach; ?>
                <option value="other"><?php e(lang('translate_other')); ?></option>
            </select>
        </div>
        <div class="form-group">
            <label><?php echo lang('translate_include'); ?></label>
            <div class="checkbox">
                <label class="checkbox" for="include_core">
                    <input type="checkbox" id="include_core" name="include_core" value="1" checked="checked"/>
                    <?php echo lang('translate_include_core'); ?>
                </label>
                <label class="checkbox" for="include_mods">
                    <input type="checkbox" id="include_mods" name="include_mods" value="1"/>
                    <?php echo lang('translate_include_mods'); ?>
                </label>
            </div>
        </div>
    </fieldset>
    <fieldset class="form-actions">
        <input type="submit" name="export" class="btn btn-primary" value="<?php e(lang('translate_export_short')); ?>"/>
    </fieldset>
    <?php echo form_close(); ?>
</div>