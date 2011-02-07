<div class="v-split">
	<!-- List -->
	<div class="vertical-panel">
	
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
	<div id="content">
		<div class="scrollable" id="ajax-content">
			<div class="inner">
			
				<div class="notification information">
					<p>You are currently running on the <b><?php echo ENVIRONMENT ?></b> server.</p>
				</div>
				
				<br />
				
				<h2>Databases At-a-glance</h2>
				
				<div class="row">
					<div class="column size1of3">
						<h3>Development</h3>
						
						<ul>
							<li>Hostname: <b><?php echo $settings['dev']['hostname'] ?></b></li>
							<li>Database: <b><?php echo $settings['dev']['database'] ?></b></li>
							<li>User: <b><?php echo $settings['dev']['username'] ?></b></li>
							<li>Driver: <b><?php echo $settings['dev']['dbdriver'] ?></b></li>
							<li>Prefix: <b><?php echo $settings['dev']['dbprefix'] ?></b></li>	
							<li>Debug On?: <b><?php echo $settings['dev']['db_debug'] == 1 ? 'Yes' : 'No' ?></b></li>
							<li>Persistent Connection: <b><?php echo $settings['dev']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></li>	
							<li>Strict Mode?: <b><?php echo $settings['dev']['stricton'] = 1 ? 'Yes' : 'No' ?></b></li>
						</ul>
					</div>
					
					<div class="column size1of3">
						<h3>Staging</h3>
						
						<ul>
							<li>Hostname: <b><?php echo $settings['test']['hostname'] ?></b></li>
							<li>Database: <b><?php echo $settings['test']['database'] ?></b></li>
							<li>User: <b><?php echo $settings['test']['username'] ?></b></li>
							<li>Driver: <b><?php echo $settings['test']['dbdriver'] ?></b></li>
							<li>Prefix: <b><?php echo $settings['test']['dbprefix'] ?></b></li>	
							<li>Debug On?: <b><?php echo $settings['test']['db_debug'] == 1 ? 'Yes' : 'No' ?></b></li>
							<li>Persistent Connection: <b><?php echo $settings['test']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></li>	
							<li>Strict Mode?: <b><?php echo $settings['test']['stricton'] = 1 ? 'Yes' : 'No' ?></b></li>
						</ul>
					</div>
					
					<div class="column size1of3">
						<h3>Development</h3>
						
						<ul>
							<li>Hostname: <b><?php echo $settings['prod']['hostname'] ?></b></li>
							<li>Database: <b><?php echo $settings['prod']['database'] ?></b></li>
							<li>User: <b><?php echo $settings['prod']['username'] ?></b></li>
							<li>Driver: <b><?php echo $settings['prod']['dbdriver'] ?></b></li>
							<li>Prefix: <b><?php echo $settings['prod']['dbprefix'] ?></b></li>	
							<li>Debug On?: <b><?php echo $settings['prod']['db_debug'] == 1 ? 'Yes' : 'No' ?></b></li>
							<li>Persistent Connection: <b><?php echo $settings['prod']['pconnect'] == 1 ? 'Yes' : 'No' ?></b></li>	
							<li>Strict Mode?: <b><?php echo $settings['prod']['stricton'] = 1 ? 'Yes' : 'No' ?></b></li>
						</ul>
					</div>
				</div>
				
			</div>	<!-- /inner -->
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->