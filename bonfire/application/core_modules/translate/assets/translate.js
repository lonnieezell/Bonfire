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

})