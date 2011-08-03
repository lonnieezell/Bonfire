<h2><?php echo lang('bf_action_delete'); ?> <?php echo lang('db_database'); ?> <?php echo lang('db_tables'); ?></h2>

<?php echo form_open(SITE_AREA .'/developer/database/drop'); ?>
	
	<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
		<?php foreach ($tables as $table) : ?>
			<input type="hidden" name="tables[]" value="<?php echo $table ?>" />
		<?php endforeach; ?>
	
	
		<h3><?php echo lang('db_drop_confirm'); ?></h3>
		
		<ul>
		<?php foreach($tables as $file) : ?>
			<li><?php echo $file ?></li>
		<?php endforeach; ?>
		</ul>
		
		<div class="notification attention png_bg">
			<?php echo lang('db_drop_attention'); ?>
		</div>
		
		<div style="margin-top: 20px;">
			<button type="submit" name="submit" class="button"><?php echo lang('bf_action_delete'); ?> <?php echo lang('db_tables'); ?></button> <?php echo lang('bf_or'); ?> 
			<a href="/admin/database"><?php echo lang('bf_action_cancel'); ?></a>
		</div>
	
	<?php endif; ?>
	
<?php echo form_close(); ?>