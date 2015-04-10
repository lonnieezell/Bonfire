$(document).ready(function(){
	$('#allow_name_change').on('change', function(){
		if ('checked' == $(this).attr('checked')) {
			$('#name-change-settings').css('display', 'block');
		} else {
			$('#name-change-settings').css('display', 'none');
		}
	});

	$('#allow_remember').on('change', function(){
		if ('checked' == $(this).attr('checked')) {
			$('#remember-length').css('display', 'block');
		} else {
			$('#remember-length').css('display', 'none');
		}
	});

    $('#status').on('change', function() {
        if (0 == $(this).val()) {
            $('#offline_reason').parents('.control-group').css('display', 'block');
        } else {
            $('#offline_reason').parents('.control-group').css('display', 'none');
        }
    });
});