		</div>	<!-- /main -->
	
		<div class="foot">
		
			<div class="align-right">
				<?php if ($this->auth->is_logged_in()) : ?>
					<?php echo anchor('logout','Logout'); ?>
				<?php else : ?>
					<?php echo anchor('login','Login'); ?>
				<?php endif; ?>
			</div>
		
			<div class="align-left">
				<p>Powered by <strong>Bonfire</strong>.</p>
				<?php if (ENVIRONMENT == 'dev') : ?>
					<p class="small">Page rendered in {elapsed_time} seconds, using {memory_usage}.</p>
				<?php endif; ?>
			</div>
			
		</div>
		
		<div class="clearfix"></div>
	
	</div>	<!-- /page -->
</body>
</html>