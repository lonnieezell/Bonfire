<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open(SITE_AREA .'/developer/database/backup', 'class="form-horizontal"'); ?>

		<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
			<?php foreach ($tables as $table) : ?>
				<input type="hidden" name="tables[]" value="<?php echo $table ?>" />
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="alert alert-info">
			<p><?php echo lang('db_backup_warning'); ?></p>
		</div>

		<div class="control-group">
			<label for="file_name"><?php echo lang('db_filename'); ?></label>
			<div class="controls">
				<input type="text" name="file_name" value="<?php echo $file ?>" />
			</div>
		</div>

		<div class="control-group">
			<label for="drop_tables"><?php echo lang('db_drop_question') ?></label>
			<div class="controls">
				<select name="drop_tables">
					<option><?php echo lang('bf_no'); ?></option>
					<option><?php echo lang('bf_yes'); ?></option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="add_inserts"><?php echo lang('db_insert_question'); ?></label>
			<div class="controls">
				<select name="add_inserts">
					<option><?php echo lang('bf_no'); ?></option>
					<option selected="selected"><?php echo lang('bf_yes'); ?></option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="file_type"><?php echo lang('db_compress_question'); ?></label>
			<div class="controls">
				<select name="file_type">
					<option value="txt"><?php echo lang('bf_none'); ?></option>
					<option><?php echo lang('db_gzip'); ?></option>
					<option><?php echo lang('db_zip'); ?></option>
				</select>
			</div>
		</div>

		<br />

		<div class="alert alert-warning">
			<?php echo lang('db_restore_note'); ?>
		</div>

		<div style="padding: 20px" class="small">
			<p><strong><?php echo lang('db_backup') .' '. lang('db_tables'); ?>: &nbsp;&nbsp;</strong>
				<?php foreach ($tables as $table) : ?>
					<?php echo $table . '&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
				<?php endforeach; ?>
			</p>
		</div>

		<div class="form-actions">
			<button type="submit" name="submit" class="btn btn-primary" ><?php echo lang('db_backup'); ?></button> <?php echo lang('bf_or'); ?>
			<a href="/admin/developer/database"><?php echo lang('bf_action_cancel'); ?></a>
		</div>

	<?php echo form_close(); ?>
