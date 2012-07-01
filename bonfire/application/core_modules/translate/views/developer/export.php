<h2><?php echo lang('tr_export_heading'); ?></h2>

<p class="intro"><?php echo lang('tr_export_note'); ?></p>

<?php echo form_open(current_url(), 'class="form-horizontal"'); ?>

	<div class="control-group">
		<label for="export_lang" class="control-label"><?php echo lang('tr_language'); ?></label>
		<div class="controls">
			<select name="export_lang">
			<?php foreach ($languages as $lang) :?>
				<option value="<?php echo $lang; ?>" <?php echo isset($trans_lang) && $trans_lang == $lang ? 'selected="selected"' : '' ?>><?php echo ucfirst($lang); ?></option>
			<?php endforeach; ?>
				<option value="other"><?php echo lang('tr_other'); ?></option>
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label"><?php echo lang('tr_include'); ?></label>
		<div class="controls">
			<label for="include_core">
				<input type="checkbox" id="include_core" name="include_core" value="1" checked="checked" />
				<?php echo lang('tr_include_core'); ?>
			</label>
			<label for="include_mods">
				<input type="checkbox" id="include_mods" name="include_mods" value="1" />
				<?php echo lang('tr_include_mods'); ?>
			</label>
		</div>
	</div>


	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('tr_action_export'); ?>" />
	</div>

<?php echo form_close(); ?>