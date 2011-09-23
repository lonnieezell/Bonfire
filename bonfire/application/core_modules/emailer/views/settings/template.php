<p><?php echo lang('em_template_note'); ?></p>

<?php echo form_open(SITE_AREA .'/settings/emailer/template'); ?>

	<div class="fancy-text">
		<label for="header"><?php echo lang('em_header'); ?></label>
		<textarea name="header" rows="15"><?php echo htmlspecialchars_decode($this->load->view('email/_header', null, true)) ;?></textarea>
	</div>
	
	<div class="fancy-text">
		<label for="footer"><?php echo lang('em_footer'); ?></label>
		<textarea name="footer" rows="15"><?php echo htmlspecialchars_decode($this->load->view('email/_footer', null, true)) ;?></textarea>
	</div>

	<div class="submits">
		<input type="submit" name="submit" id="submit" value="Save Template" /> or <?php echo anchor(SITE_AREA .'/settings/emailer', 'Cancel'); ?>
	</div>

<?php echo form_close(); ?>