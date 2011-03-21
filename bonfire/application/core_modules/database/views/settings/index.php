<div class="view split-view">
	<!-- List -->
	<div class="view">
	
		<h2 class="panel-title">Servers</h2>
		
		<div class="scrollable">
			<div class="list-view" id="role-list">
			
				<div class="list-item" data-id="dev">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Development</b><br/>
					<?php echo $settings['dev']['hostname'] .'/'. $settings['dev']['database'] ?>
					</p>
				</div>
				
				<div class="list-item" data-id="test">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Staging/Test</b><br/>
					<?php echo $settings['test']['hostname'] .'/'. $settings['test']['database'] ?>
					</p>
				</div>
				
				<div class="list-item" data-id="prod">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Production</b><br/>
					<?php echo $settings['prod']['hostname'] .'/'. $settings['prod']['database'] ?>
					</p>
				</div>
			
			</div>
		</div>
	
	</div>	<!-- /vertical-panel -->
	
	<!-- Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
			
				<div class="notification attention">
					<p class="text-center">You are currently running on the <b><?php echo ENVIRONMENT ?></b> server.</p>
				</div>

				<div class="row">
					<div class="column size1of3">
						<h3>Development</h3>
						
						<table>
							<tr>
								<td>Hostname</td>
								<td><b><?php echo $settings['dev']['hostname'] ?></b></td>
							</tr>
							<tr>
								<td>Database</td>
								<td><b><?php echo $settings['dev']['database'] ?></b></td>
							</tr>
							<tr>
								<td>User</td>
								<td><b><?php echo $settings['dev']['username'] ?></b></td>
							</tr>
							<tr>
								<td>Driver</td>
								<td><b><?php echo $settings['dev']['dbdriver'] ?></b></td>
							</tr>
							<tr>
								<td>Prefix</td>
								<td><b><?php echo $settings['dev']['dbprefix'] ?></b></td>
							</tr>
							<tr>
								<td>Debug On?</td>
								<td><b><?php echo $settings['dev']['db_debug'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Persistent?</td>
								<td><b><?php echo $settings['dev']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Strict Mode?</td>
								<td><b><?php echo $settings['dev']['stricton'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
						</table>
					</div>
					
					<div class="column size1of3">
						<h3>Staging</h3>
						
						<table>
							<tr>
								<td>Hostname</td>
								<td><b><?php echo $settings['test']['hostname'] ?></b></td>
							</tr>
							<tr>
								<td>Database</td>
								<td><b><?php echo $settings['test']['database'] ?></b></td>
							</tr>
							<tr>
								<td>User</td>
								<td><b><?php echo $settings['test']['username'] ?></b></td>
							</tr>
							<tr>
								<td>Driver</td>
								<td><b><?php echo $settings['test']['dbdriver'] ?></b></td>
							</tr>
							<tr>
								<td>Prefix</td>
								<td><b><?php echo $settings['test']['dbprefix'] ?></b></td>
							</tr>
							<tr>
								<td>Debug On?</td>
								<td><b><?php echo $settings['test']['db_debug'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Persistent?</td>
								<td><b><?php echo $settings['test']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Strict Mode?</td>
								<td><b><?php echo $settings['test']['stricton'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
						</table>
					</div>
					
					<div class="column size1of3 last-column">
						<h3>Production</h3>
						
						<table>
							<tr>
								<td>Hostname</td>
								<td><b><?php echo $settings['prod']['hostname'] ?></b></td>
							</tr>
							<tr>
								<td>Database</td>
								<td><b><?php echo $settings['prod']['database'] ?></b></td>
							</tr>
							<tr>
								<td>User</td>
								<td><b><?php echo $settings['prod']['username'] ?></b></td>
							</tr>
							<tr>
								<td>Driver</td>
								<td><b><?php echo $settings['prod']['dbdriver'] ?></b></td>
							</tr>
							<tr>
								<td>Prefix</td>
								<td><b><?php echo $settings['prod']['dbprefix'] ?></b></td>
							</tr>
							<tr>
								<td>Debug On?</td>
								<td><b><?php echo $settings['prod']['db_debug'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Persistent?</td>
								<td><b><?php echo $settings['prod']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Strict Mode?</td>
								<td><b><?php echo $settings['prod']['stricton'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
						</table>
					</div>
				</div>
				
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->