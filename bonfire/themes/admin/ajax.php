<div class="scrollable">
	<div style="<?php echo isset($padding_style) ? $padding_style : 'padding: 1em 2em;'; ?>">
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
	$('form.ajax-form').ajaxForm({
		target: '#content',
	});

</script>