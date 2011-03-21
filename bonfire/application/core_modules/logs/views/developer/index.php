<div class="view split-view">
	<!-- List -->
	<div class="view">
	
		<?php if (isset($logs) && is_array($logs) && count($logs)) : ?>
		
		<div class="scrollable">
			<div class="list-view" id="user-list">
			<?php foreach ($logs as $log) : ?>
				<?php if ($log != 'index.html') : ?>
				<div class="list-item" data-id="<?php echo $log ?>">				
					<img src="<?php echo Template::theme_url('images/issue.png') ?>" />

					<p>
						<b><?php 
								echo date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $log))));
							?></b><br/>
						<?php echo $log ?>
					</p>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div>	<!-- /list -->
		</div>
		
		<?php else : ?>
		
			<div class="notification information">
				<p>No logs found.</p>
			</div>
		
		<?php endif; ?>
	</div>	<!-- /vertical-panel -->
	
	<!-- Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
				<?php if ($log_threshold == 0) : ?>
				<div class="notification attention">
					<p>Logging is not currently enabled.</p>
				</div>
				<?php endif; ?>
			
			
				<?php echo form_open(site_url('admin/developer/logs/enable'), 'class="constrained"'); ?>

				<div>
					<br/>
					<label>Log the following:</label>
					<select name="log_threshold">
						<option value="0" <?php echo ($log_threshold == 0) ? 'selected="selected"' : ''; ?>>0 - Nothing</option>
						<option value="1" <?php echo ($log_threshold == 1) ? 'selected="selected"' : ''; ?>>1 - Error Message (including PHP Errors)</option>
						<option value="2" <?php echo ($log_threshold == 2) ? 'selected="selected"' : ''; ?>>2 - Debug Messages</option>
						<option value="3" <?php echo ($log_threshold == 3) ? 'selected="selected"' : ''; ?>>3 - Information Messages</option>
						<option value="4" <?php echo ($log_threshold == 4) ? 'selected="selected"' : ''; ?>>4 - All Messages</option>
					</select>						   
					
					<p class="small indent">The higher log values also include all messages from the lower numbers. So, logging 2 - Debug Messages also logs 1 - Error Messages.</p>
				</div>
			
				<div class="submits">
					<br/>
					<input type="submit" name="submit" value="Save Log Settings" />
				</div>
			
			<?php echo form_close(); ?>
			
			
			
			<div class="notification information">
				<p>Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.</p>
			</div>
			
			<?php if (isset($logs) && is_array($logs) && count($logs)) : ?>
			<br/>
			
			<!-- Purge? -->
			<div class="box delete rounded">
				<a class="button" id="delete-me" href="<?php echo site_url('admin/developer/logs/purge/'); ?>" onclick="return confirm('Are you sure you want to delete all log files?')">Delete Log Files</a>
				
				<h3>Delete all log files?</h3>
				
				<p>Deleting log files is permanent. There is no going back, so please make sure.</p>
			</div>
			<?php endif; ?>
			
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->

