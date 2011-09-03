$.subscribe('list-view/list-item/click', function(which) {
	$('#content').load('<?php echo site_url(SITE_AREA .'/reports/activities/') ?>/'+ which);
});

function verify_delete(whom, action) {
	var date_for = 'for';
	if (action.indexOf('date') != -1) date_for = 'before';
	
    var verify = confirm('Are you sure you wish to delete the activity logs '+date_for+' "'+whom+'"?')
    
    if (verify) {
        var url = '<?php echo site_url(SITE_AREA .'/reports/activities/delete/') ?>/'+ action;
        window.location.href = url
    }
    
    return false;
}

$('.button').click( function() {
	var which = $(this).attr('id').replace('delete-', '');
	var whom = $('#'+which+'_select option:selected').text();
	var action = which + '/' + $('#'+which+'_select option:selected').val();
	
	event.stopImmediatePropagation();
	event.preventDefault();
	verify_delete(whom,action);
});