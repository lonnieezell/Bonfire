<h3>Database Restore</h3>
	
<?php if (isset($results) && !empty($results)) : ?>
	
	<h3>Restore Results:</h3>
	
	<div style="text-align: right; margin-bottom: 10px;">
		<a href="/admin/database/backups">Back to Database Tools</a>
	</div>
	
	<div class="content-box" style="padding: 15px">
		<p><?= $results ?></p>
	</div>
	
	<div style="text-align: right">
		<a href="/admin/database/backups">Back to Database Tools</a>
	</div>

<?php else : ?>

	<?php echo form_open($this->uri->uri_string()); ?>
	
		<input type="hidden" name="filename" value="<?= $filename ?>" />
	
		<h3>Restore database from file: <span style="color:#509b00"><?= $filename ?></span>?</h3>
		
		<div class="notification attention png_bg">
			<div>
				<p>Restoring a database from a backup file will result in some or all of your database being erased before restoring.</p>
				<p><strong>This may result in a loss of data</strong>.</p>
			</div>
		</div>
		
			<button type="submit" name="submit" class="button">Restore</button> or 
			<a href="/admin/developer/database/backups">Cancel</a>
	
	<?php echo form_close(); ?>
<?php endif; ?>
