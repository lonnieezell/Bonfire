<style>
.well form { margin: 0; }
</style>
<div class="well">
	<?php echo form_open(current_url(), 'class="form-inline"'); ?>
		<input type="hidden" name="new_lang" id="new_lang" value="" />
        <label for='trans_lang'><?php e(lang('tr_current_lang')); ?></label>
        <select name="trans_lang" id="trans_lang">
            <?php foreach ($languages as $lang) : ?>
            <option value="<?php e($lang); ?>" <?php echo isset($trans_lang) && $trans_lang == $lang ? 'selected="selected"' : ''; ?>><?php e(ucfirst($lang)); ?></option>
            <?php endforeach; ?>
            <option value="other"><?php e(lang('tr_other')); ?></option>
        </select>
        <input type="submit" name="select_lang" class="btn btn-small btn-primary" value="<?php e(lang('tr_select_lang')); ?>" />
	<?php echo form_close(); ?>
</div>
<!-- Core -->
<div class="admin-box">
	<h3><?php echo lang('tr_core') ?></h3>
    <table class="table table-striped">
        <tbody>
            <?php
            $linkUrl = site_url(SITE_AREA . "/developer/translate/edit/{$trans_lang}");
            foreach ($lang_files as $file) :
            ?>
            <tr>
                <td><a href='<?php echo "{$linkUrl}/{$file}"; ?>'><?php e($file); ?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Modules -->
<div class="admin-box">
	<h3><?php e(lang('tr_modules')); ?></h3>
    <?php if (isset($modules) && is_array($modules) && count($modules)) :  ?>
    <table class="table table-striped">
        <tbody>
            <?php
            $linkUrl = site_url(SITE_AREA . "/developer/translate/edit/{$trans_lang}");
            foreach ($modules as $file) :
            ?>
            <tr>
                <td><a href="<?php echo "{$linkUrl}/{$file}"; ?>"><?php e($file); ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <div class="alert alert-info fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <?php echo lang('tr_no_modules'); ?>
    </div>
    <?php endif; ?>
</div>