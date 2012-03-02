      <hr>

      <footer class="footer">
        <?php if (ENVIRONMENT == 'development') :?>
    			<p style="float: right; margin-right: 80px;">Page rendered in {elapsed_time} seconds, using {memory_usage}.</p>
    		<?php endif; ?>

    		<p>Powered Proudly by <a href="http://cibonfire.com" target="_blank">Bonfire <?php echo BONFIRE_VERSION ?></a></p>

      </footer>

    </div><!--/.fluid-container-->

	<div id="debug"></div>

	<script type="text/javascript">
			if (typeof jQuery == 'undefined') {
					document.write(unescape("%3Cscript src='<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js' type='text/javascript'%3E%3C/script%3E"));
			}
	</script>

	<script>
		head.js(<?php echo Assets::external_js(null, true) ?>);
		head.js(<?php echo Assets::module_js(true) ?>);
	</script>
	<?php echo Assets::inline_js(); ?>
	<?php echo Modules::run('analytics/analytics/show_gcode'); ?>

</body>
</html>
