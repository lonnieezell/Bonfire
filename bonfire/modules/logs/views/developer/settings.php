<?php if ($log_threshold == 0) : ?>
	<div class="alert alert-warning fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php echo lang('log_not_enabled'); ?>
	</div>
<?php endif; ?>

<div class="admin-box">

	<?php echo form_open(site_url(SITE_AREA .'/developer/logs/enable'), 'class="form-horizontal"'); ?>

	<fieldset>

		<div class="control-group">
			<label for="log_threshold" class="control-label"><?php echo lang('log_the_following'); ?></label>
			<div class="controls">
				<select name="log_threshold" id="log_threshold" style="width: auto; max-width: none;">
					<option value="0" <?php echo ($log_threshold == 0) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_0'); ?></option>
					<option value="1" <?php echo ($log_threshold == 1) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_1'); ?></option>
					<option value="2" <?php echo ($log_threshold == 2) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_2'); ?></option>
					<option value="3" <?php echo ($log_threshold == 3) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_3'); ?></option>
					<option value="4" <?php echo ($log_threshold == 4) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_4'); ?></option>
				</select>

				<p class="help-block"><?php echo lang('log_what_note'); ?></p>
			</div>
		</div>

	</fieldset>

	<div class="alert alert-info fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php echo lang('log_big_file_note'); ?>
	</div>

	<div class="form-actions">
		<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('log_save_button'); ?>" />
	</div>

<?php echo form_close(); ?>
</div>
