<div class="box delete rounded">

<?php echo form_open($this->uri->uri_string()); ?>

	<button type="submit" name="submit" class="button">Delete Files</button> 
	<span class="cancel">or	<a href="/admin/developer/database/backups">Cancel</a></span>

	<h3>Delete Backup File<?php echo count($files) > 1 ? 's' : ''; ?></h3>
		
		<?php if (isset($files) && is_array($files) && count($files) > 0) : ?>
			<?php foreach ($files as $file) : ?>
				<input type="hidden" name="files[]" value="<?php echo $file ?>" />
			<?php endforeach; ?>
		
		
			<p><b>Really delete the following backup files?</b></p>
			
			<ul>
			<?php foreach($files as $file) : ?>
				<li><?php echo $file ?></li>
			<?php endforeach; ?>
			</ul>
		
		<?php endif; ?>
		
	<?php echo form_close(); ?>
</div>
