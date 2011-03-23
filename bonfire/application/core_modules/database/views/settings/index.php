<div class="view split-view">
	<!-- List -->
	<div class="view">
	
		<h2 class="panel-title">Servers</h2>
		
		<div class="scrollable">
			<div class="list-view" id="role-list">
			
				<div class="list-item with-icon" data-id="development">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Development</b><br/>
					<span><?php echo $settings['development']['default']['hostname'] .'/'. $settings['development']['default']['database'] ?></span>
					</p>
				</div>
				
				<div class="list-item with-icon" data-id="testing">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Staging/Test</b><br/>
					<span><?php echo $settings['testing']['default']['hostname'] .'/'. $settings['testing']['default']['database'] ?></span>
					</p>
				</div>
				
				<div class="list-item with-icon" data-id="production">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Production</b><br/>
					<span><?php echo $settings['production']['default']['hostname'] .'/'. $settings['production']['default']['database'] ?></span>
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
								<td><b><?php echo $settings['development']['default']['hostname'] ?></b></td>
							</tr>
							<tr>
								<td>Database</td>
								<td><b><?php echo $settings['development']['default']['database'] ?></b></td>
							</tr>
							<tr>
								<td>User</td>
								<td><b><?php echo $settings['development']['default']['username'] ?></b></td>
							</tr>
							<tr>
								<td>Driver</td>
								<td><b><?php echo $settings['development']['default']['dbdriver'] ?></b></td>
							</tr>
							<tr>
								<td>Prefix</td>
								<td><b><?php echo $settings['development']['default']['dbprefix'] ?></b></td>
							</tr>
							<tr>
								<td>Debug On?</td>
								<td><b><?php echo $settings['development']['default']['db_debug'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Persistent?</td>
								<td><b><?php echo $settings['development']['default']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Strict Mode?</td>
								<td><b><?php echo $settings['development']['default']['stricton'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
						</table>
					</div>
					
					<div class="column size1of3">
						<h3>Staging</h3>
						
						<table>
							<tr>
								<td>Hostname</td>
								<td><b><?php echo $settings['testing']['default']['hostname'] ?></b></td>
							</tr>
							<tr>
								<td>Database</td>
								<td><b><?php echo $settings['testing']['default']['database'] ?></b></td>
							</tr>
							<tr>
								<td>User</td>
								<td><b><?php echo $settings['testing']['default']['username'] ?></b></td>
							</tr>
							<tr>
								<td>Driver</td>
								<td><b><?php echo $settings['testing']['default']['dbdriver'] ?></b></td>
							</tr>
							<tr>
								<td>Prefix</td>
								<td><b><?php echo $settings['testing']['default']['dbprefix'] ?></b></td>
							</tr>
							<tr>
								<td>Debug On?</td>
								<td><b><?php echo $settings['testing']['default']['db_debug'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Persistent?</td>
								<td><b><?php echo $settings['testing']['default']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Strict Mode?</td>
								<td><b><?php echo $settings['testing']['default']['stricton'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
						</table>
					</div>
					
					<div class="column size1of3 last-column">
						<h3>Production</h3>
						
						<table>
							<tr>
								<td>Hostname</td>
								<td><b><?php echo $settings['production']['default']['hostname'] ?></b></td>
							</tr>
							<tr>
								<td>Database</td>
								<td><b><?php echo $settings['production']['default']['database'] ?></b></td>
							</tr>
							<tr>
								<td>User</td>
								<td><b><?php echo $settings['production']['default']['username'] ?></b></td>
							</tr>
							<tr>
								<td>Driver</td>
								<td><b><?php echo $settings['production']['default']['dbdriver'] ?></b></td>
							</tr>
							<tr>
								<td>Prefix</td>
								<td><b><?php echo $settings['production']['default']['dbprefix'] ?></b></td>
							</tr>
							<tr>
								<td>Debug On?</td>
								<td><b><?php echo $settings['production']['default']['db_debug'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Persistent?</td>
								<td><b><?php echo $settings['production']['default']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
							<tr>
								<td>Strict Mode?</td>
								<td><b><?php echo $settings['production']['default']['stricton'] = 1 ? 'Yes' : 'No' ?></b></td>
							</tr>
						</table>
					</div>
				</div>
				
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->