<?php echo $this->load->view('emailer/settings/sub_nav', null, true); ?>

<br/>
<p>Emails are sent in HTML format. They can be customized by editing the header and footer, below.</p>

<?php echo form_open('admin/settings/emailer/template'); ?>

	<label>Header</label>
	<textarea name="header" rows="15"><?php echo htmlspecialchars_decode($this->load->view('email/_header', null, true)) ;?></textarea>
	
	
	<label>Footer</label>
	<textarea name="footer" rows="15"><?php echo htmlspecialchars_decode($this->load->view('email/_footer', null, true)) ;?></textarea>

	<div class="text-right">
		<br/>
		<input type="submit" name="submit" id="submit" value="Save Template" /> or <?php echo anchor('admin/settings/emailer', 'Cancel'); ?>
	</div>

<?php echo form_close(); ?>