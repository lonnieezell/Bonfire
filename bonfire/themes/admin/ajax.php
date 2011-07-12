<div class="scrollable" id="ajax-scroller" style="margin: 18px 0 36px 0">
	<?php 
		echo Template::message();
		echo Template::yield(); 
	?>
	<br/>
</div>

<script>
	/*
		Ajax form submittal
	*/
	$('form.ajax-form').ajaxForm({
		target: '#content',
	});
	
	/*
		AJAX Setup
	*/
	$.ajaxSetup({cache: false});

	$('#loader').ajaxStart(function(){
		$('#loader').show();
	});

	$('#loader').ajaxStop(function(){
		$('#loader').hide();
	});
</script>