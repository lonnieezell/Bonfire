		</div>	<!-- /main -->
	</div>	<!-- /page -->
	
	<div class="foot">
		<p>Powered by <strong>Bonfire</strong>.</p>
		<?php if (ENVIRONMENT == 'dev') : ?>
			<p>Page rendered in {elapsed_time} seconds, using {memory_usage}.</p>
		<?php endif; ?>
	</div>
</body>
</html>