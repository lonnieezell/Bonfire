<div class="well">
    <?php echo form_open(current_url(), 'class="form-inline"'); ?>
        <label for='trans_lang'><?php e(lang('translate_current_lang')); ?></label>
        <select name="trans_lang" id="trans_lang">
            <?php foreach ($languages as $lang) : ?>
            <option value="<?php e($lang); ?>"<?php echo isset($trans_lang) && $trans_lang == $lang ? ' selected="selected"' : ''; ?>><?php e(ucfirst($lang)); ?></option>
            <?php endforeach; ?>
            <option value="other"><?php e(lang('translate_other')); ?></option>
        </select>
        <div id='new_lang_field' style='display: none;'>
            <label for='new_lang'><?php e(lang('translate_new_lang')); ?></label>
            <input type="text" name="new_lang" id="new_lang" value="<?php echo set_value('new_lang'); ?>" />
        </div>
        <input type="submit" name="select_lang" class="btn btn-small btn-primary" value="<?php e(lang('translate_select')); ?>" />
    <?php echo form_close(); ?>
</div>
<!-- Core -->
<div class="admin-box">
    <h3><?php echo lang('translate_core'); ?> <span class="subhead"><?php echo count($lang_files) . ' ' . lang('bf_files'); ?></span></h3>
    <?php
    $linkUrl = site_url(SITE_AREA . "/developer/translate/edit/{$trans_lang}");
    $cnt = 1;
    $brk = 3;
    foreach ($lang_files as $file) :
        if ($cnt == 1) :
    ?>
    <div class="row-fluid">
        <?php
        endif;
        ++$cnt;
        ?>
        <a class='span4' href='<?php echo "{$linkUrl}/{$file}"; ?>'><?php e($file); ?></a>
        <?php
        if ($cnt > $brk) :
        ?>
    </div>
    <?php
            $cnt = 1;
        endif;
    endforeach;
    if ($cnt != 1) :
    ?>
    </div>
<?php endif; ?>
</div>
<!-- Modules -->
<div class="admin-box">
    <h3><?php echo lang('translate_modules').((! empty($modules) && is_array($modules))?' <span class="subhead">'.count($modules).' '.lang('bf_files').'</span>':''); ?></h3>
    <?php
    if (! empty($modules) && is_array($modules)) :
        $linkUrl = site_url(SITE_AREA . "/developer/translate/edit/{$trans_lang}");
        $cnt = 1;
        $brk = 3;
        foreach ($modules as $file) :
            if ($cnt == 1) :
    ?>
    <div class="row-fluid">
        <?php
            endif;
            $cnt++;
        ?>
        <a class='span4' href="<?php echo "{$linkUrl}/{$file}"; ?>"><?php e($file); ?></a>
        <?php if ($cnt > $brk) : ?>
    </div>
    <?php
                $cnt = 1;
            endif;
        endforeach;
        if ($cnt != 1) :
        ?>
    </div>
    <?php
        endif;
    else :
    ?>
    <div class="alert alert-info fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <?php echo lang('translate_no_modules'); ?>
    </div>
    <?php endif; ?>
</div>