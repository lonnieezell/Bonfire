<style>
td { vertical-align: baseline; }
tr:hover { background: #f6f6f6; border-top: 1px solid #e7e7e7; border-bottom: 1px solid #e7e7e7; }
</style>
<br/>
<p><?php echo lang('mig_intro'); ?></p>

<?php if ($this->config->item('migrations_enabled') === false) :?>

	<div class="notification attention">
		<p><?php echo lang('mig_not_enabled'); ?></p>
	</div>

<?php else : ?>

	<div id="tabs">
		<ul>
			<li><a href="#app-tab">Application</a></li>
			<li><a href="#mod-tab">Modules</a></li>
			<li><a href="#core-tab">Bonfire</a></li>
		</ul>
		
		<!-- Application Migrations -->
		<div id="app-tab">
			<h2><?php echo lang('mig_app_migrations'); ?></h2>
		
			<div class="notification information">
				<p><?php echo lang('mig_installed_version'); ?> <b><?php echo $installed_version; ?></b> / 
				<?php echo lang('mig_latest_version'); ?> <b><?php echo $latest_version ?></b></p>
			</div>
				
			<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
				<input type="hidden" name="core_only" value="0" />
			
				<?php if (count($app_migrations)) : ?>
				<p>
					<?php echo lang('mig_choose_migration'); ?> 
					<select name="migration">
					<?php foreach ($app_migrations as $migration) :?>
						<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
					<?php endforeach; ?>
					</select>
				</p>
			
				<div class="submits">
					<input type="submit" name="submit" value="<?php echo lang('mig_migrate_button'); ?>" /> or <?php echo anchor(SITE_AREA .'/developer/migrations', lang('bf_action_cancel')); ?>
				</div>
				<?php else: ?>
					<p><?php echo lang('mig_no_migrations') ?></p>
				<?php endif; ?>
			<?php echo form_close(); ?>
		</div>
		
		<!-- Module Migrations -->
		<div id="mod-tab">
			<h2><?php echo lang('mig_mod_migrations'); ?></h2>
	
			<?php if (isset($mod_migrations) && is_array($mod_migrations)) :?>
				<table>
					<thead>
						<tr>
							<th style="vertical-align: bottom;">Module</th>
							<th style="width: 6em">Installed Version</th>
							<th style="width: 6em">Latest Version</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($mod_migrations as $module => $migrations) : ?>
							<?php echo form_open(site_url(SITE_AREA .'/developer/migrations/migrate_module/'. $module)); ?>
								<input type="hidden" name="is_module" value="1" />
						<tr>
							<td><?php echo ucfirst($module) ?></td>
							<td><?php echo $migrations['installed_version'] ?></td>
							<td><?php echo $migrations['latest_version'] ?></td>
							<td style="width: 25em; text-align: right;">
								<select name="version">
									<option value="uninstall">Uninstall</option>
								<?php foreach ($migrations as $migration) : ?>
									<?php if(is_array($migration)): ?>
										<?php foreach ($migration as $filename) :?>
											<option><?php echo $filename; ?></option>
										<?php endforeach; ?>
									<?php endif;?>
								<?php endforeach; ?>
								</select>
							</td>
							<td style="width: 10em">
								<input type="submit" name="submit" value="Migrate Module" />
							</td>
						</tr>
						</form>
						<?php endforeach; ?>
					</tbody>
				</table>
			
			<?php else : ?>
				<div class="notification information">
					<p>No modules have any migrations available.</p>
				</div>
			<?php endif; ?>
		</div>
		
		<!-- Bonfire Migrations -->
		<div id="core-tab">
			<h2><?php echo lang('mig_core_migrations'); ?></h2>
		
			<div class="notification information">
				<p><?php echo lang('mig_installed_version'); ?> <b><?php echo $core_installed_version; ?></b> / 
				<?php echo lang('mig_latest_version'); ?> <b><?php echo $core_latest_version ?></b></p>
			</div>
			
			<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
				<input type="hidden" name="core_only" value="1" />
			
				<?php if (count($core_migrations)) : ?>
				<p>
					<?php echo lang('mig_choose_migration'); ?> 
					<select name="migration">
					<?php foreach ($core_migrations as $migration) :?>
						<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
					<?php endforeach; ?>
					</select>
				</p>
			
				<div class="submits">
					<input type="submit" name="submit" value="<?php echo lang('mig_migrate_button'); ?>" /> or <?php echo anchor(SITE_AREA .'/developer/migrations', lang('bf_action_cancel')); ?>
				</div>
				<?php else: ?>
					<p><?php echo lang('mig_no_migrations') ?></p>
				<?php endif; ?>
			<?php echo form_close(); ?>
		</div>
		
	</div>

<?php endif; ?>



<script>
head.ready(function(){
	
	$( "#tabs" ).tabs();
	
});
</script>