	<footer>
		<div class="container">
			<div class="row">
				<div class="column size1of2">
					<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $this->current_user->id) ?>">My Account</a>
				</div>

				<div class="column size1of2 last-column">
					<p>{elapsed_time} seconds. {memory_usage}<br/>
					Built with <a href="http://cibonfire.com" target="_blank">Bonfire</a></p>
				</div>
			</div>
		</div>
	</footer>

	<div id="debug"><!-- Stores the Profiler Results --></div>
	<script type="text/javascript">
			if (typeof jQuery == 'undefined') {
					document.write(unescape("%3Cscript src='<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js' type='text/javascript'%3E%3C/script%3E"));
			}
	</script>

	<?php echo Assets::js(); ?>
</body>
</html>
