<?php
	// Setup our default assets to load.
	Assets::add_js( array(
		base_url() .'assets/js/bootstrap.js',
		base_url() .'assets/js/bootstrap-dropdown.js'
	));

	Assets::add_css( array('bootstrap.css', 'bootstrap-responsive'));

	$inline = '$(".dropdown-toggle").dropdown();';
	$inline .= '$(".tooltips").tooltip();';
	$inline .= '$(".login-btn").click(function(e){ e.preventDefault(); $("#modal-login").modal(); });';

	Assets::add_js( $inline, 'inline' );

	Template::block('header', 'parts/head');

	Template::block('topbar', 'parts/topbar');
?>
