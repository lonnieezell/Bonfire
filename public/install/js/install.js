$(document).ready(function() {
	
	$('.db_check').bind('keyup focus', function() {

		$.post(base_url + 'index.php/ajax/confirm_database', 
		{
			driver: $('#driver :selected').val(),
			server: $('input[name=hostname]').val(),
			port: $('input[name=port]').val(),
			username: $('input[name=username]').val(),
			password: $('input[name=password]').val()
		}, function (data) {
			if (data.success == 'true')
			{
				$('#confirm_db').html(data.message).removeClass('notification error').addClass('notification success');
			}
			else
			{
				$('#confirm_db').html(data.message).removeClass('notification success').addClass('notification error');
			}
		},
		'json');
	});
	
	//--------------------------------------------------------------------
	
});