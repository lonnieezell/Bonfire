$(document).ready(function(e){
	var translate_form = $('#translate_form'),
		toggleNewLang = function() {
			var lang = $('#trans_lang').val();
			if (lang == 'other') {
				$('#new_lang_field').show('slow');
			} else {
				$('#new_lang_field').hide('slow');
			}
		},
		isChanged = function() {
			var changed = false;

			$('input[type="text"]', translate_form).each(function() {
				if (this.value != this.defaultValue) {
					changed = true;
				}
			});

			return changed;
		};

	$('#trans_lang').change(toggleNewLang);

	if (translate_form.length) {
		translate_form.submit(function() {
			$(window).unbind();
		});

		$(window).bind('beforeunload', function(e) {
			if ( ! isChanged()) {
				return null;
			}

			message = "Your changes have not been saved. Are you sure you want to leave this page?";
			e.returnValue = message;

			// Apparently Safari needs this instead
			return message;
		});

		$("button.gobottom").on('click', function(e) {
			e.preventDefault();
			$("html, body").animate({ scrollTop: $(document).height() }, 1000);
		});

		$("button.gotop").on('click', function(e) {
			e.preventDefault();
			$("html, body").animate({ scrollTop: 0 }, 1000);
		});
	}

	// Run on document ready to make sure the form is in sync
	toggleNewLang();
});
