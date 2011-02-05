<div class="scrollable">
	<div style="padding: 1em 2em;">
	<?php 
		echo Template::message();
		echo Template::yield(); 
	?>
	</div>
</div>

<script>
	/*
		Ajax form submittal
	*/
	$('form').ajaxForm({
		target: '#content',
	});

</script>