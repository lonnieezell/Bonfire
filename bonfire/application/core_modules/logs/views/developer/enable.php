<?php echo $this->load->view('developer/sub_nav.php', null, true); ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>

	<div>
		<label>Log the following:</label>
		<select name="log_threshold">
			<option value="0" <?php echo ($log_threshold == 0) ? 'selected="selected"' : ''; ?>>0 - Nothing</option>
			<option value="1" <?php echo ($log_threshold == 1) ? 'selected="selected"' : ''; ?>>1 - Error Message (including PHP Errors)</option>
			<option value="2" <?php echo ($log_threshold == 2) ? 'selected="selected"' : ''; ?>>2 - Debug Messages</option>
			<option value="3" <?php echo ($log_threshold == 3) ? 'selected="selected"' : ''; ?>>3 - Information Messages</option>
			<option value="4" <?php echo ($log_threshold == 4) ? 'selected="selected"' : ''; ?>>4 - All Messages</option>
		</select>						   
		
		<p class="small" style="margin-left: 28%">The higher log values also include all messages from the lower numbers. So, logging 2 - Debug Messages also logs 1 - Error Messages.</p>
	</div>

	<div class="text-right">
		<br/>
		<input type="submit" name="submit" value="Save Log Settings" />
	</div>

<?php echo form_close(); ?>



<div class="notification information">
	<p>Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.</p>
</div>