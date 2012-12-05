$(document).ready(function(e){

	$('#trans_lang').change(function() {
		var lang = $(this +'option:selected').val();
		
		if (lang == 'other')
		{
			$('#new_lang').show('slow');
		}
		else
		{
			$('#new_lang').hide('slow');
		}
	});

	var translate_form = $('#translate_form');
	if (translate_form.length)
	{
		function is_changed() {
			var changed = false;

			$('input[type="text"]', translate_form).each(function()
			{
				if (this.value != this.defaultValue)
				{
					changed = true;
				}
			});

			return changed;
		}

		translate_form.submit(function() {
			$(window).unbind();
		});

		$(window).bind('beforeunload', function(e) {
			if (!is_changed())
			{
				return;
			}

			message = "Your changes have not been saved.  Are you sure you want to leave this page?";

			e.returnValue = message;

			// Apparently Safari needs this instead
			return message;
		});
	}

})