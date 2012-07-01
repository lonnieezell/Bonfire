<br/>
<?php if (isset($results) && !empty($results)) : ?>

	<h3><?php echo lang('db_restore_results'); ?>:</h3>

	<div style="text-align: right; margin-bottom: 10px;">
		<a href="/admin/database/backups"><?php echo lang('db_back_to_tools'); ?></a>
	</div>

	<div class="content-box" style="padding: 15px">
		<p><?php echo $results; ?></p>
	</div>

	<div class="text-right">
		<a href="/admin/database/backups"><?php echo lang('db_back_to_tools'); ?></a>
	</div>

<?php else : ?>

	<?php echo form_open($this->uri->uri_string()); ?>

		<input type="hidden" name="filename" value="<?php echo $filename; ?>" />

		<h3><?php printf(lang('db_restore_file'), '<span style="color:green">' . $filename . '</span>'); ?></h3>

		<div class="notification attention png_bg">
			<div>
				<?php echo lang('db_restore_attention'); ?>
			</div>
		</div>

		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_restore'); ?>" /> <?php echo lang('bf_or'); ?>
			<a href="<?php echo site_url(SITE_AREA .'/developer/database/backups') ?>"><?php echo lang('bf_action_cancel'); ?></a>
		</div>

	<?php echo form_close(); ?>
<?php endif; ?>