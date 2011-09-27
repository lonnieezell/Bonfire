<div class="view split-view">
	
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
				<p><?php echo lang('log_no_logs'); ?></p>
			</div>
		
		<?php endif; ?>
	</div>	
	
	
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
				<?php if ($log_threshold == 0) : ?>
				<div class="notification attention">
					<p><?php echo lang('log_not_enabled'); ?></p>
				</div>
				<?php endif; ?>
			
			
				<?php echo form_open(site_url(SITE_AREA .'/developer/logs/enable'), 'class="constrained"'); ?>

				<div>
					<br/>
					<label for="log_threshold"><?php echo lang('log_the_following'); ?></label>
					<select name="log_threshold">
						<option value="0" <?php echo ($log_threshold == 0) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_0'); ?></option>
						<option value="1" <?php echo ($log_threshold == 1) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_1'); ?></option>
						<option value="2" <?php echo ($log_threshold == 2) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_2'); ?></option>
						<option value="3" <?php echo ($log_threshold == 3) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_3'); ?></option>
						<option value="4" <?php echo ($log_threshold == 4) ? 'selected="selected"' : ''; ?>><?php echo lang('log_what_4'); ?></option>
					</select>						   
					
					<p class="small indent"><?php echo lang('log_what_note'); ?></p>
				</div>
			
				<div class="submits">
					<br/>
					<input type="submit" name="submit" value="<?php echo lang('log_save_button'); ?>" />
				</div>
			
			<?php echo form_close(); ?>
			
			
			
			<div class="notification information">
				<p><?php echo lang('log_big_file_note'); ?></p>
			</div>
			
			<?php if (isset($logs) && is_array($logs) && (count($logs) > 1)) : //index.html is 1 ?>
			<br/>
			
			<!-- Purge? -->
			<div class="box delete rounded">
				<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .'/developer/logs/purge/'); ?>" onclick="return confirm('Are you sure you want to delete all log files?')"><?php echo lang('log_delete_button'); ?></a>
				
				<?php echo lang('log_delete_note'); ?>
			</div>
			<?php endif; ?>
			
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->

