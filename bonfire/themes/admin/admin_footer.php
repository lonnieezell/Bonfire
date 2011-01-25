		</div>	<!-- /main -->
	</div>	<!-- /page -->
	
	<div class="foot">
		<p>Powered by <b>Bonfire</b>.</p>
		<?php if (ENVIRONMENT == 'dev') : ?>
			<p>Page rendered in {elapsed_time} seconds, using {memory_usage}.</p>
		<?php endif; ?>
	</div>
	
	<script>
		head.js(
			"<?php echo Template::theme_url('js/jquery-1.4.4.min.js'); ?>",
			"<?php echo Template::theme_url('js/jquery-ui-1.8.8.min.js'); ?>",
			"<?php echo Template::theme_url('js/admin_global.js'); ?>"
		);
	</script>
</body>
</html>