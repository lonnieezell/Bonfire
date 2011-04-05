<h2>Delete Database Tables</h2>

<?php echo form_open('admin/developer/database/drop'); ?>
	
	<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
		<?php foreach ($tables as $table) : ?>
			<input type="hidden" name="tables[]" value="<?php echo $table ?>" />
		<?php endforeach; ?>
	
	
		<h3>Really delete the following database tables?</h3>
		
		<ul>
		<?php foreach($tables as $file) : ?>
			<li><?php echo $file ?></li>
		<?php endforeach; ?>
		</ul>
		
		<div class="notification attention png_bg">
			<p>Deleting tables from the database will result in loss of data.</p>
			<p><strong>This may make your application non-functional.</strong></p>
		</div>
		
		<div style="margin-top: 20px;">
			<button type="submit" name="submit" class="button">Delete Tables</button> or 
			<a href="/admin/database">Cancel</a>
		</div>
	
	<?php endif; ?>
	
<?php echo form_close(); ?>