<div class="scrollable" id="ajax-scroller" style="margin: 18px 0 36px 0">
	<?php 
		echo Template::message();
		echo Template::yield(); 
	?>
	<br/>
</div>

<script>
	head.js(<?php echo Assets::external_js(null, true) ?>);
</script>
<?php echo Assets::module_js(); ?>
<?php echo Assets::inline_js(); ?>

<script>
	/*
		Ajax form submittal
	*/
   	$('form.ajax-form').ajaxForm({
    		target: '#content',
            beforeSubmit:function(arr, $form, options)
            {
                console.log($form);
                if ($form.hasClass('validate'))
                {
                    if (!$form.valid())
                        {
                            return false;
                        }
                }
            }
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