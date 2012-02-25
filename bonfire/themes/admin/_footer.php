	<footer class="fluid-container footer">
		<p class="pull-right">
			Executed in {elapsed_time} seconds, using {memory_usage}.
			<br/>Powered by Bonfire <?php echo BONFIRE_VERSION ?>
		</p>
	</footer>

	<div id="debug"><!-- Stores the Profiler Results --></div>
	<script src="<?php echo base_url() ?>/assets/js/jquery-1.7.1.min.js"></script>
	<?php echo Assets::js(); ?>
</body>
</html>