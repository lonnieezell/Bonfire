$(document).ready(function(){

	$('#allow_name_change').change(function(){
		var allow_change = $(this).attr('checked');
	
		if (allow_change == 'checked')
		{
			$('#name-change-settings').css('display', 'block');
		}
		else
		{
			$('#name-change-settings').css('display', 'none');
		}
	});
	
	$('#allow_remember').change(function(){
		var allow_change = $(this).attr('checked');
	
		if (allow_change == 'checked')
		{
			$('#remember-length').css('display', 'block');
		}
		else
		{
			$('#remember-length').css('display', 'none');
		}
	});

});
