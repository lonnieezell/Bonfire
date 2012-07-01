<div class="admin-box">
	<h3><?php echo $toolbar_title; ?></h3>

	<?php echo form_open(SITE_AREA .'/developer/database/backup', 'class="form-horizontal"'); ?>

		<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
			<?php foreach ($tables as $table) : ?>
				<input type="hidden" name="tables[]" value="<?php echo $table; ?>" />
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="alert alert-info">
			<p><?php echo lang('db_backup_warning'); ?></p>
		</div>

		<div class="control-group <?php echo form_error('file_name') ? 'error' : ''; ?>">
			<label for="file_name" class="control-label"><?php echo lang('db_filename'); ?></label>
			<div class="controls">
				<input type="text" name="file_name" value="<?php echo set_value('file_name', isset($file) && !empty($file) ? $file : ''); ?>" />
				<?php if (form_error('file_name')) echo '<span class="help-inline">'. form_error('file_name') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group">
			<label for="backup_options" class="control-label"><?php echo lang('db_backup_options'); ?></label>
			<div class="controls">
				<label class="checkbox" for="drop_tables">
					<input type="checkbox" name="drop_tables" id="drop_tables" value="1" />
					<?php echo lang('db_drop_question'); ?>
				</label>
				<label class="checkbox" for="add_inserts">
					<input type="checkbox" name="add_inserts" id="add_inserts" value="1" checked="checked" />
					<?php echo lang('db_insert_question'); ?>
				</label>
			</div>
		</div>

		<div class="control-group">
			<label for="file_type" class="control-label"><?php echo lang('db_compresssion_type'); ?></label>
			<div class="controls">
				<label class="radio">
					<input type="radio" name="file_type" id="file_type" value="txt" checked="checked" />
					<span><?php echo lang('db_compresssion_none'); ?></span>
				</label>
				<label class="radio">
					<input type="radio" name="file_type" id="file_type" value="gzip" />
					<span><?php echo lang('db_compresssion_gzip'); ?></span>
				</label>
				<label class="radio">
					<input type="radio" name="file_type" id="file_type" value="zip" />
					<span><?php echo lang('db_compresssion_zip'); ?></span>
				</label>
			</div>
		</div>
		<br />

		<div class="alert alert-warning">
			<?php echo lang('db_restore_note'); ?>
		</div>

		<div style="padding: 20px" class="small">
			<p><strong><?php echo lang('db_backup_database_tables'); ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php foreach ($tables as $table) : ?>
					<?php echo $table . '&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
				<?php endforeach; ?>
			</p>
		</div>

		<div class="form-actions">
			<button type="submit" name="submit" class="btn btn-primary" ><?php echo lang('bf_action_save'); ?></button> <?php echo lang('bf_or'); ?>
			<a href="/admin/developer/database"><?php echo lang('bf_action_cancel'); ?></a>
		</div>

	<?php echo form_close(); ?>