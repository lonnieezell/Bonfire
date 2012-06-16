<p class="intro"><?php echo lang('mig_intro'); ?></p>

<?php if ($this->config->item('migrations_enabled') === false) :?>

	<div class="alert alert-warning fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php echo lang('mig_not_enabled'); ?></p>
	</div>

<?php else : ?>

	<div class="admin-box">
		<h3><?php echo $toolbar_title ?></h3>

		<ul class="nav nav-tabs">
			<li class="active"><a href="#app-tab" data-toggle="tab"><?php echo lang('mig_t_application'); ?></a></li>
			<li><a href="#mod-tab" data-toggle="tab"><?php echo lang('mig_t_module'); ?></a></li>
			<li><a href="#core-tab" data-toggle="tab"><?php echo lang('mig_t_bonfire'); ?></a></li>
		</ul>

		<div class="tab-content">
			<!-- Application Migrations -->
			<div class="tab-pane active" id="app-tab">
				<fieldset>
				<legend><?php echo lang('mig_app_migrations'); ?></legend>

				<br/>

				<div class="alert alert-info fade in">
					<a class="close" data-dismiss="alert">&times;</a>
					<?php echo lang('mig_installed_version'); ?> <b><?php echo $installed_version; ?></b> /
					<?php echo lang('mig_latest_version'); ?> <b><?php echo $latest_version ?></b>
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

					<div class="form-actions">
						<input type="submit" name="submit" value="<?php echo lang('mig_action_migrate_database'); ?>" /> or <?php echo anchor(SITE_AREA .'/developer/migrations', '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-danger"'); ?>
					</div>
					<?php else: ?>
						<div class="alert alert-warning fade in">
	  						<a class="close" data-dismiss="alert">&times;</a>

							<?php echo lang('mig_no_migration') ?>
						</div>
					<?php endif; ?>
				<?php echo form_close(); ?>
				</fieldset>
			</div>

			<!-- Module Migrations -->
			<div id="mod-tab" class="tab-pane">
				<fieldset>
				<legend><?php echo lang('mig_mod_migrations'); ?></legend>

				<?php if (isset($mod_migrations) && is_array($mod_migrations)) :?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th style="vertical-align: bottom;"><?php echo lang('mig_mod_module'); ?></th>
								<th style="width: 6em"><?php echo lang('mig_mod_installed_version'); ?></th>
								<th style="width: 6em"><?php echo lang('mig_mod_latest_version'); ?></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($mod_migrations as $module => $migrations) : ?>
								<?php echo form_open(site_url(SITE_AREA .'/developer/migrations/migrate_module/'. $module), 'class="form-horizontal"'); ?>
									<input type="hidden" name="is_module" value="1" />
							<tr>
								<td><?php echo ucfirst($module); ?></td>
								<td><?php echo $migrations['installed_version']; ?></td>
								<td><?php echo $migrations['latest_version']; ?></td>
								<td style="width: 25em; text-align: right;">
									<select name="version">
										<option value="uninstall"><?php echo lang('mig_mod_uninstall'); ?></option>
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
									<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('mig_action_migrate_module'); ?>" />
								</td>
							</tr>
							</form>
							<?php endforeach; ?>
						</tbody>
					</table>

				<?php else : ?>
					<br/>
					<div class="alert alert-info fade in ">
  						<a class="close" data-dismiss="alert">&times;</a>
						<?php echo lang('mig_mod_no_migration'); ?>
					</div>
				<?php endif; ?>
				</fieldset>
			</div>

			<!-- Bonfire Migrations -->
			<div id="core-tab" class="tab-pane">

				<fieldset>
					<legend><?php echo lang('mig_core_migrations'); ?></legend>

					<br/>
					<div class="alert alert-info fade in ">
  						<a class="close" data-dismiss="alert">&times;</a>
						<?php echo lang('mig_installed_version'); ?> <b><?php echo $core_installed_version; ?></b> /
						<?php echo lang('mig_latest_version'); ?> <b><?php echo $core_latest_version ?></b>
					</div>

					<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
						<input type="hidden" name="core_only" value="1" />

						<?php if (count($core_migrations)) : ?>
						<div class="control-group">
							<?php echo form_simple_label('migration', lang('mig_choose_migration')); ?>
							<div class="controls">
								<select name="migration">
								<?php foreach ($core_migrations as $migration) :?>
									<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
								<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('mig_action_migrate_database'); ?>" /> <?php echo lang('bf_or'); ?> 
							<?php echo anchor(SITE_AREA .'/developer/migrations', '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-danger"'); ?>
						</div>
						<?php else: ?>
							<p><?php echo lang('mig_no_migration'); ?></p>
						<?php endif; ?>
					<?php echo form_close(); ?>
				</div>
			</fieldset>
		</div>

<?php endif; ?>
