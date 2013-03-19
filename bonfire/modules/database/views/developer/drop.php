<h2><?php echo lang('bf_action_delete'); ?> <?php echo lang('db_database'); ?> <?php echo lang('db_tables'); ?></h2>

<?php echo form_open(SITE_AREA .'/developer/database/drop'); ?>

	<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
		<?php foreach ($tables as $table) : ?>
			<input type="hidden" name="tables[]" value="<?php e($table) ?>" />
		<?php endforeach; ?>


		<h3><?php echo lang('db_drop_confirm'); ?></h3>

		<ul>
		<?php foreach($tables as $file) : ?>
			<li><?php e($file) ?></li>
		<?php endforeach; ?>
		</ul>

		<div class="notification attention png_bg">
			<?php echo lang('db_drop_attention'); ?>
		</div>

		<div class="actions">
			<button type="submit" name="drop" class="btn btn-danger"><?php echo lang('bf_action_delete'); ?> <?php echo lang('db_tables'); ?></button> <?php echo lang('bf_or'); ?>
			<?php echo anchor(SITE_AREA .'/developer/database', lang('bf_action_cancel')); ?>
		</div>

	<?php endif; ?>

<?php echo form_close(); ?>
