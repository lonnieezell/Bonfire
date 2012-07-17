<p class="intro"><?php echo lang('em_template_note'); ?></p>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>
	
		<?php echo form_open(SITE_AREA .'/settings/emailer/template'); ?>
	
		<fieldset>
			<legend><?php echo lang('em_header'); ?></legend>
			<div class="clearfix">
				<div class="input">
					<textarea name="header" rows="15" style="width: 99%"><?php echo htmlspecialchars_decode($this->load->view('email/_header', null, true)) ;?></textarea>
				</div>
			</div>
		</fieldset>
	
		<fieldset>
			<legend><?php echo lang('em_footer'); ?></legend>
	
			<div class="clearfix">
				<div class="input">
					<textarea name="footer" rows="15" style="width: 99%"><?php echo htmlspecialchars_decode($this->load->view('email/_footer', null, true)) ;?></textarea>
				</div>
			</div>
		</fieldset>
	
		<div class="form-actions">
			<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Save Template" /> or <?php echo anchor(SITE_AREA .'/settings/emailer', 'Cancel'); ?>
		</div>
	
	<?php echo form_close(); ?>

</div>
