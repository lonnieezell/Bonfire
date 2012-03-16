    </div><!--/.container-->
    
    <footer class="footer">
    	<div class="container">
	        <?php if (ENVIRONMENT == 'development') :?>
				<p style="float: right; margin-right: 80px;">Page rendered in {elapsed_time} seconds, using {memory_usage}.</p>
			<?php endif; ?>
	
			<p>Powered Proudly by <a href="http://cibonfire.com" target="_blank">Bonfire <?php echo BONFIRE_VERSION ?></a></p>
		</div>
	</footer>
	
	<?php echo theme_view('parts/modal_login'); ?>

	<div id="debug"></div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"><\/script>')</script>

	<?php echo Assets::js(); ?>

</body>
</html>
