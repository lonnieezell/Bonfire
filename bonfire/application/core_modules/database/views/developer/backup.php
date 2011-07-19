<style type="text/css">
form div label { width: 37%; }
form div input[type=text] { width: 45%; }
</style>

	<?php echo form_open(SITE_AREA .'/developer/database/backup', 'class="constrained"'); ?>
	
		<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
			<?php foreach ($tables as $table) : ?>
				<input type="hidden" name="tables[]" value="<?php echo $table ?>" />
			<?php endforeach; ?>
		<?php endif; ?>
		
		<div class="notification information png_bg">
			<p><?php echo lang('db_backup_warning'); ?></p>
		</div>
		
		<div>
			<label for="file_name" style="display: inline-block; width: 18em; margin-right: 2em"><?php echo lang('db_filename'); ?></label>
			<input type="text" name="file_name" class="text-input input" value="<?php echo $file ?>" />
		</div>

		<br/>
		
		<div>
			<label for="drop_tables" style="display: inline-block; width: 18em; margin-right: 2em"><?php echo lang('db_drop_question') ?></label>
			<select name="drop_tables">
				<option><?php echo lang('bf_no'); ?></option>
				<option><?php echo lang('bf_yes'); ?></option>
			</select>
		</div>		
		
		<div>
			<label for="add_inserts" style="display: inline-block; width: 18em; margin-right: 2em"><?php echo lang('db_insert_question'); ?></label>
			<select name="add_inserts">
				<option><?php echo lang('bf_no'); ?></option>
				<option selected="selected"><?php echo lang('bf_yes'); ?></option>
			</select>
		</div>		
		
		<div>
			<label for="file_type" style="display: inline-block; width: 18em; margin-right: 2em"><?php echo lang('db_compress_question'); ?></label>
			<select name="file_type">
				<option value="txt"><?php echo lang('bf_none'); ?></option>
				<option><?php echo lang('db_gzip'); ?></option>
				<option><?php echo lang('db_zip'); ?></option>
			</select>
		</div>
		
		<br />
		
		<p class="small"><?php echo lang('db_restore_note'); ?></p>
		
		<div style="padding: 20px" class="small">
			<p><strong><?php echo lang('db_backup') .' '. lang('db_tables'); ?>: &nbsp;&nbsp;</strong>
				<?php foreach ($tables as $table) : ?>
					<?php echo $table . '&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
				<?php endforeach; ?>
			</p>
		</div>
		
		<div style="text-align: right">
			<button type="submit" name="submit" class="button" ><?php echo lang('db_backup'); ?></button> <?php echo lang('bf_or'); ?> 
			<a href="/admin/developer/database"><?php echo lang('bf_action_cancel'); ?></a>
		</div>
		
	<?php echo form_close(); ?>
