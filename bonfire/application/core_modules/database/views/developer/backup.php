<style type="text/css">
form div label { width: 37%; }
form div input[type=text] { width: 45%; }
</style>

	<?php echo form_open('/admin/developer/database/backup', 'class="constrained"'); ?>
	
		<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
			<?php foreach ($tables as $table) : ?>
				<input type="hidden" name="tables[]" value="<?php echo $table ?>" />
			<?php endforeach; ?>
		<?php endif; ?>
		
		<div class="notification information png_bg">
			<p>Note: Due to the limited execution time and memory available to PHP, backing up very large databases may not be possible. If your database is very large you might need to backup directly from your SQL server via the command line, or have your server admin do it for you if you do not have root privileges.</p>
		</div>
		
		<div>
			<label for="file_name">File Name</label>
			<input type="text" name="file_name" class="text-input input" value="<?php echo $file ?>" />
		</div>

		<br/>
		
		<div>
			<label for="drop_tables" style="display: inline-block; width: 18em; margin-right: 2em">Add 'Drop Tables' command to SQL?</label>
			<select name="drop_tables">
				<option>No</option>
				<option>Yes</option>
			</select>
		</div>		
		
		<div>
			<label for="add_inserts" style="display: inline-block; width: 18em; margin-right: 2em">Add 'Inserts' for data to SQL?</label>
			<select name="add_inserts">
				<option>No</option>
				<option selected="selected">Yes</option>
			</select>
		</div>		
		
		<div>
			<label for="file_type" style="display: inline-block; width: 18em; margin-right: 2em">Compression type?</label>
			<select name="file_type">
				<option value="txt">None</option>
				<option>gzip</option>
				<option>zip</option>
			</select>
		</div>
		
		<br />
		
		<p class="small">The Restore option is only capable of reading un-compressed files. Gzip and Zip compression is good if you just want a backup to download and store on your computer.</p>
		
		<div style="padding: 20px" class="small">
			<p><strong>Backup Tables: &nbsp;&nbsp;</strong>
				<?php foreach ($tables as $table) : ?>
					<?php echo $table . '&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
				<?php endforeach; ?>
			</p>
		</div>
		
		<div style="text-align: right">
			<button type="submit" name="submit" class="button" >Backup</button> or 
			<a href="/admin/developer/database">Cancel</a>
		</div>
		
	<?php echo form_close(); ?>
