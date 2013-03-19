<br/>
<?php if (isset($results) && !empty($results)) : ?>

	<h3><?php echo lang('db_restore_results'); ?>:</h3>

	<div style="text-align: right; margin-bottom: 10px;">
		<?php echo anchor(SITE_AREA .'/developer/database/backups', lang('db_back_to_tools')); ?>
	</div>

	<div class="content-box" style="padding: 15px">
		<p><?php echo $results ?></p>
	</div>

	<div class="text-right">
		<?php echo anchor(SITE_AREA .'/developer/database/backups', lang('db_back_to_tools')); ?>
	</div>

<?php else : ?>

	<?php echo form_open($this->uri->uri_string()); ?>

		<input type="hidden" name="filename" value="<?php echo $filename ?>" />

		<h3><?php echo lang('db_restore_file'); ?>: <span style="color:#509b00"><?php echo $filename ?></span>?</h3>

		<div class="notification attention png_bg">
			<div>
				<?php echo lang('db_restore_attention'); ?>
			</div>
		</div>

		<div class="form-actions">
			<input type="submit" name="restore" class="btn btn-primary" value="<?php echo lang('db_restore'); ?>" /> <?php echo lang('bf_or'); ?>
			<?php echo anchor(SITE_AREA .'/developer/database/backups', lang('bf_action_cancel')); ?>
		</div>

	<?php echo form_close(); ?>
<?php endif; ?>
