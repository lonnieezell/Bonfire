$('input[name=remove_shortcut]').click(function(id) {
	var id = $(this).attr('id').replace($(this).attr('name'), '');
	var action = $('input#action' + id).val();
	
	$('form input#remove_action').val(action);
	$('form').submit();
});