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
	$('#test-form').submit(function(e){
		e.preventDefault();

		var email	= $('#test-email').val();
		var url		= $(this).attr('action');

		$('#test-ajax').load(
			url,
			{
				email: email,
				url: url
			}
		);
	});
});