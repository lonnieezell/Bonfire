	<footer class="container footer">
		<p class="pull-right">
			Executed in {elapsed_time} seconds, using {memory_usage}.<br />
			Powered by <a href="http://cibonfire.com" target="_blank"><span class="icon-fire"></span>&nbsp;Bonfire</a> <?php echo BONFIRE_VERSION; ?>
		</p>
	</footer>
	<div id="debug"><!-- Stores the Profiler Results --></div>
    <script src="<?php  echo base_url(); ?>components/jquery/jquery.min.js"></script>
    <script src='<?php  echo base_url(); ?>components/bootstrap/js/bootstrap.min.js'></script>
	<?php echo Assets::js(); ?>
</body>
</html>