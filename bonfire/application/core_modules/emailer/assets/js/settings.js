$(document).ready(function(){
	// Server Settings
	$('#server_type').change(function(){
		// First, hide everything
		$('#mail, #sendmail, #smtp').css('display', 'none');

		switch ($(this).val())
		{
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

	// since js is active, hide the server settings
	$('#server_type').trigger('change');

	// Email Test
	$('#test-form[type=submit]').click(function(e){
		e.preventDefault();

		var form = this.form;
		
		// Grab all the form data, including the CodeIgniter anti-CSRF token
		var data    = $(form).serialize();

		// Add the submit button which was clicked
		if (this.name) {
			data[this.name] = this.value;
		}

		// Submit the form by AJAX, and display the result.
		var url		= $(form).attr('action');

		$.post(
			url,
			data,
			function success(data) {
				$('#test-ajax').html(data);
			}
		);
	});
});