<div class="view split-view">
	<!-- Views List -->
	<div class="view">
		
		<div class="panel-header">
			<?php if (isset($modules) && is_array($modules) && count($modules) > 1) :?>
			<select id="view-filter" style="display: inline; max-width: 40%;">
				<option value="0">Module...</option>
				<?php foreach ($modules as $module) : ?>
					<?php if (strpos($module, '.') === false) :?>
						<option><?php echo ucfirst($module) ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			<?php endif; ?>
		</div>
		
		<?php if (isset($module_files) && is_array($module_files) && count($module_files)) : ?>
				
		<div class="scrollable">
			<div class="list-view" id="view-list">
		
			<?php foreach ($module_files as $name => $folders) : ?>
				<?php if (isset($folders['views']) && is_array($folders['views'])) : ?>
				
					<?php foreach ($folders['views'] as $folder => $files) : ?>
						<?php if (is_array($files)) :?>
							<?php foreach ($files as $file) :?>
							<div class="list-item with-icon" data-id="<?php echo $name .':'. $folder .'/'. $file ?>">
								<img src="<?php echo Template::theme_url('images/page.png') ?>" />
								<p>
									<b><?php echo ucfirst($name) ?></b><br/>
									<?php echo $folder .'/'. $file ?>
								</p>
							</div>
							<?php endforeach; ?>
						<?php else: ?>
						<div class="list-item with-icon" data-id="<?php echo $name .':'. $files ?>"">
							<img src="<?php echo Template::theme_url('images/page.png') ?>" />
							<p>
								<b><?php echo ucfirst($name) ?></b><br/>
								<span><?php echo $files ?></span>
							</p>
						</div>						
						<?php endif; ?>
					<?php endforeach;	// $folders['views'] ?>
				
				<?php endif; ?>
			<?php endforeach; // $module_files ?>
		
			</div>
		</div>
		
		<?php else: ?>
			
			<p>No View files found for your modules.</p>
			
		<?php endif; ?>
		
	</div>	<!-- /vertical-panel -->
	
	<!-- Content -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
				
				<?php echo '<pre>'; print_r($module_files); ?>
				
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>	<!-- /v-split -->