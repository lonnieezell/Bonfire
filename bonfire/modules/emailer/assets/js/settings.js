$(document).ready(function(){
	// Server type select
	$('#server_type').on('change', function(){
		// First, hide everything
		$('#mail, #sendmail, #smtp').css('display', 'none');

        // Display the settings for the selected server type.
		switch ($(this).val().toLowerCase()) {
			case 'mail':
				$('#mail').css('display', 'block');
				break;

			case 'sendmail':
				$('#sendmail').css('display', 'block');
				break;

			case 'smtp':
				$('#smtp').css('display', 'block');
				break;
		}
	});

	// Email test form handler.
	$('#test-form[type=submit]').click(function(e){
		e.preventDefault();

		var form = this.form, data, url;
		
		// Grab all the form data, including the CodeIgniter anti-CSRF token
		data = $(form).serialize();

		// Add the submit button which was clicked
		if (this.name) {
			data[this.name] = this.value;
		}

		// Submit the form by AJAX, and display the result.
		url = $(form).attr('action');

		$.post(
			url,
			data,
			function success(data) {
				$('#test-ajax').html(data);
			}
		);
	});

    // Trigger the server type select's change event to display only the
    // currently-selected server type's settings.
    $('#server_type').trigger('change');
});