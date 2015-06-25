    <?php if ( ! isset($show) || $show == true) : ?>
    <hr />
    <footer class="footer">
        <div class="container">
            <p>Powered by <a href="http://cibonfire.com" target="_blank">Bonfire <?php echo BONFIRE_VERSION; ?></a></p>
        </div>
    </footer>
    <?php endif; ?>
	<div id="debug"><!-- Stores the Profiler Results --></div>
    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <script src="<?php echo base_url(); ?>components/jquery/jquery.min.js"></script>
    <script src='<?php echo base_url(); ?>components/bootstrap/js/bootstrap.min.js'></script>
    <?php echo Assets::js(); ?>
</body>
</html>