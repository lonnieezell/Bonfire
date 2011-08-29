$.subscribe('list-view/list-item/click', function(userid) {
	$('#content').load('<?php echo site_url(SITE_AREA .'/reports/activities/user/') ?>/'+ userid);
});

$('#module-list .list-item').click( function() {
	var module = $(this).attr('data-id');
	$('#content').load('<?php echo site_url(SITE_AREA .'/reports/activities/module/') ?>/' + module);
	alert(module);
});

function verify_delete(whom, action) {
	
    var verify = confirm('Are you sure you wish to delete the activity logs for "'+whom+'"?')
    
    if (verify) {
        var url = '<?php echo site_url(SITE_AREA .'/reports/activities/delete/') ?>/'+ action;
        window.location.href = url
    }
    
    return false;
}


$('.button').click( function() {
	if ($(this).attr('id') == 'delete-user-activity') {
		var whom = $('#user_select option:selected').text();
		var action = 'user_id/' + $('#user_select option:selected').val();
	}
	
	if ($(this).attr('id') == 'delete-module-activity') {
		var whom = $('#module_select option:selected').text();
		var action = 'module/' + $('#module_select option:selected').val();
	}
	
	event.stopImmediatePropagation();
	event.preventDefault();
	verify_delete(whom,action);
});