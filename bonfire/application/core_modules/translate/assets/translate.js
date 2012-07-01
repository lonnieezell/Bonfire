$(document).ready(function(e){

	$('#trans_lang').change(function() {
		var lang = $(this +'option:selected').val();
		
		if (lang == 'new')
		{
			$('#new_lang').show('slow');
		}
		else
		{
			$('#new_lang').hide('slow');
		}
	});

})