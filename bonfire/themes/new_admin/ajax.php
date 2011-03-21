<div class="scrollable" style="margin: 18px 0 36px 0">
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

</script>