$(document).ready(function(){

	$('input[name=remove_shortcut]').click(function(e) {
//		e.preventDefault();
	
		var id = $(this).attr('id').replace($(this).attr('name'), '');
		var action = $('input#action' + id).val();

		$('form#shortcut_form input#remove_action').val(action);
//		$('form#shortcut_form').submit();
	});

});