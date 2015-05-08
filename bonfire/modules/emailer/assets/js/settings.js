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

    // Trigger the server type select's change event to display only the
    // currently-selected server type's settings.
    $('#server_type').trigger('change');
});