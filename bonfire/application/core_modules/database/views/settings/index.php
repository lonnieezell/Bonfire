<div class="v-split">
	<!-- List -->
	<div class="vertical-panel">
	
		<h2 class="panel-title">Servers</h2>
		
		<div class="scrollable">
			<div class="list-view" id="role-list">
			
				<div class="list-item" data-id="dev">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Development</b></p>
				</div>
				
				<div class="list-item" data-id="test">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Staging/Test</b></p>
				</div>
				
				<div class="list-item" data-id="prod">
					<img src="<?php echo Template::theme_url('images/database.png') ?>" />
					
					<p><b>Production</b></p>
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
				
			</div>	<!-- /inner -->
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div>	<!-- /vsplit -->