<div class="content-box">
	
	<div class="content-box-header">
		<h3>Delete Backup Files</h3>
	</div>
	
	<div class="content-box-content">
	
		<?php echo form_open('/admin/database/delete'); ?>
			
			<?php if (isset($files) && is_array($files) && count($files) > 0) : ?>
				<?php foreach ($files as $file) : ?>
					<input type="hidden" name="files[]" value="<?= $file ?>" />
				<?php endforeach; ?>
			
			
				<h3>Really delete the following backup files?</h3>
				
				<ul>
				<?php foreach($files as $file) : ?>
					<li><?= $file ?></li>
				<?php endforeach; ?>
				</ul>
				
				<div style="margin-top: 20px;">
					<button type="submit" name="submit" class="button">Delete Files</button> or 
					<a href="/admin/database/backups">Cancel</a>
				</div>
			
			<?php endif; ?>
			
		<?php echo form_close(); ?>
	</div>	<!-- /content-box-content -->
</div>	<!-- /content-box -->